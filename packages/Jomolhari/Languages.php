<?php

namespace Mpdf\Fonts\Jomolhari;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// Tibetan
			case 'bo':
			case 'bod':

			// Dzongkha
			case 'dz':
			case 'dzo':
				return 'jomolhari';
		}

		return '';
	}
}
