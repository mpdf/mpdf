<?php

namespace Mpdf\Color;

use Mpdf\Mpdf;

class ColorConvertor
{

	private $mpdf;

	private $cache;

	public function __construct(Mpdf $mpdf)
	{
		$this->mpdf = $mpdf;
	}

	public function convert($color, array &$PDFAXwarnings = [])
	{
		$color = trim(strtolower($color));
		$cstr = '';

		if ($color == 'transparent') {
			return false;
		} elseif ($color == 'inherit') {
			return false;
		} elseif (isset(NamedColors::$colors[$color])) {
			$color = NamedColors::$colors[$color];
		}

		if (!isset($this->cache[$color])) {
			$c = $this->convertPlain($color, $PDFAXwarnings);
			if (is_array($c)) {
				$c = array_pad($c, 6, 0);
				$cstr = pack("a1ccccc", $c[0], ($c[1] & 0xFF), ($c[2] & 0xFF), ($c[3] & 0xFF), ($c[4] & 0xFF), ($c[5] & 0xFF));
			}

			$this->cache[$color] = $cstr;
		}

		return $this->cache[$color];
	}

	public function lighten($c)
	{
		if (is_array($c)) {
			throw new \Mpdf\MpdfException('Color error in _lightencolor');
		}
		if ($c{0} == 3 || $c{0} == 5) {  // RGB
			list($h, $s, $l) = $this->rgb2hsl(ord($c{1}) / 255, ord($c{2}) / 255, ord($c{3}) / 255);
			$l += ((1 - $l) * 0.8);
			list($r, $g, $b) = $this->hsl2rgb($h, $s, $l);
			$ret = [3, $r, $g, $b];
		} elseif ($c{0} == 4 || $c{0} == 6) {  // CMYK
			$ret = [4, max(0, (ord($c{1}) - 20)), max(0, (ord($c{2}) - 20)), max(0, (ord($c{3}) - 20)), max(0, (ord($c{4}) - 20))];
		} elseif ($c{0} == 1) { // Grayscale
			$ret = [1, min(255, (ord($c{1}) + 32))];
		}

		$c = array_pad($ret, 6, 0);
		$cstr = pack("a1ccccc", $c[0], ($c[1] & 0xFF), ($c[2] & 0xFF), ($c[3] & 0xFF), ($c[4] & 0xFF), ($c[5] & 0xFF));

		return $cstr;
	}

	public function darken($c)
	{
		if (is_array($c)) {
			throw new \Mpdf\MpdfException('Color error in _darkenColor');
		}
		if ($c{0} == 3 || $c{0} == 5) {  // RGB
			list($h, $s, $l) = $this->rgb2hsl(ord($c{1}) / 255, ord($c{2}) / 255, ord($c{3}) / 255);
			$s *= 0.25;
			$l *= 0.75;
			list($r, $g, $b) = $this->hsl2rgb($h, $s, $l);
			$ret = [3, $r, $g, $b];
		} elseif ($c{0} == 4 || $c{0} == 6) {  // CMYK
			$ret = [4, min(100, (ord($c{1}) + 20)), min(100, (ord($c{2}) + 20)), min(100, (ord($c{3}) + 20)), min(100, (ord($c{4}) + 20))];
		} elseif ($c{0} == 1) { // Grayscale
			$ret = [1, max(0, (ord($c{1}) - 32))];
		}
		$c = array_pad($ret, 6, 0);
		$cstr = pack("a1ccccc", $c[0], ($c[1] & 0xFF), ($c[2] & 0xFF), ($c[3] & 0xFF), ($c[4] & 0xFF), ($c[5] & 0xFF));
		return $cstr;
	}

	public function invert($cor)
	{
		if ($cor[0] == 3 || $cor[0] == 5) { // RGB
			return [3, (255 - $cor[1]), (255 - $cor[2]), (255 - $cor[3])];
		} elseif ($cor[0] == 4 || $cor[0] == 6) { // CMYK
			return [4, (100 - $cor[1]), (100 - $cor[2]), (100 - $cor[3]), (100 - $cor[4])];
		} elseif ($cor[0] == 1) { // Grayscale
			return [1, (255 - $cor[1])];
		}
		// Cannot cope with non-RGB colors at present
		throw new \Mpdf\MpdfException('Error in _invertColor - trying to invert non-RGB color');
	}

	public function colAtoString($cor)
	{
		$s = '';
		if ($cor{0} == 1) {
			$s = 'rgb(' . ord($cor{1}) . ',' . ord($cor{1}) . ',' . ord($cor{1}) . ')';
		} elseif ($cor{0} == 2) {
			$s = 'spot(' . ord($cor{1}) . ',' . ord($cor{2}) . ')';  // SPOT COLOR
		} elseif ($cor{0} == 3) {
			$s = 'rgb(' . ord($cor{1}) . ',' . ord($cor{2}) . ',' . ord($cor{3}) . ')';
		} elseif ($cor{0} == 4) {
			$s = 'cmyk(' . ord($cor{1}) . ',' . ord($cor{2}) . ',' . ord($cor{3}) . ',' . ord($cor{4}) . ')';
		} elseif ($cor{0} == 5) {
			$s = 'rgba(' . ord($cor{1}) . ',' . ord($cor{2}) . ',' . ord($cor{3}) . ',' . sprintf('%0.2F', ord($cor{4}) / 100) . ')';
		} elseif ($cor{0} == 6) {
			$s = 'cmyka(' . ord($cor{1}) . ',' . ord($cor{2}) . ',' . ord($cor{3}) . ',' . ord($cor{4}) . ',' . sprintf('%0.2F', ord($cor{5}) / 100) . ')';
		}
		return $s;
	}

	private function convertPlain($color, array &$PDFAXwarnings = [])
	{
		$c = false;

		if (preg_match('/^[\d]+$/', $color)) {
			$c = ([1, $color]); // i.e. integer only
		} elseif ($color[0] == '#') { //case of #nnnnnn or #nnn
			$c = $this->processHashColor($color);
		} elseif (preg_match('/(rgba|rgb|device-cmyka|cmyka|device-cmyk|cmyk|hsla|hsl|spot)\((.*?)\)/', $color, $m)) {
			$c = $this->processModeColor($m);
		}

		if ($this->mpdf->PDFA || $this->mpdf->PDFX || $this->mpdf->restrictColorSpace) {
			$c = $this->restrictColorSpace($c, $color, $PDFAXwarnings);
		}

		return $c;
	}

	private function rgb2gray($c)
	{
		if (isset($c[4])) {
			return [1, (($c[1] * .21) + ($c[2] * .71) + ($c[3] * .07)), ord(1), $c[4]];
		} else {
			return [1, (($c[1] * .21) + ($c[2] * .71) + ($c[3] * .07))];
		}
	}

	private function cmyk2gray($c)
	{
		$rgb = $this->cmyk2rgb($c);
		return $this->rgb2gray($rgb);
	}

	public function rgb2cmyk($c)
	{
		$cyan = 1 - ($c[1] / 255);
		$magenta = 1 - ($c[2] / 255);
		$yellow = 1 - ($c[3] / 255);
		$min = min($cyan, $magenta, $yellow);

		if ($min == 1) {
			if ($c[0] == 5) {
				return [6, 100, 100, 100, 100, $c[4]];
			} else {
				return [4, 100, 100, 100, 100];
			}
			// For K-Black
			//if ($c[0]==5) { return array (6,0,0,0,100, $c[4]); }
			//else { return array (4,0,0,0,100); }
		}
		$K = $min;
		$black = 1 - $K;
		if ($c[0] == 5) {
			return [6, ($cyan - $K) * 100 / $black, ($magenta - $K) * 100 / $black, ($yellow - $K) * 100 / $black, $K * 100, $c[4]];
		} else {
			return [4, ($cyan - $K) * 100 / $black, ($magenta - $K) * 100 / $black, ($yellow - $K) * 100 / $black, $K * 100];
		}
	}

	private function cmyk2rgb($c)
	{
		$rgb = [];
		$colors = 255 - ($c[4] * 2.55);
		$rgb[0] = intval($colors * (255 - ($c[1] * 2.55)) / 255);
		$rgb[1] = intval($colors * (255 - ($c[2] * 2.55)) / 255);
		$rgb[2] = intval($colors * (255 - ($c[3] * 2.55)) / 255);
		if ($c[0] == 6) {
			return [5, $rgb[0], $rgb[1], $rgb[2], $c[5]];
		} else {
			return [3, $rgb[0], $rgb[1], $rgb[2]];
		}
	}

	private function rgb2hsl($var_r, $var_g, $var_b)
	{
		$var_min = min($var_r, $var_g, $var_b);
		$var_max = max($var_r, $var_g, $var_b);
		$del_max = $var_max - $var_min;
		$l = ($var_max + $var_min) / 2;
		if ($del_max == 0) {
			$h = 0;
			$s = 0;
		} else {
			if ($l < 0.5) {
				$s = $del_max / ($var_max + $var_min);
			} else {
				$s = $del_max / (2 - $var_max - $var_min);
			}
			$del_r = ((($var_max - $var_r) / 6) + ($del_max / 2)) / $del_max;
			$del_g = ((($var_max - $var_g) / 6) + ($del_max / 2)) / $del_max;
			$del_b = ((($var_max - $var_b) / 6) + ($del_max / 2)) / $del_max;
			if ($var_r == $var_max) {
				$h = $del_b - $del_g;
			} elseif ($var_g == $var_max) {
				$h = (1 / 3) + $del_r - $del_b;
			} elseif ($var_b == $var_max) {
				$h = (2 / 3) + $del_g - $del_r;
			};
			if ($h < 0) {
				$h += 1;
			}
			if ($h > 1) {
				$h -= 1;
			}
		}
		return [$h, $s, $l];
	}

	private function hsl2rgb($h2, $s2, $l2)
	{
		// Input is HSL value of complementary colour, held in $h2, $s, $l as fractions of 1
		// Output is RGB in normal 255 255 255 format, held in $r, $g, $b
		// Hue is converted using function hue_2_rgb, shown at the end of this code
		if ($s2 == 0) {
			$r = $l2 * 255;
			$g = $l2 * 255;
			$b = $l2 * 255;
		} else {
			if ($l2 < 0.5) {
				$var_2 = $l2 * (1 + $s2);
			} else {
				$var_2 = ($l2 + $s2) - ($s2 * $l2);
			}
			$var_1 = 2 * $l2 - $var_2;
			$r = round(255 * $this->hue2rgb($var_1, $var_2, $h2 + (1 / 3)));
			$g = round(255 * $this->hue2rgb($var_1, $var_2, $h2));
			$b = round(255 * $this->hue2rgb($var_1, $var_2, $h2 - (1 / 3)));
		}
		return [$r, $g, $b];
	}

	private function hue2rgb($v1, $v2, $vh)
	{
		if ($vh < 0) {
			$vh += 1;
		};

		if ($vh > 1) {
			$vh -= 1;
		};

		if ((6 * $vh) < 1) {
			return ($v1 + ($v2 - $v1) * 6 * $vh);
		};

		if ((2 * $vh) < 1) {
			return ($v2);
		};

		if ((3 * $vh) < 2) {
			return ($v1 + ($v2 - $v1) * ((2 / 3 - $vh) * 6));
		};

		return ($v1);
	}

	private function processHashColor($color)
	{
		// in case of Background: #CCC url() x-repeat etc.
		$cor = preg_replace('/\s+.*/', '', $color);

		// Turn #RGB into #RRGGBB
		if (strlen($cor) == 4) {
			$cor = "#" . $cor[1] . $cor[1] . $cor[2] . $cor[2] . $cor[3] . $cor[3];
		}

		$r = hexdec(substr($cor, 1, 2));
		$g = hexdec(substr($cor, 3, 2));
		$b = hexdec(substr($cor, 5, 2));

		return [3, $r, $g, $b];
	}

	private function processModeColor(array $m)
	{
		$c = false;

		$type = $m[1];

		$cores = explode(",", $m[2]);
		$ncores = count($cores);

		if (stristr($cores[0], '%')) {
			$cores[0] = (float) $cores[0];
			if ($type == 'rgb' || $type == 'rgba') {
				$cores[0] = (int) ($cores[0] * 255 / 100);
			}
		}

		if ($ncores > 1 && stristr($cores[1], '%')) {
			$cores[1] = (float) $cores[1];
			if ($type == 'rgb' || $type == 'rgba') {
				$cores[1] = (int) ($cores[1] * 255 / 100);
			}
			if ($type == 'hsl' || $type == 'hsla') {
				$cores[1] = $cores[1] / 100;
			}
		}

		if ($ncores > 2 && stristr($cores[2], '%')) {
			$cores[2] = (float) $cores[2];
			if ($type == 'rgb' || $type == 'rgba') {
				$cores[2] = (int) ($cores[2] * 255 / 100);
			}
			if ($type == 'hsl' || $type == 'hsla') {
				$cores[2] = $cores[2] / 100;
			}
		}

		if ($ncores > 3 && stristr($cores[3], '%')) {
			$cores[3] = (float) $cores[3];
		}

		switch ($type) {
			case 'rgb':
				return [3, $cores[0], $cores[1], $cores[2]];

			case 'rgba':
				return [5, $cores[0], $cores[1], $cores[2], $cores[3] * 100];

			case 'cmyk':
			case 'device-cmyk':
				return [4, $cores[0], $cores[1], $cores[2], $cores[3]];

			case 'cmyka':
			case 'device-cmyka':
				return [6, $cores[0], $cores[1], $cores[2], $cores[3], $cores[4] * 100];

			case 'hsl':
				$conv = $this->hsl2rgb($cores[0] / 360, $cores[1], $cores[2]);
				return [3, $conv[0], $conv[1], $conv[2]];

			case 'hsla':
				$conv = $this->hsl2rgb($cores[0] / 360, $cores[1], $cores[2]);
				return [5, $conv[0], $conv[1], $conv[2], $cores[3] * 100];

			case 'spot':
				$name = strtoupper(trim($cores[0]));

				if (!isset($this->mpdf->spotColors[$name])) {
					if (isset($cores[5])) {
						$this->mpdf->AddSpotColor($cores[0], $cores[2], $cores[3], $cores[4], $cores[5]);
					} else {
						throw new \Mpdf\MpdfException(sprintf('Undefined spot color "%s"', $name));
					}
				}

				return [2, $this->mpdf->spotColors[$name]['i'], $cores[1]];
		}

		return $c;
	}

	/**
	 * Process $this->mpdf->restrictColorSpace settings
	 *     1 - allow GRAYSCALE only [convert CMYK/RGB->gray]
	 *     2 - allow RGB / SPOT COLOR / Grayscale [convert CMYK->RGB]
	 *     3 - allow CMYK / SPOT COLOR / Grayscale [convert RGB->CMYK]
	 *
	 * @param mixed[] $c
	 * @param string $color
	 * @param string[] $PDFAXwarnings
	 *
	 * @return mixed[]
	 */
	private function restrictColorSpace($c, $color, &$PDFAXwarnings = [])
	{
		if ($c[0] == 1) { // GRAYSCALE
		} elseif ($c[0] == 2) { // SPOT COLOR

			if (!isset($this->mpdf->spotColorIDs[$c[1]])) {
				throw new \Mpdf\MpdfException('Error: Spot colour has not been defined - ' . $this->mpdf->spotColorIDs[$c[1]]);
			}

			if ($this->mpdf->PDFA) {
				if ($this->mpdf->PDFA && !$this->mpdf->PDFAauto) {
					$PDFAXwarnings[] = "Spot color specified '" . $this->mpdf->spotColorIDs[$c[1]] . "' (converted to process color)";
				}
				if ($this->mpdf->restrictColorSpace != 3) {
					$sp = $this->mpdf->spotColors[$this->mpdf->spotColorIDs[$c[1]]];
					$c = $this->cmyk2rgb([4, $sp['c'], $sp['m'], $sp['y'], $sp['k']]);
				}
			} elseif ($this->mpdf->restrictColorSpace == 1) {
				$sp = $this->mpdf->spotColors[$this->mpdf->spotColorIDs[$c[1]]];
				$c = $this->cmyk2gray([4, $sp['c'], $sp['m'], $sp['y'], $sp['k']]);
			}
		} elseif ($c[0] == 3) { // RGB
			if ($this->mpdf->PDFX || ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace == 3)) {
				if (($this->mpdf->PDFA && !$this->mpdf->PDFAauto) || ($this->mpdf->PDFX && !$this->mpdf->PDFXauto)) {
					$PDFAXwarnings[] = "RGB color specified '" . $color . "' (converted to CMYK)";
				}
				$c = $this->rgb2cmyk($c);
			} elseif ($this->mpdf->restrictColorSpace == 1) {
				$c = $this->rgb2gray($c);
			} elseif ($this->mpdf->restrictColorSpace == 3) {
				$c = $this->rgb2cmyk($c);
			}
		} elseif ($c[0] == 4) { // CMYK
			if ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace != 3) {
				if ($this->mpdf->PDFA && !$this->mpdf->PDFAauto) {
					$PDFAXwarnings[] = "CMYK color specified '" . $color . "' (converted to RGB)";
				}
				$c = $this->cmyk2rgb($c);
			} elseif ($this->mpdf->restrictColorSpace == 1) {
				$c = $this->cmyk2gray($c);
			} elseif ($this->mpdf->restrictColorSpace == 2) {
				$c = $this->cmyk2rgb($c);
			}
		} elseif ($c[0] == 5) { // RGBa
			if ($this->mpdf->PDFX || ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace == 3)) {
				if (($this->mpdf->PDFA && !$this->mpdf->PDFAauto) || ($this->mpdf->PDFX && !$this->mpdf->PDFXauto)) {
					$PDFAXwarnings[] = "RGB color with transparency specified '" . $color . "' (converted to CMYK without transparency)";
				}
				$c = $this->rgb2cmyk($c);
				$c = [4, $c[1], $c[2], $c[3], $c[4]];
			} elseif ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace != 3) {
				if (!$this->mpdf->PDFAauto) {
					$PDFAXwarnings[] = "RGB color with transparency specified '" . $color . "' (converted to RGB without transparency)";
				}
				$c = $this->rgb2cmyk($c);
				$c = [4, $c[1], $c[2], $c[3], $c[4]];
			} elseif ($this->mpdf->restrictColorSpace == 1) {
				$c = $this->rgb2gray($c);
			} elseif ($this->mpdf->restrictColorSpace == 3) {
				$c = $this->rgb2cmyk($c);
			}
		} elseif ($c[0] == 6) { // CMYKa
			if ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace != 3) {
				if (($this->mpdf->PDFA && !$this->mpdf->PDFAauto) || ($this->mpdf->PDFX && !$this->mpdf->PDFXauto)) {
					$PDFAXwarnings[] = "CMYK color with transparency specified '" . $color . "' (converted to RGB without transparency)";
				}
				$c = $this->cmyk2rgb($c);
				$c = [3, $c[1], $c[2], $c[3]];
			} elseif ($this->mpdf->PDFX || ($this->mpdf->PDFA && $this->mpdf->restrictColorSpace == 3)) {
				if (($this->mpdf->PDFA && !$this->mpdf->PDFAauto) || ($this->mpdf->PDFX && !$this->mpdf->PDFXauto)) {
					$PDFAXwarnings[] = "CMYK color with transparency specified '" . $color . "' (converted to CMYK without transparency)";
				}
				$c = $this->cmyk2rgb($c);
				$c = [3, $c[1], $c[2], $c[3]];
			} elseif ($this->mpdf->restrictColorSpace == 1) {
				$c = $this->cmyk2gray($c);
			} elseif ($this->mpdf->restrictColorSpace == 2) {
				$c = $this->cmyk2rgb($c);
			}
		}

		return $c;
	}
}
