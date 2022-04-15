<?php

namespace Mpdf\Helpers;

use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfReader\PdfReader;

class PdfContentHelper
{
	/**
	 * @var string
	 */
	private $pdf;

	/**
	 * @param string $pdf
	 */
	public function __construct($pdf)
	{
		$this->pdf = $pdf;
	}

	/**
	 * Search for a text in the PDF
	 * @param string   $search
	 * @param null|int $page
	 * @return array
	 * @throws \Exception
	 */
	public function findText($search, $page = null)
	{
		// Strings have null char between each letter ?!!? e.g. Hello => \0H\0e\0l\0l\0o
		// Couldn't find any documentation about it in
		// https://www.adobe.com/content/dam/acom/en/devnet/pdf/pdfs/pdf_reference_archives/PDFReference.pdf @page 29
		$search = "\0" . mb_substr(
			implode("\0", str_split($search, 1)),
			0,
			( strlen($search) * 2 ) - 1
		);

		// Literal characters must be enclosed in ()
		// more or less see page 312 from PDF for example with operators between letters
		// @Todo check/escape backslash and unbalanced parentheses
		$search = \preg_quote($search);
		$regex  = "/BT.*?\((.*{$search}.*)\).*?ET/"; // BT ... ET  (begin text, end text)

		$parser    = new PdfParser(StreamReader::createByString($this->pdf));
		$pdfReader = new PdfReader($parser);

		if ($page !== null) {
			if ((int) $page < 1) {
				throw new \Exception('Page must be >= 1');
			}

			return $this->findTextInContents(
				$regex,
				$pdfReader->getPage($page)->getContentStream()
			);
		}

		$page    = 1;
		$results = [];
		do {
			$this->findTextInContents(
				$regex,
				$pdfReader->getPage($page++)->getContentStream(),
				$results
			);

		} while ($page <= $pdfReader->getPageCount());

		return $results;
	}

	/**
	 * Get the number of pages in the PDF
	 * @throws \setasign\Fpdi\PdfParser\PdfParserException
	 */
	public function pageCount()
	{
		$parser    = new PdfParser(StreamReader::createByString($this->pdf));
		$pdfReader = new PdfReader($parser);

		return (int) $pdfReader->getPageCount();
	}


	/**
	 * Finds a string literal in the page contents
	 * @param string $search   regex escaped search pattern
	 * @param string $contents pdf page contents search in
	 * @return array results
	 * @since 3.4.0
	 */
	private function findTextInContents($regex, $contents, array &$results = [])
	{
		if (\preg_match_all(
			$regex,
			$contents,
			$matches,
			PREG_SET_ORDER
		)) {
			$results = array_merge($results, $matches);
		}

		return $results;
	}
}
