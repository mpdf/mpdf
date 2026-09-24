<?php

namespace Mpdf\Fonts\LohitKannada;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// Kannada
			case 'kn':
			case 'kan':
				return 'lohitkannada';
		}

		return '';
	}
}
