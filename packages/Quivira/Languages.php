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
		switch ($script) {
			case 'latn': // LATIN
			case 'cyrl': // CYRILLIC
			case 'tfng': // TIFINAGH
			case 'brai': // BRAILLE
			case 'ogam': // OGHAM
			case 'runr': // RUNIC
			case 'glag': // GLAGOLITIC
				return 'quivira';
		}

		return '';
	}
}
