<?php

$html = '
<style>
ol, ul { text-align: justify; 
}

.lista { list-style-type: upper-roman; }
.listb{ list-style-type: decimal; font-family: sans-serif; color: blue; font-weight: bold; font-style: italic; font-size: 19pt; }
.listc{ list-style-type: upper-alpha; padding-left: 25mm; }
.listd{ list-style-type: lower-alpha; color: teal; line-height: 2; }
.liste{ list-style-type: disc; }
.listarabic { direction: rtl; list-style-type: arabic-indic; font-family: dejavusanscondensed; padding-right: 40px;}
</style>


<h1>mPDF</h1>
<h2>Lists</h2>

<div style="background-color:#ddccff; padding:0pt; border: 1px solid #555555;">
<ol class="lista">
<li>Text here lorem ipsum ibisque totum.</li>
<li><span style="color:green; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</span></li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum. Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listb">
<li>Text here lorem ipsum ibisque totum.</li>
<li><span style="color:green; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</span></li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.
<ol class="listc">
<li>Big text indent 25mm: Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.
</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listd">
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="liste">
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum. 
<ol class="listc">
<li>Big text indent 25mm: Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol class="listd">
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.
<ol class="liste">
<li>Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.
<ol>
<li>No class specified. Text here lorem ipsum ibisque totum.</li>
<li style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>
</li>
</ol>
</li>
</ol>
</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem <span style="color:red; font-size:9pt; font-family:courier; font-weight: normal; font-style: normal;">ipsum</span> ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="list-style-type: U+263Argb(255,0,0);">Text here lorem ipsum ibisque totum.</li>
<li style="list-style-image:url(goto2.gif)">Text here lorem ipsum ibisque totum.</li>
<li style="list-style-position: inside; list-style-type: U+263Argb(255,0,0);">Text here lorem ipsum ibisque totum.</li>
<li style="list-style-position: inside; list-style-image:url(goto2.gif)">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
<li style="list-style-type: disc">Text here lorem ipsum ibisque totum.</li>
<li style="list-style-type: circle">Text here lorem ipsum ibisque totum.</li>
<li style="list-style-type: square">Text here lorem ipsum ibisque totum.</li>
<li>Text here lorem ipsum ibisque totum.</li>
</ol>

<ol class="listarabic">
<li>&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
<li style="color:red;">&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
<li>&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; <span style="color:red;">&#x627;&#x646;</span> &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
<li style="list-style-image:url(goto2rtl.gif)">&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
<li style="list-style-type: U+263Argb(255,0,0);">&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
<li style="list-style-type: disc">&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;</li>
</ol>


</div>
';
//==============================================================
//==============================================================

//echo $html; exit;
//==============================================================
include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);

$mpdf->list_number_suffix = ')';

$mpdf->WriteHTML($html);

$mpdf->Output();

exit;
//==============================================================
//==============================================================
//==============================================================


?>