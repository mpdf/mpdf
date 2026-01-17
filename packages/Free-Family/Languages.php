<?php

namespace Mpdf\Fonts\FreeFamily;

use Mpdf\Language\LanguageToFontInterface;

class Languages implements LanguageToFontInterface
{
	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags    = explode('-', $mode);
		$language    = strtolower($tags[0]);

		$country = '';
		$script  = '';
		if (! empty($tags[1])) {
			if (strlen($tags[1]) === 4) {
				$script = strtolower($tags[1]);
			} else {
				$country = strtolower($tags[1]);
			}
		}
		if (! empty($tags[2])) {
			$country = strtolower($tags[2]);
		}

		switch ($language) {
			// GOTHIC
			case 'got':

			// Vai (Liberian, Vy or Gallinas)
			case 'vai':

			// Assamese
			case 'as':
			case 'asm':

			// BENGALI; Bangla
			case 'bn':
			case 'ben':

			// Kashmiri
			case 'ks':
			case 'kas':

			// Hindi	DEVANAGARI
			case 'hi':
			case 'hin':

			// Bihari (Bhojpuri, Magahi, and Maithili)
			case 'bh':
			case 'bih':

			// Sanskrit
			case 'sa':
			case 'san':

			// Gujarati
			case 'gu':
			case 'guj':

			// Panjabi, Punjabi GURMUKHI
			case 'pa':
			case 'pan':

			// Marathi
			case 'mr':
			case 'mar':

			// MALAYALAM
			case 'ml':
			case 'mal':

			// Nepali
			case 'ne':
			case 'nep':

			// ORIYA
			case 'or':
			case 'ori':

			// TAMIL
			case 'ta':
			case 'tam':

			// Divehi; Maldivian  THAANA
			case 'dv':
			case 'div':

			// BUGINESE
			case 'bug':
				return 'freeserif';

			// Sindhi (Arabic or Devanagari)
			case 'sd':
			case 'snd':
				if ($country === 'in') {
					return 'freeserif';
				}

			/* Undetermined language - script used */
			case 'und':
				return $this->fontByScript($script);
		}

		return '';
	}

	protected function fontByScript($script)
	{
		switch ($script) {
			/* South East Asian */
			case 'kali': // KAYAH_LI
				return 'freemono';
		}

		return '';
	}

}
