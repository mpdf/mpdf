<?php

namespace Mpdf\PdfParser;

use Mpdf\PdfParser\CrossReference\CrossReference;

class PdfParser extends \setasign\Fpdi\PdfParser\PdfParser
{

	public function getCrossReference()
	{
		if ($this->xref === null) {
			$this->xref = new CrossReference($this, $this->resolveFileHeader());
		}

		return $this->xref;
	}
}
