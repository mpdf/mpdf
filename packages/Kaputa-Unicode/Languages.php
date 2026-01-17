<?php

namespace Mpdf\Fonts\KaputaUnicode;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// SINHALA
			case 'si':
			case 'sin':
				return 'kaputaunicode';
		}

		return '';
	}
}
