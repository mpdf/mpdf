<?php


include("../mpdf.php");

$mpdf=new mPDF(''); 


//==============================================================

$html = '
<body>


<ul style="line-height: 1.7em;">
<li><b>Assamese</b>:
&#x9ad;&#x9be;&#x9b2;&#x9c7; &#x986;&#x99b;&#x9cb; &#x9a7;&#x9a8;&#x9cd;&#x9af;&#x9ac;&#x9be;&#x9a6;&#x9f7; &#x986;&#x9f0;&#x9c1; &#x986;&#x9aa;&#x9c1;&#x9a8;&#x9bf;?

<li><b>Bangla / Bengali</b>:
&#x986;&#x9ae;&#x9bf; &#x995;&#x9be;&#x981;&#x99a; &#x996;&#x9c7;&#x9a4;&#x9c7; &#x9aa;&#x9be;&#x9b0;&#x9bf;, &#x9a4;&#x9be;&#x9a4;&#x9c7; &#x986;&#x9ae;&#x9be;&#x9b0; &#x995;&#x9cb;&#x9a8;&#x9cb; &#x995;&#x9cd;&#x9b7;&#x9a4;&#x9bf; &#x9b9;&#x9df; &#x9a8;&#x9be;&#x964; 

<li><b>Gujarati</b>:
&#xa86; &#xaad;&#xabe;&#xa88; &#xaa4;&#xac7;&#xaa8;&#xabe; &#xab0;&#xac2;&#xaaa;&#xabf;&#xaaf;&#xabe; &#xa86;&#xaaa;&#xac0; &#xaa6;&#xac7;&#xab6;&#xac7;

<li><b>Kannada</b>:
&#xca8;&#xca8;&#xc97;&#xcc6; &#xcb9;&#xcbe;&#xca8;&#xcbf; &#xc86;&#xc97;&#xca6;&#xcc6;, &#xca8;&#xcbe;&#xca8;&#xcc1; &#xc97;&#xc9c;&#xca8;&#xccd;&#xca8;&#xcc1; &#xca4;&#xcbf;&#xca8;&#xcac;&#xcb9;&#xcc1;&#xca6;&#xcc1;

<li><b>Hindi</b>: &#x92e;&#x948;&#x902; &#x915;&#x93e;&#x901;&#x91a; &#x916;&#x93e; &#x938;&#x915;&#x924;&#x93e; &#x939;&#x942;&#x901; &#x914;&#x930; &#x92e;&#x941;&#x91d;&#x947; &#x909;&#x938;&#x938;&#x947; &#x915;&#x94b;&#x908; &#x91a;&#x94b;&#x91f; &#x928;&#x939;&#x940;&#x902; &#x92a;&#x939;&#x941;&#x902;&#x91a;&#x924;&#x940;.

<li><b>Malayalam</b>:
&#xd0e;&#xd28;&#xd4d;&#x200d;&#xd31;&#xd46; &#xd2a;&#xd31;&#xd15;&#xd4d;&#xd15;&#xd41;&#xd02;-&#xd2a;&#xd47;&#xd1f;&#xd15;&#xd02; &#xd28;&#xd3f;&#xd31;&#xd2f;&#xd46; &#xd35;&#xd4d;&#xd33;&#xd3e;&#xd19;&#xd4d;&#xd15;&#xd41;&#xd15;&#xd33;&#xd3e;&#xd23;&#xd4d; 

<li><b>Marathi</b>: &#x92e;&#x940; &#x915;&#x93e;&#x91a; &#x916;&#x93e;&#x90a; &#x936;&#x915;&#x924;&#x94b;, &#x92e;&#x932;&#x93e; &#x924;&#x947; &#x926;&#x941;&#x916;&#x924; &#x928;&#x93e;&#x939;&#x940;.

<li><b>Nepali</b>: &#x92e; &#x915;&#x93e;&#x901;&#x91a; &#x916;&#x93e;&#x928; &#x938;&#x915;&#x94d;&#x91b;&#x942; &#x930; &#x92e;&#x932;&#x93e;&#x908; &#x915;&#x947;&#x939;&#x93f; &#x928;&#x940; &#x939;&#x941;&#x928;&#x94d;&#x200d;&#x928;&#x94d; &#x964;

<li><b>Oriya</b>:
&#xb06;&#xb2a;&#xb23; &#xb07;&#xb02;&#xb30;&#xb3e;&#xb1c;&#xb40; &#xb15;&#xb41;&#xb39;&#xb28;&#xb4d;&#xb24;&#xb3f; &#xb15;&#xb3f;?

<li><b>Punjabi</b>:
&#xa2e;&#xa47;&#xa30;&#xa3e; &#xa39;&#xa35;&#xa30;&#xa15;&#xa4d;&#xa30;&#xa3e;&#xa2b;&#xa3c;&#xa24; &#xa28;&#xa3e;&#xa02;&#xa17;&#xa3e;&#xa02; &#xa28;&#xa3e;&#xa32;&#xa3c; &#xa2d;&#xa30;&#xa3f;&#xa06; &#xa2a;&#xa3f;&#xa06;&#x964;

<li><b>Sanskrit</b>: &#xfeff;&#x915;&#x93e;&#x91a;&#x902; &#x936;&#x915;&#x94d;&#x928;&#x94b;&#x92e;&#x94d;&#x92f;&#x924;&#x94d;&#x924;&#x941;&#x92e;&#x94d; &#x964; &#x928;&#x94b;&#x92a;&#x939;&#x93f;&#x928;&#x938;&#x94d;&#x924;&#x93f; &#x92e;&#x93e;&#x92e;&#x94d; &#x965;

<li><b>Sinhalese</b>: &#xdb8;&#xda7; &#xdc0;&#xdd3;&#xdaf;&#xdd4;&#xdbb;&#xdd4; &#xd9a;&#xdd1;&#xdb8;&#xda7; &#xdc4;&#xdd0;&#xd9a;&#xdd2;&#xdba;&#xdd2;. &#xd91;&#xdba;&#xdd2;&#xdb1;&#xdca; &#xdb8;&#xda7; &#xd9a;&#xdd2;&#xdc3;&#xdd2; &#xdc4;&#xdcf;&#xdb1;&#xdd2;&#xdba;&#xd9a;&#xdca; &#xdc3;&#xdd2;&#xdaf;&#xdd4; &#xdb1;&#xddc;&#xdc0;&#xdda;.

<li><b>Tamil</b>: &#xba8;&#xbbe;&#xba9;&#xbcd; &#xb95;&#xba3;&#xbcd;&#xba3;&#xbbe;&#xb9f;&#xbbf; &#xb9a;&#xbbe;&#xbaa;&#xbcd;&#xbaa;&#xbbf;&#xb9f;&#xbc1;&#xbb5;&#xbc7;&#xba9;&#xbcd;, &#xb85;&#xba4;&#xba9;&#xbbe;&#xbb2;&#xbcd; &#xb8e;&#xba9;&#xb95;&#xbcd;&#xb95;&#xbc1; &#xb92;&#xbb0;&#xbc1; &#xb95;&#xbc7;&#xb9f;&#xbc1;&#xbae;&#xbcd; &#xbb5;&#xbb0;&#xbbe;&#xba4;&#xbc1;.

<li><b>Telugu</b>: &#xc28;&#xc47;&#xc28;&#xc41; &#xc17;&#xc3e;&#xc1c;&#xc41; &#xc24;&#xc3f;&#xc28;&#xc17;&#xc32;&#xc28;&#xc41; &#xc2e;&#xc30;&#xc3f;&#xc2f;&#xc41; &#xc05;&#xc32;&#xc3e; &#xc1a;&#xc47;&#xc38;&#xc3f;&#xc28;&#xc3e; &#xc28;&#xc3e;&#xc15;&#xc41;  &#xc0f;&#xc2e;&#xc3f; &#xc07;&#xc2c;&#xc4d;&#xc2c;&#xc02;&#xc26;&#xc3f;  &#xc32;&#xc47;&#xc26;&#xc41;

</ul>

<ul style="line-height: 1.7em;">
<li><b>Arabic</b>: &#x623;&#x646;&#x627; &#x642;&#x627;&#x62f;&#x631; &#x639;&#x644;&#x649; &#x623;&#x643;&#x644; &#x627;&#x644;&#x632;&#x62c;&#x627;&#x62c; &#x648; &#x647;&#x630;&#x627; &#x644;&#x627; &#x64a;&#x624;&#x644;&#x645;&#x646;&#x64a;.

<li><b>Farsi / Persian</b>: &#x645;&#x646; &#x645;&#x6cc; &#x62a;&#x648;&#x627;&#x646;&#x645; &#x628;&#x62f;&#x648;&#x646;&#x650; &#x627;&#x62d;&#x633;&#x627;&#x633; &#x62f;&#x631;&#x62f; &#x634;&#x64a;&#x634;&#x647; &#x628;&#x62e;&#x648;&#x631;&#x645;.

<li><b>Urdu</b>: 
&#x645;&#x6cc;&#x6ba; &#x6a9;&#x627;&#x646;&#x686; &#x6a9;&#x6be;&#x627; &#x633;&#x6a9;&#x62a;&#x627; &#x6c1;&#x648;&#x6ba; &#x627;&#x648;&#x631; &#x645;&#x62c;&#x6be;&#x6d2; &#x62a;&#x6a9;&#x644;&#x6cc;&#x641; &#x646;&#x6c1;&#x6cc;&#x6ba; &#x6c1;&#x648;&#x62a;&#x6cc; &#x6d4;

<li><b>Pashto</b>: &#x632;&#x647; &#x634;&#x64a;&#x634;&#x647; &#x62e;&#x648;&#x693;&#x644;&#x6d0; &#x634;&#x645;&#x60c; &#x647;&#x63a;&#x647; &#x645;&#x627; &#x646;&#x647; &#x62e;&#x648;&#x696;&#x648;&#x64a;

<li><b>Turkish</b> <i>(Ottoman):</i> &#x62c;&#x627;&#x645;  &#x64a;&#x64a;&#x647; &#x628;&#x644;&#x648;&#x631;&#x645;  &#x628;&#x6ad;&#x627;  &#x636;&#x631;&#x631;&#x649;  &#x637;&#x648;&#x642;&#x648;&#x646;&#x645;&#x632;

<li><b>Sindhi</b> <i>(Arabic script):</i>
&#x633;&#x646;&#x68c;&#x64a; &#x67b;&#x648;&#x644;&#x64a; &#x627;&#x646;&#x68a;&#x648; &#x64a;&#x648;&#x631;&#x67e;&#x64a; &#x62e;&#x627;&#x646;&#x62f;&#x627;&#x646; &#x633;&#x627;&#x646; &#x62a;&#x639;&#x644;&#x642; &#x631;&#x6a9;&#x646;&#x62f;&#x699; &#x622;&#x631;&#x64a;&#x627;&#x626;&#x64a; &#x67b;&#x648;&#x644;&#x64a; &#x622;&#x6be;&#x64a;&#x60c; &#x62c;&#x646;&#x6be;&#x646; &#x62a;&#x64a; &#x6aa;&#x62c;&#x6be;&#x647; &#x62f;&#x631;&#x627;&#x648;&#x699;&#x64a; &#x627;&#x6be;&#x683;&#x627;&#x6bb; &#x67e;&#x6bb; &#x645;&#x648;&#x62c;&#x648;&#x62f; &#x200f;&#x622;&#x647;&#x646; 

<li><b>Sindhi</b> <i>(Devanagari):</i>
&#x938;&#x93f;&#x928;&#x94d;&#x927;&#x940; &#x97f;&#x94b;&#x932;&#x940; &#x907;&#x923;&#x94d;&#x921;&#x94b; &#x92f;&#x942;&#x930;&#x92a;&#x940; &#x916;&#x93c;&#x93e;&#x928;&#x94d;&#x926;&#x93e;&#x928; &#x938;&#x93e;&#x902; &#x924;&#x93e;&#x932;&#x94d;&#x932;&#x941;&#x915;&#x93c;&#x941; &#x930;&#x916;&#x928;&#x94d;&#x926;&#x921;&#x93c; &#x906;&#x930;&#x94d;&#x92f;&#x93e;&#x908; &#x97f;&#x94b;&#x932;&#x940; &#x906;&#x939;&#x947; 

<li><b>Hausa</b> (<i>Ajami</i>): 
&#x625;&#x650;&#x646;&#x627; &#x625;&#x650;&#x649;&#x64e; &#x62a;&#x64e;&#x648;&#x646;&#x64e;&#x631; &#x63a;&#x650;&#x644;&#x64e;&#x627;&#x634;&#x650; &#x643;&#x64f;&#x645;&#x64e; &#x625;&#x650;&#x646; &#x63a;&#x64e;&#x645;&#x64e;&#x627; &#x644;&#x64e;&#x627;&#x641;&#x650;&#x649;&#x64e;&#x627;

<li><B>Hebrew</B>: &#x5d0;&#x5e0;&#x5d9; &#x5d9;&#x5db;&#x5d5;&#x5dc; &#x5dc;&#x5d0;&#x5db;&#x5d5;&#x5dc; &#x5d6;&#x5db;&#x5d5;&#x5db;&#x5d9;&#x5ea; &#x5d5;&#x5d6;&#x5d4; &#x5dc;&#x5d0; &#x5de;&#x5d6;&#x5d9;&#x5e7; &#x5dc;&#x5d9;.

<li><B>Yiddish</B>: &#x5d0;&#x5d9;&#x5da; &#x5e7;&#x5e2;&#x5df; &#x5e2;&#x5e1;&#x5df; &#x5d2;&#x5dc;&#x5d0;&#x5b8;&#x5d6; &#x5d0;&#x5d5;&#x5df; &#x5e2;&#x5e1; &#x5d8;&#x5d5;&#x5d8; &#x5de;&#x5d9;&#x5e8; &#x5e0;&#x5d9;&#x5e9;&#x5d8; &#x5f0;&#x5f2;.

</ul>

<ul style="line-height: 1.7em;">

<li><B>Vietnamese (qu&#x1ed1;c  ng&#x1eef;)</B>: T&#xf4;i c&#xf3; th&#x1ec3; &#x103;n th&#x1ee7;y tinh m&#xe0; kh&#xf4;ng h&#x1ea1;i g&#xec;.

<li><b>Thai</b>: &#xe09;&#xe31;&#xe19;&#xe01;&#xe34;&#xe19;&#xe01;&#xe23;&#xe30;&#xe08;&#xe01;&#xe44;&#xe14;&#xe49; &#xe41;&#xe15;&#xe48;&#xe21;&#xe31;&#xe19;&#xe44;&#xe21;&#xe48;&#xe17;&#xe33;&#xe43;&#xe2b;&#xe49;&#xe09;&#xe31;&#xe19;&#xe40;&#xe08;&#xe47;&#xe1a;

<li><b>Khmer</b>:
&#x1781;&#x17d2;&#x1789;&#x17bb;&#x17c6;&#x17a2;&#x17b6;&#x1785;&#x1789;&#x17bb;&#x17c6;&#x1780;&#x1789;&#x17d2;&#x1785;&#x1780;&#x17cb;&#x1794;&#x17b6;&#x1793;
&#x178a;&#x17c4;&#x1799;&#x1782;&#x17d2;&#x1798;&#x17b6;&#x1793;&#x1794;&#x1789;&#x17d2;&#x17a0;&#x17b6;&#x179a;

<li><b>Lao</b>:
&#xe82;&#xead;&#xec9;&#xe8d;&#xe81;&#xeb4;&#xe99;&#xec1;&#xe81;&#xec9;&#xea7;&#xec4;&#xe94;&#xec9;&#xec2;&#xe94;&#xe8d;&#xe97;&#xeb5;&#xec8;&#xea1;&#xeb1;&#xe99;&#xe9a;&#xecd;&#xec8;&#xec4;&#xe94;&#xec9;&#xec0;&#xeae;&#xeb1;&#xe94;&#xec3;&#xeab;&#xec9;&#xe82;&#xead;&#xec9;&#xe8d;&#xec0;&#xe88;&#xeb1;&#xe9a;.

<li><b>Burmese</b>:
&#x1019;&#x102e;&#x1038;&#x1019;&#x103c;&#x1031;&#x1001;&#x103d;&#x1031;&#x1038;&#x1021;&#x102c;&#x1038; &#x1017;&#x1019;&#x102c; &#x1018;&#x102c;&#x101e;&#x102c;&#x101e;&#x102d;&#x102f;&#x1037; &#x1015;&#x103c;&#x1014;&#x103a;&#x1006;&#x102d;&#x102f;&#x1014;&#x1031;&#x101e;&#x100a;&#x103a;&#x1019;&#x103e;&#x102c; &#x1014;&#x103e;&#x1005;&#x103a;&#x1014;&#x103e;&#x1005;&#x103a;&#x1000;&#x103b;&#x1031;&#x102c;&#x103a;&#x1000;&#x103c;&#x102c;&#x1015;&#x103c;&#x102e; &#x1016;&#x103c;&#x1005;&#x103a;&#x1015;&#x102b;&#x1010;&#x101a;

<li><b>Tibetan</b>: &#xf64;&#xf7a;&#xf63;&#xf0b;&#xf66;&#xf92;&#xf7c;&#xf0b;&#xf5f;&#xf0b;&#xf53;&#xf66;&#xf0b;&#xf44;&#xf0b;&#xf53;&#xf0b;&#xf42;&#xf72;&#xf0b;&#xf58;&#xf0b;&#xf62;&#xf7a;&#xf51;&#xf0d;
</ul>



<ul style="line-height: 1.7em;">
<li><b>Anglo-Saxon</b> <i>(Runes):</i>
&#x16c1;&#x16b3;&#x16eb;&#x16d7;&#x16a8;&#x16b7;&#x16eb;&#x16b7;&#x16da;&#x16a8;&#x16cb;&#x16eb;&#x16d6;&#x16a9;&#x16cf;&#x16aa;&#x16be;&#x16eb;&#x16a9;&#x16be;&#x16de;&#x16eb;&#x16bb;&#x16c1;&#x16cf;&#x16eb;&#x16be;&#x16d6;&#x16eb;&#x16bb;&#x16d6;&#x16aa;&#x16b1;&#x16d7;&#x16c1;&#x16aa;&#x16a7;&#x16eb;&#x16d7;&#x16d6;&#x16ec;
<li><b>Old Norse</b> <i>(Runes):</i> 
&#x16d6;&#x16b4; &#x16b7;&#x16d6;&#x16cf;
&#x16b7;&#x16d6;&#x16cf; &#x16d6;&#x16cf;&#x16c1;
&#x16a7; &#x16b7;&#x16da;&#x16d6;&#x16b1; &#x16d8;&#x16be; 
&#x16a6;&#x16d6;&#x16cb;&#x16cb; &#x16a8;&#x16a7; &#x16a1;&#x16d6;
&#x16b1;&#x16a7;&#x16a8; &#x16cb;&#x16a8;&#x16b1;

<li><b>Old Irish</b> <i>(Ogham):</i> &#x169b;&#x169b;&#x1689;&#x1691;&#x1685;&#x1694;&#x1689;&#x1689;&#x1694;&#x168b;&#x1680;&#x1694;&#x1688;&#x1694;&#x1680;&#x168d;&#x1682;&#x1690;&#x1685;&#x1691;&#x1680;&#x1685;&#x1694;&#x168b;&#x168c;&#x1693;&#x1685;&#x1690;&#x169c;

<li><b>English</b> <i>(Braille):</i> &#x280a;&#x2800;&#x2809;&#x2801;&#x281d;&#x2800;&#x2811;&#x2801;&#x281e;&#x2800;&#x281b;&#x2807;&#x2801;&#x280e;&#x280e;&#x2800;&#x2801;&#x281d;&#x2819;&#x2800;&#x280a;&#x281e;&#x2800;&#x2819;&#x2815;&#x2811;&#x280e;&#x281d;&#x281e;&#x2800;&#x2813;&#x2825;&#x2817;&#x281e;&#x2800;&#x280d;&#x2811;
<li><b>Gothic</b>: &#x1033c;&#x10330;&#x10332; &#x10332;&#x10330;&#x10334;&#x10343; &#x10339;&#x308;&#x10344;&#x10330;&#x1033d;, &#x1033d;&#x10339; &#x1033c;&#x10339;&#x10343; &#x10345;&#x1033f; &#x1033d;&#x10333;&#x10330;&#x1033d; &#x10331;&#x10342;&#x10339;&#x10332;&#x10332;&#x10339;&#x10338;
<li><b>Georgian</b>: &#x10db;&#x10d8;&#x10dc;&#x10d0;&#x10e1; &#x10d5;&#x10ed;&#x10d0;&#x10db; &#x10d3;&#x10d0; &#x10d0;&#x10e0;&#x10d0; &#x10db;&#x10e2;&#x10d9;&#x10d8;&#x10d5;&#x10d0;.
<li><b>Armenian</b>: &#x53f;&#x580;&#x576;&#x561;&#x574; &#x561;&#x57a;&#x561;&#x56f;&#x56b; &#x578;&#x582;&#x57f;&#x565;&#x56c; &#x587; &#x56b;&#x576;&#x56e;&#x56b; &#x561;&#x576;&#x570;&#x561;&#x576;&#x563;&#x56b;&#x57d;&#x57f; &#x579;&#x568;&#x576;&#x565;&#x580;&#x589;
<li><b>Inuktitut</b>: &#x140a;&#x14d5;&#x148d;&#x1585; &#x14c2;&#x1546;&#x152d;&#x154c;&#x1593;&#x1483;&#x146f; &#x14f1;&#x154b;&#x1671;&#x1466;&#x1450;&#x14d0;&#x14c7;&#x1585;&#x1450;&#x1593;
 


<li><b>Amharic</b>: &#x12e8;&#x1230;&#x12cd; &#x1361; &#x120d;&#x1305; &#x1361; &#x1201;&#x1209; &#x1361; &#x1232;&#x12c8;&#x1208;&#x12f5; &#x1361; &#x1290;&#x133b;&#x1293; &#x1361; &#x1260;&#x12ad;&#x1265;&#x122d;&#x1293; &#x1361; &#x1260;&#x1218;&#x1265;&#x1275;&#x121d; &#x1361; &#x12a5;&#x12a9;&#x120d;&#x1290;&#x1275; &#x1361; &#x12eb;&#x1208;&#x12cd; &#x1361; &#x1290;&#x12cd; ...

<li><b>Somali</b> <i>(Osmanya alphabet):</i> &#x1049b;&#x10486;&#x10496;&#x10492;&#x10496;&#x10494;&#x10496; &#x1048a;&#x10496;&#x10491;&#x10491;&#x1049b;&#x10492;&#x10482;&#x10495;&#x10488; &#x10493;&#x1049a;&#x10484;&#x10493; &#x1048a;&#x10496;&#x10489;&#x1049b; &#x10498;&#x10488;&#x10496;&#x1048c;&#x1049d; &#x10484;&#x10499;&#x10487; &#x10496;&#x10494; &#x1048f;&#x10496;&#x10492;&#x10496; &#x10488;&#x10498;&#x10491;&#x10496;&#x10492; &#x10484;&#x10496;&#x1048c;&#x1048c;&#x10496; &#x10489;&#x10496;&#x10487;&#x10496;&#x1048d;&#x10482;&#x10496; ...

<li><b>Tamazight</b> <i>(Neo-Tifinagh alphabet):</i> &#x2d49;&#x2d4e;&#x2d37;&#x2d30;&#x2d4f;&#x2d3b;&#x2d4f;, &#x2d30;&#x2d3d;&#x2d3d;&#x2d3b;&#x2d4f; &#x2d4e;&#x2d30; &#x2d4d;&#x2d4d;&#x2d30;&#x2d4f; &#x2d5c;&#x2d5c;&#x2d4d;&#x2d30;&#x2d4d;&#x2d3b;&#x2d4f; &#x2d37; &#x2d49;&#x2d4d;&#x2d3b;&#x2d4d;&#x2d4d;&#x2d49;&#x2d62;&#x2d3b;&#x2d4f; ...

<li><b>Tigrinya</b>: &#x1265;&#x1218;&#x1295;&#x1345;&#x122d; &#x12ad;&#x1265;&#x122d;&#x1295; &#x1218;&#x1230;&#x120d;&#x1295; &#x12a9;&#x120e;&#x121d; &#x1230;&#x1263;&#x1275; &#x12a5;&#x1295;&#x1275;&#x12cd;&#x1208;&#x1339; &#x1290;&#x1343;&#x1295; &#x121b;&#x12d5;&#x1228;&#x1295; &#x12a5;&#x12ee;&#x121d;&#x1362; &#x121d;&#x1235;&#x1275;&#x12cd;&#x12d3;&#x120d;&#x1295; ...


<li><b>Vai</b>: &#xa549;&#xa55c;&#xa56e; &#xa514;&#xa60b; &#xa5b8; &#xa530; &#xa5cb;&#xa60b; &#xa56e;&#xa568; &#xa514;&#xa60b; &#xa5b8; &#xa54e; &#xa549;&#xa5b8;&#xa54a; &#xa574;&#xa583; &#xa543;&#xa524;&#xa602; &#xa5f1;, &#xa549;&#xa5b7; &#xa5ea;&#xa5e1; &#xa53b;&#xa524; &#xa5cf;&#xa5d2;&#xa5e1; &#xa54e; &#xa5ea; &#xa549;&#xa5b8;&#xa54a; &#xa58f;&#xa54e;. &#xa549;&#xa561; &#xa58f; &#xa5f3;&#xa56e;&#xa54a; &#xa5cf; &#xa56a; ...

</ul>
';



//==============================================================
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;	// Use values in classes/ucdn.php  1 = LATIN
$mpdf->autoVietnamese = true;
$mpdf->autoArabic = true;

$mpdf->autoLangToFont = true;

/* This works almost exactly the same as using autoLangToFont:
	$stylesheet = file_get_contents('../lang2fonts.css');
	$mpdf->WriteHTML($stylesheet,1);
*/

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;


?>