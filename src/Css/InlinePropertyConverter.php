<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorConverter;

class InlinePropertyConverter
{
	/**
	 * @var ColorConverter
	 */
	private $colorConverter;

	public function __construct(ColorConverter $colorConverter)
	{
		$this->colorConverter = $colorConverter;
	}

	/**
	 * Convert inline properties back to CSS.
	 *
	 * Transforms internal inline property format (used in TextVars) back into
	 * CSS property array. Used for property inheritance and cascading.
	 *
	 * @param array $properties Inline properties array
	 * @return array Converted CSS properties
	 */
	public function convert($properties)
	{
		$css = [];

		if (!empty($properties['family'])) {
			$css['FONT-FAMILY'] = $properties['family'];
		}

		if (!empty($properties['I'])) {
			$css['FONT-STYLE'] = 'italic';
		}

		if (!empty($properties['sizePt'])) {
			$css['FONT-SIZE'] = $properties['sizePt'] . 'pt';
		}

		if (!empty($properties['B'])) {
			$css['FONT-WEIGHT'] = 'bold';
		}

		if (!empty($properties['colorarray'])) {
			$css['COLOR'] = $this->colorConverter->colAtoString($properties['colorarray']);
		}

		if (!empty($properties['lSpacingCSS'])) {
			$css['LETTER-SPACING'] = $properties['lSpacingCSS'];
		}

		if (!empty($properties['wSpacingCSS'])) {
			$css['WORD-SPACING'] = $properties['wSpacingCSS'];
		}

		if (!empty($properties['textparam'])) {
			if (isset($properties['textparam']['hyphens'])) {
				$hyphens = (int) $properties['textparam']['hyphens'];
				switch ($hyphens) {
					case 1:
						$css['HYPHENS'] = 'auto';
						break;

					case 2:
						$css['HYPHENS'] = 'none';
						break;

					default:
						$css['HYPHENS'] = 'manual';
				}
			}

			if (isset($properties['textparam']['outline-s']) && !$properties['textparam']['outline-s']) {
				$css['TEXT-OUTLINE'] = 'none';
			}

			if (!empty($properties['textparam']['outline-COLOR'])) {
				$css['TEXT-OUTLINE-COLOR'] = $this->colorConverter->colAtoString($properties['textparam']['outline-COLOR']);
			}

			if (!empty($properties['textparam']['outline-WIDTH'])) {
				$css['TEXT-OUTLINE-WIDTH'] = $properties['textparam']['outline-WIDTH'] . 'mm';
			}
		}

		if (!empty($properties['textvar'])) {
			// CSS says text-decoration is not inherited, but IE7 does??
			if ($properties['textvar'] & TextVars::FD_LINETHROUGH) {
				if ($properties['textvar'] & TextVars::FD_UNDERLINE) {
					$css['TEXT-DECORATION'] = 'underline line-through';
				} else {
					$css['TEXT-DECORATION'] = 'line-through';
				}
			} elseif ($properties['textvar'] & TextVars::FD_UNDERLINE) {
				$css['TEXT-DECORATION'] = 'underline';
			} else {
				$css['TEXT-DECORATION'] = 'none';
			}

			if ($properties['textvar'] & TextVars::FA_SUPERSCRIPT) {
				$css['VERTICAL-ALIGN'] = 'super';
			} elseif ($properties['textvar'] & TextVars::FA_SUBSCRIPT) {
				$css['VERTICAL-ALIGN'] = 'sub';
			} else {
				$css['VERTICAL-ALIGN'] = 'baseline';
			}

			if ($properties['textvar'] & TextVars::FT_CAPITALIZE) {
				$css['TEXT-TRANSFORM'] = 'capitalize';
			} elseif ($properties['textvar'] & TextVars::FT_UPPERCASE) {
				$css['TEXT-TRANSFORM'] = 'uppercase';
			} elseif ($properties['textvar'] & TextVars::FT_LOWERCASE) {
				$css['TEXT-TRANSFORM'] = 'lowercase';
			} else {
				$css['TEXT-TRANSFORM'] = 'none';
			}

			if ($properties['textvar'] & TextVars::FC_KERNING) {
				$css['FONT-KERNING'] = 'normal';
			} else {
				$css['FONT-KERNING'] = 'none';
			} // ignore 'auto' as default already applied

			if ($properties['textvar'] & TextVars::FA_SUPERSCRIPT) {
				$css['FONT-VARIANT-POSITION'] = 'super';
			} elseif ($properties['textvar'] & TextVars::FA_SUBSCRIPT) {
				$css['FONT-VARIANT-POSITION'] = 'sub';
			} else {
				$css['FONT-VARIANT-POSITION'] = 'normal';
			}

			if ($properties['textvar'] & TextVars::FC_SMALLCAPS) {
				$css['FONT-VARIANT-CAPS'] = 'small-caps';
			}
		}

		if (isset($properties['fontLanguageOverride'])) {
			if ($properties['fontLanguageOverride']) {
				$css['FONT-LANGUAGE-OVERRIDE'] = $properties['fontLanguageOverride'];
			} else {
				$css['FONT-LANGUAGE-OVERRIDE'] = 'normal';
			}
		}

		// All the variations of font-variant-* we are going to set as font-feature-settings...
		if (!empty($properties['OTLtags'])) {
			$fontFeature = [];
			if (!empty($properties['OTLtags']['Minus'])) {
				$f = preg_split('/\s+/', trim($properties['OTLtags']['Minus']));
				foreach ($f as $ff) {
					$fontFeature[] = "'" . $ff . "' 0";
				}
			}

			if (!empty($properties['OTLtags']['FFMinus'])) {
				$f = preg_split('/\s+/', trim($properties['OTLtags']['FFMinus']));
				foreach ($f as $ff) {
					$fontFeature[] = "'" . $ff . "' 0";
				}
			}

			if (!empty($properties['OTLtags']['Plus'])) {
				$f = preg_split('/\s+/', trim($properties['OTLtags']['Plus']));
				foreach ($f as $ff) {
					$fontFeature[] = "'" . $ff . "' 1";
				}
			}

			if (!empty($properties['OTLtags']['FFPlus'])) { // May contain numeric value e.g. salt4
				$f = preg_split('/\s+/', trim($properties['OTLtags']['FFPlus']));
				foreach ($f as $ff) {
					if (strlen($ff) > 4) {
						$fontFeature[] = "'" . substr($ff, 0, 4) . "' " . substr($ff, 4);
					} else {
						$fontFeature[] = "'" . $ff . "' 1";
					}
				}
			}

			$css['FONT-FEATURE-SETTINGS'] = implode(', ', $fontFeature);
		}

		return $css;
	}
}
