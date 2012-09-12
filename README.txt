Upgrading
============

To upgrade from mPDF 5.4 to 5.5, simply upload all the files to their corresponding folders, overwriting files as required.

You will need to make the following edits to your config.php file:

Add the one new variable:
$this->margBuffer = 0;		// Allow an (empty) end of block to extend beyond the bottom margin by this amount (mm)

You will need to make the following edits to your config_fonts.php file:

Add to (arabic) fonts to allow "use non-mapped Arabic Glyphs" e.g. for Pashto:
	'unAGlyphs' => true,


