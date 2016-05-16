<?php

namespace Mpdf;

use Mpdf\Fonts\FontCache;

/*
 * This script prints out all characters in a TrueType font file to a PDF document.
 *
 * By default this will examine the font directory defined by _MPDF_TTFONTPATH
 * By default this will examine the font dejavusanscondensed.
 *
 * You can optionally define an alternative font file to examine by setting
 * the variable below (must be a relative path, or filesystem path):
*/

$font = 'dejavusanscondensed'; // Use internal mPDF font-name

$min = 0x0020;         // Minimum Unicode value to show
$max = 0x2FFFF;        // Maximum Unicode value to show

$showmissing = false;    // Show all missing unicode blocks / characters

require_once '../vendor/autoload.php';

$mpdf = new Mpdf();
$fontCache = new FontCache(new Cache($mpdf->fontTempDir));

$mpdf->SetDisplayMode('fullpage');

$mpdf->useSubstitutions = true;
$mpdf->simpleTables = true;

// force fonts to be embedded whole i.e. NOT susbet
$mpdf->percentSubset = 0;

// This generates a .mtx.php file if not already generated
$mpdf->WriteHTML('<style>td { border: 0.1mm solid #555555; } body { font-weight: normal; }</style>');
$mpdf->WriteHTML('<h3 style="font-family:' . $font . '">' . strtoupper($font) . '</h3>');    // Separate Paragraphs	defined by font
$html = '';

$unifile = file(__DIR__ . '/data/UnicodeData.txt');
$unichars = array();

foreach ($unifile AS $line) {
	if (isset($smp) && preg_match('/^(1[0-9A-Za-z]{4});/', $line, $m)) {
		$unichars[hexdec($m[1])] = hexdec($m[1]);
	} else if (preg_match('/^([0-9A-Za-z]{4});/', $line, $m)) {
		$unichars[hexdec($m[1])] = hexdec($m[1]);
	}
}

$unicode_ranges = require __DIR__ . '/data/UnicodeRanges.php';

$cw = $fontCache->load($font . '.cw.dat');

if (!$cw) {
	die("Error - Must be able to read font metrics file: " . $fontCache->tempFilename($font . '.cw.dat'));
}

$counter = 0;

require $fontCache->tempFilename($font . '.mtx.php');

if (isset($smp)) {
	$max = min($max, 131071);
} else {
	$max = min($max, 65535);
}

$justfinishedblank = false;
$justfinishedblankinvalid = false;

foreach ($unicode_ranges as $urk => $ur) {
	if (0 >= $ur['startdec'] && 0 <= $ur['enddec']) {
		$rangekey = $urk;
		$range = $ur['range'];
		$rangestart = $ur['starthex'];
		$rangeend = $ur['endhex'];
		break;
	}
}

$lastrange = $range;
// create HTML content
$html .= '<table cellpadding="2" cellspacing="0" style="font-family:' . $font . ';text-align:center; border-collapse: collapse; ">';
$html .= '<tr><td colspan="18" style="font-family:dejavusanscondensed;font-weight:bold">' . strtoupper($font) . '</td></tr>';
$html .= '<tr><td colspan="18" style="font-family:dejavusanscondensed;font-size:8pt;font-weight:bold">' . strtoupper($range) . ' (U+' . $rangestart . '-U+' . $rangeend . ')</td></tr>';
$html .= '<tr><td></td>';

$html .= '<td></td>';
for ($i = 0; $i < 16; $i++) {
	$html .= '<td><b>-' . sprintf('%X', $i) . '</b></td>';
}

// print each character
for ($i = $min; $i <= $max; ++$i) {

	if (($i > 0) && (($i % 16) == 0)) {

		$notthisline = true;

		while ($notthisline) {
			for ($j = 0; $j < 16; $j++) {
				if ($mpdf->_charDefined($cw, ($i + $j))) {
					//if (isset($cw[($i+$j)])) {
					$notthisline = false;
				}
			}
			if ($notthisline) {

				if ($showmissing) {

					$range = '';

					foreach ($unicode_ranges as $urk => $ur) {
						if ($i >= $ur['startdec'] && $i <= $ur['enddec']) {
							$rangekey = $urk;
							$range = $ur['range'];
							$rangestart = $ur['starthex'];
							$rangeend = $ur['endhex'];
							break;
						}
					}

					$anyvalid = false;

					for ($j = 0; $j < 16; $j++) {
						if (isset($unichars[$i + $j])) {
							$anyvalid = true;
							break;
						}
					}

					if ($range && $range == $lastrange) {

						if (!$anyvalid) {
							if (!$justfinishedblankinvalid) {
								$html .= '<tr><td colspan="18" style="background-color:#555555; font-size: 4pt;">&nbsp;</td></tr>';
							}
							$justfinishedblankinvalid = true;
						} elseif (!$justfinishedblank) {
							$html .= '<tr><td colspan="18" style="background-color:#FFAAAA; font-size: 4pt;">&nbsp;</td></tr>';
							$justfinishedblank = true;
						}

					} elseif ($range) {

						$html .= '</tr></table><br />';
						$mpdf->WriteHTML($html);
						$html = '';
						$html .= '<table cellpadding="2" cellspacing="0" style="font-family:' . $font . ';text-align:center; border-collapse: collapse; ">';
						$html .= '<tr><td colspan="18" style="font-family:dejavusanscondensed;font-size:8pt;font-weight:bold">' . strtoupper($range) . ' (U+' . $rangestart . '-U+' . $rangeend . ')</td></tr>';
						$html .= '<tr><td></td>';
						$html .= '<td></td>';

						for ($k = 0; $k < 16; $k++) {
							$html .= '<td><b>-' . sprintf('%X', $k) . '</b></td>';
						}

						$justfinishedblank = false;
						$justfinishedblankinvalid = false;

					}
					$lastrange = $range;
				}
				$i += 16;
				if ($i > $max) {
					break 2;
				}
			}
		}

		foreach ($unicode_ranges AS $urk => $ur) {
			if ($i >= $ur['startdec'] && $i <= $ur['enddec']) {
				$rangekey = $urk;
				$range = $ur['range'];
				$rangestart = $ur['starthex'];
				$rangeend = $ur['endhex'];
				break;
			}
		}

		if ($i > 0 && ($i % 16) == 0 && ($range != $lastrange)) {

			$html .= '</tr></table><br />';
			$mpdf->WriteHTML($html);
			$html = '';

			$html .= '<table cellpadding="2" cellspacing="0" style="font-family:' . $font . ';text-align:center; border-collapse: collapse; ">';
			$html .= '<tr><td colspan="18" style="font-family:dejavusanscondensed;font-size:8pt;font-weight:bold">' . strtoupper($range) . ' (U+' . $rangestart . '-U+' . $rangeend . ')</td></tr>';
			$html .= '<tr><td></td>';
			$html .= '<td></td>';

			for ($k = 0; $k < 16; $k++) {
				$html .= '<td><b>-' . sprintf('%X', $k) . '</b></td>';
			}
		}

		$lastrange = $range;
		$justfinishedblank = false;
		$justfinishedblankinvalid = false;
		$html .= '</tr><tr><td><i>' . (floor($i / 16) * 16) . '</i></td>';
		$html .= '<td><b>' . sprintf('%03X', floor($i / 16)) . '-</b></td>';
	}

	// Add dotted circle to any character (mark) with width=0
	if ($mpdf->_charDefined($cw, $i) && _getCharWidth($cw, $i) == 0) {
		$html .= '<td>&#x25cc;&#' . $i . ';</td>';
		$counter++;
	} else {
		if ($mpdf->_charDefined($cw, $i)) {
			$html .= '<td>&#' . $i . ';</td>';
			$counter++;
		} elseif (isset($unichars[$i])) {
			$html .= '<td style="background-color: #FFAAAA;"></td>';
		} else {
			$html .= '<td style="background-color: #555555;"></td>';
		}
	}
}

if (($i % 16) > 0) {
	for ($j = ($i % 16); $j < 16; ++$j) {
		$html .= '<td style="background-color: #555555;"></td>';
	}
}

$html .= '</tr></table><br />';

function _getCharWidth(&$cw, $u, $isdef = true)
{
	if ($u == 0) {
		$w = false;
	} else {
		$w = (ord($cw[$u * 2]) << 8) + ord($cw[$u * 2 + 1]);
	}
	if ($w == 65535) {
		return 0;
	} else if ($w) {
		return $w;
	} else if ($isdef) {
		return false;
	} else {
		return 0;
	}
}

$mpdf->WriteHTML($html);

$mpdf->Output();
