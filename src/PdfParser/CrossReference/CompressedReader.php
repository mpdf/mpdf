<?php

namespace Mpdf\PdfParser\CrossReference;

use setasign\Fpdi\PdfParser\CrossReference\ReaderInterface;
use setasign\Fpdi\PdfParser\Filter\Flate;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfName;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfNull;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

class CompressedReader implements ReaderInterface
{

	/**
	 * @var PdfDictionary
	 */
	private $trailer;

	/**
	 * @var int[]
	 */
	private $offsets = [];

	/**
	 * @var array<int, array{0:int, 1:int}>
	 */
	private $compressedObjects = [];

	public function __construct(PdfParser $parser, PdfStream $stream)
	{
		$this->trailer = $stream->value;
		$this->read($stream);
	}

	public function getOffsetFor($objectNumber)
	{
		return $this->offsets[$objectNumber] ?? false;
	}

	public function getCompressedObjectInfo($objectNumber)
	{
		return $this->compressedObjects[$objectNumber] ?? false;
	}

	public function getTrailer()
	{
		return $this->trailer;
	}

	private function read(PdfStream $stream)
	{
		$widths = PdfArray::ensure(PdfDictionary::get($this->trailer, 'W'), 3)->value;
		$widths = [
			PdfNumeric::ensure($widths[0])->value,
			PdfNumeric::ensure($widths[1])->value,
			PdfNumeric::ensure($widths[2])->value,
		];

		$index = PdfDictionary::get($this->trailer, 'Index');
		if ($index instanceof PdfArray) {
			$index = $index->value;
		} else {
			$index = [PdfNumeric::create(0), PdfDictionary::get($this->trailer, 'Size')];
		}

		$entryLength = array_sum($widths);
		if ($entryLength === 0) {
			throw new PdfTypeException('Invalid compressed xref entry width.');
		}
		$data = $this->getDecodedStream($stream, $entryLength);

		$position = 0;
		for ($i = 0, $count = count($index); $i < $count; $i += 2) {
			$objectNumber = PdfNumeric::ensure($index[$i])->value;
			$objectCount = PdfNumeric::ensure($index[$i + 1])->value;

			for ($j = 0; $j < $objectCount; $j++, $objectNumber++) {
				$entry = substr($data, $position, $entryLength);
				if (strlen($entry) < $entryLength) {
					return;
				}

				$position += $entryLength;
				$fields = [];
				$fieldPosition = 0;
				foreach ($widths as $field => $width) {
					$fields[$field] = $width === 0
						? ($field === 0 ? 1 : 0)
						: $this->readUnsignedInteger(substr($entry, $fieldPosition, $width));
					$fieldPosition += $width;
				}

				if ($fields[0] === 1) {
					$this->offsets[$objectNumber] = $fields[1];
				} elseif ($fields[0] === 2) {
					$this->compressedObjects[$objectNumber] = [$fields[1], $fields[2]];
				}
			}
		}
	}

	private function readUnsignedInteger($bytes)
	{
		$value = 0;
		for ($i = 0, $length = strlen($bytes); $i < $length; $i++) {
			$value = ($value << 8) + ord($bytes[$i]);
		}

		return $value;
	}

	private function getDecodedStream(PdfStream $stream, $columns)
	{
		$data = $stream->getStream();
		$filters = $stream->getFilters();
		if ($filters === []) {
			return $data;
		}

		$decodeParams = PdfDictionary::get($stream->value, 'DecodeParms');
		if ($decodeParams instanceof PdfArray) {
			$decodeParams = $decodeParams->value;
		} else {
			$decodeParams = [$decodeParams];
		}

		foreach ($filters as $key => $filter) {
			if (!$filter instanceof PdfName) {
				continue;
			}

			switch ($filter->value) {
				case 'FlateDecode':
				case 'Fl':
					$data = (new Flate())->decode($data);
					$decodeParam = $decodeParams[$key] ?? null;
					if ($decodeParam instanceof PdfDictionary) {
						$data = $this->decodePredictor($data, $decodeParam, $columns);
					}
					break;

				default:
					throw new PdfTypeException(sprintf('Unsupported xref stream filter "%s".', $filter->value));
			}
		}

		return $data;
	}

	private function decodePredictor($data, PdfDictionary $decodeParam, $defaultColumns)
	{
		$predictor = PdfDictionary::get($decodeParam, 'Predictor', PdfNumeric::create(1));
		if ($predictor instanceof PdfNull || $predictor->value === 1) {
			return $data;
		}

		$columns = PdfDictionary::get($decodeParam, 'Columns', PdfNumeric::create($defaultColumns));
		$columns = PdfNumeric::ensure($columns)->value;

		if ($predictor->value === 2) {
			return $this->decodeTiffPredictor($data, $columns);
		}

		if ($predictor->value >= 10 && $predictor->value <= 15) {
			return $this->decodePngPredictor($data, $columns);
		}

		throw new PdfTypeException(sprintf('Unsupported xref stream predictor "%s".', $predictor->value));
	}

	private function decodeTiffPredictor($data, $columns)
	{
		$result = '';
		for ($offset = 0, $length = strlen($data); $offset < $length; $offset += $columns) {
			$row = substr($data, $offset, $columns);
			for ($i = 1, $rowLength = strlen($row); $i < $rowLength; $i++) {
				$row[$i] = chr((ord($row[$i]) + ord($row[$i - 1])) & 0xff);
			}
			$result .= $row;
		}

		return $result;
	}

	private function decodePngPredictor($data, $columns)
	{
		$result = '';
		$previous = str_repeat("\0", $columns);
		for ($offset = 0, $length = strlen($data); $offset < $length;) {
			$filter = ord($data[$offset++]);
			$row = substr($data, $offset, $columns);
			$offset += $columns;
			$rowLength = strlen($row);

			for ($i = 0; $i < $rowLength; $i++) {
				$left = $i > 0 ? ord($row[$i - 1]) : 0;
				$up = ord($previous[$i]);
				$upperLeft = $i > 0 ? ord($previous[$i - 1]) : 0;
				$value = ord($row[$i]);

				switch ($filter) {
					case 0:
						break;
					case 1:
						$value += $left;
						break;
					case 2:
						$value += $up;
						break;
					case 3:
						$value += (int) floor(($left + $up) / 2);
						break;
					case 4:
						$value += $this->paethPredictor($left, $up, $upperLeft);
						break;
					default:
						throw new PdfTypeException(sprintf('Unsupported PNG predictor filter "%s".', $filter));
				}

				$row[$i] = chr($value & 0xff);
			}

			$result .= $row;
			$previous = str_pad($row, $columns, "\0");
		}

		return $result;
	}

	private function paethPredictor($left, $up, $upperLeft)
	{
		$p = $left + $up - $upperLeft;
		$pa = abs($p - $left);
		$pb = abs($p - $up);
		$pc = abs($p - $upperLeft);

		if ($pa <= $pb && $pa <= $pc) {
			return $left;
		}

		return $pb <= $pc ? $up : $upperLeft;
	}
}
