<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorConverter;
use Mpdf\Mpdf;
use Mpdf\SizeConverter;

class ShadowParser
{

	/**
	 * @var Mpdf
	 */
	private $mpdf;

	/**
	 * @var SizeConverter
	 */
	private $sizeConverter;

	/**
	 * @var ColorConverter
	 */
	private $colorConverter;

	public function __construct(Mpdf $mpdf, SizeConverter $sizeConverter, ColorConverter $colorConverter)
	{
		$this->mpdf = $mpdf;
		$this->sizeConverter = $sizeConverter;
		$this->colorConverter = $colorConverter;
	}

	/**
	 * Normalize shadow colors.
	 *
	 * Replaces commas in color functions (rgb, hsl, etc.) with placeholders
	 * to prevent splitting multiple shadows on those commas.
	 *
	 * @param string $value Shadow property value
	 * @return string Normalized shadow property value
	 */
	public function normalizeShadowColors($value)
	{
		$c = preg_match_all('/(rgba|rgb|device-cmyka|cmyka|device-cmyk|cmyk|hsla|hsl)\(.*?\)/', $value, $x); // mPDF 5.6.05
		for ($i = 0; $i < $c; $i++) {
			$col = preg_replace('/,\s/', '*', $x[0][$i]);
			$value = str_replace($x[0][$i], $col, $value);
		}

		return $value;
	}

	/**
	 * Parse box-shadow CSS property.
	 *
	 * Converts box-shadow CSS property string into array format used internally.
	 * Handles multiple shadows, inset shadows, blur, spread, and colors.
	 *
	 * @param string $value Box-shadow property value
	 * @return array Array of shadow definitions
	 */
	public function parseBoxShadow($value)
	{
		$sh = [];
		$ss = explode(',', $this->normalizeShadowColors($value));
		foreach ($ss as $s) {
			$boxShadow = $this->parseSingleBoxShadow($s);
			if ($boxShadow) {
				array_unshift($sh, $boxShadow);
			}
		}

		return $sh;
	}

	/**
	 * Parse a single box-shadow definition.
	 *
	 * Helper method for setCSSboxshadow to parse individual shadow components
	 * (inset, x, y, blur, spread, color).
	 *
	 * @param string $s Shadow definition string
	 * @return array|null Parsed shadow array or null if invalid
	 */
	protected function parseSingleBoxShadow($s)
	{
		$boxShadow = [
			'inset' => false,
			'blur' => 0,
			'spread' => 0
		];

		if (stripos($s, 'inset') !== false) {
			$boxShadow['inset'] = true;
			$s = preg_replace('/\s*inset\s*/', '', $s);
		}

		$p = explode(' ', trim($s));
		if (isset($p[0])) {
			$parentWidth = 0;
			if (isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width'])) {
				$parentWidth = isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width']);
			} elseif (isset($this->mpdf->blk[0]['inner_width'])) {
				$parentWidth = $this->mpdf->blk[0]['inner_width'];
			}

			$boxShadow['x'] = $this->sizeConverter->convert(
				trim($p[0]),
				$parentWidth,
				$this->mpdf->FontSize,
				false
			);
		}

		if (isset($p[1])) {
			$parentWidth = 0;
			if (isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width'])) {
				$parentWidth = isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width']);
			} elseif (isset($this->mpdf->blk[0]['inner_width'])) {
				$parentWidth = $this->mpdf->blk[0]['inner_width'];
			}

			$boxShadow['y'] = $this->sizeConverter->convert(
				trim($p[1]),
				$parentWidth,
				$this->mpdf->FontSize,
				false
			);

		}

		if (isset($p[2])) {
			if (preg_match('/^\s*[\.\-0-9]/', $p[2])) {
				$parentWidth = 0;
				if (isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width'])) {
					$parentWidth = isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width']);
				} elseif (isset($this->mpdf->blk[0]['inner_width'])) {
					$parentWidth = $this->mpdf->blk[0]['inner_width'];
				}

				$boxShadow['blur'] = $this->sizeConverter->convert(
					trim($p[2]),
					$parentWidth,
					$this->mpdf->FontSize,
					false
				);
			} else {
				$boxShadow['col'] = $this->colorConverter->convert(
					preg_replace('/\*/', ',', $p[2]),
					$this->mpdf->PDFAXwarnings
				);
			}
		}

		if (isset($p[3])) {
			if (preg_match('/^\s*[\.\-0-9]/', $p[3])) {
				$parentWidth = 0;
				if (isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width'])) {
					$parentWidth = isset($this->mpdf->blk[$this->mpdf->blklvl - 1]['inner_width']);
				} elseif (isset($this->mpdf->blk[0]['inner_width'])) {
					$parentWidth = $this->mpdf->blk[0]['inner_width'];
				}

				$boxShadow['spread'] = $this->sizeConverter->convert(
					trim($p[3]),
					$parentWidth,
					$this->mpdf->FontSize,
					false
				);
			} else {
				$boxShadow['col'] = $this->colorConverter->convert(
					preg_replace('/\*/', ',', $p[3]),
					$this->mpdf->PDFAXwarnings
				);
			}
		}

		if (isset($p[4])) {
			$boxShadow['col'] = $this->colorConverter->convert(
				preg_replace('/\*/', ',', $p[4]),
				$this->mpdf->PDFAXwarnings
			);
		}

		if (empty($boxShadow['col'])) {
			$boxShadow['col'] = $this->colorConverter->convert('#888888', $this->mpdf->PDFAXwarnings);
		}

		return isset($boxShadow['y']) ? $boxShadow : null;
	}

	/**
	 * Parse text-shadow CSS property.
	 *
	 * Converts text-shadow CSS property string into array format used internally.
	 * Handles multiple shadows, blur, and colors.
	 *
	 * @param string $value Text-shadow property value
	 * @return array Array of text shadow definitions
	 */
	public function parseTextShadow($value)
	{
		$sh = [];
		$ss = explode(',', $this->normalizeShadowColors($value));

		foreach ($ss as $s) {
			$textShadow = $this->parseSingleTextShadow($s);
			if ($textShadow) {
				array_unshift($sh, $textShadow);
			}
		}

		return $sh;
	}

	/**
	 * Parse a single text-shadow definition.
	 *
	 * Helper method for setCSStextshadow to parse individual shadow components
	 * (x, y, blur, color).
	 *
	 * @param string $s Shadow definition string
	 * @return array|null Parsed shadow array or null if invalid
	 */
	protected function parseSingleTextShadow($s)
	{
		$textShadow = ['blur' => 0];
		$p = explode(' ', trim($s));

		if (isset($p[0])) {
			$textShadow['x'] = $this->sizeConverter->convert(
				trim($p[0]),
				$this->mpdf->FontSize,
				$this->mpdf->FontSize,
				false
			);
		}

		if (isset($p[1])) {
			$textShadow['y'] = $this->sizeConverter->convert(
				trim($p[1]),
				$this->mpdf->FontSize,
				$this->mpdf->FontSize,
				false
			);
		}

		if (isset($p[2])) {
			if (preg_match('/^\s*[\.\-0-9]/', $p[2])) {
				$textShadow['blur'] = $this->sizeConverter->convert(
					trim($p[2]),
					isset($this->mpdf->blk[$this->mpdf->blklvl]['inner_width']) ? $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'] : 0,
					$this->mpdf->FontSize,
					false
				);
			} else {
				$textShadow['col'] = $this->colorConverter->convert(
					preg_replace('/\*/', ',', $p[2]),
					$this->mpdf->PDFAXwarnings
				);
			}
		}

		if (isset($p[3])) {
			$textShadow['col'] = $this->colorConverter->convert(
				preg_replace('/\*/', ',', $p[3]),
				$this->mpdf->PDFAXwarnings
			);
		}

		if (empty($textShadow['col'])) {
			$textShadow['col'] = $this->colorConverter->convert(
				'#888888',
				$this->mpdf->PDFAXwarnings
			);
		}

		return isset($textShadow['y']) ? $textShadow : null;
	}
}
