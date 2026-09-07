<?php

namespace Mpdf;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\AsciiHex;
use setasign\Fpdi\PdfParser\Type\PdfHexString;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfNull;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfString;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PageBoundaries;

/**
 * @mixin Mpdf
 */
trait FpdiTrait
{
	use \setasign\Fpdi\FpdiTrait {
		writePdfType as fpdiWritePdfType;
		useImportedPage as fpdiUseImportedPage;
		importPage as fpdiImportPage;
	}

	protected $k = Mpdf::SCALE;

	/**
	 * The currently used object number.
	 *
	 * @var int
	 */
	public $currentObjectNumber;

	/**
	 * A counter for template ids.
	 *
	 * @var int
	 */
	protected $templateId = 0;

	protected function setPageFormat($format, $orientation)
	{
		// in mPDF this needs to be "P" (why ever)
		$orientation = 'P';
		$this->_setPageSize([$format['width'], $format['height']], $orientation);

		if ($orientation != $this->DefOrientation) {
			$this->OrientationChanges[$this->page] = true;
		}

		$this->wPt = $this->fwPt;
		$this->hPt = $this->fhPt;
		$this->w = $this->fw;
		$this->h = $this->fh;

		$this->CurOrientation = $orientation;
		$this->ResetMargins();
		$this->pgwidth = $this->w - $this->lMargin - $this->rMargin;
		$this->PageBreakTrigger = $this->h - $this->bMargin;

		$this->pageDim[$this->page]['w'] = $this->w;
		$this->pageDim[$this->page]['h'] = $this->h;
	}

	/**
	 * Set the minimal PDF version.
	 *
	 * @param string $pdfVersion
	 */
	protected function setMinPdfVersion($pdfVersion)
	{
		if (\version_compare($pdfVersion, $this->pdf_version, '>')) {
			$this->pdf_version = $pdfVersion;
		}
	}

	/**
	 * Get the next template id.
	 *
	 * @return int
	 */
	protected function getNextTemplateId()
	{
		return $this->templateId++;
	}

	/**
	 * Draws an imported page or a template onto the page or another template.
	 *
	 * Omit one of the size parameters (width, height) to calculate the other one automatically in view to the aspect
	 * ratio.
	 *
	 * @param mixed $tpl The template id
	 * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
	 *                           with the keys "x", "y", "width", "height", "adjustPageSize".
	 * @param float|int $y The ordinate of upper-left corner.
	 * @param float|int|null $width The width.
	 * @param float|int|null $height The height.
	 * @param bool $adjustPageSize
	 * @return array The size
	 * @see Fpdi::getTemplateSize()
	 */
	public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
	{
		return $this->useImportedPage($tpl, $x, $y, $width, $height, $adjustPageSize);
	}

	/**
	 * Draws an imported page onto the page.
	 *
	 * Omit one of the size parameters (width, height) to calculate the other one automatically in view to the aspect
	 * ratio.
	 *
	 * @param mixed $pageId The page id
	 * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
	 *                           with the keys "x", "y", "width", "height", "adjustPageSize".
	 * @param float|int $y The ordinate of upper-left corner.
	 * @param float|int|null $width The width.
	 * @param float|int|null $height The height.
	 * @param bool $adjustPageSize
	 * @return array The size.
	 * @see Fpdi::getTemplateSize()
	 */
	public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
	{
		if ($this->state == 0) {
			$this->AddPage();
		}

		/* Extract $x if an array */
		if (is_array($x)) {
			unset($x['pageId']);
			extract($x, EXTR_IF_EXISTS);
			if (is_array($x)) {
				$x = 0;
			}
		}

		$newSize = $this->fpdiUseImportedPage($pageId, $x, $y, $width, $height, $adjustPageSize);

		return $newSize;
	}

	/**
	 * Imports a page.
	 *
	 * @param int $pageNumber The page number.
	 * @param string $box The page boundary to import. Default set to PageBoundaries::CROP_BOX.
	 * @param bool $groupXObject Define the form XObject as a group XObject to support transparency (if used).
	 * @param bool $importExternalLinks
	 * @return string A unique string identifying the imported page.
	 * @throws CrossReferenceException
	 * @throws FilterException
	 * @throws PdfParserException
	 * @throws PdfTypeException
	 * @throws PdfReaderException
	 * @see PageBoundaries
	 */
	public function importPage($pageNumber, $box = PageBoundaries::CROP_BOX, $groupXObject = true, $importExternalLinks = true)
	{
		$pageId = $this->fpdiImportPage($pageNumber, $box, $groupXObject, $importExternalLinks);

		return $pageId;
	}

	/**
	 * Imports the external page links
	 *
	 * @param int $pageNumber The page number.
	 * @return array
	 * @throws CrossReferenceException
	 * @throws PdfTypeException
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 * @deprecated Handled automatically in self::importPage() when $importExternalLinks = true (default))
	 */
	public function getImportedExternalPageLinks($pageNumber)
	{
		return [];
	}

	/**
	 * @param mixed $pageId The page id
	 * @param int|float $x The abscissa of upper-left corner.
	 * @param int|float $y The ordinate of upper-right corner.
	 * @param array $newSize The size.
	 * @deprecated Handled automatically in self::importPage() when $importExternalLinks = true (default)
	 */
	public function setImportedPageLinks($pageId, $x, $y, $newSize)
	{
	}

	/**
	 * Get the size of an imported page or template.
	 *
	 * Omit one of the size parameters (width, height) to calculate the other one automatically in view to the aspect
	 * ratio.
	 *
	 * @param mixed $tpl The template id
	 * @param float|int|null $width The width.
	 * @param float|int|null $height The height.
	 * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
	 */
	public function getTemplateSize($tpl, $width = null, $height = null)
	{
		return $this->getImportedPageSize($tpl, $width, $height);
	}

	/**
	 * @throws CrossReferenceException
	 * @throws PdfTypeException
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 */
	public function writeImportedPagesAndResolvedObjects()
	{
		$this->currentReaderId = null;

		foreach ($this->importedPages as $key => $pageData) {
			$this->writer->object();
			$this->importedPages[$key]['objectNumber'] = $this->n;
			$this->currentReaderId = $pageData['readerId'];
			$this->writePdfType($pageData['stream']);
			$this->_put('endobj');
		}

		foreach (\array_keys($this->readers) as $readerId) {
			$parser = $this->getPdfReader($readerId)->getParser();
			$this->currentReaderId = $readerId;

			while (($objectNumber = \array_pop($this->objectsToCopy[$readerId])) !== null) {
				try {
					$object = $parser->getIndirectObject($objectNumber);

				} catch (CrossReferenceException $e) {
					if ($e->getCode() === CrossReferenceException::OBJECT_NOT_FOUND) {
						$object = PdfIndirectObject::create($objectNumber, 0, new PdfNull());
					} else {
						throw $e;
					}
				}

				$this->writePdfType($object);
			}
		}

		$this->currentReaderId = null;
	}

	public function getImportedPages()
	{
		return $this->importedPages;
	}

	protected function _put($s, $newLine = true)
	{
		$this->buffer->append($s, $newLine);
	}

	/**
	 * Writes a PdfType object to the resulting buffer.
	 *
	 * @param PdfType $value
	 * @throws PdfTypeException
	 */
	public function writePdfType(PdfType $value)
	{
		if (!$this->encrypted) {
			if ($value instanceof PdfIndirectObject) {
				/**
				 * @var $value PdfIndirectObject
				 */
				$n = $this->objectMap[$this->currentReaderId][$value->objectNumber];
				$this->writer->object($n);
				$this->writePdfType($value->value);
				$this->_put('endobj');
				return;
			}

			$this->fpdiWritePdfType($value);
			return;
		}

		if ($value instanceof PdfString) {
			$string = PdfString::unescape($value->value);
			$string = $this->protection->rc4($this->protection->objectKey($this->currentObjectNumber), $string);
			$value->value = $this->writer->escape($string);

		} elseif ($value instanceof PdfHexString) {
			$filter = new AsciiHex();
			$string = $filter->decode($value->value);
			$string = $this->protection->rc4($this->protection->objectKey($this->currentObjectNumber), $string);
			$value->value = $filter->encode($string, true);

		} elseif ($value instanceof PdfStream) {
			$stream = $value->getStream();
			$stream = $this->protection->rc4($this->protection->objectKey($this->currentObjectNumber), $stream);
			$dictionary = $value->value;
			$dictionary->value['Length'] = PdfNumeric::create(\strlen($stream));
			$value = PdfStream::create($dictionary, $stream);

		} elseif ($value instanceof PdfIndirectObject) {
			/**
			 * @var $value PdfIndirectObject
			 */
			$this->currentObjectNumber = $this->objectMap[$this->currentReaderId][$value->objectNumber];
			/**
			 * @var $value PdfIndirectObject
			 */
			$n = $this->objectMap[$this->currentReaderId][$value->objectNumber];
			$this->writer->object($n);
			$this->writePdfType($value->value);
			$this->_put('endobj');
			return;
		}

		$this->fpdiWritePdfType($value);
	}
}
