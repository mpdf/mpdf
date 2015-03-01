<?php

if (!isset($_REQUEST['html'])) { $_REQUEST['html'] = ''; }

include("../mpdf.php");

$mpdf=new mPDF(''); 

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
<body>


<div class="shadowtitle">New Features in mPDF v6.0</div>

<h3>Images</h3>

<h4>Gamma correction in PNG images</h4>
<p>Some PNG images contain a source Gamma correction value to maintain consistent colour display between devices.</p>
<p>mPDF will adjust for gamma correction if a PNG image has a source gAMA entry &lt;&gt; 2.2</p>
<p>Gamma correction is not supported in GIF files.</p>

<p>For more information, and sample image files see <a href="http://www.libpng.org/pub/png/colorcube/gamma-consistency-test.html">http://www.libpng.org/pub/png/colorcube/gamma-consistency-test.html</a></p>

<p>Below are some of the example images, displayed on a background of HTML colour, such that when displayed correctly they should appear as one solid block of the same colour:</p>



<table border="0" cellpadding="10" cellspacing="1" style="font-size: 8pt">
  <tbody>
  <tr>
    <td align="center" bgcolor="#cc9900"><img src="cc9900.gif" alt="" width="48" height="48"></td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center" bgcolor="#cc9900"><img src="cc9900_003.png" alt="" width="48" height="48"></td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center" bgcolor="#cc9900"><img src="cc9900.png" alt="" width="48" height="48"></td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center" bgcolor="#cc9900"><img src="cc9900_004.png" alt="" width="48" height="48"></td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center" bgcolor="#cc9900"><img src="cc9900_002.png" alt="" width="48" height="48"></td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center" bgcolor="#cc9900"><img src="cc9900_005.png" alt="" width="48" height="48"></td>
  </tr>
  <tr>
    <td align="center">Unlabelled GIF image<br />(the usual kind)</td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center">Unlabelled PNG image<br />(no gamma info)</td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center">PNG image with gamma 1/1.6<br />(i.e. 0.625)</td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center">PNG image with gamma 1/2.2<br />(i.e. 0.4545)</td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center">sRGB PNG image<br />("absolute colorimetric" rendering intent "sRGB")</td>
    <td><font size="1">&nbsp;</font></td>
    <td align="center">iCCP PNG images<br />("gamma 1.0" pixel data, linear ICC profiles: "iCCPGamma 1.0 profile")</td>
  </tr>
</tbody></table>

<div>NB View this page as <a href="example63_new_mPDF_v6-0_features Images.php?html=1">HTML in your browser</a> and see the difference between browsers!</div>


<p>Note that there are inconsistencies between browsers, so the image display varies considerably on the system you are using. There are also image errors which are not always apparent.</p>


<p>The image below is taken from <a href="http://www.w3.org/TR/CSS21/intro.html">http://www.w3.org/TR/CSS21/intro.html</a> and has the gAMA value set to 1.45454   This is probably unintentional and should be 0.45454 which is 1 / 2.2</p>
<p>The image appears differently on IE9/Safari versus Firefox/Opera. To quote from http://www.libpng.org/pub/png/spec/1.2/PNG-Encoders.html "If the source file\'s gamma value is greater than 1.0, it is probably a display system exponent,....and you should use its reciprocal for the PNG gamma."
Some applications seem to ignore this, displaying the image how it was probably intended.</p>

<img src="bach1.png" />

<pagebreak />

<p>The two images below should be displayed with similar colour intensity. The one on the left is a 16-bit gamma-corrected PNG file; on the right is an unlabelled GIF file. Note that in Firefox (31.0) the image on the left looks duller because it has not been gamma-corrected for the display, whilst in IE (9)/Safari/Opera the images looks identical (correct).</p>
<p style="background-color: #cc9900; padding: 2em;"><img src="butterfly_ProPhoto.png" > <img src="butterfly_ProPhoto.gif" ></p>

<br />

<h4>ICC color profiles</h4>
<p>Some PNG and JPEG image files contain an ICC color profile to alter colour display. These are supported in mPDF in PNG and JPG files, except PNG images which require converting via gd_image e.g. Alpha transparency, interlaced etc.</p>

<p>Below is a PNG image which contains an ICC Profile which deliberately changes colours. When correctly displayed (applying the colour profile) ths colours should match the colour names. (In Firefox red appears as green, blue as red etc.)</p>

<div style="background-color: #cc9900; padding: 2em;"><img src="colourTestFakeBRG.png" width="300px" /></div>

<pagebreak />

<h4>Wider support for PNG Images</h4>
<p>mPDF will now display almost every type of PNG image, including: paletted (Indexed) images with Alpha channel (full transparency), and grayscale or RGB truecolor images with single-colour transparency. One PNG type which cannot be handled by mPDF is a 16-bit image with binary (single-color) transparency. (This is because all images need to be converted to 8-bit for inclusion in PDF, and so for example if colour 0x4F27 is set as a transparency, it will treat all 0x4F.. pixels as transparent.)</p>



<h4>Alpha transparency (PNG images)</h4>
Alpha transparency in PNG images has been fixed to work correctly against colour backgrounds e.g.:
<table>
<tr>
<td style="background-color: transparent;">
<img src="alpha09.png" height="180px" />
<img src="alpha36.png" height="180px" />
</td>
<td style="background-color: darkblue;">
<img src="alpha09.png" height="180px" />
<img src="alpha36.png" height="180px" />
</td>
</tr>
</table>




<h4>Interpolation</h4>
<p>PDF allows you to set image interpolation for an image - the result of this is variable and is dependent on the PDF viewer.
mPDF allows you to specify whether interpolation is enabled, using CSS for each image, but: 1) it will not cascade i.e. the CSS property must be set directly on the img object or as class e.g. using &lt;img class="smooth" style="image-rendering:auto"&gt;  2) if an image appears more than once in the document, the interpolation setting will be that of the first appearance.</p>

<p>A configurable variable in config.php determines the default value for the whole document: $this-&gt;interpolateImages = false;</p>

<p>The draft CSS3 property "image-rendering" with the following values will be recognised by mPDF:</p>
<ul>
<li>auto (default) - uses the value set by $this-&gt;interpolateImages</li>
<li>crisp-edges - interpolation disabled</li>
<li>optimizequality - interpolation enabled</li>
<li>smooth - interpolation enabled</li>
</ul>

<p>The image below on the left has interpolation enabled:</p>

<div>
<img style="image-rendering:smooth; image-rendering:optimizeQuality; -ms-interpolation-mode:bicubic; " src="bgan6a16.png" width="300px" />
<img style="image-rendering:crisp-edges; image-rendering:-moz-crisp-edges; image-rendering:-o-crisp-edges; image-rendering:-webkit-optimize-contrast; -ms-interpolation-mode: nearest-neighbor;" src="bgan6a162.png" width="300px" />
</div>

<p>NB Interpolation cannot be enabled on background images, SVG or WMF images.</p>




<h4>SVG Fonts</h4>
<p>mPDF 6 introduces (limited) support for SVG fonts, recognising the following elements and attributes:</p>
<p class="code">
&lt;defs&gt;<br />
&lt;font <i>horiz-adv-x</i>&gt;<br />
&lt;font-face <i>font-family units-per-em ascent descent</i> /&gt;<br />
&lt;missing-glyph <i>horiz-adv-x d</i> /&gt;<br />
&lt;glyph <i>unicode horiz-adv-x d</i> /&gt;
</p>

<p>In the example SVG below, the upper row of characters are drawn using paths and lines; in the lower row, they are written as text using glyphs defined as an SVG font.</p>
<img src="fonts-elem-01-t.svg" width="480px" height="360px" />

<p>See the fonts-elem-01-t.svg file in the examples folder for more details.</p>
<p>NB: @font-face is not supported.</p>



';
//==============================================================
if (isset($_REQUEST['html']) && $_REQUEST['html']) { echo $html; exit; }
//==============================================================

$mpdf->WriteHTML($html);

$mpdf->Output(); 

exit;



?>