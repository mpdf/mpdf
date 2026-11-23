<?php

namespace Mpdf\Fonts\AncientScriptsBundle;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
		$language = strtolower($tags[0]);

		$script  = '';
		if (! empty($tags[1]) && strlen($tags[1]) === 4) {
			$script = strtolower($tags[1]);
		}

		switch ($language) {
			// CARIAN
			case 'xcr':

				// LYCIAN
			case 'xlc':

				// LYDIAN
			case 'xld':

				// PHOENICIAN
			case 'phn':

				// UGARITIC
			case 'uga':
				return 'aegean';

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			// EGYPTIAN HIEROGLYPHS
			case 'egyp':
				return 'aegyptus';

			case 'cprt': // CYPRIOT
			case 'linb': // LINEAR_B
			case 'ital': // OLD_ITALIC
				return 'aegean';

			case 'xsux': // CUNEIFORM
				return 'akkadian';
		}

		return '';
	}
}
