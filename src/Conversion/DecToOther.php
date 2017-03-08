<?php

namespace Mpdf\Conversion;

use Mpdf\Mpdf;

class DecToOther
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	public function __construct(Mpdf $mpdf)
	{
		$this->mpdf = $mpdf;
	}

	public function convert($num, $cp, $check = TRUE)
	{
		// From printlistbuffer: font is set, so check if character is available
		// From docPageNum: font is not set, so no check
		$nstr = (string) $num;
		$rnum = '';

		for ($i = 0; $i < strlen($nstr); $i++) {
			if (!$check || $this->mpdf->_charDefined($this->mpdf->CurrentFont['cw'], $cp + intval($nstr[$i]))) {
				$rnum .= code2utf($cp + intval($nstr[$i]));
			} else {
				$rnum .= $nstr[$i];
			}
		}

		return $rnum;
	}

	public function getCp($match)
	{
		switch ($match) { // Format type
			case 'arabic-indic':
				$cp = 0x0660;
				break;
			case 'persian':
			case 'urdu':
				$cp = 0x06F0;
				break;
			case 'bengali':
				$cp = 0x09E6;
				break;
			case 'devanagari':
				$cp = 0x0966;
				break;
			case 'gujarati':
				$cp = 0x0AE6;
				break;
			case 'gurmukhi':
				$cp = 0x0A66;
				break;
			case 'kannada':
				$cp = 0x0CE6;
				break;
			case 'malayalam':
				$cp = 0x0D66;
				break;
			case 'oriya':
				$cp = 0x0B66;
				break;
			case 'telugu':
				$cp = 0x0C66;
				break;
			case 'tamil':
				$cp = 0x0BE6;
				break;
			case 'thai':
				$cp = 0x0E50;
				break;
			case 'khmer':
			case 'cambodian':
				$cp = 0x17E0;
				break;
			case 'lao':
				$cp = 0x0ED0;
				break;
		}
	}

}

