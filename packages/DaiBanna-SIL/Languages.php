<?php

namespace Mpdf\Fonts\DaiBannaSil;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		$script = '';
		if (!empty($tags[1]) && strlen($tags[1]) === 4) {
			$script = strtolower($tags[1]);
		}

		switch ($language) {
			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			case 'talu': // NEW_TAI_LUE
				return 'daibannasil';
		}

		return '';
	}
}
