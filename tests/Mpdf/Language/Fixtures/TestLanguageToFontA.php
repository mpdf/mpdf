<?php

namespace Mpdf\Language\Fixtures;

use Mpdf\Language\LanguageToFontInterface;

class TestLanguageToFontA implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		if ($mode === 'core_a') {
			return 'font_a';
		}

		return '';
	}
}
