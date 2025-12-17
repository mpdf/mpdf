<?php

namespace Mpdf\Fonts\Dhyana;

use Mpdf\Fonts\FontRegistration;

class Registration extends FontRegistration
{
	/**
	 * Get the absolute path to the fonts directory
	 *
	 * @return string
	 */
	public function getDirectory()
	{
		return __DIR__ . '/fonts';
	}

	/**
	 * Get the fonts to be registered with mPDF
	 *
	 * Return an array listing the file name(s) of the TrueType .ttf or .otf font files for
	 * each variant of the (internal mPDF) font-family name.
	 *
	 * ['R'] = Regular (Normal),
	 * ['B'] = Bold
	 * ['I'] = Italics
	 * ['BI'] = Bold Italics
	 *
	 * ['sip-ext'] = 'sun-extb', name a related font file containing SIP characters
	 * ['useOTL'] => 0xFF,    Enable use of OTL features.
	 * ['useKashida'] => 75,    Enable use of kashida for text justification in Arabic text
	 *
	 * If a .ttc TrueType collection file is referenced, the number of the font
	 * within the collection is required. Fonts in the collection are numbered
	 * starting at 1, as they appear in the .ttc file.
	 *
	 * @return array{
	 *     'R': string,
	 *     'B'?: string,
	 *     'I'?: string,
	 *     'BI'?: string,
	 *     'sip-ext'?: string,
	 *     'useOTL'?: int,
	 *     'useKashida'?: int,
	 *     'TTCfontID'?: array{
	 *     		'R': int,
	 *     		'B'?: int,
	 *     		'I'?: int,
	 *     		'BI'?: int,
	 *     }
	 * }
	 */
	public function getFonts()
	{
		return [
			'dhyana' => [
				'R' => 'Dhyana-Regular.ttf',
				'B' => 'Dhyana-Bold.ttf',
				'useOTL' => 0xFF,
			],
		];
	}

	/**
	 * Get the Language Package LanguageToFont implementation
	 *
	 * @return \Mpdf\Language\LanguageToFontInterface|null
	 */
	public function getLanguageToFont()
	{
		return new Languages();
	}

	/**
	 * Get a list of substituted fonts used when a font is not available in mPDF
	 *
	 * @return array Multidimensional array with keys 'sans_fonts', 'serif_fonts', and 'mono_fonts'
	 */
	public function getFontFamilySubstitution()
	{
		return [
			'sans_fonts' => [
				'dhyana'
			],
		];
	}
}
