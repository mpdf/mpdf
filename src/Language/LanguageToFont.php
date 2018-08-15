<?php

namespace Mpdf\Language;

class LanguageToFont implements \Mpdf\Language\LanguageToFontInterface
{

	public function getLanguageOptions($llcc, $adobeCJK)
	{
		$tags = explode('-', $llcc);
		$lang = strtolower($tags[0]);
		$country = '';
		$script = '';
		if (!empty($tags[1])) {
			if (strlen($tags[1]) === 4) {
				$script = strtolower($tags[1]);
			} else {
				$country = strtolower($tags[1]);
			}
		}
		if (!empty($tags[2])) {
			$country = strtolower($tags[2]);
		}

		$unifont = '';
		$coreSuitable = false;

		switch ($lang) {
			/* European */
			case 'en':
			case 'eng': // English		// LATIN
			case 'eu':
			case 'eus': // Basque
			case 'br':
			case 'bre': // Breton
			case 'ca':
			case 'cat': // Catalan
			case 'co':
			case 'cos': // Corsican
			case 'kw':
			case 'cor': // Cornish
			case 'cy':
			case 'cym': // Welsh
			case 'cs':
			case 'ces': // Czech
			case 'da':
			case 'dan': // Danish
			case 'nl':
			case 'nld': // Dutch
			case 'et':
			case 'est': // Estonian
			case 'fo':
			case 'fao': // Faroese
			case 'fi':
			case 'fin': // Finnish
			case 'fr':
			case 'fra': // French
			case 'gl':
			case 'glg': // Galician
			case 'de':
			case 'deu': // German
			case 'ht':
			case 'hat': // Haitian; Haitian Creole
			case 'hu':
			case 'hun': // Hungarian
			case 'ga':
			case 'gle': // Irish
			case 'is':
			case 'isl': // Icelandic
			case 'it':
			case 'ita': // Italian
			case 'la':
			case 'lat': // Latin
			case 'lb':
			case 'ltz': // Luxembourgish
			case 'li':
			case 'lim': // Limburgish
			case 'lt':
			case 'lit': // Lithuanian
			case 'lv':
			case 'lav': // Latvian
			case 'gv':
			case 'glv': // Manx
			case 'no':
			case 'nor': // Norwegian
			case 'nn':
			case 'nno': // Norwegian Nynorsk
			case 'nb':
			case 'nob': // Norwegian Bokmål
			case 'pl':
			case 'pol': // Polish
			case 'pt':
			case 'por': // Portuguese
			case 'ro':
			case 'ron': // Romanian
			case 'gd':
			case 'gla': // Scottish Gaelic
			case 'es':
			case 'spa': // Spanish
			case 'sv':
			case 'swe': // Swedish
			case 'sl':
			case 'slv': // Slovene
			case 'sk':
			case 'slk': // Slovak
				$coreSuitable = true;
				break;

			case 'cop':  // COPTIC
				$unifont = 'quivira';
				break;

			/* African */
			case 'am':
			case 'amh': // Amharic ETHIOPIC
			case 'ti':
			case 'tir': // Tigrinya ETHIOPIC
					$unifont = 'abyssinicasil';
				break;

			/* Middle Eastern */
			case 'ar':
			case 'ara': // Arabic	NB Arabic text identified by Autofont will be marked as und-Arab
				$unifont = 'xbriyaz';
				break;
			case 'fa':
			case 'fas': // Persian (Farsi)
				$unifont = 'xbriyaz';
				break;
			case 'ps':
			case 'pus': // Pashto
				$unifont = 'xbriyaz';
				break;
			case 'ku':
			case 'kur': // Kurdish
				$unifont = 'xbriyaz';
				break;
			case 'ur':
			case 'urd': // Urdu
				$unifont = 'xbriyaz';
				break;
			case 'he':
			case 'heb': // HEBREW
			case 'yi':
			case 'yid': // Yiddish
					$unifont = 'taameydavidclm'; // dejavusans,dejavusanscondensed,freeserif are fine if you do not need cantillation marks
				break;

			case 'syr':  // SYRIAC
				$unifont = 'estrangeloedessa';
				break;

			//CASE 'arc':	// IMPERIAL_ARAMAIC
			//CASE ''ae:	// AVESTAN
			case 'xcr':  // CARIAN
				$unifont = 'aegean';
				break;
			case 'xlc':  // LYCIAN
				$unifont = 'aegean';
				break;
			case 'xld':  // LYDIAN
				$unifont = 'aegean';
				break;
			//CASE 'mid':	// MANDAIC
			//CASE 'peo':	// OLD_PERSIAN
			case 'phn':  // PHOENICIAN
				$unifont = 'aegean';
				break;
			//CASE 'smp':	// SAMARITAN
			case 'uga':  // UGARITIC
				$unifont = 'aegean';
				break;

			/* Central Asian */
			case 'bo':
			case 'bod': // TIBETAN
			case 'dz':
			case 'dzo': // Dzongkha
					$unifont = 'jomolhari';
				break;

			//CASE 'mn':  CASE 'mon':	// MONGOLIAN	(Vertical script)
			//CASE 'ug':  CASE 'uig':	// Uyghur
			//CASE 'uz':  CASE 'uzb':	// Uzbek
			//CASE 'az':  CASE 'azb':	// South Azerbaijani

			/* South Asian */
			case 'kn':
			case 'kan': // Kannada
				$unifont = 'lohitkannada';
				break;
			case 'mr':
			case 'mar': // Marathi
				$unifont = 'freeserif';
				break;
			case 'si':
			case 'sin': // SINHALA
				$unifont = 'kaputaunicode';
				break;
			case 'te':
			case 'tel': // TELUGU
				$unifont = 'pothana2000';
				break;

			// Sindhi (Arabic or Devanagari)
			case 'sd':
			case 'snd': // Sindhi
				$unifont = 'lateef';
				break;

			//CASE 'ccp':	// CHAKMA
			//CASE 'lep':	// LEPCHA
			case 'lif':  // LIMBU
				$unifont = 'sun-exta';
				break;
			//CASE 'sat':	// OL_CHIKI
			//CASE 'saz':	// SAURASHTRA
			case 'syl':  // SYLOTI_NAGRI
				$unifont = 'mph2bdamase';
				break;

			/* South East Asian */
			case 'km':
			case 'khm': // KHMER
				$unifont = 'khmeros';
				break;
			case 'lo':
			case 'lao': // LAO
				$unifont = 'dhyana';
				break;
			case 'my':
			case 'mya': // MYANMAR Burmese
				$unifont = 'tharlon'; // zawgyi-one is non-unicode compliant but in wide usage
				// ayar is also not strictly compliant
				// padaukbook is unicode compliant
				break;
			case 'th':
			case 'tha': // THAI
				$unifont = 'garuda';
				break;

			// VIETNAMESE
			//CASE 'cjm':	// CHAM
			//CASE 'jv':	// JAVANESE
			case 'su':  // SUNDANESE
				$unifont = 'sundaneseunicode';
				break;
			case 'tdd':  // TAI_LE
				$unifont = 'tharlon';
				break;
			case 'blt':  // TAI_VIET
				$unifont = 'taiheritagepro';
				break;

			/* Phillipine */
			case 'bku':  // BUHID
				$unifont = 'quivira';
				break;
			case 'hnn':  // HANUNOO
				$unifont = 'quivira';
				break;
			case 'tl':  // TAGALOG
				$unifont = 'quivira';
				break;
			case 'tbw':  // TAGBANWA
				$unifont = 'quivira';
				break;

			/* East Asian */
			case 'zh':
			case 'zho': // Chinese
				$unifont = 'sun-exta';
				if ($adobeCJK) {
					$unifont = 'gb';
					if ($country === 'hk' || $country === 'tw') {
						$unifont = 'big5';
					}
				}
				break;
			case 'ko':
			case 'kor': // HANGUL Korean
				$unifont = 'unbatang';
				if ($adobeCJK) {
					$unifont = 'uhc';
				}
				break;
			case 'ja':
			case 'jpn': // Japanese HIRAGANA KATAKANA
				$unifont = 'sun-exta';
				if ($adobeCJK) {
					$unifont = 'sjis';
				}
				break;
			case 'ii':
			case 'iii': // Nuosu; Yi
				$unifont = 'sun-exta';
				if ($adobeCJK) {
					$unifont = 'gb';
				}
				break;
			case 'lis':  // LISU
				$unifont = 'quivira';
				break;

			/* American */
			case 'chr':  // CHEROKEE
			case 'oj':
			case 'oji': // Ojibwe; Chippewa
			case 'cr':
			case 'cre': // Cree CANADIAN_ABORIGINAL
			case 'iu':
			case 'iku': // Inuktitut
				$unifont = 'aboriginalsans';
				break;

			/* Undetermined language - script used */
			case 'und':
				$unifont = $this->fontByScript($script, $adobeCJK);
				break;
		}

		return [$coreSuitable, $unifont];
	}

	protected function fontByScript($script, $adobeCJK)
	{
		switch ($script) {
			/* European */
			case 'cprt': // CYPRIOT
				return 'aegean';
			case 'glag': // GLAGOLITIC
				return 'mph2bdamase';
			case 'linb': // LINEAR_B
				return 'aegean';
			case 'ital': // OLD_ITALIC
				return 'aegean';
			case 'runr': // RUNIC
				return 'sun-exta';
			case 'shaw': // SHAVIAN
				return 'mph2bdamase';

			/* African */
			case 'egyp': // EGYPTIAN_HIEROGLYPHS
				return 'aegyptus';
			case 'ethi': // ETHIOPIC
				return 'abyssinicasil';
			//CASE 'merc':	// MEROITIC_CURSIVE
			//CASE 'mero':	// MEROITIC_HIEROGLYPHS
			case 'osma': // OSMANYA
				return 'mph2bdamase';

			/* Middle Eastern */
			case 'arab':  // ARABIC
				return 'xbriyaz';
			case 'xsux': // CUNEIFORM
				return 'akkadian';
			//CASE 'sarb':	// OLD_SOUTH_ARABIAN
			//CASE 'prti':	// INSCRIPTIONAL_PARTHIAN
			//CASE 'phli':	// INSCRIPTIONAL_PAHLAVI


			/* Central Asian */
			//CASE 'orkh':	// OLD_TURKIC
			//CASE 'phag':	// PHAGS_PA		(Vertical script)

			/* South Asian */
			//CASE 'brah':	// BRAHMI
			//CASE 'kthi':	// KAITHI
			case 'khar': // KHAROSHTHI
				return 'mph2bdamase';
			case 'mtei': // MEETEI_MAYEK
				return 'eeyekunicode';
			//CASE 'shrd':	// SHARADA
			//CASE 'sora':	// SORA_SOMPENG

			/* South East Asian */
			//CASE 'rjng':	// REJANG
			case 'lana': // TAI_THAM
				return 'lannaalif';
			case 'talu': // NEW_TAI_LUE
				return 'daibannasilbook';

			/* East Asian */
			case 'hans': // HAN (SIMPLIFIED)
				if ($adobeCJK) {
					return 'gb';
				}
				return 'sun-exta';
			case 'bopo': // BOPOMOFO
				return 'sun-exta';
			//CASE 'plrd':	// MIAO
			case 'yiii': // YI
				return 'sun-exta';

			/* American */
			case 'dsrt': // DESERET
				return 'mph2bdamase';
		}

		return null;
	}

}
