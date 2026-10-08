<?php

namespace Mpdf\Fonts\TaiHeritagePro;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// TAI_VIET
			case 'blt':
				return 'taiheritagepro';
		}

		return '';
	}
}
