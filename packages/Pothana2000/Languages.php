<?php

namespace Mpdf\Fonts\Pothana2000;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// TELUGU
			case 'te':
			case 'tel':
				return 'pothana2000';
		}

		return '';
	}
}
