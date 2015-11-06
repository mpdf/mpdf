<?php

include("../mpdf.php");

$mpdf=new mPDF(''); 


//==============================================================

$html = '
<style>
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
	background-color: #d5d5d5; 
	margin: 1em 1cm;
	padding: 0 0.3cm;
	border:0.2mm solid #000088; 
	box-shadow: 0.3em 0.3em #888888;
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

.aBDP { text-align: "." center; }
.arBDP { text-align: "\66B" center; }
.middot { text-align: "\B7" center ; }

p.menu {
	text-align: justify;
	padding-right: 4em;
}
dottab.menu {
	outdent: 4em;
}


.outlined {
	text-outline: 0.1mm 0.1mm #FF0000;
	font-weight: bold;
	font-size: 20pt; 
	color: #FFFFFF;
}

/* For background-clip and -origin */
.divclip {
	border: 10px dashed #000000;
	border-radius: 3em;
	padding: 20px;
	background:yellow;
	background-image: -moz-linear-gradient(top right 210deg, red, orange, yellow, green, blue, indigo, violet);
	width: 300px;
	height: 50px;
	margin-bottom: 1em;
	background-repeat: no-repeat ;
	background-size: 100% 100%;
}
.divpic {
	background:yellow;
	background: yellow url("bayeux1.jpg") no-repeat scroll left top; 
	background-size: 100% 100%;
}
.div1 {
	background-clip: content-box;
	background-origin: content-box;
}
.div2 {
	background-clip: padding-box;
	background-origin: padding-box;
}
.div3 {
	background-clip: border-box;
	background-origin: border-box;
}
.div4 {
	background-clip: content-box;
	background-origin: border-box;
}
.div5 {
	background-clip: border-box;
	background-origin: content-box;
}


/* For Table of Contents */
div.mpdf_toc {
	font-family: sans-serif;
	font-size: 11pt;
}
a.mpdf_toc_a  {
	text-decoration: none;
	color: black;
}
div.mpdf_toc_level_0 {		/* Whole line level 0 */
	line-height: 1.5;
	margin-left: 0;
	padding-right: 2em;	/* should match e.g <dottab outdent="2em" /> 0 is default */
}
span.mpdf_toc_t_level_0 {	/* Title level 0 - may be inside <a> */
	font-weight: bold;
}
span.mpdf_toc_p_level_0 {	/* Page no. level 0 - may be inside <a> */
}
div.mpdf_toc_level_1 {		/* Whole line level 1 */
	margin-left: 2em;
	text-indent: -2em;
	padding-right: 2em;	/* should match <dottab outdent="2em" /> 2em is default */
}
span.mpdf_toc_t_level_1 {	/* Title level 1 */
	font-style: italic;
	font-weight: bold;
}
span.mpdf_toc_p_level_1  {	/* Page no. level 1 - may be inside <a> */
}
div.mpdf_toc_level_2 {		/* Whole line level 2 */
	margin-left: 4em;
	text-indent: -2em;
	padding-right: 2em;	/* should match <dottab outdent="2em" /> 2em is default */
}
span.mpdf_toc_t_level_2 {	/* Title level 2 */
}
span.mpdf_toc_p_level_2 {	/* Page no. level 2 - may be inside <a> */
}

</style>
<body>

<tocpagebreak links="on" toc-preHTML="&lt;div class=&quot;shadowtitle&quot;&gt;New Features in mPDF v5.7&lt;/div&gt;&lt;h3&gt;Table of Contents&lt;/h3&gt;" toc-bookmarktext="Table of Contents"/>


<h3>ToC Layout and styling</h3>
<div class="gradient text">
<h4>Table of Contents styling</h4>
<p>When a Table of Contents is generated by mPDF using e.g. &lt;tocpagebreak&gt;, mPDF 5.7 will generate the ToC as HTML. This means that a CSS stylesheet can be used to format its appearance.</p>
</div>

<div class="gradient text">
<p>Example table of contents:</p>
<div class="mpdf_toc" id="mpdf_toc_0">
	<div class="mpdf_toc_level_0">
		<a class="mpdf_toc_a" href="#__mpdfinternallink_1"><span class="mpdf_toc_t_level_0">Section 1</span></a>
		<dottab outdent="2em" />
		<a class="mpdf_toc_a" href="#__mpdfinternallink_1"><span class="mpdf_toc_p_level_0">5</span></a>
	</div>
	<div class="mpdf_toc_level_1">
		<a class="mpdf_toc_a" href="#__mpdfinternallink_2"><span class="mpdf_toc_t_level_1">Chapter 1</span></a>
		<dottab outdent="2em" />
		<a class="mpdf_toc_a" href="#__mpdfinternallink_2"><span class="mpdf_toc_p_level_1">6</span></a>
	</div>
	<div class="mpdf_toc_level_2">
		<a class="mpdf_toc_a" href="#__mpdfinternallink_3"><span class="mpdf_toc_t_level_2">Topic 1</span></a>
		<dottab outdent="2em" />
		<a class="mpdf_toc_a" href="#__mpdfinternallink_3"><span class="mpdf_toc_p_level_2">7</span></a>
	</div>
</div>
</div>

<div class="gradient text">
<p>This will result in the following HTML code generated (internally):</p>
<p class="code">
&lt;div class="mpdf_toc" id="mpdf_toc_0"&gt;<br />
&nbsp; &lt;div class="mpdf_toc_level_0"&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_1"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_t_level_0"&gt;Section 1&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &nbsp; &lt;dottab outdent="2em" /&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_1"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_p_level_0"&gt;5&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &lt;/div&gt;<br />
&nbsp; &lt;div class="mpdf_toc_level_1"&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_2"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_t_level_1"&gt;Chapter 1&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &nbsp; &lt;dottab outdent="2em" /&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_2"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_p_level_1"&gt;6&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &lt;/div&gt;<br />
&nbsp; &lt;div class="mpdf_toc_level_2"&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_3"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_t_level_2"&gt;Topic 1&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &nbsp; &lt;dottab outdent="2em" /&gt;<br />
&nbsp; &nbsp; &lt;a class="mpdf_toc_a" href="#__mpdfinternallink_3"&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;span class="mpdf_toc_p_level_2"&gt;7&lt;/span&gt;<br />
&nbsp; &nbsp; &lt;/a&gt;<br />
&nbsp; &lt;/div&gt;<br />
&lt;/div&gt;
</p>

<p>NB The id is "0" (mpdf_toc_0) for root/un-named ToC; otherwise it is lowercase of the name="" used for the ToC</p>
</div>

<div class="gradient text">

<p>Example Styling using CSS</p>
<p>The following CSSwill format the ToC as it appears in this document:</p>
<p class="code">
/* For Table of Contents */<br />
div.mpdf_toc {<br />
&nbsp; font-family: sans-serif;<br />
&nbsp; font-size: 11pt;<br />
}<br />
a.mpdf_toc_a  {<br />
&nbsp; text-decoration: none;<br />
&nbsp; color: black;<br />
}<br /><br />
/* Whole line level 0 */<br />
div.mpdf_toc_level_0 {<br />
&nbsp; line-height: 1.5;<br />
&nbsp; margin-left: 0;<br />
&nbsp; padding-right: 2em;<br />
}<br /><br />
/* Title level 0 - may be inside &lt;a&gt; */<br />
span.mpdf_toc_t_level_0 {<br />
&nbsp; font-weight: bold;<br />
}<br /><br />
/* Page no. level 0 - may be inside &lt;a&gt; */<br />
span.mpdf_toc_p_level_0 { }<br /><br />
/* Whole line level 1 */<br />
div.mpdf_toc_level_1 {<br />
&nbsp; margin-left: 2em;<br />
&nbsp; padding-right: 2em;<br />
}<br /><br />
/* Title level 1 */<br />
span.mpdf_toc_t_level_1 {<br />
&nbsp; font-style: italic;<br />
&nbsp; font-weight: bold;<br />
}<br /><br />
/* Page no. level 1 - may be inside &lt;a&gt; */<br />
span.mpdf_toc_p_level_1  { }<br /><br />
/* Whole line level 2 */<br />
div.mpdf_toc_level_2 {<br />
&nbsp; margin-left: 4em;<br />
&nbsp; padding-right: 2em;<br />
}<br /><br />
/* Title level 2 */<br />
span.mpdf_toc_t_level_2 { }<br /><br />
/* Page no. level 2 - may be inside &lt;a&gt; */<br />
span.mpdf_toc_p_level_2 { }<br /><br />
</p>
<p>NB padding-right should match &lt;dottab&gt; "outdent" (0 is default). See &lt;dottab&gt; for more details</p>
</div>

<div class="gradient text">
<p>The functions TOCpagebreakByArray() and TOCpagebreak() have a new final parameter, and HTML tags &lt;TOC&gt; and &lt;TOCpagebreak&gt; have a new attribute "tocoutdent". This should be blank or a valid CSS length e.g. "2em". See &lt;dottab&gt; for more details.</p>
</div>





<h3>Text-align on decimal point</h3>
<div class="gradient text">
<p>Text inside a table column can be aligned on a decimal point (or any other character) by using either HTML attribute or CSS.</p>
<p>This example table uses the following CSS stylesheet:</p>
<p class="code">
&lt;style&gt;<br />
.aBDP { text-align: "." center; }<br />
.arBDP { text-align: "\66B" center; }<br />
.middot { text-align: "\B7" center ; }<br />
&lt;/style&gt;
</p>
<p></p>


<table border="1" style="border-collapse: collapse;" align="center">  
  <tr><th>&lt;TD&gt; element</th><th>Column</th></tr>
  <tr><td class="code">align="left"</td><td align="left">Left text</td></tr>  
  <tr><td class="code">align="right"</td><td align="right">Right text</td></tr>  
  <tr><td class="code">align="center"</td><td align="center">Center text</td></tr>  
  <tr><td class="code">align="char"</td><td align="char"><p>1000.0001</p></td></tr>
  <tr><td class="code">align="char" char=","</td><td align="char" char=","><p>1000,0001</p></td></tr>
  <tr><td class="code">align="char" char="&amp;middot;"</td><td align="char" char="&middot;"><p>1000&#183;0001</p></td></tr>
  <tr><td class="code">align="char" char="&amp;#183;"</td><td align="char" char="&#183;"><p>1000&#183;0001</p></td></tr>
  <tr><td class="code">style="text-align: \'.\' center"</td><td style="text-align: \'.\' center">100.001</td></tr>  
  <tr><td class="code">style="text-align: \'.\' center"</td><td style="text-align: \'.\' center">DP aligned text</td></tr>  
  <tr><td class="code">style="text-align: \',\' center"</td><td style="text-align: \',\' center"><p>1.000,0001</p></td></tr>
  <tr><td class="code">class="aBDP"</td><td class="aBDP">10.01</td></tr>  
  <tr><td class="code">class="aBDP"</td><td class="aBDP">1000</td></tr>  
  <tr><td class="code">class="aBDP"</td><td class="aBDP"><p>1000.0001</p></td></tr>
  <tr><td class="code">class="middot"</td><td class="middot">1&#8201;000&#183;0001</td></tr>
  <tr><td class="code">class="aBDP"</td><td class="aBDP"><p>1,000,000.00001</p></td></tr>
  <tr><td class="code">class="aBDP"</td><td class="aBDP">1.000000001</td></tr>
  <tr><td class="code">class="aBDP"</td><td class="aBDP">1.000.000.001</td></tr>
  <tr><td class="code">class="arBDP"</td><td class="arBDP"><p style="lang: ar">&#x661;&#x66c;&#x665;&#x666;&#x667;&#x66c;&#x662;&#x663;&#x664;&#x66b;&#x662;&#x663;&#x664;&#x667;</p></td></tr>
  <tr><td class="code">class="aBDP"</td><td class="aBDP">(GBP) 1,000,000.00001<br />1,000,000.00001 (EUR)<br />1,000,000.00001</td></tr>  
</table>

</div>




<pagebreak />

<h3>Automatic ToC and Bookmarks</h3>
<div class="gradient text">
<p>A Table of Contents and/or Bookmarks can be generated automatically from any of the heading tags H1 - H6. This example will generate ToC and bookmarks from all &lt;h3&gt; tags (top level) and &lt;h4&gt; tags (next level)</p>
<p class="code">
	$mpdf->h2toc = array(\'H3\'=>0, \'H4\'=>1);<br />
	$mpdf->h2bookmarks = array(\'H3\'=>0, \'H4\'=>1);
</p>
</div>






<h3>Improved line-breaking</h3>
<p>mPDF will now avoid line-breaks in the middle of words even between &lt;tags&gt;</p>
<div class="gradient text">
<p class="code">
&lt;b>Na&lt;sub&gt;2&lt;/sub&gt;HCO&lt;sub&gt;3&lt;/sub&gt;&lt;/b&gt;
</p>
<p>Pellentesque purus feugiat semper. Donec nunc odio, et vitae pellentesque. Pellentesque <b>Na<sub>2</sub>HCO<sub>3</sub></b> velit lacus.</p>
</div>


<h3>CSS hyphens</h3>
<div class="gradient text">
<p>The CSS property hyphens is now supported on all block elements</p>
<p class="code">
hyphens: manual | auto | none
</p>
<p>In the following example, the word interdependent contains no soft hyphen or similar characters, and is moved to the next line.</p>

<p style="hyphens:none;border: 1px solid #000000;">Cum velit lacus pena sociis natoque penatibus et magnis disa montes, nascetur ridicuus interdependent (no characters suggesting line-break).</p>

<p><b>manual</b> (default)
	Words are only broken at line breaks where there are characters inside the word that suggest line break opportunities. Characters can be explicit ("-" hard hyphen) or conditional (&amp;shy; &amp;#173; &lt;wbr&gt;). </p>
<p style="hyphens:manual;border: 1px solid #000000;">Cum velit lacus pena sociis natoque penatibus et magnis disa montes, nascetur ridicuus inter&shy;dependent (uses soft hyphen &amp;shy;).</p>

<p><b>none</b> - Words are not broken at line breaks, even if characters inside the word suggest line break points. </p>
<p style="hyphens:none;border: 1px solid #000000;">Cum velit lacus pena sociis natoque penatibus et magnis disa montes, nascetur ridicuus inter-dependent (hard hyphen).</p>

<p><b>auto</b>
	Words can be broken at appropriate hyphenation points, as determined by characters inside the word.</p>
<p style="hyphens:auto;border: 1px solid #000000;">Cum velit lacus pena sociis natoque penatibus et magnis disa montes, nascetur ridicuus interdependent (No characters suggesting line-break).</p>
<p>SHY inside the word take priority over hyphenation points determined by other resources. </p>
<p style="hyphens:auto;border: 1px solid #000000;">Cum velit lacus pena sociis natoque penatibus et magnis disa montes, nascetur ridicuus inter&shy;dependent (uses soft hyphen &amp;shy;).</p>

<p>The configurable variables $this-&gt;hyphenate and $this-&gt;hyphenateTables are henceforth redundant and have no effect.</p>
<p>NB Support for &lt;wbr&gt; is new in mPDF 5.7</p>
</div>





<h3>Text circle</h3>
<div class="gradient text">
<p>Added in mPDF 5.6 but not included in New Features example - transparent background and Divider were new. Now also added support for font-size:auto</p>
<p class="code">
&lt;textcircle r="30mm" top-text="Text Circular Text Circular" bottom-text="Text Circular Text Circular" divider="&amp;bull;" style="font-size: auto" /&gt;
</p>
<p></p>
<div align="center"><textcircle r="30mm" top-text="Text Circular Text Circular" bottom-text="Text Circular Text Circular" divider="&bull;" style="font-size: auto" /></div>
</div>


<h3>List numbering</h3>
<div class="gradient text">
<p>This list is set to start numbering at 5</p>
<p class="code">&lt;ol start="5"&gt;</p>
<ol start="5">
<li>List item number 1</li>
<li>List item number 2</li>
<li>List item number 3</li>
</ol>
</div>




<h3>&lt;dottab&gt; and outdent</h3>
<div class="gradient text">
<p>&lt;dottab&gt; now supports a custom CSS property "outdent", which can also be used as an HTML attribute i.e. &lt;dottab outdent="2em"&gt;</p>
<p>The first item uses &lt;dottab outdent="4em"&gt; whereas the following items have &lt;dottab class="menu"&gt; (with CSS <code>dottab.menu{outdent: 4em;}</code> ) and all have padding-right="4em" on the &lt;div&gt; element</p>
<div style="border: 0.2mm solid #000088; padding: 1em;">
<p class="menu">Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus <dottab outdent="4em" />&nbsp;&pound;37.00</p>

<p class="menu">Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat <dottab class="menu" />&nbsp;&pound;3700.00</p>

<p class="menu">Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus <dottab class="menu" />&nbsp;&pound;27.00</p>

<p class="menu">Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod <dottab class="menu" />&nbsp;&pound;7.00</p>

<p class="menu">Donec et nulla. Sed quis orci <dottab class="menu" />&nbsp;&pound;1137.00</p>
</div>
<p></p>
<p class="code">
p.menu { text-align: justify; padding-right: 4em; }<br />
dottab.menu { outdent: 4em; }
</p>
<p>NB It is recommended to use &amp;nbsp; after the dottab if a space is required before the following content.</p>
<p>NB This (outdent) is also used in the Table of Contents (see earlier in this document).</p>
</div>



<h3>Layers</h3>
<div class="gradient text">
<p>mPDF 5.7 will create layers in the document using the CSS property z-index. All layers are visible by default.</p>
<p>This demonstrates layers in a PDF document. Select the layers named "Correct Answers" or "Wrong Answers" in your PDF viewer (the layers pane should be open already in Adobe Acrobat)</p>
<div><b>What is the name of the Prime Minister of Britain?</b></div>
<div style="z-index:1;color: green;float: left; width:30%">David Cameron</div>
<div style="z-index:2;color: red;">Rupert Murdoch</div>
<div><b>What is the name of the David Beckham\'s bulldog?</b></div>
<div style="z-index:1;color: green;float: left; width:30%;">Scarlet</div>
<div style="z-index:2;color: red;">Victoria</div>
<p>To open/close/select layers in Adobe Reader (10):<br />
<img src="layers_tab.jpg" /></p>
<p>The layer names and initial state can be set (optionally) e.g.</p>
<p class="code">
	$mpdf-&gt;layerDetails[1][\'state\']=\'hidden\';	// Set initial state of layer - "hidden" or ""<br />
	$mpdf-&gt;layerDetails[1][\'name\']=\'Correct Answers\';<br />
	$mpdf-&gt;layerDetails[2][\'state\']=\'hidden\';<br />
	$mpdf-&gt;layerDetails[2][\'name\']=\'Wrong Answers\';<br />
</p>
<p>This is the code used in the example above:</p>
<p class="code">
	&lt;div style="z-index:1;color: green;float: left; width:30%;"&gt;Scarlet&lt;/div&gt;<br />
	&lt;div style="z-index:2;color: red;"&gt;Victoria&lt;/div&gt;
</p>
<div>To force the PDF reader to open with the layers tab open, set:
<p class="code">$mpdf-&gt;open_layer_pane = true;</p>
</div>

</div>



<h3>CSS visibility on &lt;span&gt;</h3>
<p>CSS visibility:hidden is now supported on inline elements e.g. &lt;span&gt;</p>
<div class="gradient text">
<p>This next bit of text is hidden - <span style="visibility:hidden; border:1px solid #880000;background-color:yellow">Hidden text</span> - and this isn\'t.</p>
<p class="code">style="visibility:hidden;"</p>
<p>This next bit of text is only visible in print - <span style="visibility:printonly; border:1px solid #008800;background-color:yellow">Hidden text</span> - and this isn\'t.</p>
<p class="code">style="visibility:printonly;"</p>
<p>This next bit of text is only visible on screen - <span style="visibility:screenonly; border:1px solid #000088;background-color:yellow">Hidden text</span> - and this isn\'t.</p>
<p class="code">style="visibility:screenonly;"</p>
<p>You can show or hide these elements as for layers (above).</p>
</div>



<h3>CSS "rem" unit</h3>
<div class="gradient text">

<div style="font-size: 1rem; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 1rem
<div style="font-size: 0.5rem; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 0.5rem
<div style="font-size: 1.5rem; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 1.5rem
</div>
</div>
</div>
<div style="font-size: 1em; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 1em
<div style="font-size: 0.5em; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 0.5em
<div style="font-size: 1.5em; border: 1px solid #888888; padding: 5px 20px;">This line has the font-size set as 1.5em
</div>
</div>
</div>

</div>




<h3>CSS outline</h3>
<div class="gradient text">
<div class="outlined">This is text with an outline set by CSS</div>
<p class="code">
.outlined { text-outline: 0.1mm 0.1mm #FF0000; }
</p>
</div>





<h3>CSS background-clip, background-origin &amp; background-size</h3>
<div class="gradient text">
<p>CSS background-clip, background-origin &amp; background-size are now supported for most block level elements. (Not supported in tables, nor on page/body backgrounds).</p>


<div class="divclip">background-clip: border-box[default value]<br />background-origin: padding-box[default value]</div>
<div class="divclip div1">background-clip: content-box;<br />background-origin: content-box</div>
<div class="divclip div2">background-clip: padding-box;<br />background-origin: padding-box</div>
<div class="divclip div3">background-clip: border-box;<br />background-origin: border-box</div>
<div class="divclip div4">background-clip: content-box;<br />background-origin: border-box</div>
<div class="divclip div5">background-clip: border-box;<br />background-origin: content-box</div>

<div class="divclip divpic">background-clip: border-box[default value]<br />background-origin: padding-box[default value]</div>
<div class="divclip div1 divpic">background-clip: content-box;<br />background-origin: content-box</div>
<div class="divclip div2 divpic">background-clip: padding-box;<br />background-origin: padding-box</div>
<div class="divclip div3 divpic">background-clip: border-box;<br />background-origin: border-box</div>
<div class="divclip div4 divpic">background-clip: content-box;<br />background-origin: border-box</div>
<div class="divclip div5 divpic">background-clip: border-box;<br />background-origin: content-box</div>


</div>


';

$mpdf->h2toc = array('H3'=>0, 'H4'=>1);
$mpdf->h2bookmarks = array('H3'=>0, 'H4'=>1);

$mpdf->open_layer_pane = false;
$mpdf->layerDetails[1]['state']='hidden';	// Set initial state of layer - "hidden" or nothing
$mpdf->layerDetails[1]['name']='Correct Answers';
$mpdf->layerDetails[2]['state']='hidden';	// Set initial state of layer - "hidden" or nothing
$mpdf->layerDetails[2]['name']='Wrong Answers';


//==============================================================
$mpdf->autoLangToFont = true;

$mpdf->WriteHTML($html);

// OUTPUT
$mpdf->Output(); exit;


//==============================================================
//==============================================================
//==============================================================
//==============================================================
