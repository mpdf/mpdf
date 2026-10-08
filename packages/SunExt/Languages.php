<?php

namespace Mpdf\Fonts\SunExt;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		/* Skip if meant to use native adobeCJK fonts */
		if ($adobeCJK) {
			return '';
		}

		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);
		$script = '';
		if (!empty($tags[1]) && strlen($tags[1]) === 4) {
			$script = strtolower($tags[1]);
		}

		switch ($language) {

			// LIMBU
			case 'lif':

			// Chinese
			case 'zh':
			case 'zho':

			// Japanese HIRAGANA KATAKANA
			case 'ja':
			case 'jpn':

			// Nuosu; Yi
			case 'ii':
			case 'iii':
				return 'sun-exta';

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			// RUNIC
			case 'runr':

			// HAN (SIMPLIFIED)
			case 'hans':

			// BOPOMOFO
			case 'bopo':

			// YI
			case 'yiii':
				return 'sun-exta';
		}

		return '';
	}

}
