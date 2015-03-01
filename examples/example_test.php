<?php


//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
set_time_limit(600);
ini_set("memory_limit","256M");
//
$timeo_start = microtime(true);
//

//==============================================================
define('_MPDF_URI','../'); 	// must be  a relative or absolute URI - not a file system path
define('_MPDF_PATH', '../');

//==============================================================
define("_JPGRAPH_PATH", '../../jpgraph_5/jpgraph/'); // must define this before including mpdf.php file

define("_TTF_FONT_NORMAL", 'arial.ttf');
define("_TTF_FONT_BOLD", 'arialbd.ttf');

//==============================================================
include("../mpdf.php");
//
$timeo_start = microtime(true);
//
//==============================================================
//==============================================================
$lorem = 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec mattis lacus ac purus feugiat semper. Donec aliquet nunc odio, vitae pellentesque diam. Pellentesque sed velit lacus. Duis quis dui quis sem consectetur sollicitudin. Cras dolor quam, dapibus et pretium sit amet, elementum vel arcu. Duis rhoncus facilisis erat nec mattis. In hac habitasse platea dictumst. Vivamus hendrerit sem in justo aliquet a pellentesque lorem scelerisque. Suspendisse a augue sed urna rhoncus elementum. Aliquam erat volutpat. Sed et orci non massa venenatis venenatis sit amet non nulla. Fusce condimentum velit urna, sed convallis ligula. Aenean vehicula purus ac dui imperdiet varius. Curabitur justo lorem, vehicula in suscipit sit amet, pharetra ut mi. Ut nunc mauris, dapibus vitae elementum faucibus, posuere sed nisl. Vestibulum et turpis eu enim tempor iaculis. Ut venenatis mattis dolor, nec iaculis tellus malesuada vel. Curabitur eu nibh sit amet sem eleifend interdum ac eu lorem. Sed feugiat, nibh tempus porta pulvinar, nisl sem aliquet odio, idluctus augue eros eget lacus. ';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
$mpdf=new mPDF(); 
//==============================================================
//==============================================================
/*
$mpdf->fonttrans = array_merge($mpdf->fonttrans, array(
	'arial' => 'chelvetica',
	'helvetica' => 'chelvetica',
	'timesnewroman' => 'ctimes',
	'times' => 'ctimes',
	'couriernew' => 'ccourier',
	'courier' => 'ccourier',
	'sans' => 'chelvetica',
	'sans-serif' => 'chelvetica',
	'serif' => 'ctimes',
	'mono' => 'ccourier',
));
*/
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================

//==============================================================
//==============================================================
$header = array(
	'L' => array(
	),
	'C' => array(
	),
	'R' => array(
		'content' => '{PAGENO}{nbpg}',
		'font-family' => 'sans',
		'font-style' => '',
		'font-size' => '9',	/* gives default */
	),
	'line' => 1,		/* 1 or 0 to include line above/below header/footer */
);

//$mpdf->SetHeader($header,'O');
//$mpdf->SetHTMLFooter($footer);

//==============================================================

//$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins (1 or 0)

//$mpdf->showImageErrors = true;

//$mpdf->SetDisplayMode('fullpage');

//$mpdf->useLang = false;

//$mpdf->useAutoFont = true;

//$mpdf->ignore_invalid_utf8 = true;

//$mpdf->keepColumns = true;

//$mpdf->use_kwt = true;

//$mpdf->hyphenate = true;

//$mpdf->SetProtection(array('copy','print','modify','annot-forms'));
//$mpdf->SetProtection(array('copy','print','modify','annot-forms'),'',null,128);
//$mpdf->SetProtection(array('copy','print','modify','annot-forms','fill-forms','extract','assemble','print-highres'),'',null);

//$mpdf->SetTitle("\xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3");
//$mpdf->SetAuthor("\xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3");

//$mpdf->SetWatermarkText("\xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3");
//$mpdf->showWatermarkText = true;
//$mpdf->watermark_font = 'DejaVuSansCondensed';

//$mpdf->SetCompression(false);

//$mpdf->text_input_as_HTML = true;

//$mpdf->annotMargin = -8;

//$mpdf->title2annots = true;
//$mpdf->Annotation('An annotation', 145, 24, 'Comment', "Ian Back", "My Subject", 0.7, array(127, 127, 255));

//$mpdf->collapseBlockMargins = false; 	// mPDF 4.2 Allows top and bottom margins to collapse between block elements


//$mpdf->allow_charset_conversion = true;
//$mpdf->charset_in = 'win-1251';

//$mpdf->useSubstitutions = true;
//$mpdf->useActiveForms = true;

//$mpdf->simpleTables = true; // Forces all cells to have same border, background etc. Improves performance
//$mpdf->packTableData = true; // Reduce memory usage processing tables (but with increased processing time)

// Using disk to cache table data can reduce memory usage dramatically, but at a cost of increased 
// executon time and disk access (read and write)
//$mpdf->cacheTables = true;



//==============================================================
//$mpdf->SetImportUse();
//$mpdf->SetSourceFile('example_all.pdf'); 
//$tplIdx = $mpdf->ImportPage(9);
//$mpdf->UseTemplate($tplIdx);
//==============================================================
//==============================================================
//$mpdf->debug = true;
//$mpdf->showStats  = true;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
/* 
// 4.3.003 TEST
$html = '<div style="width: 170mm; text-align: justify; border: 0.2mm solid #000000;">Browser doesn\'t justify text before a BR <br />Browser does justify text before an IMG  <img src="clematis.jpg" style="width:150mm; height: 5;" /></div>
<div style="width: 170mm; text-align: justify; border: 0.2mm solid #000000;">Browser doesn\'t justify text before a HR <hr width="80%"/>Browser does justify text before an IMG<img src="clematis.jpg" style="width:150mm; height: 5;" /></div>
<div style="width: 170mm; text-align: justify; border: 0.2mm solid #000000;">Browser does justify text before a TEXTAREA <textarea name="authors" rows="5" cols="70" wrap="virtual">Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus dictum. </textarea>
</div>
<div style="width: 170mm; text-align: justify; border: 0.2mm solid #000000;">Browser does justify text before a SELECT <select size="1" name="status"><option value="A">Active</option><option value="W" >New item from auto_manager: pending validation New item from auto_manager: pending validation</option><option value="I" selected="selected">Incomplete record - pending Incomplete record - pending Incomplete record - pending</option><option value="X" >Flagged for Deletion</option> </select> </div>';

//$html = '<div style="text-align: justify; border: 0.2mm solid #000000;">If you want a single line of text to justify you need to do this<br /></div>';
//$mpdf->justifyB4br = true;	
//$mpdf->jSWord = 0.4;	// Proportion (/1) of space (when justifying margins) to allocate to Word vs. Character
//$mpdf->jSmaxChar = 0.25;	// Maximum spacing to allocate to character spacing. (0 = no maximum)
*/
//==============================================================
//==============================================================
//==============================================================
//==============================================================
/*
// CJK Fonts
$fonts = array(
array('gb', 'GB (Chinese Simpl. Adobe)'),
array('big5', 'BIG-5 (Chinese Trad. Adobe)'),
array('sjis', 'SJIS (Japanese Adobe)'),
array('arialunicodems', 'Arial Unicode MS'),
array('cyberbit', 'CyberBit'),
array('sun-exta', 'Sun-ExtA'),
array('hannoma', 'Han Nom A'),
array('mingliu', 'MingLiU'),
array('mingliu_hkscs', 'MingLiU_HKSCS'),
array('arplumingcn', 'AR PL Uming CN'),
array('arpluminghk', 'AR PL Uming HK'),
array('arplumingtw', 'AR PL Uming TW'),
);
$chars = array('34c7','4eca','4ede','4f3b','4fae','508e','50a6','50c7','517e','518f','51b2','5203','520f','5222','55c2','57d2','6b21','87a0','8880','8a03',
'4EE4', 
'76F4', 
'9AA8', 
'9F31', 
'2493F',
);

$html .= "
<div style=\"font-family:arialunicodems;\">
<table border=\"1\">
<thead>
<tr style=\"text-rotate:90\">
<th></th>
";
foreach($fonts AS $f) {
	$html .= "<th style=\"vertical-align: bottom;font-family:".$f[0]." ;\">".$f[1]."</th>";
}
$html .= "
</tr>
</thead>
";
foreach($chars AS $char) {
	$html .= "<tr><td>U+".strtoupper($char)."</td>";
	foreach($fonts AS $f) {
		$html .= "<td style=\"font-family:".$f[0].";\">&#x".$char.";</td>";
	}
	$html .= "</tr>
";
}
$html .= "</table>
</div>
";
*/
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================

/*

$html = '
<style>
div.cjk {
	font-family: GB;
	border:1px solid #888888;
	width: 40mm;
	margin-bottom: 1em;
}
</style>
mPDF 5.6.40
Bug fix
';
$mpdf->WriteHTML($html);





$mpdf->allowCJKorphans = false;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]


$html = '
<div class="cjk" style="">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;</div>

CJKleading Leading characters - Not allowed at end of line &#x3005;<br />
<div class="cjk" style="">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x3005;&#x90e8;</div>

CJKfollowing Following characters - Not allowed at start &#x30a9;<br />
<div class="cjk" style="">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x30a9;</div>

';
$mpdf->WriteHTML($html);

//==============================================================
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]


$html = '
CJKfollowing Following characters - Not allowed at start &#x30a9;<br />
<div class="cjk" style="">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x30a9;</div>
<hr />

';
$mpdf->WriteHTML($html);

//==============================================================
// CJK Line-breaking
$align = 'left';
$mpdf->allowCJKorphans = false;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = false;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
CJKoverflow Characters which are allowed to overflow the right margin &#xff0c;<br />
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>

';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'justify';
$mpdf->allowCJKorphans = false;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = false;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>

';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'left';
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = true;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>
';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'left';
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = false;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>
';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'justify';
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = false;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>
';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'justify';
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = true;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>
';
$mpdf->WriteHTML($html);
//==============================================================
// CJK Line-breaking
$align = 'justify';
$mpdf->allowCJKorphans = true;	// FALSE=always wrap to next line; TRUE=squeeze or overflow [default true]
$mpdf->allowCJKoverflow = true;	// FALSE=squeeze; TRUE=overflow (only some characters, and disabled in tables) [default false]
$mpdf->CJKforceend = true;
$html = '
text-align: '.$align.'; 
$mpdf->allowCJKorphans = '.$mpdf->allowCJKorphans.';
$mpdf->allowCJKoverflow = '.$mpdf->allowCJKoverflow.';
$mpdf->CJKforceend = '.$mpdf->CJKforceend .';
<div class="cjk" style="text-align: '.$align.'; ">&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#xff0c;</div>
';
$mpdf->WriteHTML($html);
//==============================================================
*/
//==============================================================
//==============================================================
//==============================================================
// Tai Tham (Lanna script)
$htmlx = '
<div style="font-family:lannaalif">
<p>&#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a48;&#x1a63;&#x1a60;&#x1a45;&#x1a29;&#x1a60;&#x1a3f;&#x1a26;&#x1a49;&#x1a60;&#x1a3e;&#x1a72;&#x1a75; &#x1a49;&#x1a60;&#x1a3e;&#x1a6f;&#x1a37;&#x1a74;&#x1a75;&#x1a6c;&#x1a34;&#x1a6e;&#x1a62;&#x1a75;&#x1a63;&#x1a2f;&#x1a72;&#x1a20;&#x1a74;&#x1a76;&#x1a6c;&#x1a27;&#x1a61;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a48;&#x1a63;&#x1a60;&#x1a45;&#x1a43;&#x1a6f;&#x1a76;&#x1a60;&#x1a45; &#x1a32;&#x1a67;&#x1a60;&#x1a26;&#x1a45;&#x1a62;&#x1a60;&#x1a36;&#x1a3e;&#x1a66;&#x1a37;&#x1a75;&#x1a64;&#x1a60;&#x1a45;&#x1a3e;&#x1a63;&#x1a4b;&#x1a6f;&#x1a75;&#x1a60;&#x1a45; &#x1a3e;&#x1a63;&#x1a4b;&#x1a6a;&#x1a76;&#x1a3e;&#x1a63;&#x1a2a;&#x1a6f;&#x1a60;&#x1a45; &#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a24;&#x1a6b;&#x1a60;&#x1a36;&#x1a43;&#x1a3b;&#x1a6a;&#x1a41;</p> 

<p>&#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a27;&#x1a61;&#x1a6e;&#x1a43;&#x1a65;&#x1a6c;&#x1a20;&#x1a4b;&#x1a6e;&#x1a62;&#x1a63;&#x1a39;&#x1a71; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a37;&#x1a75;&#x1a64;&#x1a60;&#x1a45;&#x1a29;&#x1a60;&#x1a3f;&#x1a26;&#x1a41;&#x1a63;&#x1a60;&#x1a3f;&#x1a29;&#x1a68;&#x1a75;&#x1a20;&#x1a6f;&#x1a76;&#x1a60;&#x1a45;&#x1a3e;&#x1a63;&#x1a43;&#x1a6a;&#x1a41; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a20;&#x1a6c;&#x1a26;&#x1a24;&#x1a6b;&#x1a60;&#x1a36;&#x1a3b;&#x1a6f;&#x1a75;&#x1a22;&#x1a60;&#x1a3f;&#x1a45;&#x1a28;&#x1a69;&#x1a36; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a24;&#x1a74;&#x1a63;&#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a3e;&#x1a6a;&#x1a41; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a48;&#x1a6b;&#x1a60;&#x1a3e;&#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a3e;&#x1a66;</p>

<p>&#x1a3b;&#x1a6e;&#x1a65;&#x1a75;&#x1a60;&#x1a36;&#x1a37;&#x1a6c;&#x1a20;&#x1a45;&#x1a75;&#x1a64;&#x1a27;&#x1a61;&#x1a3e;&#x1a63;&#x1a22;&#x1a74;&#x1a6c;&#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a20;&#x1a74;&#x1a6c;&#x1a41;&#x1a74;&#x1a6c;&#x1a3e;&#x1a63;&#x1a43;&#x1a6f;&#x1a76;&#x1a60;&#x1a45;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a38;&#x1a66; &#x1a3b;&#x1a74;&#x1a75;&#x1a6c;&#x1a3e;&#x1a6f;&#x1a75;&#x1a33;&#x1a76;&#x1a63;&#x1a38;&#x1a6a;&#x1a48;&#x1a41;&#x1a66;&#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a37;&#x1a75;&#x1a64;&#x1a60;&#x1a45;&#x1a32;&#x1a6b;&#x1a60;&#x1a45;&#x1a2f;&#x1a66;&#x1a49;&#x1a63;&#x1a60;&#x1a3f;&#x1a2a;&#x1a6f;&#x1a60;&#x1a37;&#x1a49;&#x1a63;&#x1a60;&#x1a3f;&#x1a48;&#x1a6c;&#x1a60;&#x1a3f; &#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a37;&#x1a74;&#x1a75;&#x1a6c;&#x1a29;&#x1a6e;&#x1a65;&#x1a75;&#x1a6c;&#x1a4b;&#x1a49;&#x1a6f;&#x1a60;&#x1a3e;&#x1a43;&#x1a6f;&#x1a76;&#x1a60;&#x1a45; &#x1a27;&#x1a61;&#x1a32;&#x1a6f;&#x1a75;&#x1a60;&#x1a26;&#x1a20;&#x1a62;&#x1a60;&#x1a37;&#x1a3e;&#x1a6f;&#x1a76;&#x1a60;&#x1a45;&#x1a38;&#x1a71;&#x1a40;&#x1a6a;&#x1a75;&#x1a38;&#x1a56;&#x1a63;&#x1a60;&#x1a3f;&#x1a2f;&#x1a6c;&#x1a60;&#x1a3f;&#x1a22;&#x1a63;&#x1a60;&#x1a3f;&#x1a39;&#x1a76;&#x1a63; &#x1a22;&#x1a63;&#x1a60;&#x1a3f;&#x1a3b;&#x1a6e;&#x1a29;&#x1a60;&#x1a41;&#x1a7a; &#x1a22;&#x1a63;&#x1a60;&#x1a3f;&#x1a3b;&#x1a56;&#x1a6c;&#x1a60;&#x1a3f;&#x1a22;&#x1a63;&#x1a60;&#x1a3f;&#x1a49;&#x1a60;&#x1a45;&#x1a6f;&#x1a41;&#x1a22;&#x1a63;&#x1a60;&#x1a3f;&#x1a48;&#x1a55;&#x1a6c;&#x1a60;&#x1a3f;&#x1a40;&#x1a6a;&#x1a75;&#x1a37;&#x1a6b;&#x1a60;&#x1a36;&#x1a2f;&#x1a6c;&#x1a60;&#x1a3f;&#x1a38;&#x1a69;&#x1a3f;</p>
</div>
';

//==============================================================
//==============================================================
//==============================================================
//==============================================================
// TEST DOUBLE BORDER
//$mpdf->SetColumns(3);
$htmlx = '
<div style="background-color:#ddddff; padding: 10px;">
<div style="border-left:#ff0000 solid 16pt; border-right: #0000ff double 46pt; border-top: #00ff00 double 26pt; border-bottom: #880088 double 26pt; margin-bottom: 1em;">
Hallo World
</div>

<div style="border-left:#ff0000 double 16pt; border-right: #0000ff solid 16pt; border-top: #00ff00 solid 26pt; border-bottom: #880088 dotted 16pt; margin-bottom: 1em;">
Hallo World
</div>

<div style="border-left:#ff0000 solid 16pt; border-right: #0000ff solid 16pt; border-top: #00ff00 solid 26pt; border-bottom: #880088 solid 26pt; margin-bottom: 1em;">
Hallo World
</div>

<div style="border-left:#ff0000 solid 16pt; border-right: #0000ff solid 16pt; border-top: #00ff00 solid 26pt; border-bottom: none; margin-bottom: 1em;">
Hallo World
</div>

<br />

<div style="border-left:#ff0000 double 16pt; border-right: #0000ff double 16pt; border-top: #00ff00 double 26pt; border-bottom: #880088 solid 26pt; margin-bottom: 1em;">
Hallo World
</div>

<div style="border-left:#ff0000 double 16pt; border-right: #0000ff solid 16pt; border-top: #00ff00 double 26pt; border-bottom: #880088 solid 26pt; margin-bottom: 1em;">
Hallo World
</div>

<div style="border-radius: 35pt; padding: 1em; border-left:#ff0000 double 16pt; border-right: #0000ff double 16pt; border-top: #00ff00 double 26pt; border-bottom: #880088 double 26pt; margin-bottom: 1em;">
Hallo World
</div>


<div style="border-radius: 35pt; padding: 1em; border-left:#ff0000 solid 16pt; border-right: #0000ff solid 16pt; border-top: #00ff00 solid 26pt; border-bottom: #880088 solid 26pt; margin-bottom: 1em;">
Hallo World
</div>
</div>






<div style="background-color:#ffdddd; padding: 0;">

<div style="padding: 1em; margin-bottom: 1em; font-family: arial; border-bottom: 42px double #666; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 36px double #00F; ">Hallo World</div>

<div style="padding: 1em; margin-bottom: 1em; font-family: arial; border-bottom: 42px double #666; border-left: 16px solid #F00; border-top: 28px solid #0F0; border-right: 36px double #00F; ">Hallo World</div>

<div style="padding: 1em; margin-bottom: 1em; font-family: arial; border-bottom: 42px solid #666; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 36px solid #00F; ">Hallo World</div>

</div>

<table style="border-collapse: collapse;"><tr>
<td style="font-family: arial; border-bottom: 42px double #0FF; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 16px double #00F; "> A whole new world </td>
</tr>
</table>
<table style="border-collapse: none;"><tr>
<td style="font-family: arial; border-bottom: 42px double #0FF; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 16px double #00F; "> A whole new world </td>
</tr>
</table>
<table style="border-collapse: none;"><tr>
<td style="font-family: arial; border: 16px double #F00; border-top: 16px double #0F0;"> A whole new world </td>
</tr>
</table>

<div style="background-color:#ffdddd">
<table style="border-collapse: collapse;">
<tr>
<td style="font-family: arial; border-bottom: 42px double #0FF; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 16px double #00F; "> A whole new world </td>
</tr>
<tr>
<td style="font-family: arial; border-bottom: 42px double #0FF; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 16px double #00F; "> A whole new world </td>
</tr>
</table>
<table style="border-collapse: none;"><tr>
<td style="font-family: arial; border-bottom: 42px double #0FF; border-left: 16px double #F00; border-top: 28px double #0F0; border-right: 16px double #00F; "> A whole new world </td>
</tr>
</table>
<table style="border-collapse: none;"><tr>
<td style="font-family: arial; border: 16px double #F00; border-top: 16px double #0F0;"> A whole new world </td>
</tr>
</table>
</div>


<form>
  <fieldset style="border: 4px double red; border-radius: 5px; padding-left: 15px; border-right-color: green;">
  <legend>Fieldset and legend</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>

<form>
  <fieldset style="border: 4px solid red; border-radius: 5px; padding-left: 15px; border-right-color: green;">
  <legend>Fieldset and legend</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>

<form>
  <fieldset style="border: 4px dashed red; border-radius: 5px; padding-left: 15px; border-right-color: green;">
  <legend>Fieldset and legend</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>

<form>
  <fieldset style="border: 4px solid red; padding-left: 15px; border-right-color: green;">
  <legend style="font-family:dejavusanscondensed; font-kerning:normal">AWAY To War</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>

<form>
  <fieldset style="border: 4px double red; padding-left: 15px; border-right-color: green;">
  <legend>Fieldset and legend</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>

<form>
  <fieldset style="border: 4px dashed red; padding-left: 15px; border-right-color: green;">
  <legend style="font-family:dejavusanscondensed; font-kerning:normal">AWAY To War</legend>
<p>Support for fieldset and legend was introduced in mPDF v5.5. Consider it experimental!</p>
  </fieldset>
</form>
';

$mpdf->useKerning = true;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
function SVGarcpath($start, $end, $cx = 50, $cy = 50, $r = 48) {
	$start = deg2rad($start);
	$end = deg2rad($end);
	while ($end < $start) { $end += (M_PI*2); }
	if (($end - $start) > M_PI) { $largearcflag = 1; }
	else { $largearcflag = 0; }
	$start = $start-(M_PI/2);	// Adjust to start from the top=0 degrees
	while ($start < 0) { $start += (M_PI*2); }
	$end = $end-(M_PI/2);
	while ($end < 0) { $end += (M_PI*2); }
      $commands = array('M', $cx, $cy,
            'l', $r * cos($start), $r * sin($start),
            'A', $r, $r, 0,  $largearcflag, 1, $cx + ($r * cos($end)), $cy + ($r * sin($end)),
		"z");
      $c = implode(' ', $commands);
      return $c;
}

function SVGpie($segs, $w=30, $backgroundcolor="none", $linecolor="none", $linewidth=0, $seglinecolor="none", $seglinewidth=0) {
	// $w is a number (? pixels)
	// $seglinewidth is a number (? pixels)
	$os = (max($seglinewidth,$linewidth))/2;
	$svg = '<svg width="'.$w.'" height="'.$w.'" viewBox="0,0,'.$w.','.$w.'" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'."\n";

	if ($backgroundcolor != "none") {
		$svg .= '<g stroke="none" fill="'.$backgroundcolor.'"><circle cx="'.($w/2).'" cy="'.($w/2).'" r="'.(($w/2)-$os).'" /></g>'."\n";
	}

	foreach($segs AS $arc) {
	      $path = SVGarcpath($arc[0], $arc[1], ($w/2), ($w/2), ($w/2)-$os);
	      $svg .= '<g stroke="'.$seglinecolor.'" stroke-width="'.$seglinewidth.'" fill="'.$arc[2].'"><path d="'.$path.'" /></g>'."\n";
	}

	if ($linecolor != "none") {
		$svg .= '<g stroke="'.$linecolor.'" stroke-width="'.$linewidth.'" fill="none"><circle cx="'.($w/2).'" cy="'.($w/2).'" r="'.(($w/2)-$os).'" /></g>'."\n";
	}


	$svg .= '</svg>'."\n";
	return $svg;
}

$segs = array(
     array(0, 90, "blue"),
);
$segs2 = array(
     array(0, intval(0.65*360), "blue"),
);

$svg = SVGpie($segs, 30, "wheat", "none", 0, "none", 0);
$svg2 = SVGpie($segs2, 30, "wheat", "none", 0, "none", 0);

$html_SVG = '<html><body>
<table>
<tr><td style="vertical-align: middle">Normal (25%): </td><td style="vertical-align: middle">'.$svg.'</td></tr>
<tr><td style="vertical-align: middle">Large (65%): </td><td style="vertical-align: middle">'.$svg2.'</td></tr>
</table>
</body></html>'; 
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
// Test In-line font characteristics
$htmlx = '
<div style="font-size: 12pt; font-family:Times">
Normal <span style="font-family:Arial">Arial <span style="font-size:16pt">font-size 16 <span style="color: red">red <span style="font-weight:bold">bold <span style="font-style:italic">italic <span style="font-variant:small-caps">Small Caps <span style="text-decoration:underline">underline <span style="text-shadow: 2px 2px #ff0000;">shadow</span> not shadow; </span> not underline; </span> not small-caps; </span> not italic; </span> not bold; </span> not red; </span> not font-size-16; </span>  not Arial-font-family; normal
</div>

<table style="border: 1px solid #888888"><tr><td style="font-size: 12pt; font-family:Times">
Normal <span style="font-family:Arial">Arial <span style="font-size:16pt">font-size 16 <span style="color: red">red <span style="font-weight:bold">bold <span style="font-style:italic">italic <span style="font-variant:small-caps">Small Caps <span style="text-decoration:underline">underline <span style="text-shadow: 2px 2px #ff0000;">shadow</span> not shadow; </span> not underline; </span> not small-caps; </span> not italic; </span> not bold; </span> not red; </span> not font-size-16; </span>  not Arial-font-family; normal
</td></tr></table>

<div style="font-size: 16pt; font-family:DejaVuSansCondensed">
Normal <span style="font-kerning:normal">kern AWAY To <span style="font-kerning:none">nokern AWAY To  </span> not nokern AWAY To; </span> not kern AWAY To; normal <span style="text-outline: 0.03em green">green <span style="text-outline-color: blue">blue  </span> not blue; </span> not green; normal
</div>

<table style="border: 1px solid #888888"><tr><td style="font-size: 16pt; font-family:DejaVuSansCondensed">
Normal <span style="font-kerning:normal">kern AWAY To <span style="font-kerning:none">nokern AWAY To  </span> not nokern AWAY To; </span> not kern AWAY To; normal <span style="text-outline: 0.03em green">green <span style="text-outline-color: blue">blue  </span> not blue; </span> not green; normal
</td></tr></table>

';

//==============================================================
//==============================================================
// Test In-line font-feature characteristics
$htmlx = '
<table style="border: 1px solid #888888"><tr><td style="font-size: 15pt; font-family:Calibri">
Calibri normal: <br />
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">pnum</span> <span style="font-feature-settings:\'pnum\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 
</span>
<br />
<span style="color:#8888DD;">ordn</span> <span style="font-feature-settings:\'ordn\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 
</span>
<br />

<span style="color:#8888DD;">onum</span> <span style="font-feature-settings:\'onum\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">lnum</span> <span style="font-feature-settings:\'lnum\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">case</span> <span style="font-feature-settings:\'case\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">salt</span> <span style="font-feature-settings:\'salt\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">dlig</span> <span style="font-feature-settings:\'dlig\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">c2sc</span> <span style="font-feature-settings:\'c2sc\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">smcp</span> <span style="font-feature-settings:\'smcp\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check <br />

<span style="color:#8888DD;">sups</span> <span style="font-feature-settings:\'sups\' on">
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">sups off:</span> 
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">smcp off:</span> 
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">c2sc off:</span>
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">dlig off:</span> 
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">salt off:</span> 
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">case off:</span>
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">lnum off:</span>
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 

</span> <br /><span style="color:#DD8888;">onum off:</span> 
&pound;123450 (case32) 1st 3rd [CASE] g& CaSeMeNt traffic check 
</td></tr></table>
';

//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
$mpdf->jSmaxChar = 5;	// Maximum spacing to allocate to character spacing. (0 = no maximum)

// Test GPOS
$html = '
<div style="font-family:Garuda; font-size: 28pt; font-feature-settings:\'ccmp\' off, \'mark\' 0, \'mkmk\' 0;">
&#xe1c;&#xe39;&#xe49; &#xe04;&#xe39;&#xe48;
</div>

<div style="font-family:Garuda; font-size: 28pt;">
&#xe1c;&#xe39;&#xe49; &#xe04;&#xe39;&#xe48;
</div>
// GSUB 5.2
<div style="font-family:arialunicodems; font-size: 28pt;">
&#xcb6;&#xcbf;&#xc95;&#xccd;&#xcb7;&#xcc6; &#xcb5;&#xcbf;&#xca7;&#xcbf;&#xcb8;&#xcbf;&#xca6;&#xcc6;
</div>

// GSUB 7.2
<div style="font-family: arialunicodems; font-size: 36pt; font-feature-settings:\'dist\' 0;">
&#xc95;&#xccd;&#xcb0;&#xccc; Kannada
</div>

<div style="font-family: arialunicodems; font-size: 36pt;">
&#xc95;&#xccd;&#xcb0;&#xccc;
</div>



<div dir="rtl" style="font-family: \'DejaVu Sans\'; font-size: 36pt; font-feature-settings:\'mark\' off;">
&#x5d6;&#x5bc;&#x5b5;&#x5d9;&#x5bc;&#x5b0;&#x5e9;&#x5c2;&#x5b3;&#x5da;&#x5b8;
</div>

<div dir="rtl" style="font-family: \'DejaVu Sans\'; font-size: 36pt">
&#x5d6;&#x5bc;&#x5b5;&#x5d9;&#x5bc;&#x5b0;&#x5e9;&#x5c2;&#x5b3;&#x5da;&#x5b8;
</div>

<div style="border:0.2mm solid #000088; padding: 0.5em; background-color: #EEEEEE; font-size: 36pt;">
<div style="font-family:\'Dejavu Sans Condensed\';">A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi (DejaVu Sans Condensed)
traffic
insufflate</div>
<div style="font-family:\'Arial\';">A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi traffic insufflate (Arial)</div>
<div style="font-family:\'Times New Roman\';">A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi (Times New Roman)</div>
</div>

// Test GPOS 2
<div style="border:0.2mm solid #000088; padding: 0.5em; background-color: #EEEEEE; font-size: 36pt;font-feature-settings:\'kern\';">
<div style="font-family:\'Arial\'; font-size: 36pt;font-feature-settings:\'kern\';">To A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi Wo&#x308; W&#x308; Ta Tu To&#x308; (Arial)<br />
AWAY To WAR</div>
<div style="font-family:\'Dejavu Sans Condensed\';font-feature-settings:\'kern\';">Ta To A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi (DejaVu Sans Condensed)
traffic
insufflate Ta Tu
<br />
AWAY To WAR</div>
<div style="font-family:\'Dejavu Sans Condensed\'; font-size: 16pt;font-feature-settings:\'kern\';">To A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi Wo&#x308; W&#x308; Ta Tu To&#x308; (DejaVu Sans Condensed)
traffic
insufflate</div>

<div style="font-family:\'Times New Roman\';font-feature-settings:\'kern\';">To A&#769; a&#769; A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi Wo&#x308; Ta Tu To&#x308; (Times New Roman)<br />
AWAY To WAR</div>
</div>
<div style="font-family:\'Arial\'; font-size: 36pt;font-feature-settings:\'kern\';">To Ta D&#x30F; D&#x323; a&#x309; A&#769; a&#769;  A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi Wo&#x308; Ta Tu To&#x308; (Arial)<br />
AWAY To WAR</div>
<div style="font-family:\'Arial\'; font-size: 16pt;font-feature-settings:\'kern\';">To D&#x30F; D&#x323; a&#x309; A&#769; a&#769;  A&#x307; a&#x307; A&#x308; a&#x308; i&#x308; fi Wo&#x308; Ta Tu To&#x308; (Arial)<br />
AWAY To WAR</div>
Kerning = GPOS Lookup Type 2; Mark to base = GOS Lookup Type 4
<div style="font-family:\'Arial\'; font-size: 36pt;">A&#x308;&#x0315;&#x0303; a&#x308;&#x0315;&#x0303; a&#x308;&#x0303;&#x0315; a&#x0303;&#x308;&#x0315; a&#x0315;&#x0303;&#x308; (GPOS Lookup Type 6)</div>

<div style="font-family:DejavuSansCondensed; font-size: 36pt;font-feature-settings:\'mark\' off, \'mkmk\' off;">&#x0e7;&#x325; (GPOS Lookup Type 5)</div>

<div style="font-family:DejavuSansCondensed; font-size: 36pt;">&#x0e7;&#x325; (GPOS Lookup Type 5)</div>

<div style="font-family:ArabicTypesetting; font-size: 46pt;">&#x627;&#x62b;&#x645;&#x643;&#x62d;&#x647;

  (Cursive GPOS Lookup Type 3)</div>
<div style="font-family:Arial; font-size: 16pt;">A&#x308; a&#x308; a&#x308; a&#x0303; a&#x0315;</div>
<div style="font-family:Arial; font-size: 16pt;">A&#x308;&#x0315; a&#x308;&#x0315; a&#x308;&#x0303; a&#x0303;&#x308; a&#x0315;&#x0303;</div>
<div style="font-family:Arial; font-size: 16pt;">A&#x308;&#x0315;&#x0303; a&#x308;&#x0315;&#x0303; a&#x308;&#x0303;&#x0315; a&#x0303;&#x308;&#x0315; a&#x0315;&#x0303;&#x308;</div>




<div style="font-family:arabictypesetting; font-size: 28pt;" dir="rtl">&#x627;&#x659;&#x659;&#x647; &#x627;&#x647; &#x647; &#x648;&#x659;&#x647; &#x648;&#x647; &#x647; </div>




<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">trA&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To </div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">trA&#x308;&#x0315;&#x0303;ffic AWAY To <span>tra&#x308;&#x0315;&#x0303;ffic AWAY To</span> tra&#x308;&#x0315;&#x0303;ffic AWAY To <span>tra&#x308;&#x0315;&#x0303;ffic AWAY To</span> </div>


// TEST GPOS Ligature Position

<div style="font-family:arabictypesetting; font-size: 46pt; font-feature-settings:\'kern\';" dir="rtl">
&#x64a;&#x64e;&#x640;&#x670;&#x653;&#x623;&#x64e;&#x64a;&#x651;&#x64f;&#x647;&#x64e;&#x627; &#x64a;&#x64e;&#x646;&#x62a;&#x64f;&#x645; &#x644;&#x64e;&#x649;&#x670;&#x653; &#x6da;
 &#x648;&#x64e;&#x644;&#x64e;&#x627;  &#x643;&#x64e;&#x645;&#x64e;&#x627; 
&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64f;  &#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64e;  &#x648;&#x64e;&#x644;&#x64e;&#x627; 
&#x644;&#x64e;&#x627; 
&#x631;&#x651;&#x650;&#x62c;&#x64e;&#x627;&#x644;&#x650;&#x643;&#x64f;&#x645;&#x652; &#x6d6;

&#x62e;&#x652;&#x631;&#x64e;&#x649;&#x670; &#x6da; &#x648;&#x64e;&#x644;&#x64e;&#x627; &#x64a;&#x64e;&#x623;&#x652;&#x628;&#x64e;&#x6da;
&#x648;&#x64e;&#x644;&#x64e;&#x627; &#x62a;&#x64e;&#x633;&#x652;&#x640;&#x654;&#x64e;&#x645;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x623;&#x64e;&#x648;&#x652; &#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; 
&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x650;  &#x648;&#x64e;&#x623;&#x64e;&#x62f;&#x652;&#x646;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627;  &#x625;&#x650;&#x644;&#x651;&#x64e;&#x622; 
 &#x628;&#x64e;&#x64a;&#x652;&#x646;&#x64e;&#x643;&#x64f;&#x645;&#x652;  &#x639;&#x64e;&#x644;&#x64e;&#x64a;&#x652;&#x643;&#x64f;&#x645;&#x652;  &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627; 
&#x64a;&#x64e;&#x639;&#x652;&#x62a;&#x64f;&#x645;&#x652; &#x6da; &#x648;&#x64e;&#x644;&#x64e;&#x627;  &#x643;&#x64e;&#x627;&#x62a;&#x650;&#x628;&#x64c;&#x6ed; 
</div>

// TEST GPOS Ligature Position - TABLES

<table dir="rtl" style="font-family:me_quran; font-size: 26pt;line-height: 2em; "><tr><td style="border:1px solid #888888;text-align:justify; font-feature-settings:\'kern\'; word-spacing: 0.3em;">
&#x64a;&#x64e;&#x640;&#x670;&#x653;&#x623;&#x64e;&#x64a;&#x651;&#x64f;&#x647;&#x64e;&#x627; &#x64a;&#x64e;&#x646;&#x62a;&#x64f;&#x645; &#x644;&#x64e;&#x649;&#x670;&#x653; &#x6da;
 &#x648;&#x64e;&#x644;&#x64e;&#x627;  &#x643;&#x64e;&#x645;&#x64e;&#x627; 
&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64f;  &#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64e;  &#x648;&#x64e;&#x644;&#x64e;&#x627; 
&#x644;&#x64e;&#x627; 
&#x631;&#x651;&#x650;&#x62c;&#x64e;&#x627;&#x644;&#x650;&#x643;&#x64f;&#x645;&#x652; &#x6d6;

&#x62e;&#x652;&#x631;&#x64e;&#x649;&#x670; &#x6da; &#x648;&#x64e;&#x644;&#x64e;&#x627; &#x64a;&#x64e;&#x623;&#x652;&#x628;&#x64e;&#x6da;
&#x648;&#x64e;&#x644;&#x64e;&#x627; &#x62a;&#x64e;&#x633;&#x652;&#x640;&#x654;&#x64e;&#x645;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x623;&#x64e;&#x648;&#x652; &#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; 
&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x650;  &#x648;&#x64e;&#x623;&#x64e;&#x62f;&#x652;&#x646;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627;  &#x625;&#x650;&#x644;&#x651;&#x64e;&#x622; 
 &#x628;&#x64e;&#x64a;&#x652;&#x646;&#x64e;&#x643;&#x64f;&#x645;&#x652;  &#x639;&#x64e;&#x644;&#x64e;&#x64a;&#x652;&#x643;&#x64f;&#x645;&#x652;  &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627; 
&#x64a;&#x64e;&#x639;&#x652;&#x62a;&#x64f;&#x645;&#x652; &#x6da; &#x648;&#x64e;&#x644;&#x64e;&#x627;  &#x643;&#x64e;&#x627;&#x62a;&#x650;&#x628;&#x64c;&#x6ed; 
</td></tr></table>



// TEST \'curs\' GPOS

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'curs\' off;">
&#x0640;&#x0649;&#x0766;&#x0640;
&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt;">
&#x0640;&#x0649;&#x0766;&#x0640;
&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'curs\' off;">
&#x633;&#x652;&#x62a;&#x64e;&#x634;&#x652;&#x647;&#x650;&#x62f;&#x64f;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'curs\';">
&#x633;&#x652;&#x62a;&#x64e;&#x634;&#x652;&#x647;&#x650;&#x62f;&#x64f;
</div>

// TEST LINE-BREAKING

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ff AWAY 1a) tra&#x308;&#x0315;&#x0303;ffic AWAY To in<b>suff-er</b>able 
tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY</div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal;">
trA&#x308;&#x0315;&#x0303;ffic AWAY 1b) tra&#x308;&#x0315;&#x0303;ffic AWAY To 
in&shy;suffer&shy;able fra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal;">
tr<i>A&#x308;&#x0315;&#x0303;ff</i>icAWAYinsufferable<b>Togra&#x308;&#x0315;&#x0303;ffic</b>AWAYTotra&#x308;&#x0315;&#x0303;ffic2e)i)AWAYTotra&#x308;&#x0315;&#x0303;fficAWAYTotra&#x308;&#x0315;&#x0303;fficA&#x308;WAYTo</div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ffic2e)ii)A)withSHY&shy;suf&shy;ableTotra&#x308;&#x0315;&#x0303;ffic<b>AWAYTo</b>tra&#x308;&#x0315;&#x0303;ffic AWAY To hra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal">
trA&#x308;&#x0315;&#x0303;ffic AW 2e)ii)A) tra&#x308;&#x0315;&#x0303;ffic AWAY To
in<b>suffer</b>able tra&#x308;&#x0315;&#x0303;ffic AWAY 3.) tra&#x308;&#x0315;&#x0303;ffic
A&#x308;WAY To tra&#x308;&#x0315;&#x0303;ffic</div>

<div style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ffic2e)ii)B)sufferableTotra&#x308;&#x0315;&#x0303;ffic<b>AWAYTotra&#x308;&#x0315;&#x0303;ffic</b> AWAY To hra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </div>

// TEST LINE-BREAKING - TABLES
<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ff AWAY 1a) tra&#x308;&#x0315;&#x0303;ffic AWAY To in<b>suff-er</b>able 
tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY</td></tr></table>

<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal;">
trA&#x308;&#x0315;&#x0303;ffic AWAY 1b) tra&#x308;&#x0315;&#x0303;ffic AWAY To 
in&shy;suffer&shy;able fra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </td></tr></table>

<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal;">
tr<i>A&#x308;&#x0315;&#x0303;ff</i>icAWAYinsufferable<b>Togra&#x308;&#x0315;&#x0303;ffic</b>AWAYTotra&#x308;&#x0315;&#x0303;ffic2e)i)AWAYTotra&#x308;&#x0315;&#x0303;fficAWAYTotra&#x308;&#x0315;&#x0303;fficA&#x308;WAYTo</td></tr></table>

<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ffic2e)ii)A)withSHY&shy;suf&shy;ableTotra&#x308;&#x0315;&#x0303;ffic<b>AWAYTo</b>tra&#x308;&#x0315;&#x0303;ffic AWAY To hra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </td></tr></table>

<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;font-kerning:normal">
trA&#x308;&#x0315;&#x0303;ffic AW 2e)ii)A) tra&#x308;&#x0315;&#x0303;ffic AWAY To
in<b>suffer</b>able tra&#x308;&#x0315;&#x0303;ffic AWAY 3.) tra&#x308;&#x0315;&#x0303;ffic
A&#x308;WAY To tra&#x308;&#x0315;&#x0303;ffic</td></tr></table>

<table><tr><td style="font-family:Arial; font-size: 28pt;border:1px solid #888888;text-align:justify;">
trA&#x308;&#x0315;&#x0303;ffic2e)ii)B)sufferableTotra&#x308;&#x0315;&#x0303;ffic<b>AWAYTotra&#x308;&#x0315;&#x0303;ffic</b> AWAY To hra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic AWAY To tra&#x308;&#x0315;&#x0303;ffic A&#x308;WAY To </td></tr></table>




// CSS control of features

<div style="font-family:DejavuSansCondensed; font-size: 28pt;border:1px solid #888888;text-align:justify;font-feature-settings:\'salt\';">all <span style="font-feature-settings:\'salt\' 0;">all</span> all</div>

<div style="font-family:Trebuchet MS; font-size: 28pt;border:1px solid #888888;text-align:justify;font-variant:small-caps">Small Caps. 1,278 and More.</div>

<div style="font-family:DejavuSansCondensed; font-size: 28pt;border:1px solid #888888;text-align:justify;font-variant:small-caps">Small Caps. 1,278 and More.</div>

<div style="font-family:DejavuSansCondensed; font-size: 28pt;border:1px solid #888888;text-align:justify; ">1st 100 1/2 traffic AWAY feast To</div>

<div style="font-family:DejavuSansCondensed; font-size: 28pt;border:1px solid #888888;text-align:justify; font-feature-settings:\'salt\', \'aalt\', \'dlig\', \'hlig\', \'kern\';">1st 100 1/2 traffic AWAY feast To &#x03B2; &#x03B8; &#x03C6;</div>

<div style="font-family:CambriaMath; font-size: 28pt;border:1px solid #888888;text-align:justify; font-feature-settings:\'frac\', \'zero\', \'ordn\', \'salt\', \'dlig\', \'hist\', \'kern\';">1st 100 1/2 traffic AWAY feast To &#x03B2; &#x03B8; &#x03C6;</div>

<div style="font-family:CambriaMath; font-size: 28pt;border:1px solid #888888;text-align:justify; font-feature-settings:\'sinf\';">C10H16N5O13P3</div>

Cambria:
      \'salt\'  
      \'c2sc\'  
      \'smcp\'  
      \'sups\'  
      \'sinf\'  
      \'case\'  
       \'calt\'  
      \'tnum\'  
      \'pnum\'  
      \'onum\'  
      \'lnum\'  
      \'numr\'  
       \'dnom\'  

<div style="font-family:Calibri; font-size: 28pt;border:1px solid #888888;text-align:justify; font-feature-settings:\'frac\', \'zero\', \'ordn\', \'salt\', \'aalt\', \'dlig\', \'hist\', \'kern\';">1st 100 1/2 traffic AWAY feast To &#x03B2; &#x03B8; &#x03C6;</div>

Calibri:
      \'case\'  
      \'calt\'  
      \'numr\'  
      \'dnom\'  
      \'subs\'  
      \'tnum\'  
      \'pnum\'  
      \'onum\'  
      \'lnum\'  
      \'salt\'  
      \'c2sc\'  
      \'smcp\'  
      \'sups\'  
      \'ordn\'  
      \'liga\'  
      \'dlig\'  
<div style="font-family:XBRiyaz; font-size: 28pt;border:1px solid #888888;text-align:justify; font-feature-settings:\'frac\';">1st 100 1/2 traffic AWAY feast To</div>

<div style="font-family:\'Arial Unicode MS\'; font-size: 28pt;border:1px solid #888888; ">1st 100 1/2 traffic AWAY feast To</div>

// TEST GPOS Type 2 Format 1 (abvm)
<div style="font-family:Mangal; font-size: 18pt;">&#x930;&#x94d;&#x935;&#x947;&#x951;</div>

// TEST \'kern\' GPOS in Arabic Typesetting


<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x06dd;&#x0663;&#x0664;&#x0665;
</div>


<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x0600;&#x0663;&#x0664;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x0601;&#x0663;&#x0664;&#x0665;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x0601;&#x0663;&#x0664;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x0602;&#x0663;&#x0664;
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x0603;&#x0663;&#x0664;
</div>


This test for reversal of chunks in flowing block, preserving consecutive chunks of LTR in an RTL line
<div dir="rtl" style="font-family:arial; font-size: 18pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x627;&#x644;&#x62d;&#x645;&#x62f; <span>These</span> <b>English</b> words and <img src="goto.gif" /> <span style="color:red">not reversed</span> &#x0663;&#x0664;&#x0665; &#x644;&#x644;&#x647; 
</div>


<div dir="rtl" class="mpdf_toc" id="mpdf_toc_0" style="font-family:arial; font-size: 18pt">
<div class="mpdf_toc_level_0"><a class="mpdf_toc_a" href="#__mpdfinternallink_1"><span class="mpdf_toc_t_level_0">&#x627;&#x644;&#x642; 1</span></a> .... <a class="mpdf_toc_a" href="#__mpdfinternallink_1"><span class="mpdf_toc_p_level_0">3</span></a></div>
<div class="mpdf_toc_level_0"><a class="mpdf_toc_a" href="#__mpdfinternallink_2"><span class="mpdf_toc_t_level_0">&#x627;&#x644;&#x642;&#x633;&#x645; 2</span></a> <dottab outdent="0" /> <a class="mpdf_toc_a" href="#__mpdfinternallink_2"><span class="mpdf_toc_p_level_0">3</span></a></div>
</div>

<div dir="rtl" style="font-family:\'arabic typesetting\'; font-size: 48pt; font-feature-settings:\'ccmp\', \'curs\', \'kern\';">
&#x627;&#x644;&#x62d;&#x645;&#x62f; &#x06dd;&#x0663;&#x0664;&#x0665; &#x0663;&#x0664;&#x0665; &#x644;&#x644;&#x647; 
</div>

<div style="font-family:arial; font-size: 22pt; direction: rtl;">
&#x5d1;&#x5d3;&#x5d9;&#x5e7;&#x5d4; &#x5d1;&#x5d0;&#x5e0;&#x5d2;&#x5dc;&#x5d9;&#x5ea; Latin Text - (&#x5e0;&#x5d9;&#x5e1;&#x5d9;&#x5d5;&#x5df; A)
<br />
&#x5d1;&#x5d3;&#x5d9;&#x5e7;&#x5d4; &#x5d1;&#x5d0;&#x5e0;&#x5d2;&#x5dc;&#x5d9;&#x5ea; Latin Text - (&#x5e0;&#x5d9;&#x5e1;&#x5d9;&#x5d5;&#x5df; 2)
<br />

&#x5d1;&#x5e0;&#x5e7;: 12, &#x5e1;&#x5e0;&#x5d9;&#x5e3;: 11, &#x5de;&#x5e1;\' &#x5d7;&#x5e9;&#x5d1;&#x5d5;&#x5df;: 111, &#x5de;&#x5e1;\' &#x5d4;&#x5de;&#x5d7;&#x5d0;&#x5d4;: 1112
<br />

&#x5e7;&#x5d9;&#x5d6;&#x5d5;&#x5d6; &#x5d9;&#x5de;&#x5d9;&#x5dd; &#x5d1;&#x5d2;&#x5d9;&#x5df; &#x5ea;&#x5d7;&#x5d9;&#x5dc;&#x5ea; &#x5e2;&#x5d1;&#x5d5;&#x5d3;&#x5d4; &#x5de;&#x5ea;&#x5d0;&#x5e8;&#x5d9;&#x5da; 08/01/2013
<br />

&#x5e9;&#x5d9;&#x5e8;&#x5d5;&#x5ea;&#x5d9; Cloud Computing
</div>

<div style="font-family:arial; font-size: 30pt; ">
(&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64e;) A&#x308;&#x315; a&#x308;&#x315;
</div>

<div style="font-family:arial; font-size: 30pt; ">
A&#x308;&#x315;&#x303; A&#x308;&#x315;a&#x308;&#x315;a&#x308;&#x303; (&#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x64e;) A&#x308;&#x315; a&#x308;&#x315;
</div>

GPOS Type 2 Format 1 "kern"
<div style="direction: rtl; font-family: \'arabic typesetting\'; line-height: 1.8; font-size: 42pt; font-feature-settings:\'curs\', \'kern\'; border: 1px solid #888888;">
&#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; 
</div>


';

//==============================================================
//==============================================================
//==============================================================
$mpdf->debug = true;
//==============================================================
//==============================================================
$htmlx ='
<style>
body, p { font-family: freesans; font-size: 16pt;  }
h3 { font-size: 16pt; margin-bottom:0; }
.tamil {
	 font-family:Latha;
  font-size: 16pt;
}
.oriya {
	font-family:Kalinga;
  font-size: 16pt;
}
.punjabi {
	font-family:Raavi;
	font-size: 16pt;
}
.gujarati {
	font-family:Shruti;
	font-size: 16pt;
}
.hindi {
	font-family:Mangal;
	font-size: 16pt;
}
.nepali {
	font-family:Mangal;
	font-size: 16pt;
}
.assamese { /* same as bengali */
	font-family:vrinda;
	font-size: 16pt;
}
.bengali {
	font-family:vrinda;
	font-size: 16pt;
}
.telugu {
	font-family:gautami;
	font-size: 16pt;
}
.kannada {
	font-family:Tunga;
	font-size: 16pt;
}
.malayalam {
	font-family:Kartika;
	font-size: 16pt;
}

</style>


<p class="bengali">

&#x9ce;&#x9a4;
&#x9ce;&#x9a4;&#x9c7;
&#x9a4;
&#x9a4;&#x9cd;&#x9a4;
&#x9a4;&#x9cd;&#x9a4;&#x9c7;
&#x9a4;&#x9cd;&#x200d;&#x9a4;
&#x9a4;&#x9cd;&#x200d;&#x9a4;&#x9c7;

<br />

&#x9a4;&#x9cd;&#x200d; = Khanda Ta. (U+09CE) character was added in Unicode v 4.1 and prior to this, (U+09A4 U+09CD U+200D) used <br />
</p>

Kannada
<div class="kannada">&#xca4;&#xcae;&#xccd;&#xcae;&#xca6;&#xcc7; &#xcaf;&#xcc1;&#xcb5;&#xca4;&#xcbf;&#xcaf;&#xcca;&#xcac;&#xccd;&#xcac;&#xcb3;&#xca8;&#xccd;&#xca8;&#xcc1; &#xcb8;&#xcbe;&#xcac;&#xcc0;&#xca4;&#xcbe;&#xc97;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcc1; &#xca8;&#xca1;&#xcc6;&#xcb8;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcc1; &#xc87;&#xc82;&#xc97;&#xccd;&#xcb2;&#xcc6;&#xc82;&#xca1;&#xccd; &#xca8;&#xccd;&#xcaf;&#xcbe;&#xcaf;&#xcbe;&#xcb2;&#xcaf;&#xcb5;&#xcc1; &#xcaf;&#xcc1;&#xcb5;&#xca4;&#xcbf;&#xcaf;&#xca8;&#xccd;&#xca8;&#xcc1; &#xcae;&#xcb2;&#xca6;&#xcca;&#xca1;&#xccd;&#xca1;&#xcaa;&#xccd;&#xcaa; &#xcae;&#xca4;&#xccd;&#xca4;&#xcc1; &#xcad;&#xcbe;&#xcb5; &#xc85;&#xca4;&#xccd;&#xcaf;&#xcbe;&#xc9a;&#xcbe;&#xcb0; &#xc95;&#xcbe;&#xcb0;&#xccd;&#xca1;&#xcbf;&#xcab;&#xccd; &#xc95;&#xccd;&#xcb0;&#xccc;&#xca8;&#xccd; &#xca8;&#xccd;&#xcaf;&#xcbe;&#xcaf;&#xcbe;&#xcb2;&#xcaf;&#xca6;&#xcb2;&#xccd;&#xcb2;&#xcbf; &#xc85;&#xcaa;&#xcb0;&#xcbe;&#xca7;&#xcbf;&#xc97;&#xcb3;&#xcb2;&#xccd;&#xcb2;&#xcbf; &#xcae;&#xcb2;&#xca4;&#xc82;&#xca6;&#xcc6; &#xcae;&#xca4;&#xccd;&#xca4;&#xcc1; &#xcae;&#xcb2;&#xca6;&#xcca;&#xca1;&#xccd;&#xca1;&#xcaa;&#xccd;&#xcaa; &#xc85;&#xc95;&#xccd;&#xcb0;&#xcae; &#xcb5;&#xcb2;&#xcb8;&#xcbf;&#xc97;&#xcb0;&#xcbe;&#xc97;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcc1; &#xc85;&#xcb5;&#xcb0;&#xca8;&#xccd;&#xca8;&#xcc1; &#xcad;&#xcbe;&#xcb0;&#xca4;&#xc95;&#xccd;&#xc95;&#xcc6; &#xcae;&#xcbe;&#xca1;&#xcb2;&#xcbe;&#xc97;&#xcc1;&#xca4;&#xccd;&#xca4;&#xca6;&#xcc6;.
<br />

&#xcae;&#xcb2;&#xca6;&#xcca;&#xca1;&#xccd;&#xca1;&#xcaa;&#xccd;&#xcaa; &#xcae;&#xca4;&#xccd;&#xca4;&#xcc1; &#xcae;&#xcb2;&#xca4;&#xc82;&#xca6;&#xcc6;&#xc97;&#xcc6; &#xc95;&#xccd;&#xcb0;&#xcae;&#xcb5;&#xcbe;&#xc97;&#xcbf; &#xcae;&#xca4;&#xccd;&#xca4;&#xcc1; 
<br />

&#xcac;&#xcbe;&#xcb2;&#xc95;&#xcbf;&#xcaf;&#xca8;&#xccd;&#xca8;&#xcc7; &#xc85;&#xca4;&#xccd;&#xcaf;&#xcbe;&#xc9a;&#xcbe;&#xcb0; &#xcae;&#xcbe;&#xca1;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcb0;&#xcc1; &#xcb5;&#xcb0;&#xccd;&#xcb7;&#xcb5;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcbe;&#xc97; &#xc97;&#xcb0;&#xccd;&#xcad;&#xc95;&#xccd;&#xc95;&#xcc6; &#xc95;&#xcbe;&#xcb0;&#xca3;&#xcb5;&#xcbe;&#xc97;&#xcbf;&#xcb0;&#xcc1;&#xcb5;&#xcc1;&#xca6;&#xca8;&#xccd;&#xca8;&#xcc2; &#xcae;&#xcb2; &#xca6;&#xcca;&#xca1;&#xccd;&#xca1;&#xcaa;&#xccd;&#xcaa; &#xc85;&#xcb5;&#xca7;&#xcbf;&#xcaf;&#xcb2;&#xccd;&#xcb2;&#xcbf; &#xc92;&#xcaa;&#xccd;&#xcaa;&#xcbf;&#xc95;&#xcca;&#xc82;&#xca1;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcbe;&#xca8;&#xcc6; &#xc86;&#xc95;&#xcc6;&#xcaf;&#xca8;&#xccd;&#xca8;&#xcc1; &#xc85;&#xca4;&#xccd;&#xcaf;&#xcbe;&#xc9a;&#xcbe;&#xcb0; &#xcae;&#xcbe;&#xca1;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcc1; &#xcae;&#xc97;&#xcc1;&#xcb5;&#xcbe;&#xc97;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcbe;&#xc97; &#xca4;&#xccb;&#xcb0;&#xcbf;&#xcb8;&#xcbf;&#xca6;&#xccd;&#xca6;. &#xc85;&#xcb2;&#xccd;&#xcb2;&#xca6;&#xcc6; &#xcb9;&#xcb2;&#xccd;&#xcb2;&#xcc6; &#xca8;&#xca1;&#xcc6;&#xcb8;&#xcbf;&#xca6;&#xccd;&#xca6; &#xca8;&#xccd;&#xcaf;&#xcbe;&#xcaf;&#xcbe;&#xcb2;&#xcaf; 
<br />

&#xcac;&#xcbe;&#xcb2;&#xc95;&#xcbf;&#xcaf;&#xcbe;&#xc97;&#xcbf;&#xca6;&#xccd;&#xca6;&#xcbe;&#xc97;&#xcb2;&#xcc7; &#xc86;&#xcb0;&#xc82;&#xcad;&#xcbf;&#xcb8;&#xcbf;&#xca6;&#xccd;&#xca6;&#xca8;&#xccd;&#xca8;&#xcc1; &#xca8;&#xccd;&#xcaf;&#xcbe;&#xcaf;&#xcbe;&#xcb2;&#xcaf; &#xcb9;&#xcca;&#xcb0;&#xc97;&#xcc6;&#xcb3;&#xcc6;&#xca6;&#xcbf;&#xca4;&#xccd;&#xca4;&#xcc1;. 
</div>
Telegu
<div class="telugu">&#xc06;&#xc17;&#xc4d;&#xc28;&#xc47;&#xc2f; &#xc07;&#xc30;&#xc3e;&#xc28;&#xc4d;&#x200c;&#xc32;&#xc4b; &#xc06;&#xc24;&#xc4d;&#xc2e;&#xc3e;&#xc39;&#xc41;&#xc24;&#xc3f; 
<br />

&#xc30;&#xc46;&#xc35;&#xc32;&#xc4d;&#xc2f;&#xc42;&#xc37;&#xc28;&#xc30;&#xc40; &#xc17;&#xc3e;&#xc30;&#xc4d;&#xc21;&#xc4d;&#x200c;&#xc32;&#xc24;&#xc4b; &#xc2a;&#xc4d;&#xc30;&#xc2e;&#xc41;&#xc16; &#xc15;&#xc2e;&#xc3e;&#xc02;&#xc21;&#xc30;&#xc4d;&#x200c;&#xc32;&#xc24;&#xc4b;&#xc38;&#xc39;&#xc3e; &#xc2e;&#xc4a;&#xc24;&#xc4d;&#xc24;&#xc02; &#xc1a;&#xc46;&#xc02;&#xc26;&#xc3f;&#xc28;&#xc1f;&#xc4d;&#xc32;&#xc41; &#xc32;&#xc4b;&#xc15;&#xc4d;&#x200c;&#xc38;&#xc2d; &#xc38;&#xc4d;&#xc2a;&#xc40;&#xc15;&#xc30;&#xc4d; 
<br />

&#xc26;&#xc3e;&#xc21;&#xc41;&#xc32;&#xc4d;&#xc32;&#xc4b; &#xc2d;&#xc26;&#xc4d;&#xc30;&#xc24;&#xc3e;&#xc26;&#xc33;&#xc3e;&#xc32;&#xc15;&#xc41; &#xc36;&#xc41;&#xc36;&#xc3e;&#xc24;&#xc4d;&#xc30;&#xc40;, &#xc2e;&#xc4a;&#xc39;&#xc2e;&#xc4d;&#xc2e;&#xc26;&#xc4d;&#x200c; &#xc24;&#xc26;&#xc3f;&#xc24;&#xc30;&#xc41;&#xc32;&#xc41;&#xc28;&#xc4d;&#xc28;&#xc3e;&#xc30;&#xc28;&#xc3f;, &#xc24;&#xc40;&#xc35;&#xc4d;&#xc30;&#xc17;&#xc3e;&#xc2f;&#xc3e;&#xc32;&#xc2a;&#xc3e;&#xc32;&#xc48;&#xc28;&#xc3e;&#xc30;&#xc28;&#xc3f; &#xc1a;&#xc46;&#xc2a;&#xc4d;&#xc2a;&#xc3e;&#xc30;&#xc41;.
<br />

&#xc15;&#xc2e;&#xc3e;&#xc02;&#xc21;&#xc30;&#xc4d;&#xc32;&#xc41; &#xc2a;&#xc3e;&#xc15;&#xc3f;&#xc38;&#xc4d;&#xc25;&#xc3e;&#xc28;&#xc4d;&#x200c; &#xc38;&#xc30;&#xc3f;&#xc39;&#xc26;&#xc4d;&#xc26;&#xc41;&#xc32;&#xc4d;&#xc32;&#xc4b;&#xc28;&#xc41;&#xc28;&#xc4d;&#xc28; &#xc2a;&#xc3f;&#xc36;&#xc3f;&#xc28;&#xc4d; &#xc2a;&#xc4d;&#xc30;&#xc3e;&#xc02;&#xc24;&#xc02;&#xc32;&#xc4b; &#xc2a;&#xc3e;&#xc32;&#xc4d;&#xc17;&#xc4a;&#xc28;&#xc47;&#xc02;&#xc26;&#xc41;&#xc15;&#xc41; &#xc26;&#xc47;&#xc30;&#xc3f; &#xc35;&#xc46;&#xc33;&#xc4d;&#xc33;&#xc3e;&#xc30;&#xc28;&#xc3f;
</div>
Oriya
<div class="oriya">&#xb06;&#xb2a;&#xb23;&#xb19;&#xb4d;&#xb15;&#xb41; &#xb38;&#xb4d;&#xb2c;&#xb3e;&#xb17;&#xb24; &#xb0f;&#xb39;&#xb3f; &#xb09;&#xb28;&#xb4d;&#xb2e;&#xb41;&#xb15;&#xb4d;&#xb24; &#xb07;&#xb23;&#xb4d;&#xb1f;&#xb30;&#xb28;&#xb47;&#xb1f; &#xb2c;&#xb3f;&#xb36;&#xb4d;&#xb2c;&#xb30; &#xb09;&#xb2a;&#xb32;&#xb2c;&#xb4d;&#xb27; &#xb2e;&#xb27;&#xb4d;&#xb5f; &#xb2c;&#xb30;&#xb4d;&#xb26;&#xb4d;&#xb27;&#xb3f;&#xb24; &#xb15;&#xb3f;&#xb2e;&#xb4d;&#xb2c;&#xb3e; &#xb0f;&#xb39;&#xb3f; &#xb2a;&#xb43;&#xb37;&#xb4d;&#xb20;&#xb3e;&#xb15;&#xb41; &#xb38;&#xb2e;&#xb4d;&#xb2a;&#xb3e;&#xb26;&#xb28; &#xb38;&#xb2e;&#xb38;&#xb4d;&#xb24; &#xb2e;&#xb41;&#xb15;&#xb4d;&#xb24; &#xb32;&#xb3e;&#xb07;&#xb38;&#xb47;&#xb28;&#xb4d;&#xb38;&#xb30; &#xb38;&#xb30;&#xb4d;&#xb24;&#xb4d;&#xb24; &#xb09;&#xb2a;&#xb32;&#xb2c;&#xb4d;&#xb27; &#xb2e;&#xb41;&#xb15;&#xb4d;&#xb24; &#xb07;&#xb32;&#xb47;&#xb15;&#xb4d;&#xb1f;&#xb4d;&#xb30;&#xb4b;&#xb28;&#xb3f;&#xb15;&#xb4d;&#xb38; &#xb2a;&#xb4d;&#xb30;&#xb3f;&#xb23;&#xb4d;&#xb1f;&#xb4d; &#xb2a;&#xb30;&#xb4d;&#xb2f;&#xb4d;&#xb5f;&#xb28;&#xb4d;&#xb24; &#xb2a;&#xb4d;&#xb30;&#xb38;&#xb19;&#xb4d;&#xb17; 
</div>
Punjabi
<div class="punjabi">&#xa17;&#xa4d;&#xa30;&#xa39;&#xa3f;&#xa2e;&#xa70;&#xa24;&#xa30;&#xa40; &#xa09;&#xa28;&#xa4d;&#xa39;&#xa3e; &#xa26;&#xa4d;&#xa30;&#xa2e;&#xa41;&#xa15; &#xa36;&#xa4d;&#xa30;&#xa40;&#xa32;&#xa70;&#xa15;&#xa3e; 
</div>
Malayalam
<div class="malayalam">&#xd38;&#xd3f;&#xd2a;&#xd3f;&#x200c;&#xd0e;&#xd02; 
</div>
Bengali (bn)
<div class="bengali">&#x9b8;&#x9cd;&#x9ac;&#x9c7;&#x99a;&#x9cd;&#x99b;&#x9be;&#x9b8;&#x9c7;&#x9ac;&#x9c0; &#x993; &#x9a4;&#x9cd;&#x9b0;&#x9be;&#x9a3; &#x9a4;&#x9c0;&#x9ac;&#x9cd;&#x9b0;&#x9a4;&#x9be; &#x9b8;&#x9cd;&#x9b0;&#x9cb;&#x9a4; &#x9b8;&#x982;&#x996;&#x9cd;&#x9af;&#x9be; &#x9b8;&#x9cd;&#x9ac;&#x9ad;&#x9be;&#x9ac;&#x9a4;&#x987; &#x986;&#x995;&#x9cd;&#x9b0;&#x9ae;&#x9a3; &#x9aa;&#x9cd;&#x9b0;&#x9ac;&#x9c7;&#x9b6; &#x9a8;&#x9bf;&#x9df;&#x9a8;&#x9cd;&#x9a4;&#x9cd;&#x9b0;&#x9a3; &#x9b8;&#x9be;&#x9b9;&#x9be;&#x9af;&#x9cd;&#x9af;&#x9c7;
</div>
Assamese
<div class="bengali">&#x989;&#x9a6;&#x9cd;&#x9a6;&#x9c7;&#x9b6;&#x9cd;&#x9af; &#x9ac;&#x9bf;&#x9b6;&#x9cd;&#x9ac;&#x995;&#x9cb;&#x9b7; &#x9aa;&#x9cd;&#x9f0;&#x9a3;&#x9af;&#x9bc;&#x9a8; &#x9b8;&#x9be;&#x9b9;&#x9bf;&#x9a4;&#x9cd;&#x9af;&#x9bf;&#x995;&#x9b8;&#x995;&#x9b2;
&#x997;&#x9cd;&#x9f0;&#x9be;&#x9b9;&#x9cd;&#x9af; &#x997;&#x9cd;&#x9f0;&#x9b9;&#x9a8;
</div>
Misc
<div class="bengali">&#x985;&#x9cd;&#x9af; &#x995; &#x995;&#x9bc; &#x995;&#x9bf; &#x995;&#x9cd; &#x995;&#x9cd;&#x995; &#x995;&#x9cd;&#x9b0; &#x995;&#x9cd;&#x9b0;&#x9cd;&#x995; &#x995;&#x9cd;&#x200c;&#x995; &#x995;&#x9cd;&#x200d;&#x995; &#x9a6;&#x9cd;&#x9af; &#x9a8;&#x9cd;&#x995; &#x9a8;&#x9cd;&#x9a7; &#x9a8;&#x9cd;&#x9ac; &#x9a8;&#x9cd;&#x9af; &#x9a8;&#x9cd;&#x9b0; &#x9a8;&#x9cd;&#x200c;&#x995; &#x9a8;&#x9cd;&#x200c;&#x9a7; &#x9a8;&#x9cd;&#x200c;&#x9ac; &#x9a8;&#x9cd;&#x200c;&#x9b0; &#x9a8;&#x9cd;&#x200d;&#x995; &#x9a8;&#x9cd;&#x200d;&#x9a7; &#x9a8;&#x9cd;&#x200d;&#x9ac; &#x9a8;&#x9cd;&#x200d;&#x9b0; &#x9af;&#x9cd; &#x9b0;&#x9cd;&#x995; &#x9b0;&#x9cd;&#x995;&#x9bf; &#x9b0;&#x9cd;&#x995;&#x9cc; &#x9b0;&#x9cd;&#x9a8;&#x9cd;&#x200d; &#x9b0;&#x9cd;&#x9ac;&#x9cd;&#x9ac; &#x9b6;&#x9cd;&#x9af; &#x9b7;&#x9cd;&#x9af; &#x9b8;&#x9cd;&#x9af; &#x9bf; &#x995;&#x9c7;&#x9be; &#x995;&#x9c7;&#x9d7; &#x995;&#x9cd;&#x9b0;&#x9cd;&#x995; &#x9a8;&#x9cd;&#x200c;&#x995; &#x9a8;&#x9cd;&#x200c;&#x9ac; &#x9a8;&#x9cd;&#x200d;&#x995; &#x9a8;&#x9cd;&#x200d;&#x9ac; &#x9a8;&#x9cd;&#x200d;&#x9b0; &#x9b0;&#x9cd;&#x995;&#x9be;&#x982; &#x9b0;&#x9cd;&#x995;&#x9be;&#x983; &#x9b0;&#x9cd;&#x995;&#x9cc; &#x9b0;&#x9cd;&#x9ad; &#x9f0;&#x9cd;&#x9ad; &#x9f1;&#x9cd;&#x9ad; &#x985;&#x9d7; &#x9a8;&#x9cd;&#x9a4;&#x9cd;&#x9b0; &#x9a4;&#x9cd;&#x9af;&#x9c1; &#x99a;&#x9cd;&#x9af;&#x9cd;&#x9b0;
</div>
Reph
<div class="bengali">&#x9b0;&#x9cd;&#x995; &#x9b0;&#x9cd;&#x995;&#x9be; &#x9b0;&#x9cd;&#x995;&#x9bf; &#x9b0;&#x9cd;&#x995;&#x9c0; &#x9b0;&#x9cd;&#x995;&#x9c1; &#x9b0;&#x9cd;&#x995;&#x9c2; &#x9b0;&#x9cd;&#x995;&#x9c7; &#x9b0;&#x9cd;&#x995;&#x9c8; &#x9b0;&#x9cd;&#x995;&#x9cb; &#x9b0;&#x9cd;&#x995;&#x9cc; &#x9b0;&#x9cd;&#x9af; &#x9b0;&#x9cd;&#x200d;&#x9af; &#x9b0;&#x200d;&#x9cd;&#x9af; &#x9b0;&#x9cd;&#x9b0;&#x200d;&#x9cd;&#x9af;
</div>
CP - Dependent
<div class="bengali">&#x9be; &#x9bf; &#x9c0; &#x9c1; &#x9c2; &#x9c3; &#x9c7; &#x9c8; &#x9cb; &#x9cc;
</div>
GSUB
<div class="bengali">&#x995;&#x9cd;&#x9b0; &#x996;&#x9cd;&#x9b0; &#x997;&#x9cd;&#x9b0; &#x998;&#x9cd;&#x9b0; &#x99c;&#x9cd;&#x9b0; &#x9a4;&#x9cd;&#x9b0; &#x9a6;&#x9cd;&#x9b0; &#x9a7;&#x9cd;&#x9b0; &#x9aa;&#x9cd;&#x9b0; &#x9ae;&#x9cd;&#x9b0; &#x9b6;&#x9cd;&#x9b0; &#x9b8;&#x9cd;&#x9b0; &#x9b9;&#x9cd;&#x9b0; &#x99b;&#x9cd;&#x9b0; &#x99f;&#x9cd;&#x9b0; &#x9a0;&#x9cd;&#x9b0; &#x9a1;&#x9cd;&#x9b0; &#x9a5;&#x9cd;&#x9b0; &#x9ab;&#x9cd;&#x9b0; &#x9ac;&#x9cd;&#x9b0; &#x9ad;&#x9cd;&#x9b0; &#x995;&#x9cd;&#x9af; &#x996;&#x9cd;&#x9af; &#x997;&#x9cd;&#x9af; &#x998;&#x9cd;&#x9af; &#x99a;&#x9cd;&#x9af; &#x99c;&#x9cd;&#x9af; &#x99f;&#x9cd;&#x9af; &#x9a0;&#x9cd;&#x9af; &#x9a1;&#x9cd;&#x9af; &#x9dc;&#x9cd;&#x9af; &#x9a2;&#x9cd;&#x9af; &#x9a4;&#x9cd;&#x9af; &#x9a5;&#x9cd;&#x9af; &#x9a6;&#x9cd;&#x9af; &#x9a7;&#x9cd;&#x9af; &#x9a8;&#x9cd;&#x9af; &#x9aa;&#x9cd;&#x9af; &#x9ab;&#x9cd;&#x9af; &#x9ac;&#x9cd;&#x9af; &#x9ad;&#x9cd;&#x9af; &#x9ae;&#x9cd;&#x9af; &#x9af;&#x9cd;&#x9af; &#x9b0;&#x200d;&#x9cd;&#x9af; &#x9b2;&#x9cd;&#x9af; &#x9b6;&#x9cd;&#x9af; &#x9b7;&#x9cd;&#x9af; &#x9b8;&#x9cd;&#x9af; &#x9b9;&#x9cd;&#x9af;
&#x995;&#x9cd;&#x9b2; &#x997;&#x9cd;&#x9b2; &#x9aa;&#x9cd;&#x9b2; &#x9ae;&#x9cd;&#x9b2; &#x9b2;&#x9cd;&#x9b2; &#x9b6;&#x9cd;&#x9b2; &#x9b8;&#x9cd;&#x9b2; &#x9b9;&#x9cd;&#x9b2; &#x995;&#x9cd;&#x995;
<br />

&#x995;&#x9cd;&#x9ac; &#x99c;&#x9cd;&#x9ac; &#x99f;&#x9cd;&#x9ac; &#x9a4;&#x9cd;&#x9ac; &#x9a6;&#x9cd;&#x9ac; &#x9a7;&#x9cd;&#x9ac; &#x9a8;&#x9cd;&#x9ac; &#x9ac;&#x9cd;&#x9ac; &#x9ae;&#x9cd;&#x9ac; &#x9b2;&#x9cd;&#x9ac; &#x9b6;&#x9cd;&#x9ac; &#x9b7;&#x9cd;&#x9ac; &#x9b8;&#x9cd;&#x9ac; &#x9b9;&#x9cd;&#x9ac;
<br />

&#x9a3;&#x9cd;&#x9a3; &#x9b7;&#x9cd;&#x9a3; &#x9b7;&#x9cd;&#x9a3;&#x9c1; &#x9b9;&#x9cd;&#x9a3; &#x9b9;&#x9cd;&#x9a3;&#x9bf; &#x99c;&#x9cd;&#x99c; &#x99f;&#x9cd;&#x99f; &#x9a4;&#x9cd;&#x9a4; &#x9a6;&#x9cd;&#x9a6; &#x9a8;&#x9cd;&#x9a8; &#x9aa;&#x9cd;&#x9aa; &#x9a4;&#x9cd;&#x9a8; &#x9ae;&#x9cd;&#x9a8; &#x9b8;&#x9cd;&#x9a8; &#x9b9;&#x9cd;&#x9a8; &#x995;&#x9cd;&#x9a8; &#x997;&#x9cd;&#x9a8; &#x997;&#x9cd;&#x9ae; &#x999;&#x9cd;&#x9ae; &#x99f;&#x9cd;&#x9ae; &#x9a3;&#x9cd;&#x9ae; &#x9a4;&#x9cd;&#x9ae; &#x9a6;&#x9cd;&#x9ae; &#x9a7;&#x9cd;&#x9ae; &#x9a8;&#x9cd;&#x9ae; &#x9ae;&#x9cd;&#x9ae; &#x9b2;&#x9cd;&#x9ae; &#x9b6;&#x9cd;&#x9ae; &#x9b7;&#x9cd;&#x9ae; &#x9b9;&#x9cd;&#x9ae; &#x995;&#x9cd;&#x9b7; &#x995;&#x9cd;&#x9a4; &#x997;&#x9cd;&#x9a7; &#x999;&#x9cd;&#x995; &#x999;&#x9cd;&#x996; &#x999;&#x9cd;&#x997; &#x999;&#x9cd;&#x998; &#x99a;&#x9cd;&#x99a; &#x99a;&#x9cd;&#x99b; &#x99a;&#x9cd;&#x99e; &#x99c;&#x9cd;&#x99d; &#x99c;&#x9cd;&#x99e; &#x99e;&#x9cd;&#x99a; &#x99e;&#x9cd;&#x99b; &#x99e;&#x9cd;&#x99c; &#x9a3;&#x9cd;&#x99f; &#x995;&#x9cd;&#x99f; &#x9a3;&#x9cd;&#x9a1; &#x9a8;&#x9cd;&#x9a1; &#x9a6;&#x9cd;&#x997; &#x9a6;&#x9cd;&#x998; &#x9a6;&#x9cd;&#x9a7; &#x9a6;&#x9cd;&#x9ad; &#x9a8;&#x9cd;&#x9a4; &#x9a8;&#x9cd;&#x9a5; &#x9a8;&#x9cd;&#x9a6; &#x9a8;&#x9cd;&#x9a7; &#x9aa;&#x9cd;&#x9a4; &#x9ac;&#x9cd;&#x99c; &#x9ac;&#x9cd;&#x9a6; &#x9ae;&#x9cd;&#x9aa; &#x9ae;&#x9cd;&#x9ab; &#x9ae;&#x9cd;&#x9ad; &#x9b2;&#x9cd;&#x995; &#x9b2;&#x9cd;&#x997; &#x9b2;&#x9cd;&#x9aa; &#x9b2;&#x9cd;&#x9ab; &#x9b6;&#x9cd;&#x99a; &#x9b7;&#x9cd;&#x995; &#x9b7;&#x9cd;&#x99f; &#x9b7;&#x9cd;&#x9a0; &#x9b7;&#x9cd;&#x9aa; &#x9b7;&#x9cd;&#x9ab; &#x9b8;&#x9cd;&#x995; &#x9b8;&#x9cd;&#x996; &#x9b8;&#x9cd;&#x9a4; &#x9b8;&#x9cd;&#x9a5; &#x9b8;&#x9cd;&#x9aa; &#x9b8;&#x9cd;&#x9ab; &#x9ae;&#x9cd;&#x9a5; &#x9b2;&#x9cd;&#x9a4; &#x9b2;&#x9cd;&#x9a7; &#x995;&#x9cd;&#x9ae; &#x995;&#x9cd;&#x9b8; &#x997;&#x9cd;&#x997; &#x998;&#x9cd;&#x9a8; &#x99a;&#x9cd;&#x9a8;
<br />

&#x99b;&#x9cd;&#x9ac;
<br />

&#x99e;&#x9cd;&#x99d; &#x9a1;&#x9cd;&#x9a1; &#x9a1;&#x9cd;&#x9ae; &#x9dc;&#x9cd;&#x997; &#x9a3;&#x9cd;&#x9a0; &#x9a3;&#x9cd;&#x9a2; &#x9a3;&#x9cd;&#x9ac; &#x9a4;&#x9cd;&#x9a5; &#x9a5;&#x9cd;&#x9ac; &#x9a7;&#x9cd;&#x9a8; &#x9a8;&#x9cd;&#x99f; &#x9a8;&#x9cd;&#x9a0; &#x9a8;&#x9cd;&#x9b8; &#x9aa;&#x9cd;&#x99f; &#x9aa;&#x9cd;&#x9a8; &#x9ab;&#x9cd;&#x9b2; &#x9ac;&#x9cd;&#x9a7; &#x9ac;&#x9cd;&#x9b2; &#x9ad;&#x9cd;&#x9b2; &#x9ae;&#x9cd;&#x9a4; &#x9ae;&#x9cd;&#x9a6; &#x9b2;&#x9cd;&#x99f; &#x9b2;&#x9cd;&#x9a1; &#x9b6;&#x9cd;&#x99b; &#x9b6;&#x9cd;&#x9a8; &#x9b6;&#x9cd;&#x9a4; &#x9b8;&#x9cd;&#x99f; &#x9b8;&#x9cd;&#x9ae;
<br />

&#x99a;&#x9cd;&#x99b;&#x9cd;&#x9b0; &#x99a;&#x9cd;&#x99b;&#x9cd;&#x9ac; &#x9a6;&#x9cd;&#x9a6;&#x9cd;&#x9ac; &#x9a6;&#x9cd;&#x9a7;&#x9cd;&#x9ac; &#x9a8;&#x9cd;&#x9a7;&#x9cd;&#x9b0; &#x9ac;&#x9cd;&#x9a6;&#x9cd;&#x9b0;
<br />

&#x995;&#x9cd;&#x9b7;&#x9cd;&#x9a3; &#x995;&#x9cd;&#x9b7;&#x9cd;&#x9ae;
<br />

&#x99c;&#x9cd;&#x99c;&#x9cd;&#x9ac; &#x9a4;&#x9cd;&#x9a4;&#x9cd;&#x9ac; &#x9a4;&#x9cd;&#x9ae;&#x9cd;&#x9af; &#x9a8;&#x9cd;&#x9a4;&#x9cd;&#x9b0; &#x9a8;&#x9cd;&#x9a4;&#x9cd;&#x9ac; &#x9a8;&#x9cd;&#x9a6;&#x9cd;&#x9b0; &#x9a8;&#x9cd;&#x9a7;&#x9cd;&#x9af; &#x9a8;&#x9cd;&#x9a8;&#x9cd;&#x9af; &#x9ae;&#x9cd;&#x9aa;&#x9cd;&#x9b0; &#x9ae;&#x9cd;&#x9ad;&#x9cd;&#x9b0; &#x9b0;&#x9cd;&#x9a7;&#x9cd;&#x9ac; &#x9b0;&#x9cd;&#x9b6;&#x9cd;&#x9ac; &#x9b7;&#x9cd;&#x99f;&#x9cd;&#x9b0; &#x9b7;&#x9cd;&#x9aa;&#x9cd;&#x9b0; &#x9b8;&#x9cd;&#x9a4;&#x9cd;&#x9b0; &#x9b8;&#x9cd;&#x99f;&#x9cd;&#x9b0; &#x9b8;&#x9cd;&#x995;&#x9cd;&#x9b0; &#x995;&#x9cd;&#x99f;&#x9cd;&#x9b0;
&#x9aa;&#x9cd;&#x9b8; 
</div>

';
//==============================================================
//==============================================================
//==============================================================
// ************ FIXES ************
// ************ FIXES ************
$htmlx ='
<style>
body, p { font-size: 15pt;}
h3 { font-size: 15pt; margin-bottom:0; }

.tamil {
	 font-family:Latha;
  font-size: 26pt;
}
.oriya {
	font-family:Kalinga;
  font-size: 26pt;
}
.punjabi {
	font-family:Raavi;
  font-size: 26pt;
}
.gujarati {
	font-family:Shruti;
  font-size: 26pt;
}
.hindi {
	font-family:Mangal;
  font-size: 26pt;
}
.nepali {
	font-family:Mangal;
  font-size: 26pt;
}
.assamese { /* same as bengali */
	font-family:solaimanlipi;
	font-family:vrinda;
  font-size: 26pt;
}
.bengali {
	font-family:solaimanlipi;
	font-family:vrinda;
  font-size: 26pt;
}
.telugu {
	font-family:gautami;
  font-size: 26pt;
}
.kannada {
	font-family:Tunga;
  font-size: 26pt;
}
.malayalam {
	font-family:Kartika;
  font-size: 26pt;
}

</style>

FIX OTL (ZZZ93) Kannada: Old spec Lohit-Kannada - 
<div style="font-family:\'Lohit Kannada\'; font-size: 22pt;">
&#xca8;&#xccd;&#xca8;&#xcc1;   &#xca6;&#xccd;&#xca6;&#xcc1;  &#xca8;&#xccd;&#xca8;&#xcc1;   &#xca4;&#xccd;&#xca4;&#xcc1;  &#xca6;&#xccd;&#xca6;&#xcc1;   &#xca6;&#xccd;&#xca6;&#xcc1;  &#xca8;&#xccd;&#xca8;&#xcc1;  &#xcaa;&#xccd;&#xcaa;    &#xc95;&#xccd;&#xcb0;&#xccc;   &#xc95;&#xccd;&#xcb0; 
</div>


INDIC_FIX_1:
<div class="bengali">&#x9b0;&#x9cd;&#x9a8;&#x9cd;&#x200d; </div>

INDIC_FIX_2:
<div class="telugu">
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc3e; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc3f; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc40; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc41; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc42; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc46; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc47; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc48; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc4a; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc4b; 
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc4c; 
</div>

INDIC_FIX_3:
<!-- handled differently in 2 fonts -->
<div style="font-family:vrinda;font-size: 26pt;">&#x995;&#x9cd;&#x9b0;&#x9cd;&#x995;</div>
<div style="font-family:freeserif;font-size: 26pt;">&#x995;&#x9cd;&#x9b0;&#x9cd;&#x995;</div>

INDIC_FIX_4:
<div class="telugu">&#xc30;&#xc4d;&#xc21;&#xc4d;&#x200c;</div>

OTL_FIX_1 & OTL_FIX_2:
<div><span class="oriya">&#xb28;&#xb4d;&#xb2e;&#xb41;</span> <span style="font-family:Arial; font-size: 36pt;">A&#x308;&#x0315;&#x0303;</span> </div>

OTL_FIX_3:
<div class="oriya">&#xb15;&#xb4d;&#xb1f;&#xb4d;&#xb30;</div>

ZKI8 Fix - (NB With this, Indic fix 2 no longer required.)
<div style="font-family:\'Pothana2000\'; font-size: 22pt;">
&#xc28;&#xc4d;&#x200c;&#xc32;&#xc4b;
</div>

ZZZ96 Fix - GPOS fix in OTL Type 2 (kerning) for XPlacement of first character of pair
<div style="font-family: tharlon; font-size: 26pt;">
&#x1000;&#x103c;&#x102d;&#x102f; 
</div>
Part 2) Kern on by default as needed for repositioning
<div style="font-family: \'tharlon\'; font-size: 26pt;">
&#x101e;&#x1014;&#x1039;&#x1010;&#x102c;
</div>

LOHIT fonts at :
https://fedorahosted.org/lohit/wiki
Version 2 are under development: http://pravin-s.blogspot.in/2013/08/project-creating-standard-and-reusable.html

Solaiman-Lipi (Bengali): http://www.omicronlab.com/bangla-fonts.html


Khmer Fix 1
<div style="font-family:\'daunpenh\'; font-size: 36pt; ">
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782;
&#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1793;&#x17d2;&#x178f;&#x17d2;&#x179a;&#x17b8;
&#x1784;&#x17d2;&#x179a;&#x17d2;&#x1782;&#x17c4;&#x17c7;
&#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7;
</div>

';
// ************ FIXES ************
// INDIC_FIX_1 Indic Initial re-ordering; stops search for base when Halant before ZWJ - this sets the BASE at position of ZWJ
//define('OMIT_INDIC_FIX_1', 1);

// INDIC_FIX_2 Indic Initial re-ordering; Indic If C(base) H ZWNJ C2 Matra - and Matra position is POS_BEFORE_SUB, 
// this changes it to correct position after the 2nd C (by changing it to POS_AFTER_SUB)
// i.e. when ZWNJ prevents C2 from becoming a joined/form
//define('OMIT_INDIC_FIX_2', 1);

// INDIC_FIX_3 Indic Initial re-ordering;  If C(pre-base) H Ra H C(base) - this allows blwf to be applied to pre-base H-Ra
// whereas blwf is normally only applied post-base
// If blwf not substituted, marks for Ra + H to apply 'rphf'
//define('OMIT_INDIC_FIX_3', 1);

// INDIC_FIX_4 Indic Initial re-ordering;  ZWNJ should block H C from forming blwf post-base (e.g. Ra[base] H C H ZWNJ)
// need to unmask backwards beyond first consonant arrived at */
//define('OMIT_INDIC_FIX_4', 1);

// OTL_FIX_1 GPOS Mark to Mark Attachment - prevent rule being skipped if the "base" mark is not attached to a ligature
// because it would be skipped as it deems that the marks are attached to different ligs or components of ligs.
//define('OMIT_OTL_FIX_1', 1);

// OTL_FIX_2 GPOS Mark to Mark Attachment - sets a BaseWidth for the Mark to attach to
//define('OMIT_OTL_FIX_2', 1);

// OTL_FIX_3 GSUB Type 6 substitution, returned value for shift which meant next character was skipped for lookup (x3 lines)
//define('OMIT_OTL_FIX_3', 1);

// ZKI6 fixed moving characters using _move_info_pos()

// ZKI7 fixed old_spec to mark Ra-Halant for pre-base ordering (instead of Halant-Ra)

// ZKI8 Fix Indic Initial re-ordering: ZWNJ will stop search for base
// INDIC_FIX_2 no longer required when this is used.


//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
$htmlx = '
<style>
body, p { font-size: 15pt;}
.khmer {
	font-family:daunpenh;
	font-size: 36pt;
}
</style>


KHMER
<div class="khmer">
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782;
&#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7;
</div>

<div class="khmer">
&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17d2;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1794;&#x17b6;&#x1793;&#x200b;&#x179f;&#x17d2;&#x179b;&#x17b6;&#x1794;&#x17cb;&#x200b;&#x178a;&#x17c4;&#x1799;&#x200b;&#x1782;&#x17d2;&#x179a;&#x17b6;&#x1794;&#x17cb;&#x1780;&#x17b6;&#x17c6;&#x1797;&#x17d2;&#x179b;&#x17be;&#x1784;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17b7;&#x1784;&#x200b;&#x1794;&#x17b8;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1795;&#x17d2;&#x179f;&#x17c1;&#x1784;&#x1791;&#x17c0;&#x178f;&#x200b;&#x179a;&#x1784;&#x179a;&#x1794;&#x17bd;&#x179f; &#x1793;&#x17c5;&#x1780;&#x17d2;&#x1793;&#x17bb;&#x1784;&#x200b;&#x1780;&#x17b6;&#x179a;&#x1794;&#x17d2;&#x179a;&#x1788;&#x1798;&#x200b;&#x1798;&#x17bb;&#x1781;&#x200b;&#x178a;&#x17b6;&#x1780;&#x17cb;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x200b;&#x178a;&#x17b6;&#x1785;&#x17cb;&#x178a;&#x17c4;&#x1799;&#x17a1;&#x17c2;&#x1780;&#x200b;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x1798;&#x17bd;&#x1799; &#x179a;&#x179c;&#x17b6;&#x1784;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798;&#x200b;&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798; &#x1787;&#x17b6;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17c5;&#x200b;&#x1798;&#x17d2;&#x178f;&#x17bb;&#x17c6;&#x200b;&#x179f;&#x17d2;&#x1796;&#x17b6;&#x1793;&#x200b;&#x1780;&#x17d2;&#x1794;&#x17b6;&#x179b;&#x1790;&#x17d2;&#x1793;&#x179b;&#x17cb;&#x17d4; &#x1793;&#x17c1;&#x17c7;&#x200b;&#x1794;&#x17be;&#x178f;&#x17b6;&#x1798;&#x200b;&#x1796;&#x17d0;&#x178f;&#x17cc;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1796;&#x17b8;&#x200b;&#x179b;&#x17c4;&#x1780;&#x200b; &#x1785;&#x17b6;&#x1793;&#x17cb; &#x179f;&#x17b6;&#x179c;&#x17c9;&#x17c1;&#x178f; &#x1798;&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;&#x200b;&#x179f;&#x17ca;&#x17be;&#x1794;&#x17a2;&#x1784;&#x17d2;&#x1780;&#x17c1;&#x178f;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x17a2;&#x1784;&#x17d2;&#x1782;&#x1780;&#x17b6;&#x179a;&#x200b;&#x179f;&#x17b7;&#x1791;&#x17d2;&#x1792;&#x17b7;&#x1798;&#x1793;&#x17bb;&#x179f;&#x17d2;&#x179f;&#x200b;&#x17a2;&#x17b6;&#x178a;&#x17a0;&#x17bb;&#x1780; &#x178a;&#x17c2;&#x179b;&#x200b;&#x179c;&#x178f;&#x17d2;&#x178f;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1793;&#x17c5;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x17be;&#x178f;&#x17a0;&#x17c1;&#x178f;&#x17bb; &#x1793;&#x17c5;&#x200b;&#x1799;&#x1794;&#x17cb;&#x200b;&#x1790;&#x17d2;&#x1784;&#x17c3;&#x200b;&#x17a2;&#x17b6;&#x1791;&#x17b7;&#x178f;&#x17d2;&#x1799;&#x200b;&#x1791;&#x17b8; &#x17e1;&#x17e5; &#x1780;&#x1789;&#x17d2;&#x1789;&#x17b6;&#x1793;&#x17c1;&#x17c7;&#x17d4;
</div>
Khmer OS
<div style="font-family:\'Khmer OS\'; font-size: 16pt; ">
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782;
&#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7;
</div>
<div style="font-family:\'Khmer OS\'; font-size: 16pt; ">
&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17d2;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1794;&#x17b6;&#x1793;&#x200b;&#x179f;&#x17d2;&#x179b;&#x17b6;&#x1794;&#x17cb;&#x200b;&#x178a;&#x17c4;&#x1799;&#x200b;&#x1782;&#x17d2;&#x179a;&#x17b6;&#x1794;&#x17cb;&#x1780;&#x17b6;&#x17c6;&#x1797;&#x17d2;&#x179b;&#x17be;&#x1784;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17b7;&#x1784;&#x200b;&#x1794;&#x17b8;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1795;&#x17d2;&#x179f;&#x17c1;&#x1784;&#x1791;&#x17c0;&#x178f;&#x200b;&#x179a;&#x1784;&#x179a;&#x1794;&#x17bd;&#x179f; &#x1793;&#x17c5;&#x1780;&#x17d2;&#x1793;&#x17bb;&#x1784;&#x200b;&#x1780;&#x17b6;&#x179a;&#x1794;&#x17d2;&#x179a;&#x1788;&#x1798;&#x200b;&#x1798;&#x17bb;&#x1781;&#x200b;&#x178a;&#x17b6;&#x1780;&#x17cb;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x200b;&#x178a;&#x17b6;&#x1785;&#x17cb;&#x178a;&#x17c4;&#x1799;&#x17a1;&#x17c2;&#x1780;&#x200b;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x1798;&#x17bd;&#x1799; &#x179a;&#x179c;&#x17b6;&#x1784;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798;&#x200b;&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798; &#x1787;&#x17b6;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17c5;&#x200b;&#x1798;&#x17d2;&#x178f;&#x17bb;&#x17c6;&#x200b;&#x179f;&#x17d2;&#x1796;&#x17b6;&#x1793;&#x200b;&#x1780;&#x17d2;&#x1794;&#x17b6;&#x179b;&#x1790;&#x17d2;&#x1793;&#x179b;&#x17cb;&#x17d4; &#x1793;&#x17c1;&#x17c7;&#x200b;&#x1794;&#x17be;&#x178f;&#x17b6;&#x1798;&#x200b;&#x1796;&#x17d0;&#x178f;&#x17cc;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1796;&#x17b8;&#x200b;&#x179b;&#x17c4;&#x1780;&#x200b; &#x1785;&#x17b6;&#x1793;&#x17cb; &#x179f;&#x17b6;&#x179c;&#x17c9;&#x17c1;&#x178f; &#x1798;&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;&#x200b;&#x179f;&#x17ca;&#x17be;&#x1794;&#x17a2;&#x1784;&#x17d2;&#x1780;&#x17c1;&#x178f;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x17a2;&#x1784;&#x17d2;&#x1782;&#x1780;&#x17b6;&#x179a;&#x200b;&#x179f;&#x17b7;&#x1791;&#x17d2;&#x1792;&#x17b7;&#x1798;&#x1793;&#x17bb;&#x179f;&#x17d2;&#x179f;&#x200b;&#x17a2;&#x17b6;&#x178a;&#x17a0;&#x17bb;&#x1780; &#x178a;&#x17c2;&#x179b;&#x200b;&#x179c;&#x178f;&#x17d2;&#x178f;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1793;&#x17c5;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x17be;&#x178f;&#x17a0;&#x17c1;&#x178f;&#x17bb; &#x1793;&#x17c5;&#x200b;&#x1799;&#x1794;&#x17cb;&#x200b;&#x1790;&#x17d2;&#x1784;&#x17c3;&#x200b;&#x17a2;&#x17b6;&#x1791;&#x17b7;&#x178f;&#x17d2;&#x1799;&#x200b;&#x1791;&#x17b8; &#x17e1;&#x17e5; &#x1780;&#x1789;&#x17d2;&#x1789;&#x17b6;&#x1793;&#x17c1;&#x17c7;&#x17d4;
</div>

Khmer
<div style="font-family:\'Khmer\'; font-size: 16pt; ">
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782;
&#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7;
</div>
<div style="font-family:\'Khmer\'; font-size: 16pt; ">
&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17d2;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1794;&#x17b6;&#x1793;&#x200b;&#x179f;&#x17d2;&#x179b;&#x17b6;&#x1794;&#x17cb;&#x200b;&#x178a;&#x17c4;&#x1799;&#x200b;&#x1782;&#x17d2;&#x179a;&#x17b6;&#x1794;&#x17cb;&#x1780;&#x17b6;&#x17c6;&#x1797;&#x17d2;&#x179b;&#x17be;&#x1784;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17b7;&#x1784;&#x200b;&#x1794;&#x17b8;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1795;&#x17d2;&#x179f;&#x17c1;&#x1784;&#x1791;&#x17c0;&#x178f;&#x200b;&#x179a;&#x1784;&#x179a;&#x1794;&#x17bd;&#x179f; &#x1793;&#x17c5;&#x1780;&#x17d2;&#x1793;&#x17bb;&#x1784;&#x200b;&#x1780;&#x17b6;&#x179a;&#x1794;&#x17d2;&#x179a;&#x1788;&#x1798;&#x200b;&#x1798;&#x17bb;&#x1781;&#x200b;&#x178a;&#x17b6;&#x1780;&#x17cb;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x200b;&#x178a;&#x17b6;&#x1785;&#x17cb;&#x178a;&#x17c4;&#x1799;&#x17a1;&#x17c2;&#x1780;&#x200b;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x1798;&#x17bd;&#x1799; &#x179a;&#x179c;&#x17b6;&#x1784;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798;&#x200b;&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798; &#x1787;&#x17b6;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17c5;&#x200b;&#x1798;&#x17d2;&#x178f;&#x17bb;&#x17c6;&#x200b;&#x179f;&#x17d2;&#x1796;&#x17b6;&#x1793;&#x200b;&#x1780;&#x17d2;&#x1794;&#x17b6;&#x179b;&#x1790;&#x17d2;&#x1793;&#x179b;&#x17cb;&#x17d4; &#x1793;&#x17c1;&#x17c7;&#x200b;&#x1794;&#x17be;&#x178f;&#x17b6;&#x1798;&#x200b;&#x1796;&#x17d0;&#x178f;&#x17cc;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1796;&#x17b8;&#x200b;&#x179b;&#x17c4;&#x1780;&#x200b; &#x1785;&#x17b6;&#x1793;&#x17cb; &#x179f;&#x17b6;&#x179c;&#x17c9;&#x17c1;&#x178f; &#x1798;&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;&#x200b;&#x179f;&#x17ca;&#x17be;&#x1794;&#x17a2;&#x1784;&#x17d2;&#x1780;&#x17c1;&#x178f;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x17a2;&#x1784;&#x17d2;&#x1782;&#x1780;&#x17b6;&#x179a;&#x200b;&#x179f;&#x17b7;&#x1791;&#x17d2;&#x1792;&#x17b7;&#x1798;&#x1793;&#x17bb;&#x179f;&#x17d2;&#x179f;&#x200b;&#x17a2;&#x17b6;&#x178a;&#x17a0;&#x17bb;&#x1780; &#x178a;&#x17c2;&#x179b;&#x200b;&#x179c;&#x178f;&#x17d2;&#x178f;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1793;&#x17c5;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x17be;&#x178f;&#x17a0;&#x17c1;&#x178f;&#x17bb; &#x1793;&#x17c5;&#x200b;&#x1799;&#x1794;&#x17cb;&#x200b;&#x1790;&#x17d2;&#x1784;&#x17c3;&#x200b;&#x17a2;&#x17b6;&#x1791;&#x17b7;&#x178f;&#x17d2;&#x1799;&#x200b;&#x1791;&#x17b8; &#x17e1;&#x17e5; &#x1780;&#x1789;&#x17d2;&#x1789;&#x17b6;&#x1793;&#x17c1;&#x17c7;&#x17d4;
</div>



';

//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
$htmlx = '
Thai shaper
<div style="font-family:\'Cordia New\'; font-size: 28pt;">
&#x0E14;&#x0E4B;&#x0E33;
&#x0E14;&#x0E4D;&#x0E4B;&#x0E32;
</div>
Lao shaper
<div style="font-family:\'DokChampa\'; font-size: 28pt;">
&#x0E94;&#x0ECB;&#x0EB3;
&#x0E94;&#x0ECD;&#x0ECB;&#x0EB2;
</div>

';

//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
/*
$html = '
<style>
[lang=syr] {
	color: purple;
}
div[lang="syr"] {
	color: teal;
}
:lang(syr) {
	color: blue;
}
div:lang(syr) {
	color: red;
}
span :lang("syr") {
	color: green;
}
</style>
SYRIAC - Estrangelo Edessa
<div lang="syr" class="syriac" style="font-size: 26pt; direction: rtl;">
&#x718;&#x72c;&#x718;&#x712; &#x710;&#x72c;&#x71f;&#x722;&#x71d;&#x72c; &#x717;&#x715;&#x710; &#x715;&#x71d;&#x72a;&#x710; &#x729;&#x715;&#x71d;&#x72b;&#x72c;&#x710; &#x712;&#x72b;&#x721; &#x729;&#x715;&#x71d;&#x72b;&#x710; &#x725;&#x722;&#x718;&#x71d;&#x710; &#x721;&#x72a;&#x71d; &#x710;&#x718;&#x713;&#x71d;&#x722; &#x715;&#x710;&#x72c;&#x710; &#x717;&#x331;&#x718;&#x710; &#x721;&#x722; &#x721;&#x728;&#x72a;&#x71d;&#x722; &#x706; &#x725;&#x720; &#x712;&#x719;&#x712;&#x722; &#x729;&#x72a;&#x712;&#x710; &#x710;&#x71d;&#x72c;&#x71d;&#x718; &#x715;&#x71d;&#x72a;&#x308;&#x71d;&#x710; <span lang="syr">(&#x710;&#x71d;&#x71f; &#x72c;&#x72b;&#x725;&#x71d;&#x72c;&#x710; &#x72c;&#x718;&#x715;&#x71d;&#x72c;&#x722;&#x71d;&#x72c;&#x710;)</span> &#x71a;&#x715;&#x71f;&#x721;&#x710; &#x713;&#x72a;&#x308;&#x721;&#x710; &#x715;&#x729;&#x715;&#x71d;&#x72b;&#x710; &#x721;&#x722; &#x715;&#x71d;&#x72a;&#x710; &#x715;&#x721;&#x72a;&#x71d; &#x710;&#x718;&#x713;&#x71d;&#x722; &#x712;&#x71b;&#x718;&#x72a;&#x710; &#x715; &#x710;&#x71d;&#x719;&#x720;&#x710; &#x715;&#x722;&#x726;&#x720; &#x712;&#x721;&#x715;&#x712;&#x72a;&#x710; &#x715; &#x722;&#x728;&#x71d;&#x712;&#x71d;&#x722; &#x725;&#x720; &#x72c;&#x71a;&#x718;&#x721;&#x710; &#x715; &#x729;&#x721;&#x72b;&#x720;&#x71d;. &#x718;&#x72c;&#x718;&#x712; &#x710;&#x72c;&#x71f;&#x722;&#x71d;&#x72c; &#x715;&#x71d;&#x72a;&#x710; &#x715; &#x719;&#x725;&#x726;&#x72a;&#x710;&#x722; &#x710;&#x718; &#x71f;&#x718;&#x72a;&#x71f;&#x721;&#x710; &#x712;&#x72b;&#x721; &#x721;&#x72a;&#x71d; (&#x72b;&#x720;&#x71d;&#x721;&#x718;&#x722;) &#x715;&#x71d;&#x72a;&#x71d;&#x710; &#x715;&#x72b;&#x72c;&#x710;&#x723; &#x720;&#x715;&#x71d;&#x72a;&#x710; &#x712;&#x72b;&#x722;&#x72c; 473 &#x721;.
</div>

';
$mpdf->autoLangToFont = true;
*/
//==============================================================
//==============================================================
//==============================================================
//==============================================================
/*
$html = '<p style="font-family: \'trebuchet ms\';">Distinguishes multiple languages enclosed in same element (tags): 
Arabic &#x642;&#x627;&#x644; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633; 
English Cat sat on the large mat 
Tamil &#xbb7;&#xbbf;&#xbaf;&#xbbe; 
Hindi &#x92d;&#x93e;&#x930;&#x924; &#x914;&#x930; 
Japanese &#x3044;&#x308d;&#x306f;&#x306b;&#x307b;&#x3078;&#x3068; 
Chinese &#x6765;&#x81ea;&#x5546;&#x52a1;&#x90e8;&#x65b0;&#x95fb;&#x529e;&#x516c; 
Thai &#xe40;&#xe1b;&#xe47;&#xe19;&#xe21;&#xe19;&#xe38;&#xe29;&#xe22; 
Viet M&#xf4;&#x323;t kha&#x309;o sa&#x301;t m&#x1a1;&#x301;i cho bi&#xea;&#x301;t ng&#x1b0;&#x1a1;&#x300;i d&#xe2;n 
English Cat sat on the large mat 
Korean &#xd0a4;&#xc2a4;&#xc758; &#xace0;&#xc720;&#xc870;&#xac74;&#xc740; 
Syriac &#x718;&#x72c;&#x718;&#x712; &#x710;&#x72c;&#x71f;&#x722;&#x71d;&#x72c; &#x717;&#x715;&#x710; &#x715;&#x71d;&#x72a;&#x710; 
Myanmar (Burmese) &#x1019;&#x103c;&#x1014;&#x103a;&#x200b;&#x1019;&#x102c;&#x1021;&#x1001;&#x1031;&#x102b;&#x103a; &#x1010;&#x101b;&#x102c;&#x1038;&#x101d;&#x1004;&#x103a;&#x200b;&#x1021;&#x102c;&#x1038;&#x200b;&#x1016;&#x103c;&#x1004;&#x1037;&#x103a; 
Khmer &#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17d2;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1794;&#x17b6;&#x1793;
NKo &#x7df;&#x7d0;&#x7ec;&#x7dd;&#x7cb;&#x7f2; &#x7d3;&#x7cd;&#x7ef; &#x7df;&#x7ca;&#x7dd;&#x7cb;&#x7f2; 
Thaana &#x78b;&#x7a8;&#x788;&#x7ac;&#x780;&#x7a8; &#x788;&#x7a8;&#x786;&#x7a8;&#x795;&#x7a9;&#x791;&#x7a8;&#x787;&#x7a7; 
Arabic &#x627;&#x644;&#x62d;&#x645;&#x62f; &#x644;&#x644;&#x647; &#x631;&#x628; &#x627;&#x644;&#x639;&#x627;&#x644;&#x645;&#x64a;&#x646;
Urdu &#x639;&#x632;&#x62a; &#x6a9;&#x6d2; &#x627;&#x639;&#x62a;&#x628;&#x627;&#x631; &#x633;&#x6d2; &#x686;&#x6cc;&#x641; &#x62c;&#x633;&#x679;&#x633;
Pashto &#x681;&#x627;&#x646;&#x645;&#x631;&#x6af;&#x648; &#x628;&#x631;&#x64a;&#x62f;&#x648;&#x646;&#x648; &#x644;&#x696;
Farsi  &#x6af;&#x6cc;&#x6a9;&#x62f;&#x6cc;&#x6af;&#x631;
Sindhi &#x62c;&#x64a; &#x6b3;&#x627;&#x644;&#x647;&#x647; &#x6aa;&#x626;&#x64a;
English Cat sat on the large mat 
</p>
';
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;
$mpdf->autoVietnamese = true;
$mpdf->autoArabic = true;
$mpdf->autoLangToFont = true;
*/
//==============================================================
//==============================================================
//==============================================================
// TIBETAN
$htmlx = '
<div style="font-family:\'Jomolhari\'; font-size: 36pt; line-height: 1.6;">
<div>
&#xf04;&#xf0d;&#xf4f;&#xf51;&#xfb1;&#xf50;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf42;&#xf4f;&#xf7a;&#xf42;&#xf4f;&#xf7a;&#xf54;&#xf71;&#xf62;&#xf42;&#xf4f;&#xf7a;&#xf54;&#xf71;&#xf62;&#xf66;&#xf7e;&#xf42;&#xf4f;&#xf7a;&#xf56;&#xf7c;&#xf52;&#xf72;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;
&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf74;&#xf53;&#xf72;&#xf58;&#xf74;&#xf53;&#xf72;&#xf58;&#xf67;&#xf71;&#xf58;&#xf74;&#xf53;&#xf72;&#xf61;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xf72;&#xf52;&#xf7a;&#xf5d;&#xf71;&#xf67;&#xfb2;&#xf71;&#xf72;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf4e;&#xf72;&#xf54;&#xf51;&#xfa8;&#xf7a;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf7f;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0b;
&#xf56;&#xf5b;&#xfb2;&#xf42;&#xf74;&#xf62;&#xf74;&#xf54;&#xf51;&#xfa8;&#xf66;&#xf72;&#xf51;&#xfa2;&#xf72;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xf62;&#xf71;&#xf4e;&#xf72;&#xf5b;&#xfb2;&#xf72;&#xf5d;&#xf53;&#xf4f;&#xf72;&#xf61;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf5d;&#xf42;&#xf72;&#xf64;&#xf71;&#xf62;&#xf72;&#xf58;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf0b;
&#xf58;&#xf4e;&#xf72;&#xf54;&#xf51;&#xfa8;&#xf7a;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf56;&#xf5b;&#xfb2;&#xf54;&#xf71;&#xf53;&#xf72;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf4f;&#xf71;&#xf62;&#xf7a;&#xf4f;&#xf74;&#xf4f;&#xf9f;&#xf71;&#xf62;&#xf7a;&#xf4f;&#xf74;&#xf62;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf57;&#xfb2;&#xf71;&#xf74;&#xf7e;&#xf0b;
&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xfb2;&#xf72;&#xf4f;&#xf71;&#xf68;&#xf71;&#xf61;&#xf74;&#xf62;&#xfa1;&#xf51;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf62;&#xf72;&#xf59;&#xfb1;&#xf7a;&#xf58;&#xf7e;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;
</div>
</div>
';
//==============================================================
//==============================================================
$htmlx = '
<div>
<img src="myanmartest.jpg" />
</div>
MYANMAR (Burmese) - Myanmar Text

<div style="font-family:\'Myanmar Text\'; font-size: 32pt; line-height: 1.5em;">
&#x1004;&#x103a;&#x1039;&#x1000;&#x1039;&#x1000;&#x103b;&#x103c;&#x103d;&#x103e;&#x1031;&#x102d;&#x102f;&#x1037;&#x103a;&#x102c;&#x103e;&#x102e;&#x1037;&#x1064;&#x1032;&#x1036;&#x1037;&#x1038;&#x108d;
</div>
<br />



';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
// FIXES for kerning/line-justification
$htmlx = '
<style>
body, p { font-size: 15pt;}
</style>

<div style="border: 1px solid #888888;">

// Test of XPlacement/XAdvance at end of line
<div style="direction: rtl; font-family: \'arabic typesetting\'; line-height: 1.8; font-size: 42pt; text-align:justify;">
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
&#x648;&#x64e;&#x644;&#x652;&#x64a;&#x64e;&#x643;
</div>
<div style="direction: rtl; font-family: xbzar; font-size: 36pt;">
&#x6a9;&#x627; 
</div>


GPOS Type 2: Format 2 (THAANA - MV Boli)
<div style="font-family:\'MV Boli\'; font-size: 38pt; direction: rtl;">
&#x788;&#x7a8;&#x786;&#x7a8;&#x795;&#x7a9;&#x791;&#x7a8;&#x787;&#x7a7; 
</div>

GPOS Type 2: Format 2 (ARABIC XB Zar)
<div style="direction: rtl; font-family: xbzar; font-size: 36pt;">
&#x6a9;&#x627; 
</div>

GPOS Type 2: Format 2 (ARABIC XB Zar)
<div style="direction: rtl; font-family: xbzar; font-size: 36pt">
&#x62a;&#x648;&#x6af;&#x647; 
</div>

GPOS Type 2: Format 2 (SYRIAC - Estrangelo Edessa)
<div style="font-family:\'Estrangelo Edessa\'; font-size: 26pt; direction: rtl;">
&#x721;&#x728;&#x72a;&#x71d;&#x722; 
</div>



GPOS Type 2: Format 1 (Tharlon)
<div style="font-family: tharlon; font-size: 26pt;">&#x1000;&#x103c;&#x102d;&#x102f;</div>

GPOS Type 2: Format 1 (Arabic Typesetting)
<div style="direction: rtl; font-family: \'arabic typesetting\'; line-height: 1.8; font-size: 42pt;">
&#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; 
</div>

GPOS Type 2: Format 1 (Arial)
<div style="font-family:arial; font-size: 48pt;font-feature-settings:\'kern\'; ">
A&#x308;YA&#x308;WAY To&#x308;
</div>

</div>
';

//==============================================================
// WHITESPACE
$htmlx = '
<p>
From Lazamon\'s<i> <a href="http://mesl.itd.umich.edu/b/brut/">Brut</a></i>
<p>

<div> Monotonic: <p> <span> Hallo World</span><p> <span> Hallo World</span></div>
<table cellspacing=0 cellpadding=0> <tr> <td> Monotonic: <p> <span> Hallo World</span><p> <span> Hallo World</span></td></tr> </table>

<div> Hallo <span> Hallo <span> Hallo </span> Hallo </span> Hallo </div>
';
//==============================================================
// LANGUAGE TAGS - CYRILLIC
$htmlx = '
HTML language tags to distinguish Bulgarian, Russian, and Serbian, 
which have different italic forms for lowercase &#x431;, &#x433;, &#x434;, &#x43f;, and/or &#x442;:
<table style="font-family:FreeSerif">
<tr>
<td><b>Bulgarian</b>: &nbsp;
<td><span lang=BG>[&nbsp;&#x431;&#x433;&#x434;&#x43f;&#x442;</span>&nbsp;] &nbsp;
<td><span lang=BG>[&nbsp;<i>&#x431;&#x433;&#x434;&#x43f;&#x442;</i></span>&nbsp;] &nbsp;
<td><span lang=BG><i> &#x41c;&#x43e;&#x433;&#x430; &#x434;&#x430; &#x44f;&#x43c; &#x441;&#x442;&#x44a;&#x43a;&#x43b;&#x43e; &#x438; &#x43d;&#x435; &#x43c;&#x435; &#x431;&#x43e;&#x43b;&#x438;.</i></span>
<tr>
<td><b>Russian</b>:
<td><span lang=RU>[&nbsp;&#x431;&#x433;&#x434;&#x43f;&#x442;</span>&nbsp;] &nbsp;
<td><span lang=RU>[&nbsp;<i>&#x431;&#x433;&#x434;&#x43f;&#x442;</i></span>&nbsp;] &nbsp;
<td><span lang=RU><i>&#x42f; &#x43c;&#x43e;&#x433;&#x443; &#x435;&#x441;&#x442;&#x44c; &#x441;&#x442;&#x435;&#x43a;&#x43b;&#x43e;, &#x44d;&#x442;&#x43e; &#x43c;&#x43d;&#x435; &#x43d;&#x435; &#x432;&#x440;&#x435;&#x434;&#x438;&#x442;.</i></span>
<tr>
<td><b>Serbian</b>:
<td><span lang=SR>[&nbsp;&#x431;&#x433;&#x434;&#x43f;&#x442;</span>&nbsp;] &nbsp;
<td><span lang=SR>[&nbsp;<i>&#x431;&#x433;&#x434;&#x43f;&#x442;</i></span>&nbsp;] &nbsp;
<td> <span lang=SR><i>&#x41c;&#x43e;&#x433;&#x443; &#x458;&#x435;&#x441;&#x442;&#x438; &#x441;&#x442;&#x430;&#x43a;&#x43b;&#x43e; 
&#x430;
&#x434;&#x430; &#x43c;&#x438; 
&#x43d;&#x435; 
&#x448;&#x43a;&#x43e;&#x434;&#x438;.</i></span>
</table>
';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
// BIDI ALGORITHM FOR CHUNKS
$htmlx = '
<div style="font-family: xbriyaz">Arabic (<span lang="ar">&#x627;&#x644;&#x633;&#x644;&#x627;&#x645; &#x639;&#x644;&#x64a;&#x643;&#x645;</span>)</div>
<div dir="rtl" style="font-family: xbriyaz">Arabic (<span lang="ar">&#x627;&#x644;&#x633;&#x644;&#x627;&#x645; &#x639;&#x644;&#x64a;&#x643;&#x645;</span>)</div>
<div dir="rtl" style="font-family: xbriyaz">(<span lang="ar">&#x627;&#x644;&#x633;&#x644;&#x627;&#x645; &#x639;&#x644;&#x64a;&#x643;&#x645;</span>) Arabic </div>
';

//==============================================================
// CSS unicode-bidi
// normal | embed | bidi-override | inherit
$htmlx = '
// embed = add LRE (U+202A; for \'direction: ltr\') or RLE (U+202B; for \'direction: rtl\') at the start of element and PDF (U+202C) at the end of element
<div style="font-family: freesans; direction: ltr;">
  <p>english17 <span>&#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20</span></p>
  <p>english17 <span>&#x202b;&#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20&#x202c;</span></p>
  <p>english17 <span style="direction: rtl; unicode-bidi: embed;">&#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20</span></p>
</div>

// bidi-override = LRO (U+202D; for \'direction: ltr\') or RLO (U+202E; for \'direction: rtl\') at the start of the element or at the start of each anonymous child block box, if any, and a PDF (U+202C) at the end of the element
<div style="font-family: freesans; direction: rtl;">
  <p style="direction: ltr; unicode-bidi:normal;">english17 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20</p>
  <p style="direction: ltr;">&#x202d;english17 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20&#x202c;</p>
  <p style="direction: ltr; unicode-bidi:bidi-override;">english17 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;18 english19 &#x5e2;&#x5d1;&#x5e8;&#x5d9;&#x5ea;20</p>
</div>

';
//==============================================================
//==============================================================
//==============================================================
// used to determine the aspect value of a font.
// CSS3 font-aspect: http://www.w3.org/TR/css3-fonts/#propdef-font-size-adjust
// DejaVuSansCondensed 0.547
// FreeSerif 0.45
// Arabic Typesetting 0.278
// Mondulkiri 0.45
// Sun-ExtA 0.455
// XB Riyaz 0.523
$htmlx = '
<style>
p {
    font-family: "XB Riyaz";
    font-size: 400px;
}

span {
    border: solid 1px red;
}

.adjust {
    font-size-adjust: 0.523;
}
.adjust2 {
    font-size-adjust: 0.323;
}
</style>
<p><span>b</span><span class="adjust">b</span></p>
<p><span>b</span><span class="adjust2">b</span></p>
';
//==============================================================
//==============================================================
// Test for alpha transparency in PNG
$htmlx = '
<div style="background-color: transparent;">
<img src="alpha09.png" height="400px" />
<img src="alpha36.png" height="400px" />
</div>

<div style="background-color: white;">
<img src="alpha09.png" height="400px" />
<img src="alpha36.png" height="400px" />
</div>
<div style="background-color: navy;">
<img src="alpha09.png" height="400px" />
<img src="alpha36.png" height="400px" />
</div>
<div style="background-color: maroon;">
<img src="alpha09.png" height="400px" />
<img src="alpha36.png" height="400px" />
</div>
<div style="background-color: black;">
<img src="alpha09.png" height="400px" />
<img src="alpha36.png" height="400px" />
</div>

';

//==============================================================
//==============================================================
$htmlx ='
<style>
img.smooth {
	image-rendering:auto;
	image-rendering:optimizeQuality;
	-ms-interpolation-mode:bicubic;
}
img.crisp {
	image-rendering: -moz-crisp-edges;		/* Firefox */
	image-rendering: -o-crisp-edges;		/* Opera */
	image-rendering: -webkit-optimize-contrast;/* Webkit (non-standard naming) */
	image-rendering: crisp-edges;
	-ms-interpolation-mode: nearest-neighbor;	/* IE (non-standard property) */
}
</style>
<div>
<img class="smooth" src="pngtestsuite/bgan6a16.png" width="300px" />
<span style="image-rendering:-moz-crisp-edges">
<img class="crisp" src="pngtestsuite/bgan6a162.png" width="300px" />
</span></div>
<img src="pngtestsuite/bgan6a162.png" width="300px" />
';

//==============================================================
//==============================================================
//==============================================================
// ICC based Color Profiles in Images + 16-bit Gamma
$htmlx = '
PNG (16-bit, Gamma): <div style="background-color: #cc9900; padding: 2em;"><img src="butterfly_ProPhoto.png" ></div>
PNG (Gamma): <div style="background-color: #cc9900; padding: 2em;"><img src="ColorGammaGradient.png" ></div>
PNG (ICC Profile): <div style="background-color: #cc9900; padding: 2em;"><img src="colourTestFakeBRG.png" ></div>

JPEG (ICC Profile):
<table align="center" cellspacing="4" cellpadding="4">
		<tr>
			<td> Profiles in each quadrant: </td>
			<td> v4 e-sRGB </td>
			<td> v4 YCC-RGB </td>
		</tr>
		<tr>
			<td> </td>
			<td> v2 GBR </td>
			<td> v2 Adobe RGB </td>
		</tr>
	</table>

<table align="center" width="400"border="0" cellspacing="0" cellpadding="0">
  <tr align="top">
    <td align="right"> <img src="Upper_Left.jpg" width=180> </td>
    <td align="left"> <img src="Upper_Right.jpg" width=171> </td>
  </tr>
  <tr>
    <td align="right"> <img src="Lower_Left.jpg" width=180> </td>
    <td align="left"> <img src="Lower_Right.jpg" width=171> </td>
  </tr>
</table>
This should correctly display with the motorbike in green:
<img src="BGR-Red-Ducati_WCS-Test-TriState.jpg" />
';
//==============================================================
//==============================================================
//==============================================================
// UNDERLINE and LINE-THROUGH - together
$htmlx ='
<p style="text-decoration:none">Hallo World
<span style="text-decoration:underline">Hallo World
<span style="text-decoration:line-through">Hallo World
<span style="color: red;">Hallo World</span>
Hallo World</span> 
Hallo World</span> 
Hallo World</p>
';
//==============================================================
//==============================================================
// UNDERLINE and LINE-THROUGH - across spans
$htmlx ='
<p style="font-size: 32pt"><span style="text-decoration:underline">1<sup>st</sup></span></p>
<p style="font-size: 32pt">1<sup><span style="text-decoration:underline">st</span></sup></p>
';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//BUG TABLES - Width > Page - needing to wrap letters or resize
// KEEP THIS to Illustrate mpdf - ZZZZZe.php Bug fix
$html = '
Latin script will resize rather than wrap -
<table border="1" style="font-family:sun-exta">
<tr>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td>
<td>AB</td>
<td>AB</td>
<td>ABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABAB</td>
</tr>
</table>

Can force wrap using overflow: wrap - 
<table border="1" style="font-family:sun-exta;overflow:wrap">
<tr>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td><td>AB</td>
<td>AB</td>
<td>AB</td>
<td>AB</td>
<td>ABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABABAB</td>
</tr>
</table>

CJK script will wrap characters (this example also resizes to minimum column width). In this case, minimum is 2 characters in last column because of a character which cannot start a line -
<table border="1" style="font-family:sun-exta">
<tr>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;</td>
</tr>
</table>

Default layout when resizing not required - note wrapping, but balanced column widths -
<table border="1" style="font-family:sun-exta">
<tr>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td><td>&#x90e8;&#x90e8;</td>
<td>&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;&#x90e8;&#x30a9;</td>
</tr>
</table>

';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
$html = '
<style>
table.table1 { border-spacing: 10px; }
.table1 td { padding: 10px; }

</style>

<table border="0">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>

<table border="0" cellPadding="0" cellSpacing="0">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>

<table border="0" class="table1">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>

<table border="0" class="table1" cellPadding="0" cellSpacing="0">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>

<table border="0" class="table1" style="border-spacing: 20px">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>

<table border="0" class="table1" cellPadding="0" cellSpacing="0" style="border-spacing: 20px">
<tr><td style="border-top:1px solid #000;">1</td><td style="border-top:1px solid #000;">2</td><td style="border-top:1px solid #000;">3</td></tr>
<tr><td style="border-top:1px solid #000;">4</td><td style="border-top:1px solid #000;">5</td><td style="border-top:1px solid #000;">6</td></tr>
</table>


';

//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
if (isset($_REQUEST['html'])) { echo $html; exit; }
if (isset($_REQUEST['source'])) { 
	$file = __FILE__;
	header("Content-Type: text/plain");
	header("Content-Length: ". filesize($file));
	header("Content-Disposition: attachment; filename='".$file."'");
	readfile($file);
	exit; 
}
//==============================================================
$mpdf->WriteHTML($html);


// $mpdf->WriteHTML('<div style="font-size: 8pt; margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000;">Generated in '.sprintf('%.2f',(microtime(true) - $mpdf->time0)).' seconds. Peak memory usage '.number_format((memory_get_peak_usage(true)/(1024*1024)),2).' MB</div>');

// echo number_format((memory_get_peak_usage(true)/(1024*1024)),2).' MB<br />';

//==============================================================
//==============================================================
// OUTPUT
//$mpdf->SetCompression(false);
$mpdf->Output(); exit;

//$mpdf->Output('test.pdf','D'); exit;

$s = $mpdf->Output('','S');  echo nl2br(htmlspecialchars($s));  exit;


exit;

//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>