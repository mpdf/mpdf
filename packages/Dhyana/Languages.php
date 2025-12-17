<?php

namespace Mpdf\Fonts\Dhyana;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {

			// LAO
			case 'lo':
			case 'lao':
				return 'dhyana';
		}

		return '';
	}
}
