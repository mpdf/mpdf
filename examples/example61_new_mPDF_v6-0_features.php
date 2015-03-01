<?php

set_time_limit(600);

include("../mpdf.php");

$mpdf=new mPDF(''); 

$mpdf->list_auto_mode = 'mpdf';	// Used for demonstration of lists
//==============================================================

$html = '
<style>
body, div, p {
	font-family: \'DejaVu Sans Condensed\';
	font-size: 11pt;
}
.gradient {
	border:0.1mm solid #220044; 
	background-color: #f0f2ff;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
	box-shadow: 0.3em 0.3em #888888;
}
h4 {
	font-weight: bold;
	margin-top: 1em;
	margin-bottom: 0.3em;
	margin-top: 0;
}
div.text {
	padding:0.8em; 
	margin-bottom: 0.7em;
}
p { margin: 0.25em 0; }
p.code {
	background-color: #e5e5e5; 
	margin: 1em 1cm;
	padding: 0 0.3cm;
	border:0.2mm solid #000088; 
	box-shadow: 0.3em 0.3em #888888;
}
p.example, div.example {
	background-color: #eeeeee; 
	margin: 0.3em 1em 1em 1em;
	padding: 0 0.3cm;
	border:0.2mm solid #444444; 
}
.code {
	font-family: monospace;
	font-size: 9pt;
}
.shadowtitle { 
	height: 8mm; 
	background-color: #EEDDFF; 
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;  
	padding: 0.8em; 
	padding-left: 3em;
	font-family:sans;
	font-size: 26pt; 
	font-weight: bold;
	border: 0.2mm solid white;
	border-radius: 0.2em;
	box-shadow: 0 0 1em 0.5em rgba(0,0,255,0.5);
	color: #AAAACC;
	text-shadow: 0.03em 0.03em #666, 0.05em 0.05em rgba(127,127,127,0.5), -0.015em -0.015em white;
}
h3 { 
	margin: 3em 0 2em -15mm; 
	background-color: #EEDDFF; 
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;  
	padding: 0.5em; 
	padding-left: 3em;
	width: 50%;
	font-family:sans;
	font-size: 16pt; 
	font-weight: bold;
	border-left: none;
	border-radius: 0 2em 2em 0;
	box-shadow: 0 0 2em 0.5em rgba(255,0,0,1);
	text-shadow: 0.05em 0.04em rgba(127,127,127,0.5);
}
.css {
	font-family: arial;
	font-style: italic;
	color: #000088;
}
table.fontinfo {
	border-collapse:collapse;
}
table.fontinfo td {
	vertical-align: top;
	border: 0.2mm solid #BBBBBB;
	padding: 0.2em;
}
table.fontinfo thead td {
	text-align: center;
	font-weight: bold;
}
</style>
<body>

<htmlpagefooter name="myHTMLarabic">
<table dir="rtl" width="100%" style="border-top: 1px solid #000000; vertical-align: top; font-family: dejavusanscondensed; font-size: 9pt; color: #000088;"><tr>
<td width="33%"></td>
<td width="33%" align="center">Page {PAGENO} of {nbpg}</td>
<td width="33%" style="text-align: left;"><span lang="ar">&#x627;&#x644;&#x635;&#x641;&#x62d;&#x629; {PAGENO} &#x645;&#x646; {nbpg}</span></td>
</tr></table>
</htmlpagefooter>

<htmlpagefooter name="myHTMLbengali">
<table width="100%" style="border-top: 1px solid #000000; vertical-align: top; font-family: freeserif; font-size: 9pt; color: #000088;"><tr>
<td width="33%"></td>
<td width="33%" align="center">Page {PAGENO} of {nbpg}</td>
<td width="33%" style="text-align: right;"><span lang="bn">{nbpg} &#x9aa;&#x9be;&#x9a4;&#x9be; &#x9a5;&#x9c7;&#x995;&#x9c7; &#x9aa;&#x9be;&#x9a4;&#x9be; {PAGENO}</span></td>
</tr></table>
</htmlpagefooter>

<htmlpagefooter name="myHTMLhebrew">
<table dir="rtl" width="100%" style="border-top: 1px solid #000000; vertical-align: top; font-family: dejavusanscondensed; font-size: 9pt; color: #000088;"><tr>
<td width="33%"></td>
<td width="33%" align="center">Page {PAGENO} of {nbpg}</td>
<td width="33%" style="text-align: left;"><span lang="hr">&#x5e2;&#x5de;&#x5d5;&#x5d3; {PAGENO} &#x5d0;&#x5d5; {nbpg}</span></td>
</tr></table>
</htmlpagefooter>

<sethtmlpagefooter name="myHTMLarabic" page="O" value="on" show-this-page="1" />
<sethtmlpagefooter name="myHTMLarabic" page="E" value="on" />


<div class="shadowtitle">New Features in mPDF v6.0</div>

<h3>Advanced Typography</h3>
<p>Many TrueType fonts contain OpenType Layout (OTL) tables. These Advanced Typographic tables contain additional information that extend the capabilities of the fonts to support high-quality international typography:</p>

<ul>
<li>OTL fonts support ligatures, positional forms, alternates, and other substitutions.</li>
<li>OTL fonts include information to support features for two-dimensional positioning and glyph attachment.</li>
<li>OTL fonts contain explicit script and language information, so a text-processing application can adjust its behavior accordingly.</li>
</ul>

<p>mPDF 6 introduces the power and flexibility of the OpenType Layout font model into PDF.
mPDF 6 supports GSUB, GPOS and GDEF tables for now. mPDF 6 does not support BASE and JSTF at present.</p>

<p>Other mPDF 6 features to enhance complex scripts:</p>
<ul>
<li>improved Bidirectional (Bidi) algorithm for right-to-left (RTL) text</li>
<li>support for Kashida for justification of arabic scripts</li>
<li>partial support for CSS3 optional font features e.g. font-feature-settings, font-variant</li>
<li>improved "autofont" capability to select fonts automatically for any script</li>
<li>support for CSS :lang selector</li>
<li>dictionary-based line-breaking for Lao, Thai and Khmer (U+200B is also supported)</li>
<li>separate algorithm for Tibetan line-breaking</li>
</ul>

<p>Note: There are other smart-font technologies around to deal with complex scripts, namely Graphite fonts (SIL International) and Apple Advanced Typography (AAT by Apple/Mac). mPDF 6 does not support these.</p>

<h3>What can OTL Fonts do?</h3>

<p>Support for OTL fonts allows the faithful display of almost all complex scripts:</p>
<ul>
<li>Arabic (<span lang="ar">&#x627;&#x644;&#x633;&#x644;&#x627;&#x645; &#x639;&#x644;&#x64a;&#x643;&#x645;</span>), Hebrew (<span lang="he">&#x5e9;&#x5dc;&#x5d5;&#x5dd;</span>), Syriac (<span lang="syr">&#x710;&#x723;&#x71b;&#x72a;&#x722;&#x713;&#x720;&#x710;</span>)</li>
<li>Indic - Bengali (<span lang="bn">&#x9b8;&#x9cd;&#x9b2;&#x9be;&#x9ae;&#x9be;&#x9b2;&#x9bf;&#x995;&#x9c1;&#x9ae;</span>), Devanagari (<span lang="hi">&#x928;&#x92e;&#x938;&#x94d;&#x924;&#x947;</span>), Gujarati (<span lang="gu">&#xaa8;&#xaae;&#xab8;&#xacd;&#xaa4;&#xac7;</span>), Punjabi (<span lang="pa">&#xa38;&#xa24;&#xa3f; &#xa38;&#xa4d;&#xa30;&#xa40; &#xa05;&#xa15;&#xa3e;&#xa32;</span>),<br />
Kannada (<span lang="kn">&#xca8;&#xcae;&#xcb8;&#xccd;&#xca4;&#xcc6;</span>), Malayalam (<span lang="ml">&#xd28;&#xd2e;&#xd38;&#xd4d;&#xd24;&#xd46;</span>), Oriya (<span lang="or">&#xb28;&#xb2e;&#xb38;&#xb4d;&#xb15;&#xb30;</span>), Tamil (<span lang="ta">&#xbb5;&#xba3;&#xb95;&#xbcd;&#xb95;&#xbae;&#xbcd;</span>), Telugu (<span lang="te">&#xc28;&#xc2e;&#xc38;&#xc4d;&#xc15;&#xc3e;&#xc30;&#xc02;</span>)</li>
<li>Sinhala (<span lang="si">&#xd86;&#xdba;&#xdd4;&#xd9b;&#xddd;&#xdc0;&#xdb1;&#xdca;</span>),
Thai (<span lang="th">&#xe2a;&#xe27;&#xe31;&#xe2a;&#xe14;&#xe35;</span>),
Lao (<span lang="lo">&#xeaa;&#xeb0;&#xe9a;&#xeb2;&#xe8d;&#xe94;&#xeb5;</span>),
Khmer (<span lang="km">&#x1787;&#x17c6;&#x179a;&#x17b6;&#x1794;&#x179f;&#x17bd;&#x179a;</span>),
Myanmar (<span lang="my">&#x1019;&#x1002;&#x1086;&#x101c;&#x102c;&#x1015;&#x105d;</span>),<br />
Tibetan (<span lang="bo">&#xf56;&#xf40;&#xfb2;&#xf0b;&#xf64;&#xf72;&#xf66;&#xf0b;&#xf56;&#xf51;&#xf7a;&#xf0b;&#xf63;&#xf7a;&#xf42;&#xf66;&#xf0d;</span>)</li>
</ul>

<h4>Joining and Reordering</h4>
<div class="example" lang="bn" style="font-size: 18pt">
&#x9b0; + &#x9cd; + &#x996; + &#x9cd; + &#x9ae; + &#x9cd; + &#x995; + &#x9cd; + &#x9b7; + &#x9cd; + &#x9b0; + &#x9bf; + &#x9c1; =
&#x9b0;&#x9cd;&#x996;&#x9cd;&#x9ae;&#x9cd;&#x995;&#x9cd;&#x9b7;&#x9cd;&#x9b0;&#x9bf;&#x9c1;
</div>
<p>cf. <a href="http://www.microsoft.com/typography/OpenTypeDev/bengali/intro.htm">http://www.microsoft.com/typography/OpenTypeDev/bengali/intro.htm</a></p>

<h4>Ligatures</h4>
<div class="example" style="font-family:\'Dejavu Sans Condensed\'; font-size: 18pt;">
<span style="font-feature-settings:\'liga\' off">ffi ffl fi</span>
&nbsp; &nbsp; &nbsp; 
<span>ffi ffl fi</span>
</div>

<h4>Language-dependent substitutions</h4>
<div class="example" style="font-family:xbriyaz">
<p lang="ar">Arabic: <span style="font-size: 18pt;">&#x06f4; &#x06f6; &#x0667;</span>  Urdu: <span style="font-language-override:URD; font-size: 18pt">&#x06f4; &#x06f6; &#x0667;</span> Arabic: <span style="font-size: 18pt;">&#x0647; &#x06c8; &#x06d1; &#x06d5;</span>  Kurdish: <span lang="ku" style="font-size: 18pt;">&#x0647; &#x06c8; &#x06d1; &#x06d5;</span></p>
</div>

<h4>Font features - Optional substitutions</h4>
Stylistic Alternatives (salt)
<div class="example" style="font-family:xbriyaz">
<p style="font-family:xbriyaz">Arabic: <span style="font-size: 18pt;">&#x0626; &#x0639; &#x06a9; &#x0640;&#x0647; &#x0640;&#x0647;&#x0640; &#x0640;&#x06c0; </span>  Farsi: <span lang="fa" style="font-feature-settings:\'salt\'; font-size: 18pt;">&#x0626; &#x0639; &#x06a9; &#x0640;&#x0647; &#x0640;&#x0647;&#x0640; &#x0640;&#x06c0; </span> Arabic: <span style="font-size: 18pt;">&#x06af;</span>  Turkish: <span style="font-language-override:TRK; font-feature-settings:\'salt\'; font-size: 18pt;">&#x06af;</span></p>
</div>



<h4>CSS control of discretionary OTL features</h4>

<div class="example">
salt: (off) <span style="font-size: 15pt; font-family:\'Dejavu Sans Condensed\';">all</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 15pt; font-feature-settings:\'salt\' on; font-family:\'Dejavu Sans Condensed\';">all</span>
</div>


<div class="example">
frac: (off) <span style="font-size: 15pt; font-family:\'Free Serif\';">1/4 3/10</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 15pt; font-feature-settings:\'frac\' on; font-family:\'Free Serif\';">1/4 3/10</span>
</div>

<div class="example">
zero: (off) <span style="font-size: 15pt; font-family:\'Free Serif\';">1,000</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 15pt; font-feature-settings:\'zero\' on; font-family:\'Free Serif\';">1,000</span>
</div>

<div class="example">
onum: (off) <span style="font-size: 15pt; font-family:\'Free Serif\';">0123456789</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 15pt; font-feature-settings:\'onum\' on; font-family:\'Free Serif\';">0123456789</span>
</div>

<div class="example">
sups: (off) <span style="font-size: 15pt; font-family:\'Free Serif\';">(32)</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 15pt; font-feature-settings:\'sups\' on; font-family:\'Free Serif\';">(32)</span>
</div>

<div class="example">
Stylistic Alternatives (ss03,ss04): (off) <span style="font-size: 18pt; font-family:\'Free Serif\';">&#x0905; &#x091d; &#x0923; &#x91d; &#x96f;</span>
&nbsp; &nbsp; &nbsp; (on) 
<span style="font-size: 18pt; font-feature-settings:\'ss03\' 1, \'ss04\' 1; font-family:\'Free Serif\';">&#x0905; &#x091d; &#x0923; &#x91d; &#x96f;</span>
</div>


<p>A full list of feature tags is at <a href="http://www.microsoft.com/typography/otspec/featurelist.htm">http://www.microsoft.com/typography/otspec/featurelist.htm</a></p>
<p>In mPDF, the following features are on by default:</p>
<ul>
<li>GSUB features: locl ccmp pref blwf abvf pstf pres abvs blws psts haln rlig calt liga clig mset (all scripts)</li>
<li>GSUB features: isol fina fin2 fin3 medi med2 init nukt akhn rphf rkrf half vatu cjct cfar (for appropriate scripts e.g. Indic, Arabic)</li>

<li>GPOS features: abvm blwm mark mkmk curs cpsp dist requ [kern]</li>
</ul>

<p>NB \'requ\' is not listed in the Microsoft registry of Feature tags; however it is found in the Arial Unicode MS font (it repositions the baseline for punctuation in Kannada script).</p>


<p>Kern is used in some fonts to reposition marks etc. and is essential for correct display, so in mPDF kern is on by default when any non-Latin script is used.</p>


<!--
<h4>Cursive Repositioning</h4>
(using Arabic Typesetting)

<div class="example" style="font-family:\'arabic typesetting\'; font-size: 28pt;">
<span style="font-feature-settings:\'curs\' off;">
&#x0640;&#x0649;&#x0766;&#x0640;
&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;
</span>
&nbsp; &nbsp; &nbsp; 
<span>
&#x0640;&#x0649;&#x0766;&#x0640;
&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;&#xfe8c;
</span>
</div>
-->


<h4>Mark repositioning (and diacritics)</h4>
<div class="example" style="font-family: \'Dejavu Sans\'; font-size: 18pt;">
<span style="font-feature-settings:\'mark\' off;">&#x5d6;&#x5bc;&#x5b5; &#x5d9;&#x5bc;&#x5b0; &#x5da;&#x5b8;</span>
&nbsp; &nbsp; &nbsp;&#x200e; 
<span>&#x5d6;&#x5bc;&#x5b5; &#x5d9;&#x5bc;&#x5b0; &#x5da;&#x5b8;</span>
</div>


<h4>Mark repositioning (and Contextual substitution)</h4>
<div class="example" style="font-family:\'Dejavu Sans Condensed\'; font-size: 18pt;">
<span style="font-feature-settings:\'mark\' off, \'ccmp\' off">A&#769; a&#769; i&#x308;</span>
&nbsp; &nbsp; &nbsp; 
<span >A&#769; a&#769; i&#x308;</span>
</div>



<h4>Complex syllables</h4>
<div>Note that the text displayed is dependent on the font\'s design/capabilities. These are both "correct" representations of the same string, using:</div>
<div class="example">FreeSerif: <span lang="hi" style="font-size: 18pt">&#x930;&#x94d;&#x926;&#x94d;&#x92e;&#x93f;</span>
and FreeSans font:
<span style="font-family:FreeSans; font-size: 18pt">&#x930;&#x94d;&#x926;&#x94d;&#x92e;&#x93f;</span>
</div>
<p>cf. <a href="http://www.microsoft.com/typography/OpenTypeDev/devanagari/intro.htm">http://www.microsoft.com/typography/OpenTypeDev/devanagari/intro.htm</a><p>


<h4>Complex Typography</h4>
An example which utilises many different GSUB and GPOS features together - first without GSUB and GPOS:
<div class="example" dir="rtl" style="font-family:\'KFGQPC Uthman Taha Naskh\'; font-size: 26pt; font-feature-settings:\'calt\' off, \'liga\' off, \'curs\' off, \'mark\' off, \'mkmk\' off; margin: 0.3em 0em;">
&#x62a;&#x64e;&#x633;&#x652;&#x640;&#x654;&#x64e;&#x645;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x623;&#x64e;&#x648;&#x652; &#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; &#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x650;  &#x648;&#x64e;&#x623;&#x64e;&#x62f;&#x652;&#x646;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627;  &#x625;&#x650;&#x644;&#x651;&#x64e;&#x622;  &#x628;&#x64e;&#x64a;&#x652;&#x646;&#x64e;&#x643;&#x64f;&#x645;&#x652;  &#x639;&#x64e;&#x644;&#x64e;&#x64a;&#x652;&#x643;&#x64f;&#x645;&#x652;  &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627; &#x64a;&#x64e;&#x639;&#x652;&#x62a;&#x64f;&#x645;&#x652; &#x6da;
</div>
With GSUB and GPOS:
<div class="example" dir="rtl" style="font-family:\'KFGQPC Uthman Taha Naskh\'; font-size: 26pt; margin: 0.3em 0em;">
&#x62a;&#x64e;&#x633;&#x652;&#x640;&#x654;&#x64e;&#x645;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x623;&#x64e;&#x648;&#x652; &#x643;&#x64e;&#x628;&#x650;&#x64a;&#x631;&#x64b;&#x627; &#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; &#x671;&#x644;&#x644;&#x651;&#x64e;&#x647;&#x650;  &#x648;&#x64e;&#x623;&#x64e;&#x62f;&#x652;&#x646;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627;  &#x625;&#x650;&#x644;&#x651;&#x64e;&#x622;  &#x628;&#x64e;&#x64a;&#x652;&#x646;&#x64e;&#x643;&#x64f;&#x645;&#x652;  &#x639;&#x64e;&#x644;&#x64e;&#x64a;&#x652;&#x643;&#x64f;&#x645;&#x652;  &#x623;&#x64e;&#x644;&#x651;&#x64e;&#x627; &#x64a;&#x64e;&#x639;&#x652;&#x62a;&#x64f;&#x645;&#x652; &#x6da;
</div>



<h4>Text Justification using Kashida</h4>

<div class="example" dir="rtl" style="font-family: \'KFGQPC Uthman Taha Naskh\'; font-size: 29pt; ">
&#x64a;&#x64e;&#x640;&#x670;&#x653;&#x623;&#x64e;&#x64a;&#x651;&#x64f;&#x647;&#x64e;&#x627; &#x671;&#x644;&#x651;&#x64e;&#x630;&#x650;&#x64a;&#x646;&#x64e; &#x621;&#x64e;&#x627;&#x645;&#x64e;&#x646;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x625;&#x650;&#x630;&#x64e;&#x627; 
&#x62a;&#x64e;&#x62f;&#x64e;&#x627;&#x64a;&#x64e;&#x646;&#x62a;&#x64f;&#x645; &#x628;&#x650;&#x62f;&#x64e;&#x64a;&#x652;&#x646;&#x64d; 
&#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x62c;&#x64e;&#x644;&#x64d;&#x6e2; &#x645;&#x651;&#x64f;&#x633;&#x64e;&#x645;&#x651;&#x64b;&#x6ed;&#x649; &#x641;&#x64e;&#x671;&#x643;&#x652;&#x62a;&#x64f;&#x628;&#x64f;&#x648;&#x647;&#x64f; &#x6da; 
</div>
<div class="example" dir="rtl" style="font-family: \'KFGQPC Uthman Taha Naskh\'; font-size: 29pt; text-align: justify;">
&#x64a;&#x64e;&#x640;&#x670;&#x653;&#x623;&#x64e;&#x64a;&#x651;&#x64f;&#x647;&#x64e;&#x627; &#x671;&#x644;&#x651;&#x64e;&#x630;&#x650;&#x64a;&#x646;&#x64e; &#x621;&#x64e;&#x627;&#x645;&#x64e;&#x646;&#x64f;&#x648;&#x653;&#x627;&#x6df; &#x625;&#x650;&#x630;&#x64e;&#x627; 
&#x62a;&#x64e;&#x62f;&#x64e;&#x627;&#x64a;&#x64e;&#x646;&#x62a;&#x64f;&#x645; &#x628;&#x650;&#x62f;&#x64e;&#x64a;&#x652;&#x646;&#x64d; 
&#x625;&#x650;&#x644;&#x64e;&#x649;&#x670;&#x653; &#x623;&#x64e;&#x62c;&#x64e;&#x644;&#x64d;&#x6e2; &#x645;&#x651;&#x64f;&#x633;&#x64e;&#x645;&#x651;&#x64b;&#x6ed;&#x649; &#x641;&#x64e;&#x671;&#x643;&#x652;&#x62a;&#x64f;&#x628;&#x64f;&#x648;&#x647;&#x64f; &#x6da; 
</div>


<pagebreak />

<h3>What is "correct"?</h3>
<p>There are a number of factors which determine how the input text is displayed in an application.</p>
<p>The font\'s capabilities/design (this example shows the same text input shown in 2 fonts):</p>

<div class="example">FreeSerif: <span lang="hi" style="font-size: 18pt">&#x930;&#x94d;&#x926;&#x94d;&#x92e;&#x93f;</span>
and FreeSans font:
<span  style="font-family:FreeSans; font-size: 18pt">&#x930;&#x94d;&#x926;&#x94d;&#x92e;&#x93f;</span>
</div>

<p>Complex scripts require a "shaping engine" to re-order glyphs and apply the OTL features by syllable. MS Word and Wordpad run on the Windows platform use "Uniscribe", whereas some browsers such as FireFox and OpenOffice use Pango/HarfBuzz. The different shaping engines (and indeed different versions of them) can produce different results.</p>

<p>Different applications have different defaults (on/off) for some of the features e.g. kerning.</p>

<p>When testing mPDF 6, if text does not appear as you expect, ensure that the font is installed on your computer, and view the HTML in a browser. Also try copying/pasting the text into Wordpad/Word/OpenOffice and ensure that the correct font has been applied.</p>

<p>Note that Wordpad sometimes substitutes a different font if it does not like the one you have chosen, and does not even indicate that the substitution has occurred.</p>





<h3>CSS control of font features</h3>
<p>See <a href="http://www.w3.org/TR/css3-fonts/#font-rend-props">http://www.w3.org/TR/css3-fonts/#font-rend-props</a> for information about CSS3 and font-features.</p>


<p>The following are supported in mPDF 6:</p>
<ul>
<li>font-variant-position</li>
<li>font-variant-caps</li>
<li>font-variant-ligatures</li>
<li>font-variant-numeric</li>
<li>font-variant-alternates - Only [normal | historical-forms] supported (i.e. most are NOT supported)<br />
e.g. stylistic, styleset, character-variant, swash, ornaments, annotation (use font-feature-settings for these)</li>
<li>font-variant - as above, and except for: east-asian-variant-values, east-asian-width-values, ruby</li>
<li>font-language-override</li>
<li>font-feature-settings</li>
</ul>

<p>font-variant-east-asian is NOT supported</p>
<p>NB @font-face is NOT supported</p>
<p>NB @font-feature-values is NOT supported</p>

<p>Note that font-variant specifies a single property in CSS2, whereas in CSS3 it has become a shorthand for all the other font-variant-* properties. <span class="code">font-variant: small-caps</span> was the only one supported in mPDF &lt;v6, and will still work in mPDF 6.</p>

<p>See notes later about font kerning.</p>

<h4>Some examples</h4>
<p class="code">
/* use small-cap alternate glyphs */<br />
.smallcaps { font-feature-settings: "smcp" on; }<br />
<br />
/* convert both upper and lowercase to small caps (affects punctuation also) */<br />
.allsmallcaps { font-feature-settings: "c2sc", "smcp"; }<br />
<br />
/* enable historical forms */<br />
.hist { font-feature-settings: "hist"; }<br />
<br />
/* disable common ligatures, usually on by default */<br />
.noligs { font-feature-settings: "liga" 0; }<br />
<br />
/* enable tabular (monospaced) figures */<br />
td.tabular { font-feature-settings: "tnum"; }<br />
<br />
/* enable automatic fractions */<br />
.fractions { font-feature-settings: "frac"; }<br />
<br />
/* use the second available swash character */<br />
.swash { font-feature-settings: "swsh" 2; }<br />
<br />
/* enable stylistic set 7 */<br />
.fancystyle {<br />
  font-family: Gabriola; /* available on Windows 7, and on Mac OS */<br />
  font-feature-settings: "ss07";<br />
}
</p>




<pagebreak />
<h3>More Examples</h3>
<p><i>Note the automatic line breaking used in Lao, Thai, Khmer and Tibetan text.</i></p>
SYRIAC - Estrangelo Edessa
<div style="font-family:\'Estrangelo Edessa\'; font-size: 16pt; direction: rtl;">
    &#x718;&#x72c;&#x718;&#x712; &#x710;&#x72c;&#x71f;&#x722;&#x71d;&#x72c; &#x717;&#x715;&#x710; &#x715;&#x71d;&#x72a;&#x710; &#x729;&#x715;&#x71d;&#x72b;&#x72c;&#x710; &#x712;&#x72b;&#x721; &#x729;&#x715;&#x71d;&#x72b;&#x710; &#x725;&#x722;&#x718;&#x71d;&#x710; &#x721;&#x72a;&#x71d; &#x710;&#x718;&#x713;&#x71d;&#x722; &#x715;&#x710;&#x72c;&#x710; &#x717;&#x331;&#x718;&#x710; &#x721;&#x722; &#x721;&#x728;&#x72a;&#x71d;&#x722; &#x706; &#x725;&#x720; &#x712;&#x719;&#x712;&#x722; &#x729;&#x72a;&#x712;&#x710; &#x710;&#x71d;&#x72c;&#x71d;&#x718; &#x715;&#x71d;&#x72a;&#x308;&#x71d;&#x710; (&#x710;&#x71d;&#x71f; &#x72c;&#x72b;&#x725;&#x71d;&#x72c;&#x710; &#x72c;&#x718;&#x715;&#x71d;&#x72c;&#x722;&#x71d;&#x72c;&#x710;) &#x71a;&#x715;&#x71f;&#x721;&#x710; &#x713;&#x72a;&#x308;&#x721;&#x710; &#x715;&#x729;&#x715;&#x71d;&#x72b;&#x710; &#x721;&#x722; &#x715;&#x71d;&#x72a;&#x710; &#x715;&#x721;&#x72a;&#x71d; &#x710;&#x718;&#x713;&#x71d;&#x722; &#x712;&#x71b;&#x718;&#x72a;&#x710; &#x715; &#x710;&#x71d;&#x719;&#x720;&#x710; &#x715;&#x722;&#x726;&#x720; &#x712;&#x721;&#x715;&#x712;&#x72a;&#x710; &#x715; &#x722;&#x728;&#x71d;&#x712;&#x71d;&#x722; &#x725;&#x720; &#x72c;&#x71a;&#x718;&#x721;&#x710; &#x715; &#x729;&#x721;&#x72b;&#x720;&#x71d;. &#x718;&#x72c;&#x718;&#x712; &#x710;&#x72c;&#x71f;&#x722;&#x71d;&#x72c; &#x715;&#x71d;&#x72a;&#x710; &#x715; &#x719;&#x725;&#x726;&#x72a;&#x710;&#x722; &#x710;&#x718; &#x71f;&#x718;&#x72a;&#x71f;&#x721;&#x710; &#x712;&#x72b;&#x721; &#x721;&#x72a;&#x71d; (&#x72b;&#x720;&#x71d;&#x721;&#x718;&#x722;) &#x715;&#x71d;&#x72a;&#x71d;&#x710; &#x715;&#x72b;&#x72c;&#x710;&#x723; &#x720;&#x715;&#x71d;&#x72a;&#x710; &#x712;&#x72b;&#x722;&#x72c; 473 &#x721;.
</div>


MYANMAR (Burmese)
Padauk Book (SIL Font)
<div style="font-family:\'Padauk Book\'; font-size: 12pt;">
&#x1019;&#x103c;&#x1014;&#x103a;&#x200b;&#x1019;&#x102c;&#x1021;&#x1001;&#x1031;&#x102b;&#x103a; &#x1010;&#x101b;&#x102c;&#x1038;&#x101d;&#x1004;&#x103a;&#x200b;&#x1021;&#x102c;&#x1038;&#x200b;&#x1016;&#x103c;&#x1004;&#x1037;&#x103a; &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f; &#x101e;&#x1019;&#x1039;&#x1019;&#x1010; &#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x101e;&#x100a;&#x103a; &#x1021;&#x101b;&#x103e;&#x1031;&#x1037;&#x1010;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1021;&#x102c;&#x200b;&#x101b;&#x103e;&#x1010;&#x103d;&#x1004;&#x103a; &#x1027;&#x200b;&#x101b;&#x102d;&#x200b;&#x101a;&#x102c;&#x200b;&#x1021;&#x102c;&#x1038;&#x200b;&#x1016;&#x103c;&#x1004;&#x1037;&#x103a; &#x1012;&#x102f;&#x1010;&#x102d;&#x101a; &#x1021;&#x1000;&#x103b;&#x101a;&#x103a;&#x200b;&#x101d;&#x1014;&#x103a;&#x1038;&#x200b;&#x1006;&#x102f;&#x1036;&#x1038;[&#x1041;] &#x1010;&#x102d;&#x102f;&#x1004;&#x103a;&#x1038;&#x200b;&#x1015;&#x103c;&#x100a;&#x103a; &#x1016;&#x103c;&#x1005;&#x103a;&#x200b;&#x101e;&#x100a;&#x103a;&#x104b; &#x1041;&#x1049;&#x1044;&#x1048; &#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1007;&#x1014;&#x103a;&#x1014;&#x101d;&#x102b;&#x101b;&#x102e; &#x1044; &#x101b;&#x1000;&#x103a;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a; &#x1002;&#x101b;&#x102d;&#x1010;&#x103a;&#x200b;&#x1017;&#x103c;&#x102d;&#x200b;&#x1010;&#x102d;&#x1014;&#x103a;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x1004;&#x1036;&#x1011;&#x1036;&#x200b;&#x1019;&#x103e; (&#x1021;&#x1004;&#x103a;&#x1039;&#x1002;&#x101c;&#x102d;&#x1015;&#x103a;&#x200b;&#x101c;&#x102d;&#x102f; "Myanmar" &#x1021;&#x1016;&#x103c;&#x1005;&#x103a;&#x200b;&#x1014;&#x103e;&#x1004;&#x1037;&#x103a;) &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f;&#x200b;&#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x1016;&#x103c;&#x1005;&#x103a; &#x101c;&#x103d;&#x1010;&#x103a;&#x101c;&#x1015;&#x103a;&#x200b;&#x101b;&#x1031;&#x1038;&#x200b;&#x1000;&#x102d;&#x102f; &#x101b;&#x200b;&#x101b;&#x103e;&#x102d;&#x200b;&#x1001;&#x1032;&#x1037;&#x200b;&#x101e;&#x100a;&#x103a;&#x104b; &#x1014;&#x1031;&#x102c;&#x1000;&#x103a;&#x200b;&#x1015;&#x102d;&#x102f;&#x1004;&#x103a;&#x1038;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a; &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f; &#x1006;&#x102d;&#x102f;&#x200b;&#x101b;&#x103e;&#x101a;&#x103a;&#x200b;&#x101c;&#x1005;&#x103a; &#x101e;&#x1019;&#x1039;&#x1019;&#x1010;&#x200b;&#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x1016;&#x103c;&#x1005;&#x103a; &#x1041;&#x1049;&#x1047;&#x1044; &#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1007;&#x1014;&#x103a;&#x1014;&#x101d;&#x102b;&#x101b;&#x102e; &#x1044; &#x101b;&#x1000;&#x103a;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a;&#x200b;&#x101c;&#x100a;&#x103a;&#x1038;&#x1000;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038;&#x104a; &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f; &#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x1016;&#x103c;&#x1005;&#x103a; &#x1041;&#x1049;&#x1048;&#x1048;&#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1005;&#x1000;&#x103a;&#x1010;&#x1004;&#x103a;&#x1018;&#x102c; &#x1042;&#x200b;&#x1043; &#x101b;&#x1000;&#x103a;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a;&#x200b;&#x101c;&#x100a;&#x103a;&#x1038;&#x1000;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038;&#x104a; &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f; &#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x1016;&#x103c;&#x1005;&#x103a; &#x1041;&#x1049;&#x1048;&#x1049; &#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1007;&#x103d;&#x1014;&#x103a; &#x1041;&#x200b;&#x1048; &#x101b;&#x1000;&#x103a;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a; &#x101c;&#x100a;&#x103a;&#x1038;&#x1000;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038; &#x1021;&#x1019;&#x100a;&#x103a;&#x200b;&#x1019;&#x103b;&#x102c;&#x1038;&#x200b;&#x1015;&#x103c;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038;&#x101c;&#x1032;&#x200b;&#x1001;&#x1032;&#x1037;&#x200b;&#x101e;&#x100a;&#x103a;&#x104b; &#x1021;&#x102c;&#x100f;&#x102c;&#x200b;&#x101b;&#x200b;&#x1005;&#x1005;&#x103a;&#x200b;&#x1021;&#x1005;&#x102d;&#x102f;&#x1038;&#x101b;&#x200b;&#x1021;&#x102c;&#x1038; &#x1021;&#x101e;&#x102d;&#x200b;&#x1021;&#x1019;&#x103e;&#x1010;&#x103a; &#x1019;&#x200b;&#x1015;&#x103c;&#x102f;&#x200b;&#x101e;&#x1031;&#x102c; &#x1021;&#x1016;&#x103d;&#x1032;&#x1037;&#x200b;&#x1021;&#x1005;&#x100a;&#x103a;&#x1038;&#x200b;&#x1019;&#x103b;&#x102c;&#x1038;&#x200b;&#x1000; &#x1018;&#x102c;&#x1038;&#x200b;&#x1019;&#x102c;&#x1038; ("Burma") &#x101f;&#x102f;&#x200b;&#x101e;&#x102c; &#x1021;&#x101e;&#x102d;&#x200b;&#x1021;&#x1019;&#x103e;&#x1010;&#x103a;&#x1015;&#x103c;&#x102f;&#x200b; &#x101e;&#x102f;&#x1036;&#x1038;&#x1005;&#x103d;&#x1032;&#x1001;&#x1032;&#x1037;&#x200b;&#x200b;&#x101e;&#x100a;&#x103a;&#x104b; &#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x101c;&#x1036;&#x1000;&#x102d;&#x102f;&#x101c;&#x100a;&#x103a;&#x1038; &#x101a;&#x1001;&#x1004;&#x103a; &#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a; &#x1021;&#x1031;&#x1038;&#x1001;&#x103b;&#x1019;&#x103a;&#x1038;&#x101e;&#x102c;&#x101a;&#x102c;&#x101b;&#x1031;&#x1038;&#x1014;&#x103e;&#x1004;&#x1037;&#x103a; &#x1016;&#x103d;&#x1036;&#x1037;&#x1016;&#x103c;&#x102d;&#x102f;&#x1038;&#x101b;&#x1031;&#x1038;&#x1000;&#x1031;&#x102c;&#x1004;&#x103a;&#x1005;&#x102e;&#x1021;&#x1005;&#x102d;&#x102f;&#x1038;&#x101b;&#x200b;&#x101c;&#x1000;&#x103a;&#x1011;&#x1000;&#x103a; &#x1042;&#x200b;&#x1040;&#x2060;&#x1040;&#x200b;&#x1048; &#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1016;&#x103d;&#x1032;&#x1037;&#x200b;&#x1005;&#x100a;&#x103a;&#x1038;&#x200b;&#x1015;&#x102f;&#x1036; &#x1021;&#x1001;&#x103c;&#x1031;&#x200b;&#x1001;&#x1036; &#x1025;&#x1015;&#x1012;&#x1031;&#x1010;&#x103d;&#x1004;&#x103a; &#x1015;&#x103c;&#x100b;&#x1039;&#x100c;&#x102c;&#x1014;&#x103a;&#x1038;&#x200b;&#x1011;&#x102c;&#x1038;&#x200b;&#x101e;&#x100a;&#x1037;&#x103a; &#x1015;&#x103c;&#x100a;&#x103a;&#x1011;&#x1031;&#x102c;&#x1004;&#x103a;&#x200b;&#x1005;&#x102f; &#x101e;&#x1019;&#x1039;&#x1019;&#x1010; &#x1019;&#x103c;&#x1014;&#x103a;&#x1019;&#x102c;&#x200b;&#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x200b;&#x1004;&#x1036;&#x1010;&#x1031;&#x102c;&#x103a;&#x200b;&#x1021;&#x101c;&#x1036; &#x1016;&#x103c;&#x1004;&#x1037;&#x103a; &#x1042;&#x200b;&#x1040;&#x200b;&#x1041;&#x200b;&#x1040; &#x1001;&#x102f;&#x200b;&#x1014;&#x103e;&#x1005;&#x103a; &#x1021;&#x1031;&#x102c;&#x1000;&#x103a;&#x1010;&#x102d;&#x102f;&#x1018;&#x102c;&#x200b;&#x101c; &#x1042;&#x200b;&#x1041; &#x101b;&#x1000;&#x103a;&#x200b;&#x1014;&#x1031;&#x1037;&#x200b;&#x1010;&#x103d;&#x1004;&#x103a; &#x1021;&#x101c;&#x1036;&#x1005;&#x1010;&#x1004;&#x103a;&#x200b;&#x101c;&#x103d;&#x103e;&#x1004;&#x1037;&#x103a;&#x200b;&#x1011;&#x1030;&#x200b;&#x1001;&#x103c;&#x1004;&#x103a;&#x1038; &#x1021;&#x1001;&#x1019;&#x103a;&#x1038;&#x1021;&#x1014;&#x102c;&#x1038;&#x200b;&#x1019;&#x103b;&#x102c;&#x1038;&#x200b;&#x1000;&#x102d;&#x102f; &#x1014;&#x102d;&#x102f;&#x1004;&#x103a;&#x1004;&#x1036;&#x200b;&#x1010;&#x200b;&#x101d;&#x103e;&#x1019;&#x103a;&#x1038; &#x1000;&#x103b;&#x1004;&#x103a;&#x1038;&#x1015;&#x200b;&#x1000;&#x102c; &#x1021;&#x1005;&#x102c;&#x1038;&#x1011;&#x102d;&#x102f;&#x1038;&#x104d; &#x1015;&#x103c;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038;&#x101c;&#x1032; &#x1021;&#x101e;&#x102f;&#x1036;&#x1038;&#x1001;&#x1032;&#x1037;&#x1015;&#x103c;&#x102f;&#x200b;&#x101e;&#x100a;&#x103a;&#x104b;
</div>

KHMER
<div style="font-family:\'Khmer OS\'; ">
&#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A;
&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;
&#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7;
</div>
<div style="font-family:\'Khmer OS\'; ">
&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17d2;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1794;&#x17b6;&#x1793;&#x200b;&#x179f;&#x17d2;&#x179b;&#x17b6;&#x1794;&#x17cb;&#x200b;&#x178a;&#x17c4;&#x1799;&#x200b;&#x1782;&#x17d2;&#x179a;&#x17b6;&#x1794;&#x17cb;&#x1780;&#x17b6;&#x17c6;&#x1797;&#x17d2;&#x179b;&#x17be;&#x1784;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17b7;&#x1784;&#x200b;&#x1794;&#x17b8;&#x1793;&#x17b6;&#x1780;&#x17cb;&#x200b;&#x1795;&#x17d2;&#x179f;&#x17c1;&#x1784;&#x1791;&#x17c0;&#x178f;&#x200b;&#x179a;&#x1784;&#x179a;&#x1794;&#x17bd;&#x179f; &#x1793;&#x17c5;&#x1780;&#x17d2;&#x1793;&#x17bb;&#x1784;&#x200b;&#x1780;&#x17b6;&#x179a;&#x1794;&#x17d2;&#x179a;&#x1788;&#x1798;&#x200b;&#x1798;&#x17bb;&#x1781;&#x200b;&#x178a;&#x17b6;&#x1780;&#x17cb;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x200b;&#x178a;&#x17b6;&#x1785;&#x17cb;&#x178a;&#x17c4;&#x1799;&#x17a1;&#x17c2;&#x1780;&#x200b;&#x1782;&#x17d2;&#x1793;&#x17b6;&#x1798;&#x17bd;&#x1799; &#x179a;&#x179c;&#x17b6;&#x1784;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798;&#x200b;&#x1799;&#x17bb;&#x179c;&#x1787;&#x1793;&#x200b;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1780;&#x17d2;&#x179a;&#x17bb;&#x1798; &#x1787;&#x17b6;&#x1798;&#x17bd;&#x1799;&#x200b;&#x1794;&#x17c9;&#x17bc;&#x179b;&#x17b7;&#x179f; &#x1793;&#x17c5;&#x200b;&#x1798;&#x17d2;&#x178f;&#x17bb;&#x17c6;&#x200b;&#x179f;&#x17d2;&#x1796;&#x17b6;&#x1793;&#x200b;&#x1780;&#x17d2;&#x1794;&#x17b6;&#x179b;&#x1790;&#x17d2;&#x1793;&#x179b;&#x17cb;&#x17d4; &#x1793;&#x17c1;&#x17c7;&#x200b;&#x1794;&#x17be;&#x178f;&#x17b6;&#x1798;&#x200b;&#x1796;&#x17d0;&#x178f;&#x17cc;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1796;&#x17b8;&#x200b;&#x179b;&#x17c4;&#x1780;&#x200b; &#x1785;&#x17b6;&#x1793;&#x17cb; &#x179f;&#x17b6;&#x179c;&#x17c9;&#x17c1;&#x178f; &#x1798;&#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8;&#x200b;&#x179f;&#x17ca;&#x17be;&#x1794;&#x17a2;&#x1784;&#x17d2;&#x1780;&#x17c1;&#x178f;&#x200b;&#x179a;&#x1794;&#x179f;&#x17cb;&#x200b;&#x17a2;&#x1784;&#x17d2;&#x1782;&#x1780;&#x17b6;&#x179a;&#x200b;&#x179f;&#x17b7;&#x1791;&#x17d2;&#x1792;&#x17b7;&#x1798;&#x1793;&#x17bb;&#x179f;&#x17d2;&#x179f;&#x200b;&#x17a2;&#x17b6;&#x178a;&#x17a0;&#x17bb;&#x1780; &#x178a;&#x17c2;&#x179b;&#x200b;&#x179c;&#x178f;&#x17d2;&#x178f;&#x1798;&#x17b6;&#x1793;&#x200b;&#x1793;&#x17c5;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x1793;&#x17d2;&#x179b;&#x17c2;&#x1784;&#x200b;&#x1780;&#x17be;&#x178f;&#x17a0;&#x17c1;&#x178f;&#x17bb; &#x1793;&#x17c5;&#x200b;&#x1799;&#x1794;&#x17cb;&#x200b;&#x1790;&#x17d2;&#x1784;&#x17c3;&#x200b;&#x17a2;&#x17b6;&#x1791;&#x17b7;&#x178f;&#x17d2;&#x1799;&#x200b;&#x1791;&#x17b8; &#x17e1;&#x17e5; &#x1780;&#x1789;&#x17d2;&#x1789;&#x17b6;&#x1793;&#x17c1;&#x17c7;&#x17d4;
</div>


HEBREW - with Niqud and T\'amim (cantillation)
<div dir="rtl" style="font-size: 14pt; font-family: \'Taamey David CLM\'">
&#x5dc;&#x5b8;&#x5db;&#x5b5;&#x5a4;&#x5df; &#x5d7;&#x5b7;&#x5db;&#x5bc;&#x5d5;&#x5bc;&#x5be;&#x5dc;&#x5b4;&#x5d9;&#x599; &#x5e0;&#x5b0;&#x5d0;&#x5bb;&#x5dd;&#x5be;&#x5d9;&#x5b0;&#x5d4;&#x5d5;&#x5b8;&#x594;&#x5d4; &#x5dc;&#x5b0;&#x5d9;&#x596;&#x5d5;&#x5b9;&#x5dd; &#x5e7;&#x5d5;&#x5bc;&#x5de;&#x5b4;&#x5a3;&#x5d9; &#x5dc;&#x5b0;&#x5e2;&#x5b7;&#x591;&#x5d3; &#x5db;&#x5bc;&#x5b4;&#x5a3;&#x5d9; &#x5de;&#x5b4;&#x5e9;&#x5c1;&#x5b0;&#x5e4;&#x5bc;&#x5b8;&#x5d8;&#x5b4;&#x5d9;&#x5a9; &#x5dc;&#x5b6;&#x5d0;&#x5b1;&#x5e1;&#x5b9;&#x5a8;&#x5e3; &#x5d2;&#x5bc;&#x5d5;&#x5b9;&#x5d9;&#x5b4;&#x59c;&#x5dd; &#x5dc;&#x5b0;&#x5e7;&#x5b8;&#x5d1;&#x5b0;&#x5e6;&#x5b4;&#x5a3;&#x5d9; &#x5de;&#x5b7;&#x5de;&#x5b0;&#x5dc;&#x5b8;&#x5db;&#x597;&#x5d5;&#x5b9;&#x5ea; &#x5dc;&#x5b4;&#x5e9;&#x5c1;&#x5b0;&#x5e4;&#x5bc;&#x5b9;&#x5a8;&#x5da;&#x5b0; &#x5e2;&#x5b2;&#x5dc;&#x5b5;&#x5d9;&#x5d4;&#x5b6;&#x5a4;&#x5dd; &#x5d6;&#x5b7;&#x5e2;&#x5b0;&#x5de;&#x5b4;&#x5d9;&#x599; &#x5db;&#x5bc;&#x5b9;&#x59a;&#x5dc; &#x5d7;&#x5b2;&#x5e8;&#x5a3;&#x5d5;&#x5b9;&#x5df; &#x5d0;&#x5b7;&#x5e4;&#x5bc;&#x5b4;&#x594;&#x5d9; &#x5db;&#x5bc;&#x5b4;&#x59a;&#x5d9; &#x5d1;&#x5bc;&#x5b0;&#x5d0;&#x5b5;&#x5a3;&#x5e9;&#x5c1; &#x5e7;&#x5b4;&#x5e0;&#x5b0;&#x5d0;&#x5b8;&#x5ea;&#x5b4;&#x594;&#x5d9; &#x5ea;&#x5bc;&#x5b5;&#x5d0;&#x5b8;&#x5db;&#x5b5;&#x596;&#x5dc; &#x5db;&#x5bc;&#x5b8;&#x5dc;&#x5be;&#x5d4;&#x5b8;&#x5d0;&#x5b8;&#x5bd;&#x5e8;&#x5b6;&#x5e5;&#x5c3;
</div>


NKo 
<div style="font-family:DejaVuSans; font-size: 12pt; direction: rtl">
&#x7df;&#x7d0;&#x7ec;&#x7dd;&#x7cb;&#x7f2; &#x7d3;&#x7cd;&#x7ef; &#x7df;&#x7ca;&#x7dd;&#x7cb;&#x7f2; &#x7ca;&#x7e1;&#x7cb;&#x7d9;&#x7cc;&#x7de; &#x7d2;&#x7de;&#x7cf; &#x7d8;&#x7cc;&#x7f2;&#x7de;&#x7cf; &#x7d3;&#x7cd;&#x7ee; &#x7db;&#x7ce;&#x7ec;&#x7e3;&#x7ce;&#x7f2;&#x7e3;&#x7cc;&#x7f2; &#x7e0;&#x7cb; &#x7ca;&#x7f2; &#x7de;&#x7ca;&#x7ec;&#x7d9;&#x7ca;&#x7f2;&#x7ec;&#x7e1;&#x7d0;&#x7ef; &#x7d3;&#x7ce;&#x7d3;&#x7ca;&#x7de;&#x7ca;&#x7ef;&#x7d9;&#x7cc;&#x7eb; &#x7d6;&#x7ca;&#x7ec;&#x7de;&#x7cc;&#x7ec;&#x7d5;&#x7cb;&#x7eb; &#x7dd;&#x7ca;&#x7ec; &#x7df;&#x7ca;&#x7eb; &#x7db;&#x7cf;&#x7e1;&#x7ca;&#x7e6;&#x7df;&#x7cd;&#x7e1;&#x7ca;&#x7f2; &#x7e0;&#x7ca;&#x7eb; &#x7d1; &#x7eb;&#x7ca;&#x7f2; &#x7d3;&#x7cd;&#x7ee; &#x7e1;&#x7ca;&#x7ec;&#x7d9;&#x7cc; &#x7eb; &#x7d8;&#x7ca;&#x7df;&#x7cc;&#x7df;&#x7ca;&#x7eb; &#x7ca;&#x7ec; &#x7e6;&#x7ca;&#x7dd;&#x7ca; &#x7de;&#x7cd;&#x7eb; &#x7ca;&#x7ec; &#x7e1;&#x7ca;&#x7ec; &#x7f8; &#x7de;&#x7ec;&#x7ed; &#x7ca;&#x7ec; &#x7df;&#x7ca;&#x7e6;&#x7d9;&#x7d0; &#x7db;&#x7ce;&#x7e1;&#x7ca; &#x7ca;&#x7f2; &#x7e0;&#x7ca;&#x7eb; &#x7d8;&#x7ce;&#x7d3;&#x7ca; &#x7e6;&#x7cb; &#x7d5;&#x7ca;&#x7d3;&#x7ca;&#x7ef;&#x7d5;&#x7d0;&#x7eb; &#x7d3;&#x7cd;&#x7ef; &#x7e6;&#x7cb; &#x7eb; &#x7ca;&#x7df;&#x7ca;&#x7ec;&#x7e1;&#x7ca; &#x7e4;&#x7cc;&#x7e3;&#x7ca;&#x7eb; &#x7d8;&#x7ed;&#x7f5;&#x7ca;&#x7df;&#x7ce; &#x7d3;&#x7cd;&#x7ef; &#x7df;&#x7ca;&#x7ec;
</div>



THAANA 
<div style="font-family:\'Free Serif\'; font-size: 18pt; direction: rtl;">
&#x78b;&#x7a8;&#x788;&#x7ac;&#x780;&#x7a8; &#x788;&#x7a8;&#x786;&#x7a8;&#x795;&#x7a9;&#x791;&#x7a8;&#x787;&#x7a7; &#x78e;&#x7a6;&#x787;&#x7a8; &#x784;&#x7ad;&#x782;&#x7aa;&#x782;&#x7b0;&#x786;&#x7aa;&#x783;&#x7ac;&#x788;&#x7ad; &#x784;&#x7a6;&#x790;&#x7b0;&#x78c;&#x7a6;&#x787;&#x7b0; &#x787;&#x7ac;&#x787;&#x7b0;&#x78e;&#x7ae;&#x78c;&#x7a6;&#x781;&#x7b0; &#x78b;&#x7ac;&#x789;&#x7ac;&#x780;&#x7ac;&#x787;&#x7b0;&#x793;&#x7ad;&#x78c;&#x7af; &#x789;&#x7a6;&#x790;&#x7a6;&#x787;&#x7b0;&#x786;&#x7a6;&#x78c;&#x7b0; &#x786;&#x7aa;&#x783;&#x7aa;&#x782;&#x7b0;

&#x789;&#x7a8;&#x790;&#x7a7;&#x78d;&#x7a6;&#x786;&#x7a6;&#x781;&#x7b0; &#x797;&#x7a6;&#x787;&#x7a8;&#x782;&#x7a7; &#x787;&#x7a6;&#x781;&#x7b0; &#x790;&#x7a9;&#x782;&#x7aa;&#x786;&#x7a6;&#x783;&#x7a6; &#x787;&#x7a8;&#x782;&#x7b0;&#x791;&#x7a8;&#x794;&#x7a7; &#x787;&#x7a6;&#x781;&#x7b0; &#x780;&#x7a8;&#x782;&#x7b0;&#x78b;&#x7aa;&#x790;&#x7b0;&#x78c;&#x7a7;&#x782;&#x7b0;&#x60c; &#x787;&#x7a6;&#x78b;&#x7a8; &#x790;&#x7b0;&#x783;&#x7a9;&#x78d;&#x7a6;&#x782;&#x7b0;&#x786;&#x7a7; &#x787;&#x7a6;&#x781;&#x7b0; &#x787;&#x7ae;&#x785;&#x7aa;&#x78b;&#x7ab;&#x786;&#x7a6;&#x783;&#x7a6; &#x786;&#x7a8;&#x794;&#x7a7;&#x786;&#x7a8;&#x794;&#x7aa;&#x782;&#x7b0; &#x78b;&#x7a7;&#x787;&#x7a8;&#x789;&#x7a9; &#x78e;&#x7ae;&#x78c;&#x7ac;&#x787;&#x7b0;&#x78e;&#x7a6;&#x787;&#x7a8; &#x780;&#x7a8;&#x78a;&#x7ac;&#x780;&#x7ac;&#x787;&#x7b0;&#x793;&#x7aa;&#x789;&#x7a6;&#x781;&#x7b0; &#x789;&#x7a6;&#x790;&#x7a6;&#x787;&#x7b0;&#x786;&#x7a6;&#x78c;&#x7b0;&#x786;&#x7aa;&#x783;&#x7aa;&#x782;&#x7b0;! &#x789;&#x7a7;&#x782;&#x7a6;&#x787;&#x7a6;&#x786;&#x7a9; &#x787;&#x7ac;&#x787;&#x7b0;&#x78c;&#x7a6;&#x782;&#x7ac;&#x787;&#x7b0;&#x78e;&#x7a6;&#x787;&#x7a8; &#x790;&#x7a9;&#x782;&#x7aa;&#x786;&#x7a6;&#x783;&#x7a6; &#x787;&#x7a6;&#x782;&#x7ac;&#x787;&#x7b0; &#x78c;&#x7a6;&#x782;&#x7ac;&#x787;&#x7b0;&#x78e;&#x7a6;&#x787;&#x7a8; &#x797;&#x7a6;&#x787;&#x7a8;&#x782;&#x7a7; &#x789;&#x7a8;&#x78e;&#x7ae;&#x78c;&#x7a6;&#x781;&#x7b0; &#x784;&#x7ad;&#x782;&#x7aa;&#x782;&#x7b0; &#x782;&#x7aa;&#x786;&#x7aa;&#x783;&#x7aa;&#x782;&#x7b0;&#x60c; &#x787;&#x7a6;&#x78b;&#x7a8; &#x789;&#x7a8;&#x782;&#x7ab;&#x782;&#x7b0; &#x789;&#x7a8;&#x78a;&#x7a6;&#x78b;&#x7a6; &#x784;&#x7a6;&#x790;&#x7b0;&#x78c;&#x7a6;&#x787;&#x7b0; &#x788;&#x7ac;&#x790;&#x7b0; &#x789;&#x7a8;&#x787;&#x7aa;&#x79e;&#x7ab;&#x78d;&#x7a7; &#x79a;&#x7a8;&#x78d;&#x7a7;&#x78a;&#x7aa;&#x782;&#x7aa;&#x788;&#x7a7;&#x782;&#x7ad; &#x78e;&#x7ae;&#x78c;&#x7a6;&#x781;&#x7b0; &#x784;&#x7ad;&#x782;&#x7aa;&#x782;&#x7b0;&#x786;&#x7aa;&#x783;&#x7aa;&#x782;&#x7b0;!
&#x78b;&#x7a8;&#x788;&#x7ac;&#x780;&#x7a8; &#x788;&#x7a8;&#x786;&#x7a8;&#x795;&#x7a9;&#x791;&#x7a8;&#x787;&#x7a7;&#x78e;&#x7ac; &#x78c;&#x7ac;&#x783;&#x7ac;&#x787;&#x7a8;&#x782;&#x7b0; &#x78b;&#x7a8;&#x788;&#x7ac;&#x780;&#x7a8; &#x784;&#x7a6;&#x790;&#x7b0; &#x786;&#x7aa;&#x783;&#x7a8;&#x787;&#x7ac;&#x783;&#x7aa;&#x788;&#x7aa;&#x789;&#x7a6;&#x781;&#x7b0; &#x789;&#x7a6;&#x790;&#x7a6;&#x787;&#x7b0;&#x786;&#x7a6;&#x78c;&#x7b0; &#x786;&#x7aa;&#x783;&#x7aa;&#x782;&#x7b0;
</div>


LAO 
<div style="font-family:Dhyana; font-size: 12pt;">
&#xeaa;&#xeb2;&#xe97;&#xeb2;&#xea5;&#xeb0;&#xe99;&#xeb0;&#xea5;&#xeb1;&#xe94; &#xe9b;&#xeb0;&#xe8a;&#xeb2;&#xe97;&#xeb4;&#xe9b;&#xeb0;&#xec4;&#xe95; &#xe9b;&#xeb0;&#xe8a;&#xeb2;&#xe8a;&#xebb;&#xe99;&#xea5;&#xeb2;&#xea7; (&#xe84;&#xeb3;&#xec0;&#xe84;&#xebb;&#xec9;&#xeb2;: &#xeaa;&#xeb2;&#xe97;&#xeb2;&#xea3;&#xe99;&#xea3;&#xeb1;&#xe96;&#xe9b;&#xe8a;&#xeb2;&#xe97;&#xeb4;&#xe9b;&#xe95;&#xeb1;&#xe8d;&#xe9b;&#xe8a;&#xeb2;&#xe8a;&#xebb;&#xe99;&#xea5;&#xeb2;&#xea7;[&#xed1;]) &#xeab;&#xebc;&#xeb7; &#xeaa;&#xe9b;&#xe9b; &#xea5;&#xeb2;&#xea7; &#xe95;&#xeb1;&#xec9;&#xe87;&#xea2;&#xeb9;&#xec8;&#xe97;&#xeb4;&#xe94;&#xe95;&#xeb2;&#xec0;&#xea7;&#xeb1;&#xe99;&#xead;&#xead;&#xe81;&#xeaa;&#xebd;&#xe87;&#xec3;&#xe95;&#xec9;&#xe82;&#xead;&#xe87;&#xe97;&#xeb0;&#xea7;&#xeb5;&#xe9a;&#xead;&#xeb2;&#xe8a;&#xeb5;, &#xea2;&#xeb9;&#xec8;&#xec3;&#xe88;&#xe81;&#xeb2;&#xe87;&#xe82;&#xead;&#xe87;&#xec1;&#xeab;&#xebc;&#xea1;&#xead;&#xeb4;&#xe99;&#xe94;&#xeb9;&#xe88;&#xeb5;&#xe99;&#xea5;&#xeb0;&#xeab;&#xea7;&#xec8;&#xeb2;&#xe87;&#xec0;&#xeaa;&#xeb1;&#xec9;&#xe99;&#xe82;&#xeb0;&#xedc;&#xeb2;&#xe99;&#xe97;&#xeb5; 14 - 23 &#xead;&#xebb;&#xe87;&#xeaa;&#xeb2;&#xec0;&#xedc;&#xeb7;&#xead; &#xec1;&#xea5;&#xeb0; &#xec0;&#xeaa;&#xeb1;&#xec9;&#xe99;&#xec1;&#xea7;&#xe87;&#xe97;&#xeb5; 100-108 &#xead;&#xebb;&#xe87;&#xeaa;&#xeb2; &#xeaa;&#xe9b;&#xe9b; &#xea5;&#xeb2;&#xea7;&#xea1;&#xeb5;&#xec0;&#xe99;&#xeb7;&#xec9;&#xead;&#xe97;&#xeb5;&#xec8;&#xe97;&#xeb1;&#xe87;&#xedd;&#xebb;&#xe94; 236.800 &#xe95;&#xeb2;&#xea5;&#xeb2;&#xe87;&#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94; &#xec0;&#xe9b;&#xeb1;&#xe99;&#xe9b;&#xeb0;&#xec0;&#xe97;&#xe94;&#xe97;&#xeb5;&#xec8;&#xe9a;&#xecd;&#xec8;&#xea1;&#xeb5;&#xe97;&#xeb2;&#xe87;&#xead;&#xead;&#xe81;&#xeaa;&#xeb9;&#xec8;&#xe97;&#xeb0;&#xec0;&#xea5;, &#xea1;&#xeb5;&#xe8a;&#xeb2;&#xe8d;&#xec1;&#xe94;&#xe99;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a; &#xeaa;&#xeb2;&#xe97;&#xeb2;&#xea5;&#xeb0;&#xe99;&#xeb0;&#xea5;&#xeb1;&#xe94;&#xe9b;&#xeb0;&#xe8a;&#xeb2;&#xe8a;&#xebb;&#xe99;&#xe88;&#xeb5;&#xe99; (505 &#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94;), &#xe97;&#xeb4;&#xe94;&#xec3;&#xe95;&#xec9;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a;&#xea5;&#xeb2;&#xe8a;&#xeb0;&#xead;&#xeb2;&#xe99;&#xeb2;&#xe88;&#xeb1;&#xe81;&#xe81;&#xeb3;&#xe9b;&#xeb9;&#xec0;&#xe88;&#xe8d; (435 &#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94;), &#xe97;&#xeb4;&#xe94;&#xe95;&#xeb2;&#xec0;&#xea7;&#xeb1;&#xe99;&#xead;&#xead;&#xe81;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a; &#xeaa;&#xeb2;&#xe97;&#xeb2;&#xea5;&#xeb0;&#xe99;&#xeb0;&#xea5;&#xeb1;&#xe94;&#xeaa;&#xeb1;&#xe87;&#xe84;&#xebb;&#xea1;&#xe99;&#xeb4;&#xe8d;&#xebb;&#xea1;&#xeab;&#xea7;&#xebd;&#xe94;&#xe99;&#xeb2;&#xea1; ( 2.069 &#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94; ), &#xe97;&#xeb4;&#xe94;&#xe95;&#xeb2;&#xec0;&#xea7;&#xeb1;&#xe99;&#xe95;&#xebb;&#xe81;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a;&#xea5;&#xeb2;&#xe8a;&#xeb0;&#xead;&#xeb2;&#xe99;&#xeb2;&#xe88;&#xeb1;&#xe81;&#xec4;&#xe97; ( 1.385 &#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94; ), &#xec1;&#xea5;&#xeb0; &#xe97;&#xeb4;&#xe94;&#xe95;&#xeb2;&#xec0;&#xea7;&#xeb1;&#xe99;&#xe95;&#xebb;&#xe81;&#xeaa;&#xebd;&#xe87;&#xec0;&#xedc;&#xeb7;&#xead;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a; &#xeaa;&#xeb2;&#xe97;&#xeb2;&#xea5;&#xeb0;&#xe99;&#xeb0;&#xea5;&#xeb1;&#xe94;&#xec1;&#xeab;&#xec8;&#xe87;&#xeaa;&#xeb0;&#xeab;&#xeb0;&#xe9e;&#xeb2;&#xe9a;&#xea1;&#xebd;&#xe99;&#xea1;&#xeb2; ( 236 &#xe81;&#xeb4;&#xec2;&#xea5;&#xec1;&#xea1;&#xeb1;&#xe94; ), &#xeaa;.&#xe9b;.&#xe9b;.&#xea5;&#xeb2;&#xea7; &#xec0;&#xe9b;&#xeb1;&#xe99;&#xe9b;&#xeb0;&#xec0;&#xe97;&#xe94;&#xe94;&#xebd;&#xea7;&#xec3;&#xe99;&#xe9e;&#xeb2;&#xe81;&#xe9e;&#xeb7;&#xec9;&#xe99;&#xe99;&#xeb5;&#xec9;&#xe97;&#xeb5;&#xec8;&#xe9a;&#xecd;&#xec8;&#xea1;&#xeb5;&#xe8a;&#xeb2;&#xe8d;&#xec1;&#xe94;&#xe99;&#xe95;&#xeb4;&#xe94;&#xe81;&#xeb1;&#xe9a;&#xe97;&#xeb0;&#xec0;&#xea5;.
</div>

THAI
<div style="font-family:Garuda; font-size: 12pt;">
"&#xe0b;&#xe31;&#xe21;&#xe15;&#xe34;&#xe07;" &#xe40;&#xe1b;&#xe47;&#xe19;&#xe40;&#xe1e;&#xe25;&#xe07;&#xe02;&#xe2d;&#xe07;&#xe27;&#xe07;&#xe40;&#xe14;&#xe2d;&#xe30;&#xe1a;&#xe35;&#xe15;&#xe40;&#xe17;&#xe34;&#xe25;&#xe2a;&#xe4c; &#xe43;&#xe19;&#xe1b;&#xe35; &#xe04;.&#xe28;. 1969 &#xe40;&#xe1b;&#xe47;&#xe19;&#xe40;&#xe1e;&#xe25;&#xe07;&#xe17;&#xe35;&#xe48;&#xe1a;&#xe23;&#xe23;&#xe08;&#xe38;&#xe2d;&#xe22;&#xe39;&#xe48;&#xe43;&#xe19;&#xe2d;&#xe31;&#xe25;&#xe1a;&#xe31;&#xe49;&#xe21;&#xe0a;&#xe38;&#xe14; &#xe41;&#xe2d;&#xe1a;&#xe1a;&#xe35;&#xe42;&#xe23;&#xe14; &#xe40;&#xe1e;&#xe25;&#xe07;&#xe19;&#xe35;&#xe49;&#xe40;&#xe1b;&#xe47;&#xe19;&#xe40;&#xe1e;&#xe25;&#xe07;&#xe41;&#xe23;&#xe01;&#xe17;&#xe35;&#xe48;&#xe0b;&#xe34;&#xe07;&#xe40;&#xe01;&#xe34;&#xe25;&#xe2b;&#xe19;&#xe49;&#xe32;&#xe40;&#xe2d;&#xe17;&#xe35;&#xe48;&#xe08;&#xe2d;&#xe23;&#xe4c;&#xe08; &#xe41;&#xe2e;&#xe23;&#xe4c;&#xe23;&#xe34;&#xe2a;&#xe31;&#xe19;&#xe40;&#xe02;&#xe35;&#xe22;&#xe19; &#xe41;&#xe25;&#xe30;&#xe16;&#xe37;&#xe2d;&#xe40;&#xe1b;&#xe47;&#xe19;&#xe0b;&#xe34;&#xe07;&#xe40;&#xe01;&#xe34;&#xe25;&#xe41;&#xe23;&#xe01;&#xe02;&#xe2d;&#xe07;&#xe40;&#xe14;&#xe2d;&#xe30;&#xe1a;&#xe35;&#xe15;&#xe40;&#xe17;&#xe34;&#xe25;&#xe2a;&#xe4c;&#xe17;&#xe35;&#xe48;&#xe21;&#xe35;&#xe40;&#xe1e;&#xe25;&#xe07;&#xe17;&#xe35;&#xe48;&#xe21;&#xe35;&#xe2d;&#xe22;&#xe39;&#xe48;&#xe41;&#xe25;&#xe49;&#xe27;&#xe43;&#xe19;&#xe2d;&#xe31;&#xe25;&#xe1a;&#xe31;&#xe49;&#xe21;&#xe1a;&#xe23;&#xe23;&#xe08;&#xe38;&#xe2d;&#xe22;&#xe39;&#xe48;&#xe14;&#xe49;&#xe27;&#xe22; &#xe17;&#xe31;&#xe49;&#xe07;&#xe40;&#xe1e;&#xe25;&#xe07; "&#xe0b;&#xe31;&#xe21;&#xe15;&#xe34;&#xe07;" &#xe41;&#xe25;&#xe30;&#xe40;&#xe1e;&#xe25;&#xe07; "&#xe04;&#xe31;&#xe21;&#xe17;&#xe39;&#xe40;&#xe01;&#xe15;&#xe40;&#xe15;&#xe2d;&#xe23;&#xe4c;" &#xe17;&#xe35;&#xe48;&#xe2d;&#xe22;&#xe39;&#xe48;&#xe43;&#xe19;&#xe2d;&#xe31;&#xe25;&#xe1a;&#xe31;&#xe49;&#xe21; &#xe41;&#xe2d;&#xe1a;&#xe1a;&#xe35;&#xe42;&#xe23;&#xe14; &#xe41;&#xe25;&#xe30;&#xe40;&#xe1e;&#xe25;&#xe07; "&#xe0b;&#xe31;&#xe21;&#xe15;&#xe34;&#xe07;" &#xe16;&#xe37;&#xe2d;&#xe40;&#xe1b;&#xe47;&#xe19;&#xe40;&#xe1e;&#xe25;&#xe07;&#xe40;&#xe14;&#xe35;&#xe22;&#xe27;&#xe17;&#xe35;&#xe48;&#xe41;&#xe2e;&#xe23;&#xe4c;&#xe23;&#xe34;&#xe2a;&#xe31;&#xe19;&#xe41;&#xe15;&#xe48;&#xe07;&#xe41;&#xe25;&#xe49;&#xe27;&#xe02;&#xe36;&#xe49;&#xe19;&#xe2d;&#xe31;&#xe19;&#xe14;&#xe31;&#xe1a; 1 &#xe1a;&#xe19;&#xe0a;&#xe32;&#xe23;&#xe4c;&#xe15;&#xe2d;&#xe40;&#xe21;&#xe23;&#xe34;&#xe01;&#xe31;&#xe19;&#xe02;&#xe13;&#xe30;&#xe17;&#xe35;&#xe48;&#xe22;&#xe31;&#xe07;&#xe2d;&#xe22;&#xe39;&#xe48;&#xe43;&#xe19;&#xe27;&#xe07;&#xe40;&#xe14;&#xe2d;&#xe30;&#xe1a;&#xe35;&#xe15;&#xe40;&#xe17;&#xe34;&#xe25;&#xe2a;&#xe4c;
</div>



SINHALA
<div style="font-family:KaputaUnicode; font-size: 14pt;">
&#xdb1;&#xda9;&#xdad;&#xdca;&#xdad;&#xdd4; &#xd9a;&#xdcf;&#xdbb;&#xdca;&#xdba;&#xdba;&#xdb1;&#xdca; &#xdc0;&#xdd2;&#xd9a;&#xdd2;&#xdb4;&#xdd3;&#xda9;&#xdd2;&#xdba;&#xdcf;&#xdc0; &#xdb4;&#xdca;&#x200d;&#xdbb;&#xdc1;&#xdc3;&#xdca;&#xdad; &#xdb8;&#xda7;&#xdca;&#xda7;&#xdb8;&#xd9a;&#xdd2;&#xdb1;&#xdca; &#xdb4;&#xdc0;&#xdad;&#xdca;&#xdc0;&#xdcf; &#xd9c;&#xdd0;&#xdb1;&#xdd3;&#xdb8; &#xdc3;&#xdaf;&#xdc4;&#xdcf; &#xd85;&#xdad;&#xdca;&#x200d;&#xdba;&#xdc0;&#xdc1;&#xdca;&#x200d;&#xdba; &#xd85;&#xd82;&#xd9c;&#xdba;&#xd9a;&#xdd2;. &#xd8b;&#xdb4;&#xdaf;&#xdd9;&#xdc3;&#xdca; &#xdbb;&#xdd6;&#xdbb;&#xdcf;&#xdc0; &#xdb8;&#xd9c;&#xdd2;&#xdb1;&#xdca; &#xdad;&#xdc0;&#xdad;&#xdca; &#xd85;&#xdbd;&#xdd4;&#xdad;&#xdca; &#xdb1;&#xda9;&#xdad;&#xdca;&#xdad;&#xdd4; &#xd9a;&#xdcf;&#xdbb;&#xdca;&#xdba;&#xdba;&#xdb1;&#xdca; &#xdad;&#xdd0;&#xdb1;&#xdd3;&#xdb8;&#xdda;&#xdaf;&#xdd3; &#xdb4;&#xdbb;&#xdd2;&#xdc3;&#xdca;&#xdc3;&#xdb8;&#xdca; &#xdc0;&#xdd2;&#xdba; &#xdba;&#xdd4;&#xdad;&#xdd4;&#xdba;. &#xd94;&#xdb6;&#xda7; &#xdc4;&#xdd0;&#xd9a;&#xdd2; &#xdc0;&#xdd2;&#xda7; &#xdc3;&#xd82;&#xdc0;&#xdd2;&#xdb0;&#xdcf;&#xdb1;&#xdcf;&#xdad;&#xdca;&#xdb8;&#xd9a; &#xdc0;&#xdb1;&#xdca;&#xdb1;, &#xd91;&#xdc4;&#xdd9;&#xdad;&#xdca;  &#xdc3;&#xdd1;&#xdb8;&#xdc0;&#xdd2;&#xda7;&#xdb8; &#xdc0;&#xdd2;&#xdc1;&#xdcf;&#xdbd; &#xdb4;&#xdd2;&#xdb1;&#xdca;&#xdad;&#xdd6;&#xdbb;&#xdba; &#xd87;&#xdad;&#xdd4;&#xdbd;&#xdad;&#xdd2;&#xdb1;&#xdca; &#xdad;&#xdb6;&#xdcf; &#xd9c;&#xdb1;&#xdca;&#xdb1;: &#xd85;&#xdb4; &#xdb8;&#xdd9;&#xdad;&#xdd0;&#xdb1;&#xda7; &#xdb4;&#xdd0;&#xdb8;&#xdd2;&#xdab; &#xdc3;&#xdd2;&#xda7;&#xdd2;&#xdb1;&#xdca;&#xdb1;&#xdda; &#xdc0;&#xdd2;&#xdc1;&#xdca;&#xdc0;&#xd9a;&#xddd;&#xdc2;&#xdba;&#xd9a;&#xdca; &#xdad;&#xdd0;&#xdb1;&#xdd3;&#xdb8; &#xdc3;&#xdaf;&#xdc4;&#xdcf;&#xdba;&#xdd2;. &#xd94;&#xdb6; &#xdc3;&#xdd2;&#xdad;&#xdb1;&#xdca;&#xdb1;&#xdda;  &#xdb1;&#xda9;&#xdad;&#xdca;&#xdad;&#xdd4; &#xd9a;&#xdd2;&#xdbb;&#xdd3;&#xdb8;&#xdca;&#xdc0;&#xdbd;&#xda7; &#xdc0;&#xda9;&#xdcf; &#xd85;&#xdb1;&#xdd9;&#xd9a;&#xdd4;&#xdad;&#xdca; &#xdb4;&#xdd0;&#xdad;&#xdd2;&#xdc0;&#xdbd;&#xdd2;&#xdb1;&#xdca; &#xdc0;&#xdd2;&#xd9a;&#xdd2;&#xdb4;&#xdd3;&#xda9;&#xdd2;&#xdba;&#xdcf;&#xdc0;&#xda7;  &#xdaf;&#xdcf;&#xdba;&#xd9a; &#xdc0;&#xdd3;&#xdb8;&#xda7; &#xdb1;&#xdb8;&#xdca;, &#xd9a;&#xdbb;&#xdd0;&#xdab;&#xdcf;&#xd9a;&#xdbb; &#xdc0;&#xdd2;&#xd9a;&#xdd2;&#xdb4;&#xdd3;&#xda9;&#xdd2;&#xdba;&#xdcf;:&#xdc0;&#xdd2;&#xd9a;&#xdd2;&#xdb4;&#xdd3;&#xda9;&#xdd2;&#xdba;&#xdcf;&#xdc0;&#xda7; &#xdaf;&#xdcf;&#xdba;&#xd9a; &#xdc0;&#xdd3;&#xdb8; &#xdb6;&#xdbd;&#xdb1;&#xdca;&#xdb1;. &#xd89;&#xd9a;&#xdca;&#xdb8;&#xdb1;&#xdd2;&#xdb1;&#xdca; &#xd85;&#xdc0;&#xdc1;&#xdca;&#x200d;&#xdba; &#xdc3;&#xdc4; &#xd89;&#xdad;&#xdcf; &#xdc0;&#xdd0;&#xdaf;&#xd9c;&#xdad;&#xdca; &#xdc0;&#xdb1; &#xd9a;&#xdcf;&#xdbb;&#xdca;&#xdba;&#xdba;&#xdb1;&#xdca; &#xdc0;&#xdd2;&#xd9a;&#xdd2;&#xdb4;&#xdd3;&#xda9;&#xdd2;&#xdba;&#xdcf;:&#xd85;&#xdad;&#xdb4;&#xdc3;&#xdd4; &#xdc0;&#xdd6; &#xdc0;&#xdd0;&#xda9; &#xdc4;&#xdd3;&#xdaf;&#xdd2; &#xdc3;&#xddc;&#xdba;&#xdcf;&#xd9c;&#xdd0;&#xdb1;&#xdd3;&#xdb8;&#xda7; &#xd85;&#xdc0;&#xdc3;&#xdca;&#xdae;&#xdcf;&#xdc0; &#xd87;&#xdad;.
</div>


TIBETAN
<div style="font-family:Jomolhari; font-size: 16pt;">
&#xf04;&#xf0d;&#xf4f;&#xf51;&#xfb1;&#xf50;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf42;&#xf4f;&#xf7a;&#xf42;&#xf4f;&#xf7a;&#xf54;&#xf71;&#xf62;&#xf42;&#xf4f;&#xf7a;&#xf54;&#xf71;&#xf62;&#xf66;&#xf7e;&#xf42;&#xf4f;&#xf7a;&#xf56;&#xf7c;&#xf52;&#xf72;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;
&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf74;&#xf53;&#xf72;&#xf58;&#xf74;&#xf53;&#xf72;&#xf58;&#xf67;&#xf71;&#xf58;&#xf74;&#xf53;&#xf72;&#xf61;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xf72;&#xf52;&#xf7a;&#xf5d;&#xf71;&#xf67;&#xfb2;&#xf71;&#xf72;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf4e;&#xf72;&#xf54;&#xf51;&#xfa8;&#xf7a;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf7f;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0b;
&#xf56;&#xf5b;&#xfb2;&#xf42;&#xf74;&#xf62;&#xf74;&#xf54;&#xf51;&#xfa8;&#xf66;&#xf72;&#xf51;&#xfa2;&#xf72;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xf62;&#xf71;&#xf4e;&#xf72;&#xf5b;&#xfb2;&#xf72;&#xf5d;&#xf53;&#xf4f;&#xf72;&#xf61;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf5d;&#xf42;&#xf72;&#xf64;&#xf71;&#xf62;&#xf72;&#xf58;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf0b;
&#xf58;&#xf4e;&#xf72;&#xf54;&#xf51;&#xfa8;&#xf7a;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf56;&#xf5b;&#xfb2;&#xf54;&#xf71;&#xf53;&#xf72;&#xf67;&#xf71;&#xf74;&#xf7e;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf4f;&#xf71;&#xf62;&#xf7a;&#xf4f;&#xf74;&#xf4f;&#xf9f;&#xf71;&#xf62;&#xf7a;&#xf4f;&#xf74;&#xf62;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf57;&#xfb2;&#xf71;&#xf74;&#xf7e;&#xf0b;
&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf68;&#xf71;&#xf58;&#xfb2;&#xf72;&#xf4f;&#xf71;&#xf68;&#xf71;&#xf61;&#xf74;&#xf62;&#xfa1;&#xf51;&#xf7a;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;&#xf68;&#xf7c;&#xf7e;&#xf58;&#xf62;&#xf72;&#xf59;&#xfb1;&#xf7a;&#xf58;&#xf7e;&#xf66;&#xfad;&#xf71;&#xf67;&#xf71;&#xf0d;
</div>

TAI THAM
<div style="font-family:\'lanna alif\'; font-size: 16pt">
&#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a48;&#x1a63;&#x1a60;&#x1a45;&#x1a29;&#x1a60;&#x1a3f;&#x1a26;&#x1a49;&#x1a60;&#x1a3e;&#x1a72;&#x1a75; &#x1a49;&#x1a60;&#x1a3e;&#x1a6f;&#x1a37;&#x1a74;&#x1a75;&#x1a6c;&#x1a34;&#x1a6e;&#x1a62;&#x1a75;&#x1a63;&#x1a2f;&#x1a72;&#x1a20;&#x1a74;&#x1a76;&#x1a6c;&#x1a27;&#x1a61;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a48;&#x1a63;&#x1a60;&#x1a45;&#x1a43;&#x1a6f;&#x1a76;&#x1a60;&#x1a45; &#x1a32;&#x1a67;&#x1a60;&#x1a26;&#x1a45;&#x1a62;&#x1a60;&#x1a36;&#x1a3e;&#x1a66;&#x1a37;&#x1a75;&#x1a64;&#x1a60;&#x1a45;&#x1a3e;&#x1a63;&#x1a4b;&#x1a6f;&#x1a75;&#x1a60;&#x1a45;
&#x1a3e;&#x1a63;&#x1a4b;&#x1a6a;&#x1a76;&#x1a3e;&#x1a63;&#x1a2a;&#x1a6f;&#x1a60;&#x1a45; &#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a24;&#x1a6b;&#x1a60;&#x1a36;&#x1a43;&#x1a3b;&#x1a6a;&#x1a41; &#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a27;&#x1a61;&#x1a6e;&#x1a43;&#x1a65;&#x1a6c;&#x1a20;&#x1a4b;&#x1a6e;&#x1a62;&#x1a63;&#x1a39;&#x1a71; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a37;&#x1a75;&#x1a64;&#x1a60;&#x1a45;&#x1a29;&#x1a60;&#x1a3f;&#x1a26;&#x1a41;&#x1a63;&#x1a60;&#x1a3f;&#x1a29;&#x1a68;&#x1a75;&#x1a20;&#x1a6f;&#x1a76;&#x1a60;&#x1a45;&#x1a3e;&#x1a63;&#x1a43;&#x1a6a;&#x1a41; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a20;&#x1a6c;&#x1a26;&#x1a24;&#x1a6b;&#x1a60;&#x1a36;&#x1a3b;&#x1a6f;&#x1a75;&#x1a22;&#x1a60;&#x1a3f;&#x1a45;&#x1a28;&#x1a69;&#x1a36; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a24;&#x1a74;&#x1a63;&#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a3e;&#x1a6a;&#x1a41; &#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a48;&#x1a6b;&#x1a60;&#x1a3e;&#x1a4b;&#x1a76;&#x1a63;&#x1a60;&#x1a3f;&#x1a3e;&#x1a66; &#x1a3b;&#x1a6e;&#x1a65;&#x1a75;&#x1a60;&#x1a36;&#x1a37;&#x1a6c;&#x1a20;&#x1a45;&#x1a75;&#x1a64;&#x1a27;&#x1a61;&#x1a3e;&#x1a63;&#x1a22;&#x1a74;&#x1a6c;&#x1a22;&#x1a76;&#x1a63;&#x1a27;&#x1a6e;&#x1a62;&#x1a76;&#x1a63;&#x1a20;&#x1a74;&#x1a6c;&#x1a41;&#x1a74;&#x1a6c;&#x1a3e;&#x1a63;&#x1a43;&#x1a6f;&#x1a76;&#x1a60;&#x1a45;&#x1a38;&#x1a6e;&#x1a62;&#x1a60;&#x1a36;&#x1a38;&#x1a66;
</div>

<pagebreak odd-footer-name="myHTMLbengali" odd-footer-value="1" even-footer-name="myHTMLbengali" even-footer-value="1" pagenumstyle="bengali" />

<h3>Dictionary Line breaking</h3>
<p>Lao, Thai and Khmer text does not have space between words. By default, mPDF 6 uses word dictionaries to determine appropriate opportunites for line-breaks. Users may turn this function off using the configurable variable <span class="code">useDictionaryLBR</span>.</p>

<p>Alternatively users can insert the character U+200B (zero-width space) in the text to mark line-breaking opportunities manually.</p>

<p>Similarly for Tibetan script, mPDF 6 uses a simple algorithm to identify line-breaking opportunities after the characters U+0F0B (Tsheg) or U+0F0D. This can be overriden using the configurable variable <span class="code">useTibetanLBR</span>.</p>





<h3>Myanmar Fonts</h3>

<p>Myanmar (Burmese) on the web is quite frequently written for fonts which are not strictly unicode-compliant. This includes common applications such as WordPress and a number of official Burmese government websites.</p>
<p>Ayar fonts (http://www.ayarunicodegroup.org) are based on text input where the vowel preceeds the consonant (which is contrary to Unicode specification).
</p>
<p>ZawGyi-One is another very common font in use. This font has some characters incorrectly coded e.g. U+103A as U+1039.</p>
<p>There are also fonts available which are fully unicode compliant, such as Padauk, Tharlon, Myanmar3, and Microsoft\'s Myanmar Text.</p>
<p>As long as you select the right font for the input text, all of them work fine in mPDF:</p>


<p class="example" style="font-family: Tharlon; margin-bottom:0;">Tharlon: &#x1012;&#x102e;&#x101b;&#x1000;&#x103a;&#x1015;&#x102d;&#x102f;&#x1004;&#x103a;&#x1038;&#x1019;&#x103e;&#x102c; &#x1027;&#x101b;&#x102c;&#x1016;&#x1031;&#x102c;&#x1004;&#x1037;&#x103a;&#x1000;&#x102d;&#x102f; &#x101a;&#x1030;&#x1014;&#x102e;&#x1000;&#x102f;&#x1012;&#x103a;&#x1021;&#x1016;&#x103c;&#x1005;&#x103a; &#x101b;&#x100a;&#x103a;&#x100a;&#x103d;&#x103e;&#x1014;&#x103a;&#x1038;&#x1015;&#x103c;&#x1031;&#x102c;&#x1006;&#x102d;&#x102f;&#x1014;&#x1031;&#x1000;&#x103c;&#x1010;&#x102c; &#x1010;&#x103d;&#x1031;&#x1037;&#x101b;&#x101c;&#x102d;&#x102f;&#x1037; &#x1027;&#x101b;&#x102c;&#x101f;&#x102c; &#x101a;&#x1030;&#x1014;&#x102e;&#x1000;&#x102f;&#x1012;&#x103a; &#x1019;&#x1016;&#x103c;&#x1005;&#x103a;&#x1000;&#x103c;&#x1031;&#x102c;&#x1004;&#x103a;&#x1038;&#x1014;&#x1032;&#x1037; &#x1018;&#x102c;&#x101c;&#x102d;&#x102f;&#x1037;&#x1019;&#x1016;&#x103c;&#x1005;&#x103a;&#x101b;&#x1010;&#x102c;&#x101c;&#x1032;&#x1006;&#x102d;&#x102f;&#x1010;&#x102c; &#x1021;&#x1010;&#x102d;&#x102f;&#x1015;&#x1032; &#x101b;&#x103e;&#x1004;&#x103a;&#x1038;&#x1015;&#x102b;&#x1019;&#x101a;&#x103a;&#x104b; &#x101a;&#x1030;&#x1014;&#x102e;&#x1000;&#x102f;&#x1012;&#x103a;&#x1016;&#x103c;&#x1005;&#x103a;&#x1016;&#x102d;&#x102f;&#x1037; - &#x1041;&#x104b; &#x101a;&#x1030;&#x1014;&#x102e;&#x1000;&#x102f;&#x1012;&#x103a; &#x1000;&#x102f;&#x1012;&#x103a;&#x1015;&#x103d;&#x102d;&#x102f;&#x1004;&#x1037;&#x103a;&#x1014;&#x1032;&#x1037; &#x1000;&#x102d;&#x102f;&#x1000;&#x103a;&#x100a;&#x102e;&#x101b;&#x1015;&#x102b;&#x1019;&#x101a;&#x103a;&#x104b;
&#x1042;&#x104b; &#x101a;&#x1030;&#x1014;&#x102e;&#x1000;&#x102f;&#x1012;&#x103a; &#x1005;&#x102c;&#x101c;&#x102f;&#x1036;&#x1038;&#x1005;&#x102e;&#x1015;&#x102f;&#x1036; (Encoding) &#x1014;&#x1032;&#x1037; &#x1000;&#x102d;&#x102f;&#x1000;&#x103a;&#x100a;&#x102e;&#x101b;&#x1015;&#x102b;&#x1019;&#x101a;&#x103a;&#x104b;</p>

<div style="font-size: 0.85em">from http://www.myanmarlanguage.org/unicode</div>

<p class="example" style="font-family: zawgyi-one; margin-bottom:0;">Zawgyi-one: &#x1005;&#x102e;&#x1038;&#x1015;&#x103c;&#x102c;&#x1038;&#x1031;&#x101b;&#x1038;&#x1014;&#x103d;&#x1004;&#x1039;&#x1037;&#x1000;&#x1030;&#x1038;&#x101e;&#x1014;&#x1039;&#x1038;&#x1031;&#x101b;&#x102c;&#x1004;&#x1039;&#x1038;&#x101d;&#x101a;&#x1039;&#x1031;&#x101b;&#x1038;&#x101d;&#x1014;&#x1039;&#x107e;&#x1000;&#x102e;&#x1038;&#x100c;&#x102c;&#x1014; &#x103b;&#x1015;&#x100a;&#x1039;&#x1031;&#x1011;&#x102c;&#x1004;&#x1039;&#x1005;&#x102f;&#x101d;&#x1014;&#x1039;&#x107e;&#x1000;&#x102e;&#x1038; &#x1019;&#x108f;&#x1071;&#x1031;&#x101c;&#x1038;&#x1010;&#x102f;&#x102d;&#x1004;&#x1039;&#x1038;&#x1031;&#x1012;&#x101e;&#x107e;&#x1000;&#x102e;&#x1038; &#x1031;&#x1000;&#x103a;&#x1038;&#x101c;&#x1000;&#x1039;&#x1031;&#x1012;&#x101e; &#x1021;&#x1031;&#x101e;&#x1038;&#x1005;&#x102c;&#x1038; &#x1000;&#x102f;&#x1014;&#x1039;&#x1011;&#x102f;&#x1010;&#x1039;&#x101c;&#x102f;&#x1015;&#x1039;&#x1004;&#x1014;&#x1039;&#x1038;&#x1019;&#x103a;&#x102c;&#x1038; &#x107e;&#x1000;&#x100a;&#x1039;&#x1037;&#x101b;&#x103d;&#x1033;&#x1021;&#x102c;&#x1038;&#x1031;&#x1015;&#x1038;
</p>
<div style="font-size: 0.85em">from http://www.commerce.gov.mm/</div>


<p class="example" style="font-family: ayar; margin-bottom:0;">Ayar: WordPress &#x1010;&#x101b;&#x102c;&#x1038;&#x101d;&#x1004;&#x103a; &#x103c;&#x1019;&#x1014;&#x103a;&#x1019;&#x102c;&#x1018;&#x102c;&#x101e;&#x102c; &#x1005;&#x102c;&#x1019;&#x103b;&#x1000;&#x103a;&#x1014;&#x103e;&#x102c;&#x1019;&#x103e; &#x103c;&#x1000;&#x102d;&#x102f;&#x1006;&#x102d;&#x102f;&#x1015;&#x102b;&#x1010;&#x101a;&#x103a;&#x104b; !
&#x101b;&#x102c;&#x1014;&#x103e;&#x102f;&#x1014;&#x103a;&#x1038;&#x103c;&#x1015;&#x100a;&#x103a;&#x1037; &#x1018;&#x102c;&#x101e;&#x102c;&#x103c;&#x1015;&#x1014;&#x103a;&#x1011;&#x102c;&#x1038;&#x101e;&#x100a;&#x103a;&#x1037; WordPress &#x103c;&#x1019;&#x1014;&#x103a;&#x1019;&#x102c; &#x1018;&#x102c;&#x101e;&#x102c;&#x103c;&#x1015;&#x1014;&#x103a;&#x1019;&#x1030;&#x1000;&#x102d;&#x102f; &#x1017;&#x102c;&#x1038;&#x101b;&#x103e;&#x1004;&#x103a;&#x1038; &#x1043;.&#x1041; &#x103c;&#x1016;&#x1004;&#x103a;&#x1037; &#x1005;&#x1010;&#x1004;&#x103a; &#x103c;&#x1016;&#x1014;&#x103a;&#x1037;&#x1001;&#x103b;&#x102d;&#x101c;&#x102d;&#x102f;&#x1000;&#x103a;&#x103c;&#x1015;&#x102e;&#x1038;&#x101e;&#x100a;&#x103a;&#x1037;&#x1031;&#x1014;&#x102c;&#x1000;&#x103a; &#x1006;&#x1000;&#x103a;&#x101c;&#x1000;&#x103a;&#x104d; &#x1021;&#x1006;&#x1004;&#x103a;&#x1037;&#x103c;&#x1019;&#x103e;&#x1004;&#x103a;&#x1037;&#x1010;&#x1004;&#x103a;&#x1019;&#x103e;&#x102f; &#x1017;&#x102c;&#x1038;&#x101b;&#x103e;&#x1004;&#x103a;&#x1038;&#x1019;&#x103b;&#x102c;&#x1038;&#x1000;&#x102d;&#x102f; &#x1021;&#x1001;&#x103b;&#x102d;&#x1014;&#x103a;&#x1014;&#x103e;&#x1004;&#x103a;&#x1037;&#x1010;&#x1005;&#x103a;&#x1031;&#x103c;&#x1015;&#x1038;&#x100a;&#x102e; 
</p>
<div style="font-size: 0.85em">from https://mya.wordpress.org/</div>



<h3>lang selector</h3>
<p>mPDF 6 supports use of the lang selector in CSS. All of the following are supported:</p>
<ul>
<li>:lang(fr)</li>
<li>p:lang(fr)</li>
<li>span:lang("syr")</li>
<li>[lang="fr"]</li>
<li>[lang=\'fr\']</li>
<li>p[lang=fr]</li>
<li>p[lang="zh-TW"]</li>
</ul>

<p>Note: [lang=zh] will match lang="zh-TW" and lang="zh-HK"</p>

<p>Limitation: class selectors and attribute selectors should be of equal specificity in CSS specification e.g.
<p class="code">
:lang(syr) { color: blue; }<br />
.syriac { color: red; }
</p>
<p>should be of equal specificity, and thus apply whichever comes later in the CSS stylesheet. mPDF 6 however gives :lang priority over .class</p>

<p><b>The use of the lang attribute and CSS selector is now the recommended method for handling multi-lingual documents in mPDF 6.</b></p>

<h3>lang HTML attribute</h3>
<p>The HTML lang attribute has a number of uses:</p>
<ul>
<li>when OTL tables are being used for a font, the language from the lang attribute is used to select which OTL features are applied;</li>
<li>used in conjunction with CSS lang selector to allow CSS styles to be applied;</li>
<li>can be used in conjunction with <span class="code">autoLangToFont</span> and <span class="code">autoScriptToLang</span> (see below)</li>
</ul>
<p>IETF tags should be used for lang which comply with the following:</p>
<ul>
<li>a 2 or 3 letter Language code, followed optionally by</li>
<li>a hyphen and a 4 letter Script code, and or</li>
<li>a hyphen and a 2 letter Region code</li>

<li>i.e. [xx|xxx]{-Xxxx}{-XX}</li>
<li>mPDF deals with IETF tags as case insensitive</li>
</ul>


<pagebreak />

<h3>Automatic font selection</h3>

<p><i>Note: This functionality of mPDF has changed considerably in mPDF v6 and is not backwards compatible.</i></p>

<p>mPDF 6 has two functions which can be used together or separately:</p>

<p><span class="code">autoScriptToLang</span> - marks up HTML text using the lang attribute, based on the Unicode script block in question, and configurable values in <span class="code">config_script2lang.php</span>.</p>
<p><span class="code">autoLangToFont</span> - selects the font to use, based on the HTML lang attribute, using configurable values in <span class="code">config_lang2font.php</span>.</p>

<p>For automatic font selection, ideally we would choose the font based on the language in use. However it is actually impossible to determine the language used from a string of HTML text. The Unicode script block can be ascertained, and sometimes this tells us the language e.g. Telugu. However, Cyrillic script is used for example in many different languages. So the best we can do is base it on the script used. However, mPDF 6 does this in two stages via the "lang" attribute, because this allows the options of using either of the stages alone or together:</p>

<div style="text-align: center;">
<p class="code">&lt;p&gt;English &#x440;&#x443;&#x301;&#x441;&#x441;&#x43a;&#x438;&#x439; &#x44f;&#x437;&#x44b;&#x301;&#x43a; <span lang="ps">&#x67e;&#x69a;&#x62a;&#x648;</span>&lt;/p&gt;</p>
<p>&darr; <b>autoScriptToLang</b> (config_script2lang.php) &darr;</p>

<p class="code">&lt;p&gt;English &lt;span lang="und-Cyrl"&gt;&#x440;&#x443;&#x301;&#x441;&#x441;&#x43a;&#x438;&#x439; &#x44f;&#x437;&#x44b;&#x301;&#x43a;&lt;/span&gt; <br />
&lt;span lang="ps"&gt;<span lang="ps">&#x67e;&#x69a;&#x62a;&#x648;</span>&lt;/span&gt;&lt;/p&gt;</p>
<p>&darr; <b>autoLangToFont</b> (config_lang2fonts.php) &darr;</p>

<p class="code">Uses "lang" to select font, and to determine OTL features applied</p>
</div>

<h4>autoScriptToLang</h4>

<p class="code">
$mpdf-&gt;autoScriptToLang = true;<br />
$mpdf-&gt;baseScript = 1;<br />
$mpdf-&gt;autoVietnamese = true;<br />
$mpdf-&gt;autoArabic = true;
</p>

<p><span class="code">$mpdf-&gt;baseScript = 1;</span> tells mPDF which Script to ignore. It is set by default to "1" which is for Latin script. In this mode, all scripts <i>except</i> Latin script are marked up with "lang" attribute. To select other scripts as the base, see the file /classes/ucdn.php</p>

<p>Using autoScriptToLang, mPDF detects text runs based on Unicode script block; using the values in <span class="code">config_script2lang.php</span> it then encloses the text run within a span tag with the appropriate language attribute. For many scripts, the language cannot be determined: see the example above which recognises Cyrillic script and marks it up using und-Cyrl, which is a valid IETF tag, coding for language="undetermined", script="Cyrillic".</p>

<p>Two optional refinements are added: Vietnamese text can often be recognised by the presence of certain characters which do not appear in other Latin script langauges, and similarly analysis of the text can attempt to distinguish Arabic, Farsi, Pashto, Urdu and Sindhi. If active, the text will then be marked with a specific language tag e.g. "vi", "pa", "ur", "fa" etc.</p>

<p>These features can be disabled or enabled (default) using the variables <span class="code">$mpdf-&gt;autoVietnamese</span>
<span class="code">$mpdf-&gt;autoArabic</span>, either in config.php or at runtime.</p>

<pagebreak />

<h4>autoLangToFont</h4>
<p class="code">
$mpdf-&gt;autoLangToFont = true;
</p>
<p>You can edit the values in <span class="code">config_lang2font.php</span> to specify which fonts are used for which "lang".</p>


<h4>Using text with multiple languages</h4>
<p>Recommended ways to use multiple languages in mPDF:</p>
<ol>
<li>If you have full control over the HTML, mark-up the text with the "lang" atribute and use CSS (:lang selector preferably); this method means that the language information can also be used by OTL for language dependent substitutions.</li>
<li>If you have no control over (user) HTML input and want to output faithfully, use both autoScriptToLang and autoLangToFont</li>
</ol>

<p>It is preferable not to use autoScriptToLang and autoLangToFont unless they are necessary: they will result in increased processing time, and OTL tables will not be able to use language dependent substitutions when undefined languages are set e.g "und-Cyrl".</p>


<h4>Updating from previous mPDF versions</h4>
<p>As a brief summary, to update from previous versions of mPDF:<br />
Use $this-&gt;autoScriptToLang=true instead of $this-&gt;SetAutoFont()<br />
Use $this-&gt;autoLangToFont instead of $this-&gt;useLang
</p>


<h3>Bidi Bidirectional text</h3>

<p>The algorithm to handle bi-directional text (right to left) has been completely rewritten. Text is now processed across the whole paragraph ignoring inline tags. There is also full support for the methods to control/override the display.</p>


<p>1) The following Unicode characters are supported, and can be inserted directly in the text as HTML entities:</p>
<table style="font-size:85%">
<tr>
<td>LRE</td><td>U+202A</td><td>LEFT-TO-RIGHT EMBEDDING</td><td>&amp;#x202A;</td>
</tr>
<tr>
<td>RLE</td><td>U+202B</td><td>RIGHT-TO-LEFT EMBEDDING</td><td>&amp;#x202B;</td>
</tr>
<tr>
<td>LRO</td><td>U+202D</td><td>LEFT-TO-RIGHT OVERRIDE</td><td>&amp;#x202D;</td>
</tr>
<tr>
<td>RLO</td><td>U+202E</td><td>RIGHT-TO-LEFT OVERRIDE</td><td>&amp;#x202E;</td>
</tr>
<tr>
<td>PDF</td><td>U+202C</td><td>POP DIRECTIONAL FORMATTING</td><td>&amp;#x202C;</td>
</tr>
<tr>
<td></td><td></td><td></td><td></td>
</tr>
<tr>
<td>LRI</td><td>U+2066</td><td>LEFT-TO-RIGHT ISOLATE</td><td>&amp;#x2066;</td>
</tr>
<tr>
<td>RLI</td><td>U+2067</td><td>RIGHT-TO-LEFT ISOLATE</td><td>&amp;#x2067;</td>
</tr>
<tr>
<td>FSI</td><td>U+2068</td><td>FIRST STRONG ISOLATE</td><td>&amp;#x2068;</td>
</tr>
<tr>
<td>PDI</td><td>U+2069</td><td>POP DIRECTIONAL ISOLATE</td><td>&amp;#x2069;</td>
</tr>
<tr>
<td></td><td></td><td></td><td></td>
</tr>
<tr>
<td>LRM</td><td>U+200E</td><td>LEFT-TO-RIGHT MARK</td><td>&amp;#x200E;</td>
</tr>
<tr>
<td>RLM</td><td>U+200F</td><td>RIGHT-TO-LEFT MARK</td><td>&amp;#x200F;</td>
</tr>
</table>

<p>2) The following HTML tags are supported:
<ul>
<li>&lt;bdo&gt;  (NB the "dir" attribute is mandatory on &lt;bdo&gt;)</li>
<li>&lt;bdi&gt;  (HTML5)</li>
</ul>
</p>


<p>3) The CSS property "unicode-bidi" is supported with the following (CSS3) values: normal | embed | isolate | bidi-override | isolate-override | plaintext.
<br />
See <a href="http://www.w3.org/TR/css3-writing-modes/#unicode-bidi">http://www.w3.org/TR/css3-writing-modes/#unicode-bidi</a>
for more details.
<br />
"unicode-bidi" is supported on block level elements as well as in-line elements, but note that:
<ul><li>the value is not inherited to child blocks</li>
<li>using "embed" or "isolate" has no effect on block level boxes</li>
<li>"isolate-override" is equivalent to "bidi-override" on block level boxes</li>
</ul>
</p>

<p>NB dir="auto" is not supported generally, but it is supported for &lt;bdi&gt; (has the same effect as if omitted) to use First Strong Isolate (FSI).</p>

<p>Directionality can now be set on individual table cells &lt;td style="direction:rtl;unicode-bidi:embed;"&gt; or &lt;td dir="rtl"&gt;</p>


<h4>Equivalent methods</h4>
<p>The following are equivalent methods:</p>
<table>
<tr>
<td>
EMBED 
</td></tr>
<tr><td class="code">
&lt;span dir="rtl"&gt;...&lt;/span&gt;<br />
&amp;#x202B;...&amp;#x202C;<br />
&lt;span style="direction: rtl; unicode-bidi: embed"&gt;...&lt;/span&gt;
</td></tr>
<tr><td>
OVERRIDE 
</td></tr>
<tr><td class="code">
&lt;bdo dir="rtl"&gt;...&lt;/bdo&gt;<br />
&amp;#x202E;...&amp;#x202C;<br />
&lt;span dir="rtl" style="unicode-bidi: bidi-override"&gt;...&lt;/span&gt;<br />
&lt;span style="direction: rtl; unicode-bidi: bidi-override"&gt;...&lt;/span&gt;
</td></tr>
<tr><td>
ISOLATE 
</td></tr>
<tr><td class="code">
&lt;bdi dir="ltr"&gt;...&lt;/bdi&gt;<br />
&amp;#x2067;...&amp;#x2069;<br />
&lt;span dir="rtl" style="unicode-bidi: isolate"&gt;...&lt;/span&gt;<br />
&lt;span style="direction: rtl; unicode-bidi: isolate"&gt;...&lt;/span&gt;
</td></tr>
<tr><td>
First Strong Isolate (FSI)
</td></tr>
<tr><td class="code">
&lt;bdi&gt;...&lt;/bdi&gt;<br />
&lt;bdi dir="auto"&gt;...&lt;/bdi&gt;<br />
&amp;#x2068;...&amp;#x2069;<br />
&lt;span dir="rtl" style="unicode-bidi: plaintext"&gt;...&lt;/span&gt;<br />
&lt;span style="direction: rtl; unicode-bidi: plaintext"&gt;...&lt;/span&gt;<br />
</tr>
</table>


<h4>First strong isolate (FSI)</h4>
<p>FSI is useful when including text within a paragraph where the directionality of the text is unknown. For example, if you are printing out a catalogue from a database of book titles and the number of readers, when some book titles are in right-to-left script, you may use this template:</p>

<p class="code">
&lt;li&gt;Title: {TITLE} - {READERS} readers&lt;/li&gt;
</p>

<p>This would result in the following:</p>

<ul>
<li style="font-family: freesans; direction: ltr;">Title: Alice in Wonderland - 12390 readers</li>
<li style="font-family: freesans; direction: ltr;">Title: &#x5e2;&#x5dc;&#x5d9;&#x5e1;&#x5d4; &#x5d1;&#x5d0;&#x5e8;&#x5e5; &#x5d4;&#x5e4;&#x5dc;&#x5d0;&#x5d5;&#x5ea;, &#x5e1;&#x5d9;&#x5e4;&#x5d5;&#x5e8;-&#x5d9;&#x5dc;&#x5d3;&#x5d9;&#x5dd; &#x5de;&#x5d0;&#x5ea; &#x5dc;&#x5d5;&#x5d0;&#x5d9;&#x5e1; &#x5e7;&#x5e8;&#x5d5;&#x5dc; - 17890 readers</li>
</ul>

<p class="code">
&lt;li&gt;Title: &lt;bdi&gt;{TITLE}&lt;/bdi&gt; - {READERS} readers&lt;/li&gt;
</p>

<p>Using BDI will result in the following:</p>

<ul>
<li style="font-family: freesans; direction: ltr;">Title: <bdi>Alice in Wonderland</bdi> - 12390 readers</li>
<li style="font-family: freesans; direction: ltr;">Title: <bdi>&#x5e2;&#x5dc;&#x5d9;&#x5e1;&#x5d4; &#x5d1;&#x5d0;&#x5e8;&#x5e5; &#x5d4;&#x5e4;&#x5dc;&#x5d0;&#x5d5;&#x5ea;, &#x5e1;&#x5d9;&#x5e4;&#x5d5;&#x5e8;-&#x5d9;&#x5dc;&#x5d3;&#x5d9;&#x5dd; &#x5de;&#x5d0;&#x5ea; &#x5dc;&#x5d5;&#x5d0;&#x5d9;&#x5e1; &#x5e7;&#x5e8;&#x5d5;&#x5dc;</bdi> - 17890 readers</li>
</ul>




<h3>Kerning</h3>
<p>Kerning is a bit complicated! CSS3 allows for 2 methods of specifying kerning. In mPDF 6, these 2 methods have exactly the same effect:</p>
<ul>
<li>font-kerning: normal;</li>
<li>font-feature-settings: \'kern\' on;</li>
</ul>

<p>TrueType fonts allow for 2 possible ways of including kerning data:</p>
<ul>
<li>OTL GPOS table may contain kerning information</li>
<li>A separate kern table</li>
</ul>
<p>Most fonts contain both or none, but they may exist independently.</p>

<p>If kerning is set to be active (by either of the CSS methods):</p>
<ul>
<li>if the useOTL value means that OTL GPOS tables are applied, then this method will be used;</li>
<li>if not, then the separate kern table will be used - if it exists.</li>
</ul>



<p>In Latin script, kerning will only be applied if specified by CSS. The configurable variable <span class="code">useKerning</span> determines behaviour if <span class="code">font-kerning: auto</span> is used (the default).</p>

<p>When using OTL tables, kerning is set to be on by default for non-LATIN script; this is because a number of fonts use information in the kern feature to reposition glyphs which are essential for correct display in complex scripts.</p>

<p><i>Limitation: if useOTL is set, but not for Latin script (e.g. = 0x02), and the text string contains more than one script, then kerning will not be applied to the Latin script text e.g. <span style="font-kerning:normal">[Cyrillic text][Latin text][Cyrillic text]</span>. This is because mPDF uses the presence of any repositioning applied to determine if kerning has been applied, otherwise using the alternative kern tables.</i></p>


<h3>Small-Caps</h3>
<p>Small Caps should be selected using:</p>
<p class="code">
&lt;p style="font-variant-caps:small-caps"&gt;This is in small caps&lt;/p&gt;
</p>
<p>and will appear as: <span style="font-variant-caps:small-caps">This is in small caps</span></p>

<p>Note: <span class="code">font-variant:small-caps</span> will also be recognised as font-variant is now considered the shorthand version cf. above.</p>

<p>If the font has useOTL enabled (to any value), and the font OTL tables contain the "smcp" feature, then the OTL feature will be used to substitute purpose-designed glyphs from the font. Otherwise, mPDF generates small capitals as in previous version.</p>



<h3>Superscript and Subscript</h3>

<p class="code">
&lt;p&gt;This is in &lt;span style="font-variant-position:super"&gt;superscript&lt;/span&gt;&lt;/p&gt;
</p>
<p>will appear as superscript (only) if the font is OTL-capable and contains specific glyphs for superscript.<p>

<p>Note that font-variant:super will also be recognised as font-variant is now considered the shorthand version cf. above.</p>

<p>If the font has useOTL enabled (to any value), and the font OTL tables contain the "sups" feature, then the OTL feature will be used to substitute purpose-designed glyphs from the font.</p>

<p>The same for subscript using <span class="code">font-variant-position:sub</span>. </p>

<p>If you wish to use a superscript/subscript which will work with any font, continue to use the tags &lt;sup&gt; and &lt;sub&gt; which (through the default CSS in config.php) will generate superscript using CSS vertical-align=super and font-size=55%.</p>



<h3>How to use OTL in mPDF</h3>
<p>In <span class="code">config_fonts.php</span> there are 2 new variables which affect OTL features e.g.:</p>

<p class="code">
	"dejavusanscondensed" => array(<br />
		\'R\' =&gt; "DejaVuSansCondensed.ttf",<br />
		\'B\' =&gt; "DejaVuSansCondensed-Bold.ttf",<br />
		\'I\' =&gt; "DejaVuSansCondensed-Oblique.ttf",<br />
		\'BI\' =&gt; "DejaVuSansCondensed-BoldOblique.ttf",<br />
		<span style="color: #880000;">\'useOTL\' =&gt; 0xFF,<br />
		\'useKashida\' =&gt; 75,</span><br />
		),
</p>

<p>mPDF is published with a large collection of fonts, and all configured to use their full OTL capabilities.</p>

<h4>useOTL</h4>
<p>useOTL should be set to an integer between 0 and 255. Each bit will enable OTL features for a different group of scripts:</p>
<table>
<tr><td>Bit</td>	<td>dec</td>	<td>hex</td>	<td>Enabled</td></tr>
<tr><td>1</td>	<td>1</td>	<td>0x01</td>	<td>GSUB/GPOS - Latin script</td></tr>
<tr><td>2</td>	<td>2</td>	<td>0x02</td>	<td>GSUB/GPOS - Cyrillic script</td></tr>
<tr><td>3</td>	<td>4</td>	<td>0x04</td>	<td>GSUB/GPOS - Greek script</td></tr>
<tr><td>4</td>	<td>8</td>	<td>0x08</td>	<td>GSUB/GPOS - CJK scripts (excluding Hangul-Jamo)</td></tr>
<tr><td>5</td>	<td>16</td>	<td>0x10</td>	<td>(Reserved)</td></tr>
<tr><td>6</td>	<td>32</td>	<td>0x20</td>	<td>(Reserved)</td></tr>
<tr><td>7</td>	<td>64</td>	<td>0x40</td>	<td>(Reserved)</td></tr>
<tr><td>8</td>	<td>128</td>	<td>0x80</td>	<td>GSUB/GPOS - All other scripts (including all RTL scripts, complex scripts etc)</td></tr>
</table>

<p>Setting useOTL to 0 (or omitting it) will disable all OTL features. Setting useOTL to 255 or 0xFF will enable OTL for all scripts. Setting useOTL to 0x82 will enable OTL features for Cyrillic and complex scripts.</p>

<p>In a font like Free Serif, it may be useful to enable OTL features for complex scripts, but disable OTL for Latin scripts (to save processing time). However, see above - this may disable kerning in Latin scripts in certain circumstances.</p>


<h4>useKashida</h4>
<p>useKashida should be set for arabic fonts if you wish to enable text justification using kashida. The value should be an integer between 0 and 100 and represents the percentage of additional space required to justify the text on a line as a ratio of kashida/inter-word spacing.</p>



<h4>Choosing fonts to add to mPDF 6</h4>
<p>Fonts with OTL need to have GDEF, GSUB and GPOS tables in the font file. Although TrueType font files are binary files, the table names and script/feature tags are written as ASCII characters; open the .ttf or .otf file in a text editor such as Windows Notepad, and you will see GDEF, GSUB and GPOS in the first few lines if they are present. You can also search the file to see if the script tags are present for your desired scripts cf. <a href="http://www.microsoft.com/typography/otspec/scripttags.htm">http://www.microsoft.com/typography/otspec/scripttags.htm</a>.</p>

<p>Note: The OTL specification for Indic fonts was updated in 2005 to version 2. The v2 script tag for Bengali is "bng2" whereas prior to this it was "beng". Many open-source font files are still written for the old specification. This is supported by mPDF, although v2 fonts give better results.</p>

<p>Note: mPDF does not support Graphite or AAT font features.</p>


<h4>Configuring new fonts for mPDF 6</h4>
<p>To add a font, first copy the font file to the /ttfonts/ folder.</p>
<p>Then edit config_fonts.php to add. See the manual for details if you are not already familiar with this.</p>
<p>If you wish to use this font with autoLangToFont, you also need to edit config_lang2fonts.php</p>


<h4>Setting OTL use at runtime</h4>
<p>mPDF caches some font information in the /ttfontdata/ folder to improve performance. This is regenerated if you change the value of useOTL for a font.</p>
<p>There may be circumstances when you wish to use OTL features with different scripts depending on the document e.g. for everyday use you may want to disable OTL for FreeSerif to save processing time, but on occasions use OTL for Indic and/or Arabic scripts. The recommended way to do this is to create 2 instances of the font e.g. in config_fonts.php:</p>
<p class="code">
	"freeserif" =&gt; array(<br />
		\'R\' =&gt; "FreeSerif.ttf",<br />
		\'B\' =&gt; "FreeSerifBold.ttf",<br />
		\'I\' =&gt; "FreeSerifItalic.ttf",<br />
		\'BI\' =&gt; "FreeSerifBoldItalic.ttf",<br />
		\'useOTL\' =&gt; 0x00,<br />
		),<br />
	"freeserif2" =&gt; array(<br />
		\'R\' =&gt; "FreeSerif.ttf",<br />
		\'B\' =&gt; "FreeSerifBold.ttf",<br />
		\'I\' =&gt; "FreeSerifItalic.ttf",<br />
		\'BI\' =&gt; "FreeSerifBoldItalic.ttf",<br />
		\'useOTL\' =&gt; 0xFF,	/* Uses OTL for all scripts */<br />
		\'useKashida\' =&gt; 75,<br />
		),<br />
</p>
<p>You could then either use this second font name in your stylesheets e.g.</p>
<p class="code">
&lt;p style="font-family:freeserif2;"&gt;Hallo World (in Arabic)&lt;/p&gt;
</p>

<p>or, you could use font translation e.g.</p>
<p class="code">
$mpdf-&gt;fonttrans[\'freeserif\'] = \'freeserif2\';
</p>


<pagebreak />

<h3>Page breaking</h3>

<h4>Types of page break</h4>

<p>The handling of borders and padding at page breaks has been updated. mPDF has three types of page breaks:</p>
<p>1) "slice" - no border and no padding are inserted at a break. The effect is as though the element were rendered with no breaks present, and then sliced by the breaks afterward</p>

<p>2) "cloneall" - each page fragment is independently wrapped with the borders and padding of all open elements.</p>

<p>3) "clonebycss" - open elements which have the (custom) CSS property "box-decoration-break" set to "clone" are independently wrapped with their border and padding.</p>

<p>The difference between 2) and 3) is illustrated by this example:</p>

<p class="code">
&lt;style&gt;<br />
div { border: 1px solid black; padding: 1em; }<br />
.level1 { box-decoration-break: slice; }<br />
.level2 { box-decoration-break: clone; }<br />
.level3 { box-decoration-break: clone; }<br />
&lt;/style&gt;<br />
<br />
<br />
&lt;div class="level1"&gt;<br />
  &lt;div class="level2"&gt;<br />
    &lt;div class="level3"&gt;<br />
      &lt;p style="page-break-after:always"&gt;...&lt;/p&gt;<br />
      &lt;p&gt;....&lt;/p&gt;<br />
   &lt;/div&gt;<br />
  &lt;/div&gt;<br />
&lt;/div&gt;<br />
</p>

<p>At the forced pagebreak which occurs after the P element:</p>

<p>If the page break type is "cloneall" - the three DIV elements will all be closed, by drawing the border and padding for each at the end of the page; the three DIV elements will be re-opened, drawing the borders and padding, at the top of the next page.</p>

<p>If the page break type is "clonebycss" - starting from the innermost element (div.level3) the DIV elements will have a border and padding at the end of the page if "box-decoration-break" is clone. In this case level2 and level 3 will be closed/cloned and level 1 will be sliced; the opposite will occur at the top of the next page.</p>

<h4>Control of page breaks</h4>

<table border="1">
<tr>
<td>Automatic page breaks (in flow of text)</td>
<td>Always "slice"</td>
</tr>

<tr>
<td>&lt;tocpagebreak&gt;</td>
<td>Always "cloneall"</td>
</tr>

<tr>
<td>&lt;formfeed&gt;</td>
<td>Always "slice"</td>
</tr>

<tr>
<td>If using columns</td>
<td>Always "cloneall"</td>
</tr>

<tr>
<td>Page break forced by change of @page selector</td>
<td>Always "cloneall"</td>
</tr>

<tr>
<td>&lt;pagebreak&gt; </td>
<td>Always "cloneall" if a change in page size or margins is specified.<br />
Otherwise page break type is determined by value of configurable variable: $this-&gt;defaultPagebreakType. Default is "cloneall".<br />
Default can be overridden by attribute "page-break-type" e.g. &lt;pagebreak page-break-type="clonebycss" /&gt;
</td>
</tr>

<tr>
<td>Page breaks forced by:<br />
page-break-before or page-break-after 
</td>
<td>
Page break type determined by value of configurable variable: $this-&gt;defaultPagebreakType. Default is "cloneall".
</td>
</tr>
</table>



<h4>Notes on page breaking</h4>
<p>"box-decoration-break: slice | clone" was proposed for CSS3 in <a href="http://www.w3.org/TR/2012/CR-css3-background-20120417/#the-box-decoration-break">http://www.w3.org/TR/2012/CR-css3-background-20120417/#the-box-decoration-break</a> but it appears that it may be withdrawn. Default is "slice"; it is not inherited.</p>
<p>"page-break-before" is not supported on &lt;table&gt;.</p>
<p>"page-break-before|after" is ignored if set on block elements inside a table.</p>
<p>mPDF functions e.g. AddPage() are not affected by the changes in mPDF 6.</p>
<p>Background images and gradients are not sliced.</p>
<p>$this-&gt;restoreBlockPagebreaks in config.php is now redundant.</p>




<h3>Line breaking</h3>
<p>The algorithm for determining automatic line breaks has been completely rewritten, ignoring inline tags (except for some cases of CJK line-breaking, and autohyphenation).</p>

<p>Line breaks will be allowed at:</p>
<ul>
<li>Spaces U+0020</li>
<li>Word break U+200B</li>
<li>Hyphen-minus U+002D when CSS hyphens set to "manual" or "auto", except when in a URL, or when following character is a &gt; or numeral</li>
<li>Hard hyphen U+2010 when CSS hyphens set to "manual" or "auto"</li>
<li>Soft hyphen U+00AD "&shy;" when CSS hyphens set to "manual" or "auto"</li>
<li>Automatic hyphenation when CSS hyphens set to "auto"</li>
<li>Between CJK characters, except CJK numerals, before "CJK-following" or after "CJK-leading" characters</li>
</ul>

<p>See also "Dictionary Line breaking" above.</p>


<pagebreak />


<h3>Line-height and Text Baseline</h3>

<h4>Using font metrics</h4>

<p>mPDF 6 can (optionally) use font metrics derived from each font file to:</p>
<ul>
<li>Determine the height of a line when line-height is set to \'normal\'</li>
<li>Determine the glyph baseline (previously a fixed value)</li>
</ul>

<p>Options are set by configurable variables in the <span class="code">config.php</span> file:</p>

<p>Default settings in mPDF versions 6 - recommended especially for complex scripts with marks used above or below characters:</p>
<ul class="code">
<li>$this-&gt;useFixedNormalLineHeight = false;</li>
<li>$this-&gt;useFixedTextBaseline = false;</li>
<li>$this-&gt;adjustFontDescLineheight = 1.14;</li>
</ul>
							

<p>Settings to be backwards compatible with mPDF versions &lt; 6:</p>
<ul class="code">
<li>$this-&gt;useFixedNormalLineHeight = true;</li>
<li>$this-&gt;useFixedTextBaseline = true;</li>
<li>$this-&gt;normalLineheight = 1.33;</li>
</ul>

<h4>Examples</h4>

<p>Using the font metrics will give approximately the same result as the fixed value for many standard Latin script fonts e.g. DejaVu Sans Condensed:</p>

<p class="gradient" style="line-height: normal; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; based on font metrics</p>

<p class="gradient" style="line-height: 1.33; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; using fixed value</p>

<p>However, for some fonts the normal line-height using font metrics will be significantly taller, to account for the design of the font glyphs e.g. Khmer font:</p>

<p class="gradient" style="font-family:\'Khmer OS\'; line-height: normal; font-size: 10pt; padding: 0 0.3em; margin-bottom: 1em;"><span style="font-family:\'DejaVu Sans Condensed\';">line-height: normal;  based on font metrics;</span>
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782; &#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A; &#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8; &#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8; &#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7; &#x1799;&#x17bb;&#x179c;&#x1787;&#x1793; </p>

<p class="gradient" style="font-family:\'Khmer OS\'; line-height: 1.33; font-size: 10pt; padding: 0 0.3em; margin-bottom: 1em;"><span style="font-family:\'DejaVu Sans Condensed\';">line-height: normal;  using fixed value;</span>
&#x1784;&#x17D2;&#x179A;&#x17D2;&#x1782; &#x1784;&#x17D2;&#x1782;&#x17D2;&#x179A; &#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8; &#x1793;&#x17d2;&#x179a;&#x17d2;&#x178f;&#x17b8; &#x1784;&#x17d2;&#x1782;&#x17d2;&#x179a;&#x17c4;&#x17c7; &#x1799;&#x17bb;&#x179c;&#x1787;&#x1793; </p>


<p>For more information on how complex normal lineheights are, see Eric Meyers\' website: <a href="http://meyerweb.com/eric/thoughts/2008/05/06/line-height-abnormal/">http://meyerweb.com/eric/thoughts/2008/05/06/line-height-abnormal/</a> and 
<a href="http://typophile.com/node/13081">http://typophile.com/node/13081</a></p>


<h4>CSS control of line-height</h4>

<p>There are also new controls for line-height using draft CSS3 properties. These can be set on all block level elements (P, DIV etc) and tables (TABLE/TD/TH).</p>
<p class="code"><b>line-stacking-strategy</b> = inline-line-height | block-line-height | max-height | grid-height</p>
<ul>
<li><span class="code">inline-line-height</span> - [default] lineheight is initially calculated from the block-level font[-size]; the height is expanded by any inline content, including the calculated lineheight of that inline content;</li>
<li><span class="code">block-line-height</span> - lineheight is fixed as the lineheight of the block-level font[-size];</li>
<li><span class="code">max-height</span> - lineheight is initially calculated from the block-level font; the height is expanded by any inline content, EXCLUDING the calculated lineheight of that inline content;</li>
<li><span class="code">grid-height</span> - lineheight is initially calculated from the block-level font; the height is expanded - AS MULTIPLES OF INITIAL LINEHEIGHT - by any inline content, EXCLUDING the calculated lineheight of that inline content;</li>
</ul>
<p>Note: XSL has a similar property with the same name, which uses different but equivalent values: <span class="code">line-height</span> instead of <span class="code">inline-line-height</span>, <span class="code">font-height</span> instead of <span class="code">block-line-height</span>. It also uses <span class="code">max-height</span>. The value <span class="code">grid-height</span> is new to the CSS3 property.</p>

<h4>Examples</h4>

<p class="gradient" style="line-height: normal; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; DejaVu Sans Condensed</p>

<p class="gradient" style="line-height: normal; line-stacking-strategy: inline-line-height; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; <span style="font-size: 16pt;">16pt font-size &#194;</span> with line-stacking-strategy: inline-line-height</p>

<p class="gradient" style="line-height: normal; line-stacking-strategy: block-line-height; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; <span style="font-size: 16pt;">16pt font-size &#194;</span> with line-stacking-strategy: block-line-height</p>

<p class="gradient" style="line-height: normal; line-stacking-strategy: max-height; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; <span style="font-size: 16pt;">16pt font-size &#194;</span> with line-stacking-strategy: max-height</p>

<p class="gradient" style="line-height: normal; line-stacking-strategy: grid-height; font-size: 10pt; padding: 0 0.3em;font-family:\'DejaVu Sans Condensed\'; margin-bottom: 1em;">line-height: normal; <span style="font-size: 16pt;">16pt font-size &#194;</span> with line-stacking-strategy: grid-height</p>

<br />
<br />

<p class="code"><b>line-stacking-shift</b> = consider-shifts | disregard-shifts</p>
<p>This property determines whether to include or disregard the adjusted top-  and bottom-edge of any characters that have a baseline-shift (e.g. superscript) when calculating lineheight.</p>
<p>Note: XSL has a similar property with a different name: <span class="code">line-height-shift-adjustment</span> which uses the same values.</p>

<h4>Examples</h4>

<p>In the table below, the line-height is set to 1em throughout the table; line-stacking-shift is set as \'disregard-shifts\' in the first row, and has default setting (consider-shifts) in the second row.</p> 
<table style="line-height: 1em;" border="1">
<tr>
<td style="\'DejaVu Sans Condensed\'; line-stacking-shift: disregard-shifts; ">
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
</td>
<td style="\'DejaVu Sans Condensed\'; ">
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
</td>
</tr>
<tr>
<td style="\'DejaVu Sans Condensed\'; ">
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
Normal text DejaVu Sans<sup>[53]</sup> Condensed <br />
</td>
<td style="\'DejaVu Sans Condensed\'; ">
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
Normal text DejaVu Sans Condensed <br />
</td>
</tr>
</table>



<p>For more details see the <a href="http://www.w3.org/TR/css3-linebox/#InlineBoxHeight">CSS3 draft specification</a>.</p>

<h4>Note for Advanced users</h4>

<p>There are actually three possible metrics that can be used in a TrueType font file. The differences are summed up quite well in this article at <a href="http://typophile.com/node/13081">http://typophile.com/node/13081</a>. mPDF will by default use the usWinAscent and usWinDescent values to determine a \'normal\' line-height, with two variations:</p>

<ul>
<li>if either the usWinAscent or usWinDescent are greater than the font bounding box (yMin yMax), then the values are reduced to equal the yMin/yMax values.
NB this works as a fix with Myanmar Text (Windows 8 version) to give a line-height normal that is equivalent to that produced in browsers.</li>
<li>if the USE_TYPO_METRICS bit is set on fsSelection (OS/2 table), this is telling the font to use the sTypo values and not the usWinAscent values.
NB this works as a fix with Cambria Math font to give a normal line-height; at present, this is the only font I have found with this bit set;
although note that MS WordPad and Windows FireFox browser use the big line-height from usWinAscent, whilst MS Word 2007 observes the fSelection value.</li>
</ul>

<p>You can change the font metrics used by mPDF, by editing the defined constant (_FONT_DESCRIPTOR) at the top of the <span classs="code">mpdf.php</span> file:</p>
<ul>
<li>\'winTypo\' uses sTypoAscender etc from the OS/2 table and is the one officially recommended - BUT</li>
<li>\'win\' use usWinAscent etc from OS/2 and in practice seems to be used most commonly in Windows environment; this is the default in mPDF;</li>
<li>\'mac\' uses Ascender etc from hhea table, and may be used to give results consistent with a Mac/OSX environment.</li>
</ul>

<p>Finally, you can override values for Ascent, Descent and Leading for any specific font, by setting values in <span classs="code">config_font.php</span> e.g.
<div class="gradient">
<pre style="font-size: 9pt;">	"cambriamath" => array(
		\'R\' => "cambria.ttc",
		\'useOTL\' => 0xFF,
		\'TTCfontID\' => array(
			\'R\' => 2,	
			),
		\'Ascent\' => 950,
		\'Descent\' => -222,
		\'Leading\' => 0,
		),
</pre>
</div>
<p>Note - The same values are used for all styles of the font-family. Descent values should be negative. All values should be given using a 1000 units per em scale, regardless of the UnitsPerEm used in the font design.</p>

<h4>Notes</h4>
<p>Remember that line-height for a TABLE has a default value (1.2) set in the <span classs="code">config.php</span> default CSS. This is left in for backwards compatability. You can change this value to \'normal\' for results consistent with most browsers.</p>
<p>Line-height in a &lt;textarea&gt; is fixed and defined in <span class="code">classes/mpdfform.php</span> (= 1.2)</p>
<p>Details of the font metrics can be seen by inspecting the temporary font files e.g. <span class="code">/ttfontdata/[fontname].mtx.php</span>.



<pagebreak />

<h3>Indexes</h3>
<h4>Index style and layout</h4>
<p>Indexes have been completely rewritten for mPDF 6, and are not backwards compatible:</p>
<ul>
<li>Reference() is now removed - use IndexEntry() instead.</li>
<li>CreateReference() and CreateIndex() are both removed - replaced by: InsertIndex() [or recommend &lt;indexinsert&gt;] cf. below.</li>
<li>&lt;indexinsert&gt; and InsertIndex() no longer set styles - appearance must be controlled using CSS, even if using function InsertIndex().</li>
<li>&lt;indexinsert&gt; and InsertIndex() no longer control columns - these must be specified separately.</li>
</ul>

<p>When an Index is inserted in the PDF document, the Index is now generated (internally) as HTML code in the following format:</p>

<p class="code">
&lt;div class="mpdf_index_main"&gt;<br />
&lt;div class="mpdf_index_letter"&gt;<b>A</b>&lt;/div&gt;<br />
&lt;div class="mpdf_index_entry"&gt;<b>Aardvark</b>&lt;a class="mpdf_index_link" href="#page37"&gt;37&lt;/a&gt;<br />
&lt;/div&gt;<br />
...<br />
&lt;/div&gt;
</p>

<p>CSS stylesheets can thus be used to control the layout of the Index e.g.:</p>
<p class="code">
/* For Index */<br />
div.mpdf_index_main {<br />
&nbsp; &nbsp; line-height: normal;<br />
&nbsp; &nbsp; font-family: sans-serif;<br />
}<br />
div.mpdf_index_letter {<br />
&nbsp; &nbsp; line-height: normal;<br />
&nbsp; &nbsp; font-family: sans-serif;<br />
&nbsp; &nbsp; font-size: 1.8em;<br />
&nbsp; &nbsp; font-weight: bold;<br />
&nbsp; &nbsp; text-transform: uppercase;<br />
&nbsp; &nbsp; page-break-after: avoid; <br />
&nbsp; &nbsp; margin-top: 0.3em; <br />
&nbsp; &nbsp; margin-collapse: collapse;<br />
}<br />
div.mpdf_index_entry {<br />
&nbsp; &nbsp; line-height: normal;<br />
&nbsp; &nbsp; font-family: sans-serif;<br />
&nbsp; &nbsp; text-indent: -1.5em;<br />
}<br />
a.mpdf_index_link { <br />
&nbsp; &nbsp; color: #000000; <br />
&nbsp; &nbsp; text-decoration: none;<br /> 
}<br />
</p>

<p>A default stylesheet for Indexes is included in mpdf.css (see note later for more information).</p>

<pagebreak />

<h4>Index Collation</h4>
<p>In order to generate an Index with non-ASCII characters, entries need to be sorted accordingly (collation), and non-ASCII characters should map to the appropriate Dividing letter e.g.:</p>
<div style="border: 1px solid #666666; padding: 0.3em;">
<div style="font-weight:bold;font-size: 18pt;">A</div>
<div>Alonso, Fernando</div>
<div>&#195;lvarez, Isaac</div>
<div>Arroyo Molino, David</div>
<div style="font-weight:bold;font-size: 18pt;">B</div>
<div>Ben&#195;tez, Carlos</div>
</div>

<p>Entries in an Index can now be sorted using any of the Locale values available on your system. Set it using the "collation" property/parameter e.g.:</p>
<p class="code">
&lt;indexinsert usedivletters="on" links="off" <b>collation="es_ES.utf8"</b> collation-group="Spanish_Spain" /&gt;<br />
- or -<br />
$mpdf-&gt;InsertIndex(true, false, <b>"es_ES.utf8"</b>, "Spanish_Spain");
</p>

<p>NB You should always choose a UTF-8 collation, even when you are using Core fonts or e.g. charset-in=win-1252, because mPDF handles all text internally as UTF-8 encoded.</p>

<p>You can see which Locales are available on your (Unix) system: <span class="code">&lt;?php system(\'locale -a\') ?&gt;</span></p>

<p>Note: Index collation will probably not work on Windows servers because of the problems setting Locales under Windows.</p>

<p>If you have set your index to use Dividing letters, you can also determine how letters are grouped under 
a dividing letter. In the example index above, we want &Atilde; to be grouped under the letter a/A.  Set the "collation-group" using:</p>
<p class="code">
&lt;indexinsert usedivletters="on" links="off" collation="es_ES.utf8" <b>collation-group="Spanish_Spain"</b> /&gt;
- or -<br />
$mpdf-&gt;InsertIndex(true, false, "es_ES.utf8", <b>"Spanish_Spain"</b>);
</p>
<p>Values should be selected from the available file names in folder /collations/.</p>

<p>Note: This will not affect the overall order of entries, which is determined by the value of "collation".</p>

<p>Note: The groupings do not always match the order set by locale. This is because the data for collations has come from different sources. The files in  /collations/ can be edited.</p>
<p>The array consists of [index]: unicode decimal value of character => unicode decimal value of character to group under: 
e.g. &Atilde; [A tilde] (U+00C3) (decimal 195) => a (U+0061) (decimal 97). The target character should always be the lowercase form.</p>

<h4>Non-ASCII chcracters in Index entries</h4>
<p>Note: htmlspecials_encode should be used to encode the text of content in &lt;indexentry&gt; - although not when using $mpdf->IndexEntry().</p>

<h4>Columns</h4>
<p>Columns are no longer specified as part of the &lt;indexinsert&gt;, so a typical 2-column index might be produced by:</p>
<p class="code">
&lt;pagebreak type="next-odd" /&gt;<br />
&lt;h2&gt;Index&lt;/h2&gt;<br />
&lt;columns column-count="2" column-gap="5" /&gt;<br />
&lt;indexinsert usedivletters="on" links="on" collation="en_US.utf8" collationgroup="English_United_States" /&gt;<br />
&lt;columns column-count="1" /&gt;<br />
</p>

<h4>Index Sub-entries</h4>
<p>Index entries can contain sub-entries, separated by colons e.g.</p>
<p class="code">&lt;indexentry content="Mammals:elephants" /&gt;</p>

<p>A shorthand way of displaying subentries is set by default, which suppresses the main entry if &gt; 1 subEntry. It can be disabled/enabled using the configurable variable $this-&gt;indexUseSubentries in config.php.</p>

<p>This is the default appearance, with $this-&gt;indexUseSubentries = false; -</p>
<div style="border: 1px solid #666666; padding: 0.3em;">
Mammals  73<br />
- elephants  142<br />
- humans  173<br />
Marsupials<br />
- kangaroos  75<br />
- wombats  86
</div>

<p>Index entries can also include simple mark-up tags and/or more than one colon e.g:</p>
<p class="code">&lt;indexentry content="Mammals:&amp;lt;b&amp;gt;elephants&amp;lt;/b&amp;gt;: breeding" /&gt;</p>
which appears as:
<div style="border: 1px solid #666666; padding: 0.3em;">
Mammals<br />
- <b>elephants</b>: breeding 15
</div>

<p>This is the appearance with $this-&gt;indexUseSubentries = false; -</p>
<div style="border: 1px solid #666666; padding: 0.3em;">
Mammals  73<br />
Mammals, elephants  142<br />
Mammals, <b>elephants</b>: breeding 15<br />
Mammals, humans  173<br />
Marsupials, kangaroos  75<br />
Marsupials, wombats  86<br />
</div>

<h4>Customised appearance</h4>
<p>Several variables set at beginning of function InsertIndex() in mpdf.php which could be changed to alter appearance of Index.
	e.g. spacer, and joiner characters.</p>


<pagebreak />

<h3>Lists</h3>

<p>Lists are now handled as for other block level tags, so you can apply any CSS properties usable on blocks (e.g. border, background, padding) to UL/OL and LI tags.</p>

<p>CSS property "list-style" is now handled properly as a shorthand, and there is full support for "list-style-image", "list-style-type", and "list-style-position".</p>


<p>There are two modes for lists in mPDF 6: "mpdf" mode and "browser" mode. Mode is set using the configurable variable $this-&gt;list_auto_mode in config.php</p>

<p>1) Browser mode gives the same display as most browsers. In this mode, the default list indentation is set by padding "0 auto" in the default CSS in config.php.  "auto" equates to the value of $this-&gt;list_indent_default in config.php - this is a "magic" value for padding, which is applied to either left or right depending on directionality of the list (rtl/ltr).</p>


<p>2) mPDF mode gives results compatible with previous versions of mPDF. In this mode, the indentation is calculated differently: the outside edge of the list item is considered to be the outside edge of the bullet or number. For numbered lists, mPDF calculates the width of the largest number and this width is used to set the outside edge. The default list indentation of "auto" in mPDF mode is set by $this-&gt;list_indent_default_mpdf. This value is added to the automatic calculated indentation. For backwards compatibility, $this-&gt;list_indent_first_level = 0; can be used to prevent any indentation of the first list level.</p>

<p>The automatic indentation only works for bullets or numbered lists, and is ignored if "list-style-position: inside" is set, or images are used for markers.</p>

<p>Browser mode is set as the default - for backwards compatibility, change this to "mpdf".</p>

<h4>List top & bottom margins</h4>
<p>The default in browsers is to add a top and bottom margin to the outermost list only. This can be defined using CSS as:</p>
<p class="code">
ul, ol { margin-top: 0.83em; margin-bottom: 0.83em; }<br />
ul ul, ul ol, ol ul, ol ol { margin-top: 0; margin-bottom: 0; }
</p>
<p>This style is included in file mpdf.css (see later).</p>
<p>Previous versions of mPDF always added a top and bottom margin to the outermost list, (but no variation from this was possible). mPDF 6 is therefore backwards compatible re. the margins.</p>
<p>[NB The CSS styles are included in mpdf.css, because the defaultCSS values set in config.php only works on basic elements, and cannot use selectors such as "ol ol".]</p>

<h4>Other new configurable variables</h4>


<p>Configurable variables are used to define size and offset for list bullets (i.e. disc, circle or square).
The values can be any valid CSS size.</p>
<p>To specify a fixed bullet size and offset to give a similar appearance to most browsers, the default is set as:</p>
<ul>
<li>$this-&gt;list_marker_offset = \'5.5pt\';</li>
<li>$this-&gt;list_symbol_size = \'3.6pt\';</li>
</ul>
<p>To specify size and offset proportional to the list item\'s font size (compatible with previous versions of mPDF), use:</p>
<ul>
<li>$this-&gt;list_marker_offset = \'0.45em\';</li>
<li>$this-&gt;list_symbol_size = \'0.31em\';</li>
</ul>


<h4>Notes on Lists</h4>
<p>The attribute type="" is case sensitive (whereas it is case insensitive in CSS). This allows the use of shorthand versions e.g. type="A" for uppercase alpha-numeric.</p>
<p>"list-style-type" is only inherited to child LI (not to child UL/OL); list-style-image and -position are fully inherited.</p>
<p>Lists in tables remain basic, as block-level elements are not supported inside tables.</p>
<p>Properties like text-align:justify will now be inherited from surrounding elements, which will change the appearance of lists designed with earlier versions of mPDF.</p>
<p>The attribute start="3" (integer) works for "OL"; it is an official (though depracated) HTML attribute.</p>
<p>List bullets (type = disc, circle or square) are now drawn rather than using font glyphs, for better consistency.</p>



<h4>List examples</h4>

<div style="border: 1px solid blue;">This demonstrates the appearance when list_auto_mode is set to \'mpdf\', compatible with previous mPDF versions. Indentation is set to zero (list_indent_default_mpdf). Note the top and bottom margin on the first list level only.
<ol style="list-style-type:upper-roman; border: 1px solid red;">
<li style="border: 1px solid #CCCCCC;">First item</li>
<li style="border: 1px solid #CCCCCC;">Second item
<ol style="list-style-type:upper-roman; border: 1px solid green;">
<li style="border: 1px solid #CCCCCC;">Next list level</li>
<li style="border: 1px solid #CCCCCC;">Second item</li>
</ol>
</li>
</ol>
</div>

<div style="border: 1px solid blue;">This demonstrates the same default settings, but list numbering is set to start at 32. Note how the indentation is adjusted to fit the maximum width of the numbering.
<ol start="32" style="list-style-type:upper-roman; border: 1px solid red;">
<li style="border: 1px solid #CCCCCC;">First item</li>
<li style="border: 1px solid #CCCCCC;">Second item
<ol start="32" style="list-style-type:upper-roman; border: 1px solid green;">
<li style="border: 1px solid #CCCCCC;">Next list level</li>
<li style="border: 1px solid #CCCCCC;">Second item</li>
</ol>
</li>
</ol>
</div>

<div style="border: 1px solid blue;">This demonstrates the appearance when list_auto_mode is set to \'browser\', compatible with browsers. Indentation is set to 40px (list_indent_default)
<ol style="list-style-type:upper-roman; border: 1px solid red; padding-left: 40px;">
<li style="border: 1px solid #CCCCCC;">First item</li>
<li style="border: 1px solid #CCCCCC;">Second item
<ol style="list-style-type:upper-roman; border: 1px solid green; padding-left: 40px;">
<li style="border: 1px solid #CCCCCC;">Next list level</li>
<li style="border: 1px solid #CCCCCC;">Second item</li>
</ol>
</li>
</ol>
</div>

<div style="border: 1px solid blue;">This demonstrates the same as the previous example, but list numbering is set to start at 32. Note that the default indentation remains fixed at 40px (list_indent_default)
<ol start="32" style="list-style-type:upper-roman; border: 1px solid red; padding-left: 40px;">
<li style="border: 1px solid #CCCCCC;">First item</li>
<li style="border: 1px solid #CCCCCC;">Second item
<ol start="32" style="list-style-type:upper-roman; border: 1px solid green; padding-left: 40px;">
<li style="border: 1px solid #CCCCCC;">Next list level</li>
<li style="border: 1px solid #CCCCCC;">Second item</li>
</ol>
</li>
</ol>
</div>

<div style="border: 1px solid blue;">This demonstrates control of the list-style-type, list-style-position and list-style-image.
<ol style="list-style-type:decimal; border: 1px solid red; padding-left: 40px;">
<li style="border: 1px solid #CCCCCC;">First item in list</li>
<li style="border: 1px solid #CCCCCC;">Second item</li>
<li style="border: 1px solid #CCCCCC; list-style-type: disc;">List style set as \'disc\'</li>
<li style="border: 1px solid #CCCCCC; list-style-type: none;">List style set as \'none\'</li>
<li style="border: 1px solid #CCCCCC; list-style-image:url(goto2.gif)">Using an image.</li>
<li style="border: 1px solid #CCCCCC; list-style-position: inside">List-style-position: inside.</li>
<li style="border: 1px solid #CCCCCC; list-style-type: U+263Argb(255,0,0);">User defined list bullet</li>
</ol>
</div>


<h3>Image transform</h3>

<p>The CSS property "transform" is supported on images (only). All transform functions are supported except matrix() i.e. translate(), translateX(), translateY(), skew(), skewX(), skewY(), scale(), scaleX(), scaleY(), and rotate()</p>

<p>Transforms cannot be used when using columns or Keep-with-table (use_kwt).</p>
<p>The CSS property background-color is now supported on images.</p>

<p>In the following examples, note the difference between transform (which is applied after layout) and image-orientation (which is applied before layout):</p>
<div style="border:1px solid red">
Hallo world
<img src="bayeux2.jpg" width="30mm" style="border:2px solid blue; padding:0.5em; background-color: yellow" />
Hallo world
</div>
<div style="border:1px solid red">
Hallo world
<img src="bayeux2.jpg" width="30mm" style="transform: rotate(30deg);border:2px solid blue; padding:0.5em; background-color: yellow" />
Hallo world
</div>
<div style="border:1px solid red">
Hallo world
<img src="bayeux2.jpg" width="30mm" style="transform:scale(0.5,1) skew(45deg,-45deg) translate(80mm,80mm);border:2px solid blue; padding:0.5em; background-color: yellow" />
Hallo world
</div>
<div style="border:1px solid red">
Hallo world
<img src="bayeux2.jpg" height="30mm" style="image-orientation:90deg;border:2px solid green; padding:0.5em; background-color: yellow" />
Hallo world (image-orientation)
</div>


<h3>CSS overline</h3>
<p>The CSS property "text-decoration: overline" is supported. Note that since mPDF 5.7.3 text-decoration use the parent inline block baseline/fontsize/color for child inline elements, and allows nested use of these values and superscript/subscript.</p>
<div style="font-size: 18pt;"><u>1<sup>st</sup> place</u>
  and compare with 
<u>1<sup><u>st</u></sup></u></div>

<div style="font-size:18pt">He <span style="text-decoration: line-through;color:green;"><span style="text-decoration:overline;">won</span> <span style="font-size:36pt;color:blue;">1<span style="text-decoration:underline; vertical-align:super;font-size:18pt;color:red;">st</span></span> place</span></div>


<pagebreak />

<h3>Headers and Footers</h3>
<p>Headers and Footers are all now written internally as HTMLheaders/footers. The use of non-HTML headers and footers is depracated, but remains supported. Non-HTML headers and footers are converted in mPDF to HTML equivalents.</p>

<p>Layout: This may mean that there will be a change in the resulting PDF. The main change is that an HTML table is created with three cells for left, right and middle; if you had a very long Left header item, it will not overwrite the center item, but it may wrap center onto 2 lines.</p>

<p>Naming: Default non-HTML headers will not clash with HTML headers, but named non-HTML headers WILL clash with (and overwrite) HTML headers of the same (equivalent) name e.g. html_MyFooter == MyFooter (non-HTML).</p>

<p>Aliases: {nb} or {nbpg} now only work in Headers or Footers, and not in the main text. {PAGENO} and {DATE ...} continue to only work in Headers or Footers.</p>

<p>ToC: Can now set the pagenumbering/style/reset/suppress for the ToC separately (see section on ToC).</p>

<p>The following are all depracated (but still supported) in favour of HTMLheader/footers:</p>
<ul>
<li>SetHeader()</li>
<li>SetFooter()</li>
<li>&lt;pageheader&gt;</li>
<li>&lt;pagefooter&gt;</li>
<li>DefHeaderByName()</li>
<li>DefFooterByName()</li>
<li>&lt;setpageheader&gt;</li>
<li>&lt;setpagefooter&gt;</li>
<li>SetHeaderByName()</li>
<li>SetFooterByName()</li>
</ul>

<p>If document direction is RTL (body dir=rtl, html dir=rtl), then you need to set directionality before setting non-HTML headers e.g.</p>
<p class="code">
	$mpdf = new mPDF();<br />
	$mpdf->SetDirectionality(\'rtl\');	// i.e. add this in<br />
	$mpdf->SetHeader($h);<br />
	$mpdf->SetFooter($f);<br />
	$mpdf->WriteHTML(\'&lt;body dir="rtl">...\');<br />
</p>


<pagebreak />

<h3>Table of Contents (ToC)</h3>
<p>Page numbering can now be applied and controlled for the pages containing a ToC.</p>

<p>There are three new parameters to control pagenumbering in the ToC: toc-resetpagenum, toc-pagenumstyle, and toc-suppress.
These are set as attributes in &lt;tocpagebreak&gt; or as the last 3 parameters in TOCpagebreak();
they set the pagenumbering and pagenumbering style for the ToC, and whether to suppress pagenumbers in ToC.</p>

<p>The default setting for all is to continue pagenumbering and pagenumstyle (and suppression) from pages preceding the ToC.</p>

<p>Note: Page numbering will <b>always</b> reset following a ToC. By default it will set it to 1, unless a value for resetpagenum is specified in TOCpagebreak or &lt;tocpagebreak&gt;.</p>

<p>Backwards compatibility: page numbers are no longer suppressed by default in ToC.</p>

<p>Although "suppress" and "toc-suppress" are supported, the recommended way to control whether page numbering appears is by using different headers and footers for each section.</p>

<p>Note: If you have 2 ToCs immediately following each other, and wish to use pagenumstyle or suppress to control the following text, then you need to set those values on both of the &lt;tocpagebreak&gt; elements.</p>

<p>The default CSS styles for ToCs and Indexes are now set in mpdf.css (see later).</p>

<p>See notes later on page numbering.</p>


<pagebreak odd-footer-name="myHTMLhebrew" odd-footer-value="1" even-footer-name="myHTMLhebrew" even-footer-value="1" pagenumstyle="hebrew" />

<h3>Other changes from mPDF 5</h3>

<h4>Setting up mPDF 6</h4>
<p>mPDF 6 has changed significantly from earlier version and it is recommended that a fresh install is used. You may wish to copy your previous config_* files and use them to update the new config files.</p>

<p><b>config_fonts.php</b> - values of "indic" and "unAglyphs" from previous versions are now redundant.</p>

<p><b>config_lang2fonts.php</b> - this is similar to the previous config_cp.php file; note however that $unifont (NOT $unifonts) must be only one font (not a comma-separated list as before).</p>

<p><b>Included fonts</b> - the Indic fonts e.g. ind_bn_001.ttf are no longer required (nor do they work properly with mPDF 6).</p>

<p><b>useLang</b> - this configurable variable, which used to be true by default, is now redundant. You may need to set: $mpdf-&gt;autoLangToFont = true; for the same results.</p>

<p><b>SetAutoFont()</b> - is now redundant. You may need to set: $mpdf-&gt;autoScriptToLang = true; for the same results.</p>

<p><b>Indexes</b> - have been largely redefined. See the section above.</p>

<p><b>Lists</b> - have been rewritten. See the section above.</p>

<p><b>Headers and Footers</b> - have been rewritten. See the section above.</p>


<div>A number of old depracated aliases will no longer be supported. Warning errors have been added to prompt you to change to the updated form:</div>
<ul>
<li>$mpdf->useOddEven - should now use - $mpdf->mirrorMargins</li>
<li>$mpdf->useSubstitutionsMB - should now use - $mpdf->useSubstitutions</li>
<li>$mpdf->AliasNbPg - should now use - $mpdf->aliasNbPg</li>
<li>$mpdf->AliasNbPgGp - should now use - $mpdf->aliasNbPgGp</li>
<li>$mpdf->BiDirectional - should now use - $mpdf->biDirectional</li>
<li>$mpdf->Anchor2Bookmark - should now use - $mpdf->anchor2Bookmark</li>
<li>$mpdf->KeepColumns - should now use - $mpdf->keepColumns</li>
<li>$mpdf->UnvalidatedText - should now use - $mpdf->watermarkText</li>
<li>$mpdf->TopicIsUnvalidated - should now use - $mpdf->showWatermarkText</li>
<li>$mpdf->Reference - should now use - $mpdf->IndexEntry</li>
</ul>

<div>The following functions have been removed:</div>
<ul>
<li>setUnvalidatedText - should now use - SetWatermarkText() </li>
<li>AddPages - should now use - AddPage() or HTML code methods </li>
<li>startPageNums</li>
<li>CreateReference and CreateIndex - cf. Index section above</li>
</ul>

<h4>Default style sheet</h4>
<p>A new mpdf.css file includes defaults for LISTS top/bottom margins, and also examples for Indexes and ToCs. This now acts like a normal CSS file, including cascading selectors i.e. not just main tags. This is always read (if present), so acts as a secondary default CSS, but one which allows selectors. Styles added to this act like a user stylesheet when considering precedence e.g. cellSpacing and border-spacing.</p>


<h4>Direct writing methods and OTL</h4>
<p>WriteText() WriteCell() Watermark() AutoSizeText() and ShadedBox() DO support complex scripts and right-to-left text (RTL).</p>
<p>Write() does NOT support complex scripts or RTL (NB this is a change - Write() used to support RTL).</p>
<p>CircularText() does NOT support complex scripts or RTL.</p>
<p>MultiCell() DOES support complex scripts and RTL, but complex-script line-breaking MAY NOT be accurate.</p>
MultiCell() does not support kerning and justification. NB This includes &lt;textarea&gt; in forms which uses MultiCell() internally.</p>
<p>&lt;select&gt; form objects also do NOT support kerning.</p>


<h4>Page numbering</h4>
<p>Page numbering i.e. by including {PAGENO} or {&#x200c;nbpg} in a header/footer, can use any of the number types as used for list-style e.g.</p>

<p class="code">&lt;pagebreak pagenumstyle="arabic-indic"&gt;</p>
<p>Short codes are recognised for the 5 most common:</p>
<ul>
<li>"1" - decimal</li>
<li>"A" = upper-latin or upper-alpha</li>
<li>"a" = lower-latin or lower-alpha</li>
<li>"I" = upper-roman</li>
<li>"i" = lower-roman</li>
</ul>
<p>or any of the following: 
arabic-indic, hebrew, bengali, devanagari, gujarati, gurmukhi, kannada, malayalam, oriya, persian, tamil, telugu, thai, urdu, cambodian, khmer, lao, cjk-decimal
</p>

<p>Note: A suitable font must be used in the header/footer in order to display the numbers in the selected script.</p>

<p>You can now set the pagenumberstyle from the beginning of the document by changing the configurable variable:</p>
<p class="code">
$this-&gt;defaultPageNumStyle = "arabic-indic";  // in config.php<br />
$mpdf-&gt;defaultPageNumStyle = "arabic-indic";  // at runtime<br />
</p>

<h4>Other Minor changes in mPDF 6</h4>
<p>\'hebrew\', \'khmer\', \'cambodian\', \'lao\', and \'cjk-decimal\' are recognised as values for "list-style-type" in numbered lists.</p>

<p>CSS "text-outline" is now supported on TD/TH tags</p>

<p>Text wrapping in tables has been improved when using CJK scripts (chinese-japanese-korean).</p>

<p>Text underline and strikethrough can be used together: <u><s>Hallo world</s></u>. Either &lt;u&gt;&lt;s&gt;...&lt;/s&gt;&lt;/u&gt; or &lt;span style="text-decoration:underline line-through;"&gt;...&lt;/span&gt; can be used</p>

<p>Added support for style="opacity:0.6;" in SVG - equivalent to: style="fill-opacity:0.6; stroke-opacity: 0.6;"</p>
Added support for opacity="0.6" (as attribute) in SVG - previously only supported fill-opacity="0.6" stroke-opacity="0.6"

<p>CSS position:absolute or fixed - rotate extended now to include rotate: 180; (previously just 90 or -90)</p>

<p>The default value of $this->keep_table_proportions = true; in config.php has been changed (see effect on Example 6 - nested table in top right cell).</p>

<p>Limited support has been added for SVG fonts embedded in SVG images (but not using @font-face rules) - see the separate Images demo file.</p>

<p>When using columns, the top margin is now collapsed at top of every column (not just first column of page).</p>

<p>The way mPDF handles optional end tags has been updated to be consistent with the <a href="http://www.w3.org/TR/html5/syntax.html#optional-tags">HTML5 specification</a>	- previously not well defined for HTML4.</p>

<p>Changes to the way lists are handled means that text-align:justify may be inherited by lists from surrounding block elements (which did not happen previously). See LISTS above for more information.</p>



<h3>Backwards Compatibility</h3>
<p>For maximum backwards comaptibility with older versions of mPDF, change the following configurable variables in the <span class="code">config.php</span> file:</p>
<table class="fontinfo">
<thead> 
<tr>
<td><br /></td>
<td>mPDF 6.0 Default (Browser compatible)<br /></td>
<td>Backwards Compatible</td>
</tr>
</thead> 
<tbody>
<tr>
<td>Normal Line-height</td>
<td>
<p>$this-&gt;useFixedNormalLineHeight = false;<br />$this-&gt;useFixedTextBaseline = false;<br />$this-&gt;adjustFontDescLineheight = 1.14;</p>
</td>
<td>$this-&gt;useFixedNormalLineHeight = true;<br />$this-&gt;useFixedTextBaseline = true;<br />$this-&gt;normalLineheight = 1.33;</td>
</tr>
<tr>
<td>Lists</td>
<td>
<p>$this-&gt;list_auto_mode = \'browser\';</p>
$this-&gt;list_marker_offset = \'5.5pt\';<br />$this-&gt;list_symbol_size = \'3.6pt\';</td>
<td>
<p>$this-&gt;list_auto_mode = \'mpdf\';</p>
<p>$this-&gt;list_marker_offset = \'0.45em\';<br />$this-&gt;list_symbol_size = \'0.31em\';</p>
</td>
</tr>
</tbody>
</table>



<h3>More Information</h3>
<p>For more information, see:</p>
<ul>
<li>About OTL: <a href="http://www.microsoft.com/typography/otspec/TTOCHAP1.htm">http://www.microsoft.com/typography/otspec/TTOCHAP1.htm</a></li>
<li>OTL tag Registry: <a href="http://www.microsoft.com/typography/otspec/ttoreg.htm">http://www.microsoft.com/typography/otspec/ttoreg.htm</a></li>
<li>OTL Features list: <a href="http://www.microsoft.com/typography/otspec/featurelist.htm">http://www.microsoft.com/typography/otspec/featurelist.htm</a></li>
<li>CSS3 Font Features: <a href="http://dev.w3.org/csswg/css-fonts/#font-rend-desc">http://dev.w3.org/csswg/css-fonts/#font-rend-desc</a></li>
</ul>

<pagebreak />

<h3>Font Information</h3>

<p>The following fonts are included with mPDF 6:</p>

<table class="fontinfo">
<thead> 
<tr>
<td>Font(s)</td>
<td>Download URL</td>
<td>Copyright / License<br /></td>
<td>Coverage</td>
</tr>
</thead> 
<tbody>
<tr>
<td>
<p>DejaVuSans</p>
<p>DejaVuSansCondensed</p>
<p>DejaVuSerif</p>
<p>DejaVuSerifCondensed</p>
<p>DejaVuSansMono</p>
</td>
<td>http://dejavu-fonts.org</td>
<td>
<p>&copy; Bitstream</p>
<p>http://dejavu-fonts.org/wiki/License</p>
</td>
<td>[Numerous]</td>
</tr>
<tr>
<td>
<p>FreeSans</p>
<p>FreeSerif</p>
<p>FreeMono</p>
</td>
<td>http://www.gnu.org/software/freefont/</td>
<td>
<p>GNU GPL v3</p>
</td>
<td>
<p>[Numerous incl. Indic]</p>
</td>
</tr>
<tr>
<td>Quivira</td>
<td>http://www.quivira-font.com/</td>
<td>
<p><i><span>free for   any use</span></i></p>
</td>
<td>
<p>Coptic</p>
<p>Buhid</p>
<p>Tagalog</p>
<p>Tagbanwa</p>
<p>Lisu</p>
</td>
</tr>
<tr>
<td>Abyssinica SIL</td>
<td>http://www.sil.org/resources/software_fonts/abyssinica-sil</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>Ethiopic</td>
</tr>
<tr>
<td>XBRiyaz</td>
<td>
<p>http://www.redlers.com/downloadfont.html</p>
<p>(<span>XW Zar fonts)</span></p>
<p><span>http://wiki.irmug.org/index.php/XWZar</span></p>
</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>Arabic</td>
</tr>
<tr>
<td>Taamey David CLM<br /></td>
<td>http://opensiddur.org/tools/fonts/</td>
<td>GNU GPL 2 <br /></td>
<td>Hebrew</td>
</tr>
<tr>
<td>
<p>Estrangelo Edessa</p>
</td>
<td>
<p>http://www.bethmardutho.org/index.php/resources/fonts.html</p>
<p>(SyrCOMEdessa.otf)</p>
</td>
<td>Adapted licence (free to use/share)<br /></td>
<td>Syriac</td>
</tr>
<tr>
<td>Aegean</td>
<td>http://users.teilar.gr/~g1951d/</td>
<td><i><span>free for   any use</span></i></td>
<td>
<p>Carian</p>
<p>Lycian</p>
<p>Lydian</p>
<p>Phoenecian</p>
<p>Ugaritic</p>
<p>Linear B</p>
<p>Old Italic</p>
</td>
</tr>
<tr>
<td>Jomolhari</td>
<td>https://sites.google.com/site/chrisfynn2/home/fonts/jomolhari</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>Tibetan</td>
</tr>
<tr>
<td>Lohitkannada</td>
<td>https://fedorahosted.org/lohit/ <br /></td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a> <br /></td>
<td>Kannada</td>
</tr>
<tr>
<td>Kaputaunicode</td>
<td>
<p>http://www.kaputa.com/slword/kaputaunicode.htm</p>
<p>http://www.locallanguages.lk/sinhala_unicode_converters</p>
</td>
<td>
<p>Free</p>
<p>Sri Lanka Web Community Center</p>
</td>
<td>Sinhala</td>
</tr>
<tr>
<td>Pothana2000</td>
<td>https://fedoraproject.org/wiki/Pothana2000_fonts</td>
<td>GNU GPL v2+</td>
<td>Telugu</td>
</tr>
<tr>
<td>Lateef</td>
<td>http://www.sil.org/resources/software_fonts/lateef</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>Sindhi</td>
</tr>
<tr>
<td>Khmeros</td>
<td>
<p>http://www.khmeros.info/en/fonts</p>
<p>(http://www.cambodia.org/fonts/)</p>
</td>
<td>LGPL Licence<br /></td>
<td>Khmer</td>
</tr>
<tr>
<td>Dhyana</td>
<td>
<p>Google Fonts</p>
<p>http://www.google.com/fonts/earlyaccess</p>
</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a> <br /></td>
<td>Lao</td>
</tr>
<tr>
<td>Tharlon</td>
<td>
<p>Google Fonts</p>
http://code.google.com/p/tharlon-font/</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>
<p>Myanmar</p>
<p>Tai Le</p>
</td>
</tr>
<tr>
<td>Padauk Book<br /></td>
<td>http://www.sil.org/resources/software_fonts/padauk</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>
<p>Myanmar</p>
</td>
</tr>
<tr>
<td>Ayar fonts</td>
<td>http://eng.ayarunicodegroup.org/</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a> <br /></td>
<td>Myanmar</td>
</tr>
<tr>
<td>ZawgyiOne</td>
<td>http://code.google.com/p/zawgyi/wiki/MyanmarFontDownload</td>
<td>
<p>Freely available.</p>
<p>No licence information available</p>
</td>
<td>
<p>Myanmar</p>
</td>
</tr>
<tr>
<td>
<p>Garuda</p>
</td>
<td>http://www.hawaii.edu/thai/thaifonts/</td>
<td>
<p>Freely available.</p>
No licence information available</td>
<td>Thai</td>
</tr>
<tr>
<td>Sundanese Unicode</td>
<td>http://sabilulungan.org/aksara/</td>
<td>GNU GPL<br /></td>
<td>Sundanese</td>
</tr>
<tr>
<td>Tai Heritage Pro</td>
<td>http://www.sil.org/resources/software_fonts/tai-heritage-pro</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>Tai Viet</td>
</tr>
<tr>
<td>
<p>Sun-ExtA</p>
<p>Sun-ExtB</p>
</td>
<td>http://www.alanwood.net/downloads/index.html</td>
<td>Freeware<br />(Beijing ZhongYi Electronics Co)</td>
<td>
<p>Chinese</p>
<p>Japanese</p>
<p>Runic</p>
</td>
</tr>
<tr>
<td>Unbatang</td>
<td>http://kldp.net/projects/unfonts/download</td>
<td>GNU GPL<br /></td>
<td>Korean</td>
</tr>
<tr>
<td>
<p>Aboriginal Sans</p>
</td>
<td>http://www.languagegeek.com/font/fontdownload.html <br /></td>
<td>
<p>GNU GPL 3</p>
<p>&nbsp;</p>
</td>
<td>
<p>Cree</p>
<p>Canadian Aboriginal</p>
<p>Inuktuit</p>
</td>
</tr>
<tr>
<td>MPH 2B Damase</td>
<td>http://www.alanwood.net/downloads/index.html</td>
<td>(Public domain) <br /></td>
<td>
<p>Glagolitic</p>
<p>Shavian</p>
<p>Osmanya</p>
<p>Kharoshthi</p>
<p>Deseret</p>
</td>
</tr>
<tr>
<td>Aegyptus</td>
<td>http://users.teilar.gr/~g1951d/</td>
<td><i><span>free for   any use</span></i></td>
<td>Egyptian Hieroglyphs</td>
</tr>
<tr>
<td>Akkadian</td>
<td>http://users.teilar.gr/~g1951d/</td>
<td><i><span>free for   any use</span></i></td>
<td>Cuneiforn</td>
</tr>
<tr>
<td>Eeyek Unicode</td>
<td>http://tabish.freeshell.org/eeyek/download.html</td>
<td>Freeware</td>
<td>Meetei Mayek</td>
</tr>
<tr>
<td>Lannaalif</td>
<td>http://www.geocities.jp/simsheart_alif/taithamunicode.html</td>
<td>(Unclear)</td>
<td>Tai Tham</td>
</tr>
<tr>
<td>Daibanna SIL Book</td>
<td>http://www.sil.org/resources/software_fonts/dai-banna-sil</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">SIL Open Font License</a></td>
<td>New Tai Lue</td>
</tr>
<tr>
<td>KFGQPC Uthman Taha Naskh<br /></td>
<td>http://fonts.qurancomplex.gov.sa/?page_id=42</td>
<td><a href="http://scripts.sil.org/ofl" target="_blank">https://www.ohloh.net/licenses/KFGQPC</a></td>
<td>
<p>Arabic</p>
<p>(Koran/Quran)</p>
</td>
</tr>
</tbody>
</table>

';
//==============================================================
$mpdf->h2bookmarks = array('H3'=>0, 'H4'=>1);
$mpdf->defaultPageNumStyle = 'arabic-indic';

$mpdf->autoLangToFont = true;
$mpdf->WriteHTML($html);

$mpdf->Output(); 

exit;



?>