<?php

namespace Mpdf\Fonts\Quivira;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		$script  = '';
		if (! empty($tags[1]) && strlen($tags[1]) === 4) {
			$script = strtolower($tags[1]);
		}

		switch ($language) {
			// COPTIC
			case 'cop':

			// BUHID
			case 'bku':

			// HANUNOO
			case 'hnn':

			// TAGALOG
			case 'tl':

			// TAGBANWA
			case 'tbw':

			// LISU
			case 'lis':
				return 'quivira';

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		/*
		 * Quivira covers Latin, Cyrillic, Tifinagh, Braille, Ogham, Runic and Glagolitic, but it is not the
		 * font mPDF picks for them: those scripts belong to DejaVu, Sun-ExtA and Mph2b Damase. Claiming them
		 * here would win on registration order and silently retarget the two most common scripts in the
		 * document, so this package answers only for the languages named above.
		 */
		return '';
	}
}
