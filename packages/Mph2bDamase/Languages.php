<?php

namespace Mpdf\Fonts\Mph2bDamase;

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
			// SYLOTI_NAGRI
			case 'syl':
				return 'mph2bdamase';

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			case 'glag': // GLAGOLITIC
			case 'shaw': // SHAVIAN
			case 'osma': // OSMANYA
			case 'khar': // KHAROSHTHI
			case 'dsrt': // DESERET
				return 'mph2bdamase';
		}

		return '';
	}
}
