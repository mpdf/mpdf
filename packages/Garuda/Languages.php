<?php

namespace Mpdf\Fonts\Garuda;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			case 'th':
			case 'tha': // THAI
				return 'garuda';
		}

		return '';
	}
}
