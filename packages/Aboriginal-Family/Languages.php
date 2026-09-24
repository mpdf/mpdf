<?php

namespace Mpdf\Fonts\AboriginalFamily;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		switch ($language) {
			// CHEROKEE
			case 'chr':

			// Ojibwe; Chippewa
			case 'oj':
			case 'oji':

			// Cree CANADIAN_ABORIGINAL
			case 'cr':
			case 'cre':

			// Inuktitut
			case 'iu':
			case 'iku':
				return 'aboriginalsans';
		}

		return '';
	}
}
