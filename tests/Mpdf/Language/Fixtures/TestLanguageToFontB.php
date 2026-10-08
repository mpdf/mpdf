<?php

namespace Mpdf\Language\Fixtures;

use Mpdf\Language\LanguageToFontInterface;

class TestLanguageToFontB implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		if ($mode === 'core_b') {
			return 'font_b';
		}

		if ($mode === 'core_a') {
			return 'font_b_override';
		}

		return '';
	}
}
