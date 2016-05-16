<?php

namespace Mpdf;



$family = 'khmeros';

$style = ''; // '','B','I','BI'; // At present only works for Regular style
$script = '';
$lang = '';

if (isset($_REQUEST['script'])) {
	$script = $_REQUEST['script'];
}

if (isset($_REQUEST['lang'])) {
	$lang = $_REQUEST['lang'];
}

if ($script && strlen($script) < 4) {
	$script = str_pad($script, 4, ' ');
}

if ($lang && strlen($lang) < 4) {
	$lang = str_pad($lang, 4, ' ');
}

require_once '../vendor/autoload.php';

$mpdf = new Mpdf();

$mpdf->simpleTables = true;

// This generates a .mtx.php file if not already generated
$mpdf->SetFont($family, $style);

$ff = array();
$ffs = '';

if ($lang && $script) {
	$GSUBFeatures = $mpdf->CurrentFont['GSUBFeatures'][$script][$lang];
	if (is_array($GSUBFeatures)) {
		foreach ($GSUBFeatures AS $tag => $v) {
			$ff[] = '"' . $tag . '" 0';
		}
	}
	$GPOSFeatures = $mpdf->CurrentFont['GPOSFeatures'][$script][$lang];
	if (is_array($GPOSFeatures)) {
		foreach ($GPOSFeatures AS $tag => $v) {
			$ff[] = '"' . $tag . '" 0';
		}
	}
	$ffs = implode(', ', $ff);
}
//==============================================================

$html = '<style>
body {
	font-family: DejaVuSansCondensed;
	font-weight: normal;
	font-size: 11pt;
	font-feature-settings: ' . $ffs . ';
}
h5 {
	font-size: 1rem;
	color: #000066;
	margin-bottom: 0.3em;
}
.glyphs {
	font-family: ' . $family . ';
}
.subtable {
	font-size: 0.7rem;
}
h5.level2 {
	font-size: 0.85rem;
	color: #6666AA;
}
.lookuptype {
	font-size: 0.7rem;
	color: #888888;
	text-transform: uppercase;
}
.lookuptypesub {
	font-size: 0.7rem;
	color: #888888;
	text-transform: uppercase;
}
span.unicode {
	color: #888888;
	font-size: 0.7rem;
}
span.changed {
	font-family: ' . $family . ';
	font-size: 1.5rem;
	color: #FF4444;
	font-feature-settings: ' . $ffs . ';
}
span.unchanged {
	font-family: ' . $family . ';
	font-size: 1.5rem;
	color: #4444FF;
	font-feature-settings: ' . $ffs . ';
}
span.backtrack {
	font-family: ' . $family . ';
	font-size: 1.5rem;
	color: #66aa66;
	font-feature-settings: ' . $ffs . ';
}
span.lookahead {
	font-family: ' . $family . ';
	font-size: 1.5rem;
	color: #66aa66;
	font-feature-settings: ' . $ffs . ';
}
span.inputother {
	font-family: ' . $family . ';
	font-size: 1.5rem;
	color: #006688;
	font-feature-settings: ' . $ffs . ';
}
div.context {
	font-size: 0.7rem;
	color: #888888;
	text-transform: uppercase;
}
div.sequenceIndex {
	font-size: 0.7rem;
}
div.rule {
	font-size: 0.7rem;
}
.ignore {
	color: #888888;
	font-size: 0.7rem;
}
div.level2 {
	margin-left: 5em;
}
</style>
<body>
<h1 style="text-align:center;">' . strtoupper($family . $style) . '</h1>';

if ($lang && $script) {
	$html .= '<h2 style="text-align:center;">' . $script . ' ' . $lang . '</h2>';
}

$mpdf->WriteHTML($html);

$mpdf->debugfonts = false;

$family = strtolower($family);
$style = strtoupper($style);

if ($style == 'IB') {
	$style = 'BI';
}

$fontkey = $family . $style;
$stylekey = $style;

if (!$style) {
	$stylekey = 'R';
}

$mpdf->overrideOTLsettings[$fontkey]['script'] = $script;
$mpdf->overrideOTLsettings[$fontkey]['lang'] = $lang;

// include $fontCache->tempFilename($fontkey.'.mtx.php');

$ttffile = '';

if (defined('_MPDF_SYSTEM_TTFONTS')) {
	$ttffile = _MPDF_SYSTEM_TTFONTS . $mpdf->fontdata[$family][$stylekey];
	if (!file_exists($ttffile)) {
		$ttffile = '';
	}
}

if (!$ttffile) {
	$ttffile = _MPDF_TTFONTPATH . $mpdf->fontdata[$family][$stylekey];
	if (!file_exists($ttffile)) {
		die("mPDF Error - cannot find TTF TrueType font file - " . $ttffile);
	}
}

$ttfstat = stat($ttffile);

if (isset($mpdf->fontdata[$family]['TTCfontID'][$stylekey])) {
	$TTCfontID = $mpdf->fontdata[$family]['TTCfontID'][$stylekey];
} else {
	$TTCfontID = 0;
}

$BMPonly = false;

if (in_array($family, $mpdf->BMPonly)) {
	$BMPonly = true;
}

$useOTL = $mpdf->fontdata[$family]['useOTL'];

$dump = new OtlDump($mpdf);

$mpdf->OTLscript = $script;
$mpdf->OTLlang = $lang;

if ($lang && $script) {
	$dump->getMetrics($ttffile, $fontkey, $TTCfontID, $mpdf->debugfonts, $BMPonly, true, $useOTL, 'detail');
} else {
	$dump->getMetrics($ttffile, $fontkey, $TTCfontID, $mpdf->debugfonts, $BMPonly, true, $useOTL, 'summary');
}

$mpdf->Output();
