<?php

namespace Mpdf\Fonts\DejavuFamily;

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
			// Russian	// CYRILLIC
			case 'ru':
			case 'rus':

			// Abkhaz
			case 'ab':
			case 'abk':

			// Avaric
			case 'av':
			case 'ava':

			// Bashkir
			case 'ba':
			case 'bak':

			// Belarusian
			case 'be':
			case 'bel':

			// Bulgarian
			case 'bg':
			case 'bul':

			// Chechen
			case 'ce':
			case 'che':

			// Chuvash
			case 'cv':
			case 'chv':

			// Kazakh
			case 'kk':
			case 'kaz':

			// Komi
			case 'kv':
			case 'kom':

			// Kyrgyz
			case 'ky':
			case 'kir':

			// Macedonian
			case 'mk':
			case 'mkd':

			// Old Church Slavonic
			case 'cu':
			case 'chu':

			// Ossetian
			case 'os':
			case 'oss':

			// Serbian
			case 'sr':
			case 'srp':

			// Tajik
			case 'tg':
			case 'tgk':

			// Tatar
			case 'tt':
			case 'tat':

			// Turkmen
			case 'tk':
			case 'tuk':

			// Ukrainian
			case 'uk':
			case 'ukr':

			// GREEK
			case 'el':
			case 'ell':

			// Hindi	DEVANAGARI
			case 'hi':
			case 'hin':

			// Bihari (Bhojpuri, Magahi, and Maithili)
			case 'bh':
			case 'bih':

			// VIETNAMESE
			case 'vi':
			case 'vie':

			// ARMENIAN
			case 'hy':
			case 'hye':

			// GEORGIAN
			case 'ka':
			case 'kat':

			// N'Ko
			case 'nqo':
				return 'dejavusans';

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
				return 'dejavusanscondensed';

			case 'ogam': // OGHAM
			case 'tfng': // TIFINAGH
			case 'brai': // BRAILLE
				return 'dejavusans';
		}

		return '';
	}
}
