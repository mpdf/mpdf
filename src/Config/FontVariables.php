<?php

namespace Mpdf\Config;

class FontVariables
{

	private $defaults;

	public function __construct()
	{
		$this->defaults = [

			// Specify which font metrics to use:
			// - 'winTypo uses sTypoAscender etc from the OS/2 table and is the one usually recommended - BUT
			// - 'win' use WinAscent etc from OS/2 and inpractice seems to be used more commonly in Windows environment
			// - 'mac' uses Ascender etc from hhea table, and is used on Mac/OSX environment
			'fontDescriptor' => 'win',

			// For custom fonts data folder set config key 'fontDir'. It can also be an array of directories,
			// first found file will then be returned

			// Optionally set font(s) (names as defined below in 'fontdata') to use for missing characters
			// when using useSubstitutions. Use a font with wide coverage - dejavusanscondensed is a good start
			// only works using subsets (otherwise would add very large file)
			// More than 1 font can be specified but each will add to the processing time of the script

			'backupSubsFont' => [],

			// Optionally set a font (name as defined below in 'fontdata') to use for CJK characters
			// in Plane 2 Unicode (> U+20000) when using useSubstitutions.
			// Use a font like hannomb or sun-extb if available
			// only works using subsets (otherwise would add very large file)

			'backupSIPFont' => '',

			/*
			  This array defines translations from font-family in CSS or HTML
			  to the internal font-family name used in mPDF.
			  Can include as many as want, regardless of which fonts are installed.
			  By default mPDF will take a CSS/HTML font-family and remove spaces
			  and change to lowercase e.g. "Times New Roman" will be recognised as
			  "timesnewroman"
			  You only need to define additional translations.
			  You can also use it to define specific substitutions e.g.
			  'helvetica' => 'arial'
			  Generic substitutions (i.e. to a sans-serif or serif font) are set
			  by including the font-family in e.g. 'sans_fonts' below
			 */
			'fonttrans' => [
				'times' => 'timesnewroman',
				'courier' => 'couriernew',
				'trebuchet' => 'trebuchetms',
				'comic' => 'comicsansms',
				'franklin' => 'franklingothicbook',
			],

			/*
			  This array lists the file names of the TrueType .ttf or .otf font files
			  for each variant of the (internal mPDF) font-family name.
			  ['R'] = Regular (Normal), others are Bold, Italic, and Bold-Italic
			  Each entry must contain an ['R'] entry, but others are optional.
			  Only the font (files) entered here will be available to use in mPDF.
			  Put preferred default first in order
			  This will be used if a named font cannot be found in any of
			  'sans_fonts', 'serif_fonts' or 'mono_fonts'

			  ['sip-ext'] = 'sun-extb', name a related font file containing SIP characters
			  ['useOTL'] => 0xFF,	Enable use of OTL features.
			  ['useKashida'] => 75,	Enable use of kashida for text justification in Arabic text

			  If a .ttc TrueType collection file is referenced, the number of the font
			  within the collection is required. Fonts in the collection are numbered
			  starting at 1, as they appear in the .ttc file e.g.
			  "cambria" => array(
					'R' => "cambria.ttc",
					'B' => "cambriab.ttf",
					'I' => "cambriai.ttf",
					'BI' => "cambriaz.ttf",
					'TTCfontID' => array(
						'R' => 1,
					),
				),
				"cambriamath" => array(
					'R' => "cambria.ttc",
					'TTCfontID' => array(
						'R' => 2,
					),
				),
			 */

			'fontdata' => [],

			// Add fonts to this array if they contain characters in the SIP or SMP Unicode planes
			// but you do not require them. This allows a more efficient form of subsetting to be used.
			'BMPonly' => [],

			// These next 3 arrays do two things:
			// 1. If a font referred to in HTML/CSS is not available to mPDF, these arrays will determine whether
			//    a serif/sans-serif or monospace font is substituted
			// 2. The first font in each array will be the font which is substituted in circumstances as above
			//     (Otherwise the order is irrelevant)
			// Use the mPDF font-family names i.e. lowercase and no spaces (after any translations in $fonttrans)
			// Always include "sans-serif", "serif" and "monospace" etc.
			'sans_fonts' => ['sans', 'sans-serif', 'cursive', 'fantasy', 'liberationsans',
				'arial', 'helvetica', 'verdana', 'geneva', 'lucida', 'arialnarrow', 'arialblack',
				'franklin', 'franklingothicbook', 'tahoma', 'calibri', 'trebuchet', 'lucidagrande', 'microsoftsansserif',
				'trebuchetms', 'lucidasansunicode', 'franklingothicmedium', 'albertusmedium', 'albasuper', 'quillscript',
				'humanist777', 'humanist777black', 'humanist777light', 'futura', 'hobo', 'segoeprint'
			],

			'serif_fonts' => ['serif', 'liberationserif',
				'timesnewroman', 'times', 'centuryschoolbookl', 'palatinolinotype', 'centurygothic',
				'bookmanoldstyle', 'bookantiqua', 'cyberbit', 'cambria',
				'norasi', 'charis', 'palatino', 'constantia', 'georgia', 'albertus', 'xbzar', 'algerian', 'garamond',
			],

			'mono_fonts' => ['mono', 'monospace', 'liberationmono', 'courier', 'lucidaconsole',
				'couriernew', 'monotypecorsiva'
			],
		];
	}

	public function getDefaults()
	{
		return $this->defaults;
	}

}
