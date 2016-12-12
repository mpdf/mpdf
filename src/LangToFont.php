<?php

namespace Mpdf;

/**
 * mPDF recognises IETF language tags as:
 * - a single primary language subtag composed of a two letter language code from ISO 639-1 (2002),
 *     or a three letter code from ISO 639-2 (1998), ISO 639-3 (2007) or ISO 639-5 (2008) (usually written in lower case);
 * - an optional script subtag, composed of a four letter script code from ISO 15924 (usually written in title case);
 * - an optional region subtag composed of a two letter country code from ISO 3166-1 alpha-2 (usually written in upper case),
 *     or a three digit code from UN M.49 for geographical regions;
 *
 * Subtags are not case sensitive, but the specification recommends using the same case as in the Language Subtag Registry,
 *     where region subtags are uppercase, script subtags are titlecase and all other subtags are lowercase.
 *
 * Region subtags are often deprecated by the registration of specific primary language subtags from ISO 639-3 which are now
 *    "preferred values". For example, "ar-DZ" is deprecated with the preferred value "arq" for Algerian Spoken Arabic;
 *
 * Example: Serbian written in the Cyrillic (sr-Cyrl) or Latin (sr-Latn) script
 *
 * und (for undetermined or undefined) is used in situations in which a script must be indicated but the language cannot be identified.
 * e.g. und-Cyrl is an undefined language written in Cyrillic script.
 */
class LangToFont
{

	public static function getLangOpts($llcc, $adobeCJK, &$fontdata)
	{
		$tags = preg_split('/-/', $llcc);
		$lang = strtolower($tags[0]);
		$country = '';
		$script = '';
		if (isset($tags[1]) && $tags[1]) {
			if (strlen($tags[1]) == 4) {
				$script = strtolower($tags[1]);
			} else {
				$country = strtolower($tags[1]);
			}
		}
		if (isset($tags[2]) && $tags[2]) {
			$country = strtolower($tags[2]);
		}

		$unifont = "";
		$coreSuitable = false;

		switch ($lang) {
			/* European */
			case "en":
			case "eng": // English		// LATIN
			case "eu":
			case "eus": // Basque
			case "br":
			case "bre": // Breton
			case "ca":
			case "cat": // Catalan
			case "co":
			case "cos": // Corsican
			case "kw":
			case "cor": // Cornish
			case "cy":
			case "cym": // Welsh
			case "cs":
			case "ces": // Czech
			case "da":
			case "dan": // Danish
			case "nl":
			case "nld": // Dutch
			case "et":
			case "est": // Estonian
			case "fo":
			case "fao": // Faroese
			case "fi":
			case "fin": // Finnish
			case "fr":
			case "fra": // French
			case "gl":
			case "glg": // Galician
			case "de":
			case "deu": // German
			case "ht":
			case "hat": // Haitian; Haitian Creole
			case "hu":
			case "hun": // Hungarian
			case "ga":
			case "gle": // Irish
			case "is":
			case "isl": // Icelandic
			case "it":
			case "ita": // Italian
			case "la":
			case "lat": // Latin
			case "lb":
			case "ltz": // Luxembourgish
			case "li":
			case "lim": // Limburgish
			case "lt":
			case "lit": // Lithuanian
			case "lv":
			case "lav": // Latvian
			case "gv":
			case "glv": // Manx
			case "no":
			case "nor": // Norwegian
			case "nn":
			case "nno": // Norwegian Nynorsk
			case "nb":
			case "nob": // Norwegian Bokmål
			case "pl":
			case "pol": // Polish
			case "pt":
			case "por": // Portuguese
			case "ro":
			case "ron": // Romanian
			case "gd":
			case "gla": // Scottish Gaelic
			case "es":
			case "spa": // Spanish
			case "sv":
			case "swe": // Swedish
			case "sl":
			case "slv": // Slovene
			case "sk":
			case "slk": // Slovak
																																																																													$unifont = "dejavusanscondensed";
																																																																													// Edit this value to define how mPDF behaves when using new mPDF('-x')
																																																																													// If set to TRUE, mPDF will use Adobe core fonts only when it recognises the languages above
																																																																													$coreSuitable = true;
				break;

			case "ru":
			case "rus": // Russian	// CYRILLIC
			case "ab":
			case "abk": // Abkhaz
			case "av":
			case "ava": // Avaric
			case "ba":
			case "bak": // Bashkir
			case "be":
			case "bel": // Belarusian
			case "bg":
			case "bul": // Bulgarian
			case "ce":
			case "che": // Chechen
			case "cv":
			case "chv": // Chuvash
			case "kk":
			case "kaz": // Kazakh
			case "kv":
			case "kom": // Komi
			case "ky":
			case "kir": // Kyrgyz
			case "mk":
			case "mkd": // Macedonian
			case "cu":
			case "chu": // Old Church Slavonic
			case "os":
			case "oss": // Ossetian
			case "sr":
			case "srp": // Serbian
			case "tg":
			case "tgk": // Tajik
			case "tt":
			case "tat": // Tatar
			case "tk":
			case "tuk": // Turkmen
			case "uk":
			case "ukr": // Ukrainian
																																							$unifont = "dejavusanscondensed"; /* freeserif best coverage for supplements etc. */
				break;

			case "hy":
			case "hye": // ARMENIAN
				$unifont = "dejavusans";
				break;
			case "ka":
			case "kat": // GEORGIAN
				$unifont = "dejavusans";
				break;

			case "el":
			case "ell": // GREEK
				$unifont = "dejavusanscondensed";
				break;
			case "cop":  // COPTIC
				$unifont = "quivira";
				break;

			case "got":  // GOTHIC
				$unifont = "freeserif";
				break;

			/* African */
			case "nqo":  // NKO
				$unifont = "dejavusans";
				break;
			//CASE "bax":	// BAMUM
			//CASE "ha":  CASE "hau":	// Hausa
			case "vai":  // VAI
				$unifont = "freesans";
				break;
			case "am":
			case "amh": // Amharic ETHIOPIC
			case "ti":
			case "tir": // Tigrinya ETHIOPIC
					$unifont = "abyssinicasil";
				break;

			/* Middle Eastern */
			case "ar":
			case "ara": // Arabic	NB Arabic text identified by Autofont will be marked as und-Arab
				$unifont = "xbriyaz";
				break;
			case "fa":
			case "fas": // Persian (Farsi)
				$unifont = "xbriyaz";
				break;
			case "ps":
			case "pus": // Pashto
				$unifont = "xbriyaz";
				break;
			case "ku":
			case "kur": // Kurdish
				$unifont = "xbriyaz";
				break;
			case "ur":
			case "urd": // Urdu
				$unifont = "xbriyaz";
				break;
			case "he":
			case "heb": // HEBREW
			case "yi":
			case "yid": // Yiddish
					$unifont = "taameydavidclm"; // dejavusans,dejavusanscondensed,freeserif are fine if you do not need cantillation marks
				break;

			case "syr":  // SYRIAC
				$unifont = "estrangeloedessa";
				break;

			//CASE "arc":	// IMPERIAL_ARAMAIC
			//CASE ""ae:	// AVESTAN
			case "xcr":  // CARIAN
				$unifont = "aegean";
				break;
			case "xlc":  // LYCIAN
				$unifont = "aegean";
				break;
			case "xld":  // LYDIAN
				$unifont = "aegean";
				break;
			//CASE "mid":	// MANDAIC
			//CASE "peo":	// OLD_PERSIAN
			case "phn":  // PHOENICIAN
				$unifont = "aegean";
				break;
			//CASE "smp":	// SAMARITAN
			case "uga":  // UGARITIC
				$unifont = "aegean";
				break;

			/* Central Asian */
			case "bo":
			case "bod": // TIBETAN
			case "dz":
			case "dzo": // Dzongkha
					$unifont = "jomolhari";
				break;

			//CASE "mn":  CASE "mon":	// MONGOLIAN	(Vertical script)
			//CASE "ug":  CASE "uig":	// Uyghur
			//CASE "uz":  CASE "uzb":	// Uzbek
			//CASE "az":  CASE "azb":	// South Azerbaijani

			/* South Asian */
			case "as":
			case "asm": // Assamese
				$unifont = "freeserif";
				break;
			case "bn":
			case "ben": // BENGALI; Bangla
				$unifont = "freeserif";
				break;
			case "ks":
			case "kas": // Kashmiri
				$unifont = "freeserif";
				break;
			case "hi":
			case "hin": // Hindi	DEVANAGARI
			case "bh":
			case "bih": // Bihari (Bhojpuri, Magahi, and Maithili)
			case "sa":
			case "san": // Sanskrit
							$unifont = "freeserif";
				break;
			case "gu":
			case "guj": // Gujarati
				$unifont = "freeserif";
				break;
			case "pa":
			case "pan": // Panjabi, Punjabi GURMUKHI
				$unifont = "freeserif";
				break;
			case "kn":
			case "kan": // Kannada
				$unifont = "lohitkannada";
				break;
			case "mr":
			case "mar": // Marathi
				$unifont = "freeserif";
				break;
			case "ml":
			case "mal": // MALAYALAM
				$unifont = "freeserif";
				break;
			case "ne":
			case "nep": // Nepali
				$unifont = "freeserif";
				break;
			case "or":
			case "ori": // ORIYA
				$unifont = "freeserif";
				break;
			case "si":
			case "sin": // SINHALA
				$unifont = "kaputaunicode";
				break;
			case "ta":
			case "tam": // TAMIL
				$unifont = "freeserif";
				break;
			case "te":
			case "tel": // TELUGU
				$unifont = "pothana2000";
				break;

			// Sindhi (Arabic or Devanagari)
			case "sd":
			case "snd": // Sindhi
				if ($country == "IN") {
					$unifont = "freeserif";
				} else if ($country == "PK") {
					$unifont = "lateef";
				} else {
					$unifont = "lateef";
				}
				break;

			//CASE "ccp":	// CHAKMA
			//CASE "lep":	// LEPCHA
			case "lif":  // LIMBU
				$unifont = "sun-exta";
				break;
			//CASE "sat":	// OL_CHIKI
			//CASE "saz":	// SAURASHTRA
			case "syl":  // SYLOTI_NAGRI
				$unifont = "mph2bdamase";
				break;
			//CASE "dgo":	// TAKRI
			case "dv":
			case "div": // Divehi; Maldivian  THAANA
				$unifont = "freeserif";
				break;

			/* South East Asian */
			case "km":
			case "khm": // KHMER
				$unifont = "khmeros";
				break;
			case "lo":
			case "lao": // LAO
				$unifont = "dhyana";
				break;
			case "my":
			case "mya": // MYANMAR Burmese
				$unifont = "tharlon"; // zawgyi-one is non-unicode compliant but in wide usage
				// ayar is also not strictly compliant
				// padaukbook is unicode compliant
				break;
			case "th":
			case "tha": // THAI
				$unifont = "garuda";
				break;

			// VIETNAMESE
			case "vi":
			case "vie": // Vietnamese
				$unifont = "dejavusanscondensed";
				break;

			//CASE "ms":  CASE "msa":	// Malay
			//CASE "ban":	// BALINESE
			//CASE "bya":	// BATAK
			case "bug":  // BUGINESE
				$unifont = "freeserif";
				break;
			//CASE "cjm":	// CHAM
			//CASE "jv":	// JAVANESE
			case "su":  // SUNDANESE
				$unifont = "sundaneseunicode";
				break;
			case "tdd":  // TAI_LE
				$unifont = "tharlon";
				break;
			case "blt":  // TAI_VIET
				$unifont = "taiheritagepro";
				break;

			/* Phillipine */
			case "bku":  // BUHID
				$unifont = "quivira";
				break;
			case "hnn":  // HANUNOO
				$unifont = "quivira";
				break;
			case "tl":  // TAGALOG
				$unifont = "quivira";
				break;
			case "tbw":  // TAGBANWA
				$unifont = "quivira";
				break;

			/* East Asian */
			case "zh":
			case "zho": // Chinese
				if ($country == "HK" || $country == "TW") {
					if ($adobeCJK) {
						$unifont = "big5";
					} else {
						$unifont = "sun-exta";
					}
				} else if ($country == "CN") {
					if ($adobeCJK) {
						$unifont = "gb";
					} else {
						$unifont = "sun-exta";
					}
				} else {
					if ($adobeCJK) {
						$unifont = "gb";
					} else {
						$unifont = "sun-exta";
					}
				}
				break;
			case "ko":
			case "kor": // HANGUL Korean
				if ($adobeCJK) {
					$unifont = "uhc";
				} else {
					$unifont = "unbatang";
				}
				break;
			case "ja":
			case "jpn": // Japanese HIRAGANA KATAKANA
				if ($adobeCJK) {
					$unifont = "sjis";
				} else {
					$unifont = "sun-exta";
				}
				break;
			case "ii":
			case "iii": // Nuosu; Yi
				if ($adobeCJK) {
					$unifont = "gb";
				} else {
					$unifont = "sun-exta";
				}
			case "lis":  // LISU
				$unifont = "quivira";
				break;

			/* American */
			case "chr":  // CHEROKEE
			case "oj":
			case "oji": // Ojibwe; Chippewa
			case "cr":
			case "cre": // Cree CANADIAN_ABORIGINAL
			case "iu":
			case "iku": // Inuktitut
							$unifont = "aboriginalsans";
				break;

			/* Undetermined language - script used */
			case "und":
				$unifont = self::fontByScript($script, $adobeCJK);

				break;
		}

		return [$coreSuitable, $unifont];
	}

	private static function fontByScript($script, $adobeCJK)
	{
		switch ($script) {
			/* European */
			case "latn": // LATIN
				return "dejavusanscondensed";
			case "cyrl": // CYRILLIC
				return "dejavusanscondensed"; /* freeserif best coverage for supplements etc. */
			case "cprt": // CYPRIOT
				return "aegean";
			case "glag": // GLAGOLITIC
				return "mph2bdamase";
			case "linb": // LINEAR_B
				return "aegean";
			case "ogam": // OGHAM
				return "dejavusans";
			case "ital": // OLD_ITALIC
				return "aegean";
			case "runr": // RUNIC
				return "sun-exta";
			case "shaw": // SHAVIAN
				return "mph2bdamase";

			/* African */
			case "egyp": // EGYPTIAN_HIEROGLYPHS
				return "aegyptus";
			case "ethi": // ETHIOPIC
				return "abyssinicasil";
			//CASE "merc":	// MEROITIC_CURSIVE
			//CASE "mero":	// MEROITIC_HIEROGLYPHS
			case "osma": // OSMANYA
				return "mph2bdamase";
			case "tfng": // TIFINAGH
				return "dejavusans";

			/* Middle Eastern */
			case "arab":  // ARABIC
				return "xbriyaz";
			case "xsux": // CUNEIFORM
				return "akkadian";
			//CASE "sarb":	// OLD_SOUTH_ARABIAN
			//CASE "prti":	// INSCRIPTIONAL_PARTHIAN
			//CASE "phli":	// INSCRIPTIONAL_PAHLAVI


			/* Central Asian */
			//CASE "orkh":	// OLD_TURKIC
			//CASE "phag":	// PHAGS_PA		(Vertical script)

			/* South Asian */
			//CASE "brah":	// BRAHMI
			//CASE "kthi":	// KAITHI
			case "khar": // KHAROSHTHI
				return "mph2bdamase";
			case "mtei": // MEETEI_MAYEK
				return "eeyekunicode";
			//CASE "shrd":	// SHARADA
			//CASE "sora":	// SORA_SOMPENG

			/* South East Asian */
			case "kali": // KAYAH_LI
				return "freemono";
			//CASE "rjng":	// REJANG
			case "lana": // TAI_THAM
				return "lannaalif";
			case "talu": // NEW_TAI_LUE
				return "daibannasilbook";

			/* East Asian */
			case "hans": // HAN (SIMPLIFIED)
				if ($adobeCJK) {
					return "gb";
				} else {
					return "sun-exta";
				}
			case "bopo": // BOPOMOFO
				return "sun-exta";
			//CASE "plrd":	// MIAO
			case "yiii": // YI
				return "sun-exta";

			/* American */
			case "dsrt": // DESERET
				return "mph2bdamase";

			/* Other */
			case "brai": // BRAILLE
				return "dejavusans";
		}
	}
}
