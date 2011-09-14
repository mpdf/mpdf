<?php

include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->tabSpaces = 6;

$mpdf->allow_charset_conversion=true;
$mpdf->charset_in='windows-1252';


//==============================================================

$html = '
<h1>mPDF</h1>
<h2>ChangeLog</h2>
<div style="border:1px solid #555555; background-color: #DDDDDD; padding: 1em; font-size:8pt; font-family: lucidaconsole, mono;">
';
$text = file_get_contents('../CHANGELOG.txt');

$html .= PreparePreText($text);

// This would also work:
// $html .= '<pre>'.htmlspecialchars($text).'</pre>';
$html .= '</div>';

//==============================================================

$mpdf->WriteHTML($html);


$mpdf->Output();
exit;


?>