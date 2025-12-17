<?php

namespace Mpdf\Fonts\ArabicScriptsBundle;

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

			// Arabic
			case 'ar':
			case 'ara':

			// Persian (Farsi)
			case 'fa':
			case 'fas':

			// Pashto
			case 'ps':
			case 'pus':

			// Kurdish
			case 'ku':
			case 'kur':

			// Urdu
			case 'ur':
			case 'urd':
				return 'xbriyaz';

			case 'sd':
			case 'snd': // Sindhi
				return 'lateef';

			// SYRIAC
			case 'syr':
				return 'estrangeloedessa';

			// HEBREW
			case 'he':
			case 'heb':

			// Yiddish
			case 'yi':
			case 'yid':
				return 'taameydavidclm';

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			case 'arab':  // ARABIC
				return 'xbriyaz';
		}

		return '';
	}
}
