<?php

namespace Mpdf\Fonts\SundaneseUnicode;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// SUNDANESE
			case 'su':
				return 'sundaneseunicode';
		}

		return '';
	}
}
