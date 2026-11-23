<?php

namespace Mpdf\Language;

class LanguageToFont implements \Mpdf\Language\LanguageToFontInterface
{

	public function getLanguageOptions($mode, $adobeCJK)
	{
		$tags = explode('-', $mode);
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
			case 'eng': // English
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

			case 'ru':
			case 'rus': // Russian	// CYRILLIC
			case 'ab':
			case 'abk': // Abkhaz
			case 'av':
			case 'ava': // Avaric
			case 'ba':
			case 'bak': // Bashkir
			case 'be':
			case 'bel': // Belarusian
			case 'bg':
			case 'bul': // Bulgarian
			case 'ce':
			case 'che': // Chechen
			case 'cv':
			case 'chv': // Chuvash
			case 'kk':
			case 'kaz': // Kazakh
			case 'kv':
			case 'kom': // Komi
			case 'ky':
			case 'kir': // Kyrgyz
			case 'mk':
			case 'mkd': // Macedonian
			case 'cu':
			case 'chu': // Old Church Slavonic
			case 'os':
			case 'oss': // Ossetian
			case 'sr':
			case 'srp': // Serbian
			case 'tg':
			case 'tgk': // Tajik
			case 'tt':
			case 'tat': // Tatar
			case 'tk':
			case 'tuk': // Turkmen
			case 'uk':
			case 'ukr': // Ukrainian
			case 'hy':
			case 'hye': // ARMENIAN
			case 'ka':
			case 'kat': // GEORGIAN
			case 'el':
			case 'ell': // GREEK
			case 'cop': // COPTIC
			case 'got': // GOTHIC

			/* African */
			case 'nqo': // NKO
			case 'bax':	// BAMUM
			case 'ha':
			case 'hau':	// Hausa
			case 'vai': // VAI
			case 'am':
			case 'amh': // Amharic ETHIOPIC
			case 'ti':
			case 'tir': // Tigrinya ETHIOPIC

			/* Middle Eastern */
			case 'ar':
			case 'ara': // Arabic	NB Arabic text identified by Autofont will be marked as und-Arab
			case 'fa':
			case 'fas': // Persian (Farsi)
			case 'ps':
			case 'pus': // Pashto
			case 'ku':
			case 'kur': // Kurdish
			case 'ur':
			case 'urd': // Urdu
			case 'he':
			case 'heb': // HEBREW
			case 'yi':
			case 'yid': // Yiddish
			case 'syr': // SYRIAC
			case 'arc':	// IMPERIAL_ARAMAIC
			case 'ae':	// AVESTAN
			case 'xcr': // CARIAN
			case 'xlc': // LYCIAN
			case 'xld': // LYDIAN
			case 'mid':	// MANDAIC
			case 'peo':	// OLD_PERSIAN
			case 'phn': // PHOENICIAN
			case 'smp':	// SAMARITAN
			case 'uga': // UGARITIC

			/* Central Asian */
			case 'bo':
			case 'bod': // TIBETAN
			case 'dz':
			case 'dzo': // Dzongkha
			case 'mn':
			case 'mon':	// MONGOLIAN	(Vertical script)
			case 'ug':
			case 'uig':	// Uyghur
			case 'uz':
			case 'uzb':	// Uzbek
			case 'az':
			case 'azb':	// South Azerbaijani

			/* South Asian */
			case 'as':
			case 'asm': // Assamese
			case 'bn':
			case 'ben': // BENGALI; Bangla
			case 'ks':
			case 'kas': // Kashmiri
			case 'hi':
			case 'hin': // Hindi	DEVANAGARI
			case 'bh':
			case 'bih': // Bihari (Bhojpuri, Magahi, and Maithili)
			case 'sa':
			case 'san': // Sanskrit
			case 'gu':
			case 'guj': // Gujarati
			case 'pa':
			case 'pan': // Panjabi, Punjabi GURMUKHI
			case 'kn':
			case 'kan': // Kannada
			case 'mr':
			case 'mar': // Marathi
			case 'ml':
			case 'mal': // MALAYALAM
			case 'ne':
			case 'nep': // Nepali
			case 'or':
			case 'ori': // ORIYA
			case 'si':
			case 'sin': // SINHALA
			case 'ta':
			case 'tam': // TAMIL
			case 'te':
			case 'tel': // TELUGU
				// Do nothing
				break;

			// Sindhi (Arabic or Devanagari)
			case 'sd':
			case 'snd': // Sindhi
				if ($country === 'in') {
					$unifont = 'freeserif';
				}
				break;

			case 'ccp':	// CHAKMA
			case 'lep':	// LEPCHA
			case 'lif': // LIMBU
			case 'sat':	// OL_CHIKI
			case 'saz':	// SAURASHTRA
			case 'syl': // SYLOTI_NAGRI
			case 'dgo':	// TAKRI
			case 'dv':
			case 'div': // Divehi; Maldivian  THAANA

			/* South East Asian */
			case 'km':
			case 'khm': // KHMER
			case 'lo':
			case 'lao': // LAO
			case 'my':
			case 'mya': // MYANMAR Burmese
			case 'th':
			case 'tha': // THAI
			case 'vi':
			case 'vie': // Vietnamese
			case 'ms':
			case 'msa':	// Malay
			case 'ban':	// BALINESE
			case 'bya':	// BATAK
			case 'bug': // BUGINESE
			case 'cjm':	// CHAM
			case 'jv':	// JAVANESE
			case 'su': // SUNDANESE
			case 'tdd': // TAI_LE
			case 'blt': // TAI_VIET

			/* Phillipine */
			case 'bku': // BUHID
			case 'hnn': // HANUNOO
			case 'tl': // TAGALOG
			case 'tbw': // TAGBANWA
				// Do nothing
				break;

			/* East Asian */
			case 'zh':
			case 'zho': // Chinese
				if ($adobeCJK) {
					$unifont = 'gb';
					if ($country === 'hk' || $country === 'tw') {
						$unifont = 'big5';
					}
				}
				break;

			case 'ko':
			case 'kor': // HANGUL Korean
				if ($adobeCJK) {
					$unifont = 'uhc';
				}
				break;

			case 'ja':
			case 'jpn': // Japanese HIRAGANA KATAKANA
				if ($adobeCJK) {
					$unifont = 'sjis';
				}
				break;

			case 'ii':
			case 'iii': // Nuosu; Yi
				if ($adobeCJK) {
					$unifont = 'gb';
				}
				break;

			case 'lis': // LISU

			/* American */
			case 'chr': // CHEROKEE
			case 'oj':
			case 'oji': // Ojibwe; Chippewa
			case 'cr':
			case 'cre': // Cree CANADIAN_ABORIGINAL
			case 'iu':
			case 'iku': // Inuktitut
				// Do nothing
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
			case 'latn': // LATIN
			case 'cyrl': // CYRILLIC
			case 'cprt': // CYPRIOT
			case 'glag': // GLAGOLITIC;
			case 'linb': // LINEAR_B
			case 'ogam': // OGHAM
			case 'ital': // OLD_ITALIC
			case 'runr': // RUNIC
			case 'shaw': // SHAVIAN

			/* African */
			case 'egyp': // EGYPTIAN_HIEROGLYPHS
			case 'ethi': // ETHIOPIC
			case 'merc': // MEROITIC_CURSIVE
			case 'mero': // MEROITIC_HIEROGLYPHS
			case 'osma': // OSMANYA
			case 'tfng': // TIFINAGH

			/* Middle Eastern */
			case 'arab': // ARABIC
			case 'xsux': // CUNEIFORM
			case 'sarb': // OLD_SOUTH_ARABIAN
			case 'prti': // INSCRIPTIONAL_PARTHIAN
			case 'phli': // INSCRIPTIONAL_PAHLAVI

			/* Central Asian */
			case 'orkh': // OLD_TURKIC
			case 'phag': // PHAGS_PA (Vertical script)

			/* South Asian */
			case 'brah': // BRAHMI
			case 'kthi': // KAITHI
			case 'khar': // KHAROSHTHI
			case 'mtei': // MEETEI_MAYEK
			case 'shrd': // SHARADA
			case 'sora': // SORA_SOMPENG

			/* South East Asian */
			case 'kali': // KAYAH_LI
			case 'rjng': // REJANG
			case 'lana': // TAI_THAM
			case 'talu': // NEW_TAI_LUE
				// Do nothing
				break;

			/* East Asian */
			case 'hans': // HAN (SIMPLIFIED)
				if ($adobeCJK) {
					return 'gb';
				}
				break;

			case 'bopo': // BOPOMOFO
			case 'plrd': // MIAO
			case 'yiii': // YI

			/* American */
			case 'dsrt': // DESERET

			/* Other */
			case 'brai': // BRAILLE
		}

		return '';
	}

}
