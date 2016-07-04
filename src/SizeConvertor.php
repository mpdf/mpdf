<?php

namespace Mpdf;

class SizeConvertor
{

	private $dpi;
	private $defaultFontSize;

	public function __construct($dpi, $defaultFontSize)
	{
		$this->dpi = $dpi;
		$this->defaultFontSize = $defaultFontSize;
	}

	/**
	 * Depends of maxsize value to make % work properly. Usually maxsize == pagewidth
	 * For text $maxsize = $fontsize
	 * Setting e.g. margin % will use maxsize (pagewidth) and em will use fontsize
	 *
	 * @param mixed $size
	 * @param mixed $maxsize
	 * @param mixed $fontsize
	 * @param mixed $usefontsize Set false for e.g. margins - will ignore fontsize for % values
	 *
	 * @return float Final size in mm
	 */
	public function convert($size = 5, $maxsize = 0, $fontsize = false, $usefontsize = true)
	{
		$size = trim(strtolower($size));
		$res = preg_match('/^(?P<size>[-0-9.,]+)?(?P<unit>[%a-z-]+)?$/', $size, $parts);
		if (!$res) {
			throw new MpdfException(sprintf('Invalid size representation "%s"', $size));
		}

		$unit = !empty($parts['unit']) ? $parts['unit'] : NULL;
		$size = !empty($parts['size']) ? (float) $parts['size'] : 0.0;

		switch ($unit) {

			case 'mm':
				// do nothing
				break;

			case 'cm':
				$size *= 10;
				break;

			case 'pt':
				$size *= 1 / Mpdf::SCALE;
				break;

			case 'rem':
				$size *= ($this->defaultFontSize / (1 / Mpdf::SCALE));
				break;

			case '%':
				if ($fontsize && $usefontsize) {
					$size *= $fontsize / 100;
				} else {
					$size *= $maxsize / 100;
				}
				break;

			case 'in':
				// mm in an inch
				$size *= 25.4;
				break;

			case 'pc':
				// PostScript picas
				$size *= 38.1 / 9;
				break;

			case 'ex':
				// Approximates "ex" as half of font height
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 0.5);
				break;

			case 'em':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 1);
				break;

			case 'thin':
				$size = 1 * (25.4 / $this->dpi);
				break;

			case 'medium':
				$size = 3 * (25.4 / $this->dpi);
				// Commented-out dead code from legacy method
				// $size *= $this->multiplyFontSize($fontsize, $maxsize, 1);
				break;

			case 'thick':
				$size = 5 * (25.4 / $this->dpi); // 5 pixel width for table borders
				break;

			case 'xx-small':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 0.7);
				break;

			case 'x-small':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 0.77);
				break;

			case 'small':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 0.86);
				break;

			case 'large':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 1.2);
				break;

			case 'x-large':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 1.5);
				break;

			case 'xx-large':
				$size *= $this->multiplyFontSize($fontsize, $maxsize, 2);
				break;

			case 'px':
			default:
				$size *= (25.4 / $this->dpi);
				break;
		}

		return $size;
	}

	private function multiplyFontSize($fontsize, $maxsize, $ratio)
	{
		if ($fontsize) {
			return $fontsize * $ratio;
		}

		return $maxsize * $ratio;
	}

	public function convertLegacy($size = 5, $maxsize = 0, $fontsize = false, $usefontsize = true)
	{
		$size = trim(strtolower($size));

		if ($size == 'thin')
			$size = 1 * (25.4 / $this->dpi); // 1 pixel width for table borders
		elseif (stristr($size, 'px'))
			$size = (float) $size * (25.4 / $this->dpi); // pixels
		elseif (stristr($size, 'cm'))
			$size = (float) $size * 10; // centimeters
		elseif (stristr($size, 'mm'))
			$size = (float) $size; // millimeters
		elseif (stristr($size, 'pt')) {
			$size = (float) $size * 25.4 / 72; // 72 pts/inch
		}
		elseif (stristr($size, 'rem')) {
			$size = (float) $size; // make "0.83rem" become simply "0.83"
			$size *= ($this->defaultFontSize / Mpdf::SCALE);
		} elseif (stristr($size, 'em')) {
			$size = (float) $size; // make "0.83em" become simply "0.83"
			if ($fontsize) {
				$size *= $fontsize;
			} else {
				$size *= $maxsize;
			}
		} elseif (stristr($size, '%')) {
			$size = (float) $size; // make "90%" become simply "90"
			if ($fontsize && $usefontsize) {
				$size *= $fontsize / 100;
			} else {
				$size *= $maxsize / 100;
			}
		} elseif (stristr($size, 'in'))
			$size = (float) $size * 25.4; // inches
		elseif (stristr($size, 'pc'))
			$size = (float) $size * 38.1 / 9; // PostScript picas
		elseif (stristr($size, 'ex')) { // Approximates "ex" as half of font height
			$size = (float) $size; // make "3.5ex" become simply "3.5"
			if ($fontsize) {
				$size *= $fontsize / 2;
			} else {
				$size *= $maxsize / 2;
			}
		} elseif ($size == 'medium')
			$size = 3 * (25.4 / $this->dpi); //3 pixel width for table borders
		elseif ($size == 'thick')
			$size = 5 * (25.4 / $this->dpi); //5 pixel width for table borders
		elseif ($size == 'xx-small') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 0.7;
			} else {
				$size *= $maxsize * 0.7;
			}
		} elseif ($size == 'x-small') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 0.77;
			} else {
				$size *= $maxsize * 0.77;
			}
		} elseif ($size == 'small') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 0.86;
			} else {
				$size *= $maxsize * 0.86;
			}
		} elseif ($size == 'medium') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize;
			} else {
				$size *= $maxsize;
			}
		} elseif ($size == 'large') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 1.2;
			} else {
				$size *= $maxsize * 1.2;
			}
		} elseif ($size == 'x-large') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 1.5;
			} else {
				$size *= $maxsize * 1.5;
			}
		} elseif ($size == 'xx-large') {
			$size = (float) $size;
			if ($fontsize) {
				$size *= $fontsize * 2;
			} else {
				$size *= $maxsize * 2;
			}
		} else {
			$size = (float) $size;
			$size *= (25.4 / $this->dpi); // nothing == px
		}

		return $size;
	}

}
