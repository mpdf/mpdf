<?php

namespace Mpdf;

use Mpdf\Fonts\FontCache;

/**
 * This script prints out details of any TrueType collection font files in your font directory.
 * Files ending wih .ttc and .ttcf are examined.
 *
 * By default this will examine the font directory defined by $mpdf->fontDir
 */

require_once '../vendor/autoload.php';

$mpdf = new Mpdf();
$fontCache = new FontCache(new Cache($mpdf->fontTempDir));

$ttfdir = $mpdf->fontDir;

$ttf = new TTFontFileAnalysis($fontCache, $mpdf->getFontDescriptor());

$ff = scandir($ttfdir);

printf('Searching "%s" directory for .ttc/.ttcf font collections' . "\n", $ttfdir);

$i = 0;

foreach ($ff as $f) {
	$ret = array();

	if (strtolower(substr($f, -4, 4)) === '.ttc' || strtolower(substr($f, -4, 4)) === '.ttcf') { // Mac ttcf

		$ttf->getTTCFonts($ttfdir . $f);

		$nf = $ttf->numTTCFonts;
		printf('Font collection file (%s) contains the following fonts:' . "\n", $f);

		for ($i = 1; $i <= $nf; $i++) {
			$ret = $ttf->extractCoreInfo($ttfdir . $f, $i);
			$tfname = $ret[0];
			$bold = $ret[1];
			$italic = $ret[2];
			$fname = strtolower($tfname);
			$fname = preg_replace('/[ ()]/', '', $fname);
			$style = '';

			if ($bold) {
				$style .= 'Bold';
			}
			if ($italic) {
				$style .= 'Italic';
			}
			if (!$style) {
				$style = 'Regular';
			}

			printf('[%d] %s (%s) %s', $i, $tfname, $fname, $style);
		}

		print("---------------\n\n");
		$i++;
	}
}

printf('Found and processed %d collections' . "\n", $i);
