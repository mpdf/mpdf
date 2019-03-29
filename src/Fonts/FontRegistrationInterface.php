<?php

namespace Mpdf\Fonts;

use Mpdf\Language\LanguageToFontInterface;

interface FontRegistrationInterface
{

	/**
	 * Get the unique name of the Language Package
	 *
	 * @return string
	 * @since 9.0
	 */
	public function getName();

	/**
	 * Get path to the registered font file directory
	 *
	 * @return string The full path to the font directory
	 * @since 9.0
	 */
	public function getFontDir();

	/**
	 * Get the fonts to be registered with mPDF
	 *
	 * @return array A valid 'fontdata' configuration array
	 * @see     http://mpdf.github.io/fonts-languages/fonts-in-mpdf-7-x.html
	 * @since 9.0
	 */
	public function getFontData();

	/**
	 * Get the Language Package LanguageToFont implementation
	 *
	 * @return LanguageToFontInterface|null
	 * @since 9.0
	 */
	public function getLanguageToFont();

	/**
	 * Define fonts to be used for character substitution, when the useSubstitutions configuration option enabled
	 *
	 * @return array The list of fonts to exclude using the keys found in $this->getFontData()
	 * @since 9.0
	 */
	public function getBackupSubsFont();

	/**
	 * Get a list of fonts which contain characters in the SIP or SMP Unicode planes but is not required.
	 * This allows a more efficient form of subsetting to be used.
	 *
	 * @return array The list of fonts to exclude using the keys found in $this->getFontData()
	 * @since 9.0
	 */
	public function getBmpFonts();

	/**
	 * Get a list of substituted fonts used when a font is not available in mPDF. Define 'sans_fonts', 'serif_fonts', and 'mono_fonts'
	 * fallback fonts as necessary.
	 *
	 * @return array Multidimensional array with keys 'sans', 'serif', and 'mono'. Each array should use the keys found
	 * in $this->getFontData()
	 * @since 9.0
	 */
	public function getFontFamilySubstitution();

}
