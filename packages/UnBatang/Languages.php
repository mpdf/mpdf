<?php

namespace Mpdf\Fonts\UnBatang;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		/* Skip if meant to use native adobeCJK fonts */
		if ($adobeCJK) {
			return '';
		}

		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			/// HANGUL Korean
			case 'ko':
			case 'kor':
				return 'unbatang';
		}

		return '';
	}
}
