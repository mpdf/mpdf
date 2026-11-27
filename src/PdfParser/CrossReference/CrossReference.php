<?php

namespace Mpdf\PdfParser\CrossReference;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfName;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

class CrossReference extends \setasign\Fpdi\PdfParser\CrossReference\CrossReference
{

	/**
	 * @var PdfIndirectObject[]
	 */
	private $compressedObjects = [];

	protected function initReaderInstance($initValue)
	{
		if ($initValue instanceof PdfIndirectObject) {
			try {
				$stream = PdfStream::ensure($initValue->value);
			} catch (PdfTypeException $e) {
				throw new CrossReferenceException(
					'Invalid object type at xref reference offset.',
					CrossReferenceException::INVALID_DATA,
					$e
				);
			}

			$type = PdfDictionary::get($stream->value, 'Type');
			if (!$type instanceof PdfName || $type->value !== 'XRef') {
				throw new CrossReferenceException(
					'The xref position points to an incorrect object type.',
					CrossReferenceException::INVALID_DATA
				);
			}

			$this->checkForEncryption($stream->value);

			return new CompressedReader($this->parser, $stream);
		}

		return parent::initReaderInstance($initValue);
	}

	public function getIndirectObject($objectNumber)
	{
		try {
			return parent::getIndirectObject($objectNumber);
		} catch (CrossReferenceException $e) {
			if ($e->getCode() !== CrossReferenceException::OBJECT_NOT_FOUND) {
				throw $e;
			}
		}

		$info = $this->getCompressedObjectInfo($objectNumber);
		if ($info === false) {
			throw new CrossReferenceException(
				sprintf('Object (id:%s) not found.', $objectNumber),
				CrossReferenceException::OBJECT_NOT_FOUND
			);
		}

		return $this->getCompressedObject($objectNumber, $info[0], $info[1]);
	}

	private function getCompressedObjectInfo($objectNumber)
	{
		foreach ($this->getReaders() as $reader) {
			if (!method_exists($reader, 'getCompressedObjectInfo')) {
				continue;
			}

			$info = $reader->getCompressedObjectInfo($objectNumber);
			if ($info !== false) {
				return $info;
			}
		}

		return false;
	}

	private function getCompressedObject($objectNumber, $objectStreamNumber, $index)
	{
		if (isset($this->compressedObjects[$objectNumber])) {
			return $this->compressedObjects[$objectNumber];
		}

		$objectStream = parent::getIndirectObject($objectStreamNumber);
		$stream = PdfStream::ensure($objectStream->value);
		$dictionary = PdfDictionary::ensure($stream->value);
		$type = PdfDictionary::get($dictionary, 'Type');
		if (!$type instanceof PdfName || $type->value !== 'ObjStm') {
			throw new CrossReferenceException(
				sprintf('Object stream (id:%s) not found.', $objectStreamNumber),
				CrossReferenceException::OBJECT_NOT_FOUND
			);
		}

		$first = PdfNumeric::ensure(PdfDictionary::get($dictionary, 'First'))->value;
		$count = PdfNumeric::ensure(PdfDictionary::get($dictionary, 'N'))->value;
		$data = $stream->getUnfilteredStream();
		$header = substr($data, 0, $first);
		$tokens = preg_split('/\s+/', trim($header));
		if (!is_array($tokens) || count($tokens) < ($count * 2)) {
			throw new CrossReferenceException(
				sprintf('Invalid object stream (id:%s).', $objectStreamNumber),
				CrossReferenceException::INVALID_DATA
			);
		}

		$entries = [];
		for ($i = 0; $i < $count; $i++) {
			$entries[] = [(int) $tokens[$i * 2], (int) $tokens[$i * 2 + 1]];
		}

		if (!isset($entries[$index]) || $entries[$index][0] !== (int) $objectNumber) {
			throw new CrossReferenceException(
				sprintf('Object (id:%s) not found in object stream.', $objectNumber),
				CrossReferenceException::OBJECT_NOT_FOUND
			);
		}

		$start = $first + $entries[$index][1];
		$end = isset($entries[$index + 1]) ? $first + $entries[$index + 1][1] : strlen($data);
		$objectData = substr($data, $start, $end - $start);
		$parser = new PdfParser(StreamReader::createByString($objectData));
		$value = $parser->readValue();
		if (!$value instanceof PdfType) {
			throw new CrossReferenceException(
				sprintf('Invalid compressed object (id:%s).', $objectNumber),
				CrossReferenceException::INVALID_DATA
			);
		}

		return $this->compressedObjects[$objectNumber] = PdfIndirectObject::create($objectNumber, 0, $value);
	}
}
