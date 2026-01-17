<?php

namespace Mpdf\Fonts;

abstract class FontRegistration implements FontRegistrationInterface
{
	/**
	 * Get the absolute path to the fonts directory
	 *
	 * @return string
	 */
	abstract public function getDirectory();

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
	abstract public function getFonts();

	/**
	 * Font alias mapping
	 *
	 * @return array{string, string} [ 'aliasName' => 'fontKey' ]
	 */
	public function getFontAliases()
	{
		return [];
	}

	/**
	 * Get the Language Package LanguageToFont implementation
	 *
	 * @return \Mpdf\Language\LanguageToFontInterface|null
	 */
	public function getLanguageToFont()
	{
		return null;
	}

	/**
	 * Define fonts to be used for character substitution, when the useSubstitutions configuration option enabled
	 *
	 * @return array The list of fonts to exclude using the keys found in $this->getFontData()
	 */
	public function getBackupSubsFonts()
	{
		return [];
	}

	/**
	 * Get a list of fonts which contain characters in the SIP or SMP Unicode planes but is not required.
	 * This allows a more efficient form of subsetting to be used.
	 *
	 * @return array The list of fonts to exclude using the keys found in $this->getFontData()
	 */
	public function getBmpFonts()
	{
		return [];
	}

	/**
	 * Get a list of substituted fonts used when a font is not available in mPDF
	 *
	 * @return array Multidimensional array with keys 'sans_fonts', 'serif_fonts', and 'mono_fonts'
	 */
	public function getFontFamilySubstitution()
	{
		return [
			'sans_fonts' => [],
			'serif_fonts' => [],
			'mono_fonts' => [],
		];
	}

}
