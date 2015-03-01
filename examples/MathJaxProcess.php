<?php


define('_MPDF_URI','../');
define('_MPDF_PATH', '../');
include("../mpdf.php");
$mpdf=new mPDF(''); 

if (strpos($_REQUEST['bodydata'],'id%3D%22MathJax_SVG_Hidden%22')===false) {
	die("Hacking attempt");
}

$html = $_POST['bodydata'];
$html = urldecode($html);


preg_match('/<svg[^>]*>\s*(<defs.*?>.*?<\/defs>)\s*<\/svg>/',$html,$m);
$defs = $m[1];

$html = preg_replace('/<svg[^>]*>\s*<defs.*?<\/defs>\s*<\/svg>/','',$html);

$html = preg_replace('/(<svg[^>]*>)/',"\\1".$defs,$html);

preg_match_all('/<svg([^>]*)style="(.*?)"/',$html,$m);
for ($i=0;$i<count($m[0]);$i++) {
	$style=$m[2][$i];
	preg_match('/width: (.*?);/',$style, $wr);
	$w = $mpdf->ConvertSize($wr[1],0,$mpdf->FontSize) * $mpdf->dpi/25.4;
	preg_match('/height: (.*?);/',$style, $hr);
	$h = $mpdf->ConvertSize($hr[1],0,$mpdf->FontSize) * $mpdf->dpi/25.4;
	$replace = '<svg'.$m[1][$i].' width="'.$w.'" height="'.$h.'" style="'.$m[2][$i].'"';
	$html = str_replace($m[0][$i],$replace,$html);
}


if ($_POST['PDF']=='PDF') {
//=====================================================
// ADD a stylesheet
$stylesheet = '
img {	vertical-align: middle; }
.MathJax_SVG_Display { padding: 1em 0; }
#mpdf-create { display: none; }
h3 {
	background-color: #EEEEEE;
	padding: 0.5em;
	border: 1px solid #8888FF;
	font-family: sans-serif;
	font-weight: bold;
	font-size: 14pt;
}
';

$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML($html);
$mpdf->Output(); 
//=====================================================
}

else {
//=====================================================
// To output SVG files readable by mPDF as text output
header('Content-type: text/plain');
preg_match_all('/<svg(.*?)<\/svg>/',$html,$m);
for ($i=0;$i<count($m[0]);$i++) {
	$svg = $m[0][$i];
	$svg = preg_replace('/>/',">\n",$svg);	// Just add some new lines
	echo $svg."\n\n";
}
//=====================================================
}

exit;

?>