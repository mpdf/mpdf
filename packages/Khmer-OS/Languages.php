<?php

namespace Mpdf\Fonts\KhmerOs;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// KHMER
			case 'km':
			case 'khm':
				return 'khmeros';
		}

		return '';
	}
}
