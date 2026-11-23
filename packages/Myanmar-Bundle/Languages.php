<?php

namespace Mpdf\Fonts\MyanmarBundle;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// MYANMAR Burmese
			case 'my':
			case 'mya':

			// TAI_LE
			case 'tdd':
				return 'tharlon';
		}

		return '';
	}
}
