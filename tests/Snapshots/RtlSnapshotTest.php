<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class RtlSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'rtl';
	}

	/**
	 * Generate a PDF document by initializing the Mpdf object on $this->mpdf and
	 * loading it with content
	 *
	 * @return   void
	 * @internal Don't call any $this->mpdf->Output*() method
	 */
	public function generatePdf()
	{
		ob_start();
		?>
		<style>

			body {
				font-family: 'DejaVu Sans Condensed';
				font-size: 11pt;
			}

			p {
				text-align: justify;
				margin-bottom: 4pt;
				margin-top: 0pt;
			}

			table {
				font-family: 'DejaVu Sans Condensed';
				font-size: 9pt;
				line-height: 1.2;
				margin-top: 2pt;
				margin-bottom: 5pt;
				border-collapse: collapse;
			}

			thead {
				font-weight: bold;
				vertical-align: bottom;
			}

			tfoot {
				font-weight: bold;
				vertical-align: top;
			}

			thead td {
				font-weight: bold;
			}

			tfoot td {
				font-weight: bold;
			}

			.headerrow td, .headerrow th {
				background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;
			}

			.footerrow td, .footerrow th {
				background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;
			}

			th {
				font-weight: bold;
				vertical-align: top;
				padding-left: 2mm;
				padding-right: 2mm;
				padding-top: 0.5mm;
				padding-bottom: 0.5mm;
			}

			td {
				padding-left: 2mm;
				vertical-align: top;
				padding-right: 2mm;
				padding-top: 0.5mm;
				padding-bottom: 0.5mm;
			}

			th p {
				margin: 0pt;
			}

			td p {
				margin: 0pt;
			}

			table.widecells td {
				padding-left: 5mm;
				padding-right: 5mm;
			}

			table.tallcells td {
				padding-top: 3mm;
				padding-bottom: 3mm;
			}

			hr {
				width: 70%;
				height: 1px;
				text-align: center;
				color: #999999;
				margin-top: 8pt;
				margin-bottom: 8pt;
			}

			a {
				color: #000066;
				font-style: normal;
				text-decoration: underline;
				font-weight: normal;
			}

			pre {
				font-family: 'DejaVu Sans Mono';
				font-size: 9pt;
				margin-top: 5pt;
				margin-bottom: 5pt;
			}

			h1 {
				font-weight: normal;
				font-size: 26pt;
				color: #000066;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 18pt;
				margin-bottom: 6pt;
				border-top: 0.075cm solid #000000;
				border-bottom: 0.075cm solid #000000;
				text-align: ;
				page-break-after: avoid;
			}

			h2 {
				font-weight: bold;
				font-size: 12pt;
				color: #000066;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 6pt;
				margin-bottom: 6pt;
				border-top: 0.07cm solid #000000;
				border-bottom: 0.07cm solid #000000;
				text-align: ;
				text-transform: uppercase;
				page-break-after: avoid;
			}

			h3 {
				font-weight: normal;
				font-size: 26pt;
				color: #000000;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 0pt;
				margin-bottom: 6pt;
				border-top: 0;
				border-bottom: 0;
				text-align: ;
				page-break-after: avoid;
			}

			h4 {
				font-weight: ;
				font-size: 13pt;
				color: #9f2b1e;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 10pt;
				margin-bottom: 7pt;
				font-variant: small-caps;
				text-align: ;
				margin-collapse: collapse;
				page-break-after: avoid;
			}

			h5 {
				font-weight: bold;
				font-style: italic;;
				font-size: 11pt;
				color: #000044;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 8pt;
				margin-bottom: 4pt;
				text-align: ;
				page-break-after: avoid;
			}

			h6 {
				font-weight: bold;
				font-size: 9.5pt;
				color: #333333;
				font-family: 'DejaVu Sans Condensed';
				margin-top: 6pt;
				margin-bottom: ;
				text-align: ;
				page-break-after: avoid;
			}

			.breadcrumb {
				text-align: right;
				font-size: 8pt;
				font-family: 'DejaVu Serif Condensed';
				color: #666666;
				font-weight: bold;
				font-style: normal;
				margin-bottom: 6pt;
			}

			.bpmTopic tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.bpmTopicC tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.bpmNoLines tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.bpmNoLinesC tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.bpmTopnTail tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.bpmTopnTailC tbody tr:nth-child(even) {
				background-color: #f5f8f5;
			}

			.evenrow td, .evenrow th {
				background-color: #f5f8f5;
			}

			.oddrow td, .oddrow th {
				background-color: #e3ece4;
			}

			.bpmTopic {
				background-color: #e3ece4;
			}

			.bpmTopicC {
				background-color: #e3ece4;
			}

			.bpmNoLines {
				background-color: #e3ece4;
			}

			.bpmNoLinesC {
				background-color: #e3ece4;
			}

			.bpmClear {
			}

			.bpmClearC {
				text-align: center;
			}

			.bpmTopnTail {
				background-color: #e3ece4;
				topntail: 0.02cm solid #495b4a;
			}

			.bpmTopnTailC {
				background-color: #e3ece4;
				topntail: 0.02cm solid #495b4a;
			}

			.bpmTopnTailClear {
				topntail: 0.02cm solid #495b4a;
			}

			.bpmTopnTailClearC {
				topntail: 0.02cm solid #495b4a;
			}

			.bpmTopicC td, .bpmTopicC td p {
				text-align: center;
			}

			.bpmNoLinesC td, .bpmNoLinesC td p {
				text-align: center;
			}

			.bpmClearC td, .bpmClearC td p {
				text-align: center;
			}

			.bpmTopnTailC td, .bpmTopnTailC td p {
				text-align: center;
			}

			.bpmTopnTailClearC td, .bpmTopnTailClearC td p {
				text-align: center;
			}

			.pmhMiddleCenter {
				text-align: center;
				vertical-align: middle;
			}

			.pmhMiddleRight {
				text-align: right;
				vertical-align: middle;
			}

			.pmhBottomCenter {
				text-align: center;
				vertical-align: bottom;
			}

			.pmhBottomRight {
				text-align: right;
				vertical-align: bottom;
			}

			.pmhTopCenter {
				text-align: center;
				vertical-align: top;
			}

			.pmhTopRight {
				text-align: right;
				vertical-align: top;
			}

			.pmhTopLeft {
				text-align: left;
				vertical-align: top;
			}

			.pmhBottomLeft {
				text-align: left;
				vertical-align: bottom;
			}

			.pmhMiddleLeft {
				text-align: left;
				vertical-align: middle;
			}

			.infobox {
				margin-top: 10pt;
				background-color: #DDDDBB;
				text-align: center;
				border: 1px solid #880000;
			}

			.bpmTopic td, .bpmTopic th {
				border-top: 1px solid #FFFFFF;
			}

			.bpmTopicC td, .bpmTopicC th {
				border-top: 1px solid #FFFFFF;
			}

			.bpmTopnTail td, .bpmTopnTail th {
				border-top: 1px solid #FFFFFF;
			}

			.bpmTopnTailC td, .bpmTopnTailC th {
				border-top: 1px solid #FFFFFF;
			}

			div.mpdf_index_main {
				font-family: xbriyaz;
			}

			div.mpdf_index_entry {
				font-family: xbriyaz;
			}

			div.mpdf_index_letter {
				font-family: xbriyaz;
			}
		</style>

		<body dir="rtl">

		<h1>mPDF</h1>
		<h2>RTL Languages</h2>

		<h4>English</h4>
		<p>Please note that I do not understand any of the scripts below. The texts are borrowed from News websites, and
			I have used words and bits of phrases just to demonstrate the program.&#x200E;</p>

		<h4>Hebrew (pangram)&#x200E;</h4>
		<p lang="he">&#x5d3;&#x5d2; &#x5e1;&#x5e7;&#x5e8;&#x5df; &#x5e9;&#x5d8; &#x5d1;&#x5d9;&#x5dd; &#x5de;&#x5d0;&#x5d5;&#x5db;&#x5d6;&#x5d1;
			&#x5d5;&#x5dc;&#x5e4;&#x5ea;&#x5e2; &#x5de;&#x5e6;&#x5d0; &#x5d7;&#x5d1;&#x5e8;&#x5d4; </p>

		<p lang="he">&#x5d0;&#x5d5; &#x5d4;&#x5e0;&#x5e1;&#x5d4; &#x5d0;&#x5dc;&#x5d4;&#x5d9;&#x5dd;, &#x5dc;&#x5d1;&#x5d5;&#x5d0;
			&#x5dc;&#x5e7;&#x5d7;&#x5ea; &#x5dc;&#x5d5; &#x5d2;&#x5d5;&#x5d9; &#x5de;&#x5e7;&#x5e8;&#x5d1; &#x5d2;&#x5d5;&#x5d9;,
			&#x5d1;&#x5de;&#x5e1;&#x5ea; &#x5d1;&#x5d0;&#x5ea;&#x5ea; &#x5d5;&#x5d1;&#x5de;&#x5d5;&#x5e4;&#x5ea;&#x5d9;&#x5dd;
			&#x5d5;&#x5d1;&#x5de;&#x5dc;&#x5d7;&#x5de;&#x5d4; &#x5d5;&#x5d1;&#x5d9;&#x5d3; &#x5d7;&#x5d6;&#x5e7;&#x5d4;
			&#x5d5;&#x5d1;&#x5d6;&#x5e8;&#x5d5;&#x5e2; &#x5e0;&#x5d8;&#x5d5;&#x5d9;&#x5d4;, &#x5d5;&#x5d1;&#x5de;&#x5d5;&#x5e8;&#x5d0;&#x5d9;&#x5dd;
			&#x5d2;&#x5d3;&#x5dc;&#x5d9;&#x5dd;: &#x5db;&#x5db;&#x5dc; &#x5d0;&#x5e9;&#x5e8;-&#x5e2;&#x5e9;&#x5d4;
			&#x5dc;&#x5db;&#x5dd; &#x5d9;&#x5d4;&#x5d5;&#x5d4; &#x5d0;&#x5dc;&#x5d4;&#x5d9;&#x5db;&#x5dd;, &#x5d1;&#x5de;&#x5e6;&#x5e8;&#x5d9;&#x5dd;--&#x5dc;&#x5e2;&#x5d9;&#x5e0;&#x5d9;&#x5da; </p>

		<p lang="he">&#x5dc;&#x5db;&#x5df; &#x5d7;&#x5db;&#x5d5; &#x5dc;&#x5d9; &#x5e0;&#x5d0;&#x5dd; &#x5d9;&#x5d4;&#x5d5;&#x5d4;
			&#x5dc;&#x5d9;&#x5d5;&#x5dd; &#x5e7;&#x5d5;&#x5de;&#x5d9; &#x5dc;&#x5e2;&#x5d3;, &#x5db;&#x5d9; &#x5de;&#x5e9;&#x5e4;&#x5d8;&#x5d9;
			&#x5dc;&#x5d0;&#x5e1;&#x5e3; &#x5d2;&#x5d5;&#x5d9;&#x5dd; &#x5dc;&#x5e7;&#x5d1;&#x5e6;&#x5d9; &#x5de;&#x5de;&#x5dc;&#x5db;&#x5d5;&#x5ea;,
			&#x5dc;&#x5e9;&#x5e4;&#x5da; &#x5e2;&#x5dc;&#x5d9;&#x5d4;&#x5dd; &#x5d6;&#x5e2;&#x5de;&#x5d9; &#x5db;&#x5dc;
			&#x5d7;&#x5e8;&#x5d5;&#x5df; &#x5d0;&#x5e4;&#x5d9;, &#x5db;&#x5d9; &#x5d1;&#x5d0;&#x5e9; &#x5e7;&#x5e0;&#x5d0;&#x5ea;&#x5d9;
			&#x5ea;&#x5d0;&#x5db;&#x5dc; &#x5db;&#x5dc; &#x5d4;&#x5d0;&#x5e8;&#x5e5; </p>

		<p lang="he">&#x5e9;&#x5e4;&#x5df; &#x5d0;&#x5db;&#x5dc; &#x5e7;&#x5e6;&#x5ea; &#x5d2;&#x5d6;&#x5e8; &#x5d1;&#x5d8;&#x5e2;&#x5dd;
			&#x5d7;&#x5e1;&#x5d4;, &#x5d5;&#x5d3;&#x5d9;. </p>

		<h4>Arabic</h4>
		<p lang="ar">&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;
			<indexentry content="&#x62c;&#x648;&#x631;&#x62c;"/>&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;
			&#x641;&#x64a; &#x62d;&#x62f;&#x64a;&#x62b; &#x645;&#x62a;&#x644;&#x641;&#x632;<annotation
					content="&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a; &#x62c;&#x648;&#x631;&#x62c;"
					subject="&#x62c;&#x648;&#x631;&#x62c;" icon="Comment" color="#FE88EF"
					author="&#x62c;&#x648;&#x631;&#x62c;"/>
			&#x641;&#x64a; &#x627;&#x644;&#x630;&#x643;&#x631;&#x649; &#x627;&#x644;&#x631;&#x627;&#x628;&#x639;&#x629;
			&#x644;&#x644;&#x63a;&#x632;&#x648; &#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a; &#x644;&#x644;&#x639;&#x631;&#x627;&#x642;
			&#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629; &#x62d;&#x627;&#x644;&#x64a;&#x627;
			&#x644;&#x627;&#x639;&#x627;&#x62f;&#x629;
			<indexentry content="&#x627;&#x644;&#x627;&#x645;&#x646;"/>&#x627;&#x644;&#x627;&#x645;&#x646;
			<indexentry content="&#x644;&#x644;&#x639;&#x631;&#x627;&#x642;"/>&#x644;&#x644;&#x639;&#x631;&#x627;&#x642;.
		</p>

		<p lang="ar">&#x647;&#x644;
			<indexentry content="&#x633;&#x62a;&#x633;&#x641;&#x631;"/>&#x633;&#x62a;&#x633;&#x641;&#x631; &#x627;&#x644;&#x62c;&#x647;&#x648;&#x62f;
			&#x627;&#x644;&#x62f;&#x628;&#x644;&#x648;&#x645;&#x627;&#x633;&#x64a;&#x629;
			<indexentry content="&#x627;&#x644;&#x62c;&#x627;&#x631;&#x64a;&#x629;"/>&#x627;&#x644;&#x62c;&#x627;&#x631;&#x64a;&#x629;
			&#x639;&#x646; &#x62d;&#x644;&#x648;&#x644;&#x61f; &#x648;&#x643;&#x64a;&#x641;
			<indexentry content="&#x62a;&#x646;&#x638;&#x631;"/>&#x62a;&#x646;&#x638;&#x631; &#x644;&#x644;&#x627;&#x62a;&#x647;&#x627;&#x645;&#x627;&#x62a;
			&#x644;&#x628;&#x639;&#x636; &#x647;&#x630;&#x647; &#x627;&#x644;&#x62f;&#x648;&#x644; &#x628;&#x627;&#x644;&#x62a;&#x62f;&#x62e;&#x644;
			&#x641;&#x64a; &#x627;&#x644;&#x634;&#x623;&#x646; &#x627;&#x644;&#x639;&#x631;&#x627;&#x642;&#x64a;&#x60c;
			&#x648;&#x627;&#x644;&#x62a;&#x648;&#x631;&#x637; &#x641;&#x64a; &#x62f;&#x639;&#x645; &#x639;&#x645;&#x644;&#x64a;&#x627;&#x62a;
			<indexentry content="&#x627;&#x644;&#x639;&#x646;&#x641;&#x61f;"/>&#x627;&#x644;&#x639;&#x646;&#x641;&#x61f;
			&#x648;&#x627;&#x644;&#x649; &#x627;&#x64a; &#x645;&#x62f;&#x649; &#x64a;&#x628;&#x62f;&#x648; &#x627;&#x644;&#x648;&#x636;&#x639;
			&#x641;&#x64a; &#x627;&#x644;&#x639;&#x631;&#x627;&#x642;
			<indexentry content="&#x627;&#x646;&#x639;&#x643;&#x627;&#x633;&#x627;"/>&#x627;&#x646;&#x639;&#x643;&#x627;&#x633;&#x627;
			&#x644;&#x644;&#x635;&#x631;&#x627;&#x639;&#x627;&#x62a; &#x627;&#x644;&#x625;&#x642;&#x644;&#x64a;&#x645;&#x64a;&#x629;
			&#x641;&#x64a;
			<indexentry content="&#x627;&#x644;&#x645;&#x646;&#x637;&#x642;&#x629;&#x61f;"/>&#x627;&#x644;&#x645;&#x646;&#x637;&#x642;&#x629;&#x61f;
		</p>

		<p lang="ar">&#x648;&#x627;&#x62f;&#x627;&#x646; &#x627;&#x644;&#x628;&#x64a;&#x62a; &#x627;&#x644;&#x627;&#x628;&#x64a;&#x636;
			&quot;&#x628;&#x634;&#x62f;&#x629;&quot; &#x62a;&#x641;&#x62c;&#x64a;&#x631; &#x627;&#x64a;&#x644;&#x627;&#x62a;
			&#x641;&#x64a;&#x645;&#x627; &#x627;&#x639;&#x631;&#x628;&#x62a; &#x648;&#x632;&#x627;&#x631;&#x629;
			<indexentry content="&#x627;&#x644;&#x62e;&#x627;&#x631;&#x62c;&#x64a;&#x629;"/>&#x627;&#x644;&#x62e;&#x627;&#x631;&#x62c;&#x64a;&#x629;
			&#x627;&#x644;&#x631;&#x648;&#x633;&#x64a;&#x629; &#x639;&#x646; &quot;&#x627;&#x62f;&#x627;&#x646;&#x62a;&#x647;&#x627;
			&#x627;&#x644;&#x634;&#x62f;&#x64a;&#x62f;&#x629;&quot; &#x644;&#x644;&#x62d;&#x627;&#x62f;&#x62b;&quot;
			&#x648;&#x627;&#x635;&#x641;&#x629; &#x627;&#x64a;&#x627;&#x647; &#x628;&#x640;&quot;&#x627;&#x644;&#x645;&#x62a;&#x637;&#x631;&#x641;&quot;
			<indexentry content="&#x627;&#x644;&#x630;&#x64a;"/>&#x627;&#x644;&#x630;&#x64a; &#x627;&#x633;&#x62a;&#x647;&#x62f;&#x641;
			&quot;&#x645;&#x62f;&#x646;&#x64a;&#x64a;&#x646; &#x645;&#x633;&#x627;&#x644;&#x645;&#x64a;&#x646;&quot;.
		</p>

		<p lang="ar">&#x648;&#x627;&#x636;&#x627;&#x641;&#x62a; &#x648;&#x632;&#x627;&#x631;&#x629; &#x627;&#x644;&#x62e;&#x627;&#x631;&#x62c;&#x64a;&#x629;
			&#x627;&#x644;&#x631;&#x648;&#x633;&#x64a;&#x629; &#x641;&#x64a; &#x628;&#x64a;&#x627;&#x646;&#x647;&#x627;:
			&quot;&#x645;&#x646; &#x627;&#x644;&#x645;&#x624;&#x633;&#x641; &#x627;&#x646; &#x64a;&#x623;&#x62a;&#x64a;
			&#x647;&#x630;&#x627; &#x627;&#x644;&#x62d;&#x627;&#x62f;&#x62b; &#x628;&#x64a;&#x646;&#x645;&#x627; &#x62a;&#x628;&#x630;&#x644;
			&#x627;&#x644;&#x62c;&#x647;&#x648;&#x62f; &#x644;&#x62a;&#x62e;&#x637;&#x64a; &#x627;&#x644;&#x627;&#x632;&#x645;&#x629;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x62f;&#x627;&#x62e;&#x644;&#x64a;&#x629;&quot;.</p>

		<p lang="ar">
			<indexentry content="&#x648;&#x62f;&#x639;&#x62a;"/>&#x648;&#x62f;&#x639;&#x62a;
			<indexentry content="&#x645;&#x648;&#x633;&#x643;&#x648;"/>&#x645;&#x648;&#x633;&#x643;&#x648; &#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x649; &quot;&#x628;&#x630;&#x644;
			&#x643;&#x644; &#x645;&#x627;
			<indexentry content="&#x64a;&#x645;&#x643;&#x646;"/>&#x64a;&#x645;&#x643;&#x646; &#x645;&#x646; &#x627;&#x62c;&#x644;
			&#x627;&#x62d;&#x62a;&#x648;&#x627;&#x621; &#x645;&#x638;&#x627;&#x647;&#x631;
			<indexentry content="&#x627;&#x644;&#x62a;&#x637;&#x631;&#x641;"/>&#x627;&#x644;&#x62a;&#x637;&#x631;&#x641;
			&#x627;&#x644;&#x62a;&#x64a; &#x644;&#x627; &#x645;&#x628;&#x631;&#x631; &#x644;&#x647;&#x627; &#x648;&#x627;&#x644;&#x62a;&#x64a;
			&#x644;&#x627; &#x62a;&#x641;&#x64a;&#x62f; &#x645;&#x635;&#x627;&#x644;&#x62d; &#x627;&#x644;&#x634;&#x639;&#x628;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a; &#x639;&#x644;&#x649; &#x627;&#x644;&#x627;&#x645;&#x62f;
			&#x627;&#x644;&#x637;&#x648;&#x64a;&#x644;&quot;.
		</p>

		<p lang="ar">&#x648;&#x62f;&#x639;&#x62a; &#x645;&#x648;&#x633;&#x643;&#x648; &#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x649; &quot;&#x628;&#x630;&#x644;
			&#x643;&#x644; &#x645;&#x627; &#x648;&#x62f;&#x639;&#x62a; &#x645;&#x648;&#x633;&#x643;&#x648; &#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x649; &quot;&#x628;&#x630;&#x644;
			&#x643;&#x644; </p>
		<p lang="ar">
			&#x648;&#x62f;&#x639;&#x62a; &#x645;&#x648;&#x633;&#x643;&#x648; &#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x649; &#x648;&#x62f;&#x639;&#x62a;
			&#x645;&#x648;&#x633;&#x643;&#x648;
			<indexentry content="&#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;"/>&#x627;&#x644;&#x633;&#x644;&#x637;&#x627;&#x62a;
			&#x627;&#x644;&#x641;&#x644;&#x633;&#x637;&#x64a;&#x646;&#x64a;&#x629; &#x627;&#x644;&#x649; &quot;&#x628;&#x630;&#x644;
			&#x643;&#x644; &#x645;&#x627;
			<indexentry content="&#x64a;&#x645;&#x643;&#x646;"/>&#x64a;&#x645;&#x643;&#x646; &#x645;&#x646; &#x627;&#x62c;&#x644;
		</p>

		<p lang="ar">&#x643;&#x645;&#x627; &#x627;&#x62f;&#x627;&#x646; &#x627;&#x644;&#x641;&#x627;&#x631;&#x648;
			&#x62f;&#x64a;
			<indexentry content="&#x633;&#x648;&#x62a;&#x648;"/>&#x633;&#x648;&#x62a;&#x648;
			<indexentry content="&#x645;&#x628;&#x639;&#x648;&#x62b;"/>&#x645;&#x628;&#x639;&#x648;&#x62b; &#x627;&#x644;&#x627;&#x645;&#x645;
			&#x627;&#x644;&#x645;&#x62a;&#x62d;&#x62f;&#x629; &#x627;&#x644;&#x62e;&#x627;&#x635; &#x627;&#x644;&#x649;
			&#x627;&#x644;&#x634;&#x631;&#x642; &#x627;&#x644;&#x627;&#x648;&#x633;&#x637; &#x627;&#x644;&#x639;&#x645;&#x644;&#x64a;&#x629;
			&#x648;&#x642;&#x627;&#x644; &quot;&#x627;&#x646;&#x647; &#x643;&#x627;&#x646; &#x647;&#x62c;&#x648;&#x645;&#x627;
			&#x639;&#x644;&#x649; &#x627;&#x634;&#x62e;&#x627;&#x635; &#x639;&#x627;&#x62f;&#x64a;&#x64a;&#x646; &#x643;&#x627;&#x646;&#x648;&#x627;
			<indexentry content="&#x64a;&#x642;&#x648;&#x645;&#x648;&#x646;"/>&#x64a;&#x642;&#x648;&#x645;&#x648;&#x646;
			&#x628;&#x646;&#x634;&#x627;&#x637;&#x647;&#x645; &#x627;&#x644;&#x64a;&#x648;&#x645;&#x64a; &#x648;&#x647;&#x630;&#x627;
			&#x627;&#x645;&#x631; &#x644;&#x627;
			<indexentry content="&#x64a;&#x645;&#x643;&#x646;"/>&#x64a;&#x645;&#x643;&#x646; &#x62a;&#x628;&#x631;&#x64a;&#x631;&#x647;&quot;.
		</p>

		<h4>Farsi / Persian (fa)&#x200E;</h4>
		<p lang="fa">&#x645;&#x62d;&#x645;&#x62f;
			<indexentry content="&#x627;&#x644;&#x628;&#x631;&#x627;&#x62f;&#x639;&#x6cc;"/>&#x627;&#x644;&#x628;&#x631;&#x627;&#x62f;&#x639;&#x6cc;
			&#x631;&#x626;&#x64a;&#x633; &#x622;&#x698;&#x627;&#x646;&#x633; &#x628;&#x64a;&#x646; &#x627;&#x644;&#x645;&#x644;&#x644;&#x6cc;
			&#x627;&#x646;&#x631;&#x698;&#x6cc;
			<indexentry content="&#x627;&#x62a;&#x645;&#x6cc;"/>&#x627;&#x62a;&#x645;&#x6cc; &#x67e;&#x64a;&#x634;&#x646;&#x647;&#x627;&#x62f;
			&#x6a9;&#x631;&#x62f;&#x647; &#x627;&#x633;&#x62a; &#x62a;&#x647;&#x631;&#x627;&#x646; &#x628;&#x631;&#x646;&#x627;&#x645;&#x647;
			<indexentry content="&#x62c;&#x646;&#x62c;&#x627;&#x644;&#x6cc;"/>&#x62c;&#x646;&#x62c;&#x627;&#x644;&#x6cc;
			&#x63a;&#x646;&#x6cc; &#x633;&#x627;&#x632;&#x6cc; &#x627;&#x648;&#x631;&#x627;&#x646;&#x64a;&#x648;&#x645;
			&#x631;&#x627; &#x645;&#x62a;&#x648;&#x642;&#x641; &#x6a9;&#x646;&#x62f; &#x648; &#x63a;&#x631;&#x628;
			&#x646;&#x64a;&#x632; &#x627;&#x62c;&#x631;&#x627;&#x6cc; &#x62a;&#x62d;&#x631;&#x64a;&#x645; &#x647;&#x627;&#x6cc;
			&#x62a;&#x646;&#x628;&#x64a;&#x647;&#x6cc;
			<indexentry content="&#x645;&#x648;&#x631;&#x62f;"/>&#x645;&#x648;&#x631;&#x62f; &#x62a;&#x627;&#x626;&#x64a;&#x62f;
			<indexentry content="&#x633;&#x627;&#x632;&#x645;&#x627;&#x646;"/>&#x633;&#x627;&#x632;&#x645;&#x627;&#x646;
			&#x645;&#x644;&#x644; &#x645;&#x62a;&#x62d;&#x62f; &#x631;&#x627; &#x628;&#x647; &#x62a;&#x639;&#x648;&#x64a;&#x642;
			&#x628;&#x64a;&#x627;&#x646;&#x62f;&#x627;&#x632;&#x62f;.
		</p>

		<p lang="fa">&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;&#x60c; &#x62f;&#x631;
			<indexentry content="&#x686;&#x647;&#x627;&#x631;&#x645;&#x6cc;&#x646;"/>&#x686;&#x647;&#x627;&#x631;&#x645;&#x6cc;&#x646;
			&#x633;&#x627;&#x644;&#x6af;&#x631;&#x62f; &#x627;&#x634;&#x63a;&#x627;&#x644; &#x639;&#x631;&#x627;&#x642;
			&#x645;&#x6cc; &#x6af;&#x648;&#x6cc;&#x62f; &#x627;&#x633;&#x62a;&#x631;&#x627;&#x62a;&#x698;&#x6cc;
			<indexentry content="&#x627;&#x633;&#x62a;&#x642;&#x631;&#x627;&#x631;"/>&#x627;&#x633;&#x62a;&#x642;&#x631;&#x627;&#x631;
			&#x646;&#x6cc;&#x631;&#x648;&#x647;&#x627;&#x6cc; &#x622;&#x645;&#x631;&#x6cc;&#x6a9;&#x627;&#x6cc;&#x6cc;
			<indexentry content="&#x628;&#x6cc;&#x634;&#x62a;&#x631;&#x6cc;"/>&#x628;&#x6cc;&#x634;&#x62a;&#x631;&#x6cc;
			&#x62f;&#x631;
			<indexentry content="&#x628;&#x63a;&#x62f;&#x627;&#x62f;&#x60c;"/>&#x628;&#x63a;&#x62f;&#x627;&#x62f;&#x60c;
			&#x645;&#x62f;&#x62a;&#x6cc; &#x637;&#x648;&#x644;
			<indexentry content="&#x62e;&#x648;&#x627;&#x647;&#x62f;"/>&#x62e;&#x648;&#x627;&#x647;&#x62f;
			<indexentry content="&#x6a9;&#x634;&#x6cc;&#x62f;"/>&#x6a9;&#x634;&#x6cc;&#x62f;.
		</p>

		<p lang="fa">
			<indexentry content="&#x622;&#x645;&#x631;&#x6cc;&#x6a9;&#x627;"/>&#x622;&#x645;&#x631;&#x6cc;&#x6a9;&#x627;
			&#x648;&#x6cc;&#x632;&#x627;&#x6cc; &#x631;&#x626;&#x6cc;&#x633; &#x62c;&#x645;&#x647;&#x648;&#x631; &#x627;&#x6cc;&#x631;&#x627;&#x646;
			&#x631;&#x627; &#x628;&#x647; &#x645;&#x646;&#x638;&#x648;&#x631; &#x62d;&#x636;&#x648;&#x631; &#x648;&#x6cc;
			&#x62f;&#x631; &#x62c;&#x644;&#x633;&#x647; &#x631;&#x627;&#x6cc; &#x6af;&#x6cc;&#x631;&#x6cc; &#x634;&#x648;&#x631;&#x627;&#x6cc;
			&#x627;&#x645;&#x646;&#x6cc;&#x62a;
			<indexentry content="&#x628;&#x631;&#x627;&#x6cc;"/>&#x628;&#x631;&#x627;&#x6cc;
			<indexentry content="&#x642;&#x637;&#x639;&#x646;&#x627;&#x645;&#x647;"/>&#x642;&#x637;&#x639;&#x646;&#x627;&#x645;&#x647;
			&#x62a;&#x627;&#x632;&#x647;
			<indexentry content="&#x639;&#x644;&#x6cc;&#x647;"/>&#x639;&#x644;&#x6cc;&#x647; &#x627;&#x6cc;&#x646;
			&#x6a9;&#x634;&#x648;&#x631; &#x635;&#x627;&#x62f;&#x631; &#x6a9;&#x631;&#x62f;.
		</p>

		<h4>Urdu</h4>
		<p lang="ur">
			<indexentry content="&#x62c;&#x633;&#x679;&#x633;"/>&#x62c;&#x633;&#x679;&#x633;
			<indexentry content="&#x627;&#x641;&#x62a;&#x62e;&#x627;&#x631;"/>&#x627;&#x641;&#x62a;&#x62e;&#x627;&#x631;
			&#x6a9;&#x6cc; &#x62c;&#x628;&#x631;&#x6cc; &#x631;&#x62e;&#x635;&#x62a; &#x67e;&#x631;
			<indexentry content="&#x644;&#x627;&#x6c1;&#x648;&#x631;&#x6c1;&#x627;&#x626;&#x6cc;"/>&#x644;&#x627;&#x6c1;&#x648;&#x631;&#x6c1;&#x627;&#x626;&#x6cc;
			&#x6a9;&#x648;&#x631;&#x679; &#x6a9;&#x6d2; &#x627;&#x6cc;&#x6a9; &#x627;&#x648;&#x631; &#x633;&#x646;&#x62f;&#x6be;
			&#x645;&#x6cc;&#x6ba; &#x6a9;&#x626;&#x6cc; &#x633;&#x648;&#x644; &#x62c;&#x62c; &#x645;&#x633;&#x62a;&#x639;&#x641;&#x6cc;
			<indexentry content="&#x6c1;&#x648;&#x6af;&#x626;&#x6d2;"/>&#x6c1;&#x648;&#x6af;&#x626;&#x6d2; &#x6c1;&#x6cc;&#x6ba;&#x6d4;
		</p>

		<p lang="ur">&#x686;&#x6cc;&#x641;
			<indexentry content="&#x62c;&#x633;&#x679;&#x633;"/>&#x62c;&#x633;&#x679;&#x633; &#x6a9;&#x6cc; &#x633;&#x631;&#x6af;&#x631;&#x645;&#x6cc;&#x627;&#x6ba;
			&#x645;&#x62d;&#x62f;&#x648;&#x62f; &#x6a9;&#x631;&#x646;&#x6d2; &#x627;&#x648;&#x631;
			<indexentry content="&#x67e;&#x648;&#x644;&#x6cc;&#x633;"/>&#x67e;&#x648;&#x644;&#x6cc;&#x633; &#x62a;&#x639;&#x6cc;&#x646;&#x627;&#x62a;&#x6cc;
			&#x6a9;&#x6d2; &#x62d;&#x6a9;&#x645;
			<indexentry content="&#x646;&#x627;&#x645;&#x6d2;"/>&#x646;&#x627;&#x645;&#x6d2; &#x67e;&#x631; &#x62f;&#x633;&#x62a;&#x62e;&#x637;
			&#x6a9;&#x631;&#x6a9;&#x6d2; &#x63a;&#x644;&#x637; &#x6a9;&#x6cc;&#x627;: &#x62c;&#x646;&#x631;&#x644;
			&#x645;&#x634;&#x631;&#x641;
		</p>

		<h4>&#x202a;Pashto (ps)&#x202c;</h4>
		<p lang="ps">&#x67e;&#x647; &#x6a9;&#x627;&#x628;&#x644; &#x627;&#x648; &#x6a9;&#x646;&#x62f;&#x647;&#x627;&#x631;
			&#x6a9;&#x6d0; &#x62f;&#x648;&#x648; &#x681;&#x627;&#x646;&#x645;&#x631;&#x6af;&#x648; &#x628;&#x631;&#x64a;&#x62f;&#x648;&#x646;&#x648;
			&#x644;&#x696; &#x62a;&#x631; &#x644;&#x696;&#x647; &#x64a;&#x648; &#x645;&#x627;&#x634;&#x648;&#x645;
			<indexentry content="&#x648;&#x698;&#x644;&#x649;"/>&#x648;&#x698;&#x644;&#x649; &#x627;&#x648; &#x627;&#x62a;&#x647;
			&#x62a;&#x646;&#x647; &#x646;&#x648;&#x631; &#x649;&#x6d0; &#x67c;&#x67e;&#x64a;&#x627;&#x646; &#x6a9;&#x693;&#x64a;.
		</p>

		<p lang="ps">&#x647; &#x639;&#x631;&#x627;&#x642; &#x6a9;&#x6d0; &#x64a;&#x648;&#x647; &#x62a;&#x627;&#x632;&#x647;
			&#x646;&#x638;&#x631; &#x634;&#x645;&#x6d0;&#x631;&#x646;&#x647; &#x69a;&#x64a;&#x64a; &#x686;&#x6d0;
			&#x639;&#x631;&#x627;&#x642;&#x64a;&#x627;&#x646; &#x67e;&#x647;
			<indexentry content="&#x632;&#x64a;&#x627;&#x62a;&#x6d0;&#x62f;&#x648;&#x646;&#x6a9;&#x64a;"/>&#x632;&#x64a;&#x627;&#x62a;&#x6d0;&#x62f;&#x648;&#x646;&#x6a9;&#x64a;
			&#x62a;&#x648;&#x6af;&#x647; &#x62f;
			<indexentry content="&#x62d;&#x627;&#x644;&#x627;&#x62a;&#x648;"/>&#x62d;&#x627;&#x644;&#x627;&#x62a;&#x648;
			&#x67e;&#x647; &#x627;&#x693;&#x647;
			<indexentry content="&#x628;&#x62f;&#x628;&#x64a;&#x646;&#x647;"/>&#x628;&#x62f;&#x628;&#x64a;&#x646;&#x647;
			&#x62f;&#x64a; &#x627;&#x648; &#x62f; &#x628;&#x6d0;
			<indexentry content="&#x628;&#x627;&#x648;&#x631;&#x64a;"/>&#x628;&#x627;&#x648;&#x631;&#x64a; &#x627;&#x62d;&#x633;&#x627;&#x633;
			&#x6a9;&#x648;&#x64a;&#x60c; &#x62e;&#x648; &#x62e;&#x67e;&#x644;
			<indexentry content="&#x647;&#x64a;&#x648;&#x627;&#x62f;"/>&#x647;&#x64a;&#x648;&#x627;&#x62f; &#x64a;&#x648;&#x645;&#x648;&#x67c;&#x649;
			<indexentry content="&#x63a;&#x648;&#x627;&#x693;&#x64a;"/>&#x63a;&#x648;&#x627;&#x693;&#x64a;.
		</p>

		<h4>Symbols</h4>
		<p>&#xa9;&#xae;&#x2122;&#xb5;&#x2022;&#x2026;&#x2032;&#x2033;&#xa7;&lt;&gt;&#x2264;&#x2265;&#xb0;&#x2212;&#xb1;&#xf7;&#x2044;&#xd7;&#x192;&#x222b;&#x2211;&#x221e;&#x221a;&#x2248;&#x2260;&#x2261;&#x220f;&#xac;&#x2229;&#x2202;</p>
		<p>&#x392;&#x393;&#x394;&#x395;&#x396;&#x397;&#x398;&#x399;&#x39a;&#x39b;&#x39c;&#x39d;&#x39e;&#x39f;&#x3a0;&#x3a1;&#x3a3;&#x3a4;&#x3a5;&#x3a6;&#x3a7;&#x3a8;&#x3a9;</p>
		<p>&#x3b1;&#x3b2;&#x3b3;&#x3b4;&#x3b5;&#x3b6;&#x3b7;&#x3b8;&#x3b9;&#x3ba;&#x3bb;&#x3bc;&#x3bd;&#x3be;&#x3bf;&#x3c0;&#x3c1;&#x3c2;&#x3c3;&#x3c4;&#x3c5;&#x3c6;&#x3c7;&#x3c8;&#x3c9;</p>
		<p>&#x2190;&#x2191;&#x2192;&#x2193;&#x2194;&#x25ca;&#x2663;&#x2665;&#x2666;</p>

		<h4>Dingbats</h4>
		<p>&#xa7;&lt;&gt;&#x2192;&#x2194;&#x2663;&#x2665;&#x2666;</p>

		<h4>win-1252 </h4>
		<p>&#xa2;&#x20ac;&#xa9;&#xae;&#x2122;&#x2030;&#xb5;&#xb7;&#x2022;&#x2026;&#xa7;&#xdf;&#x2039;&#x203a;&#xab;&#xbb;&#x2018;&#x2019;&#x201c;&#x201d;&#x201a;&#x201e;&lt;&gt;&#x2013;&#x2014;&#x2c6;&#x2dc;&#xb0;&#xb1;&#xf7;&#xd7;&#xbc;&#xbd;&#xbe;&#x192;&#xac;&#x2020;&#x2021;</p>
		<p>&#xc0;&#xc1;&#xc2;&#xc3;&#xc4;&#xc5;&#xc6;&#xc7;&#xc8;&#xc9;&#xca;&#xcb;&#xcc;&#xcd;&#xce;&#xcf;&#xd0;&#xd1;&#xd2;&#xd3;&#xd4;&#xd5;&#xd6;&#xd8;&#x152;&#x160;&#xd9;&#xda;&#xdb;&#xdc;&#xdd;&#x178;</p>
		<p>&#xe0;&#xe1;&#xe2;&#xe3;&#xe4;&#xe5;&#xe6;&#xe7;&#xe8;&#xe9;&#xea;&#xeb;&#xec;&#xed;&#xee;&#xef;&#xf0;&#xf1;&#xf2;&#xf4;&#xf5;&#xf6;&#xf8;&#x153;&#x161;&#xf9;&#xfa;&#xfb;&#xfc;&#xfd;&#xfe;&#xff;</p>

		<h3>Bidirectional text</h3>
		<div style="direction: ltr;">
			<p>Text alignment, unless specified, is neutral and therefore dictated by the 'direction' of the
				paragraph.</p>

			<p>Text is analysed at the end of every block element (div, p, td). If the text contains RTL characters,
				those characters and words are reversed according to the Unicode BiDirectional algorithm e.g.</p>

			<p lang="ar" style="direction: rtl;">
				<indexentry content="&#x648;&#x627;&#x62f;&#x627;&#x646;"/>&#x648;&#x627;&#x62f;&#x627;&#x646;
				<indexentry content="&#x627;&#x644;&#x628;&#x64a;&#x62a;"/>&#x627;&#x644;&#x628;&#x64a;&#x62a; &#x627;&#x644;&#x627;&#x628;&#x64a;&#x636;
				&quot;&#x628;&#x634;&#x62f;&#x629;&quot; &#x62a;&#x641;&#x62c;&#x64a;&#x631; with some english in the
				middle
				<indexentry content="&#x627;&#x64a;&#x644;&#x627;&#x62a;"/>&#x627;&#x64a;&#x644;&#x627;&#x62a; &#x641;&#x64a;&#x645;&#x627;
				&#x627;&#x639;&#x631;&#x628;&#x62a; &#x648;&#x632;&#x627;&#x631;&#x629;
			</p>

			<p>To set the 'directionality' of the whole document e.g. to reverse default alignment, tables, lists etc.
				you can set the dir attribute or the direction CSS property on the HTML or BODY tag to 'rtl' e.g.</p>
			<p>&lt;body style="direction: rtl"&gt;</p>
			<p>&lt;body dir="rtl"&gt;</p>
			<p>or you can use $this->mpdf->SetDirectionality('rtl');</p>
		</div>

		<pre style="direction: ltr; background-color: #DDFFFF; page-break-inside: avoid;">
The document now has a baseline direction; this determines the:
- text-alignment in blocks for which text-align has not been specifically set
- layout of mirrored page-margins, columns, ToC and Indexes, headers and footers
- base direction can be set by any of:
	- $this->mpdf-&gt;SetDirectionality('rtl');
	- &lt;html dir="rtl" or style="direction: rtl;"&gt;
	- &lt;body dir="rtl" or style="direction: rtl;"&gt;
Base direction is an inherited CSS property, so will affect all content, unless...
- direction can be set for all HTML block elements e.g.
	&lt;DIV&gt;&lt;P&gt;&lt;TABLE&gt;&lt;TD&gt;&lt;UL&gt;&lt;LI&gt; etc using
	- CSS property &lt; style="direction: rtl;"&gt;
NOTE
- block/table margins/paddings are NOT reversed by direction
	NB mPDF &lt;5.1 reversed the margins/paddings for blocks when RTL set.
- language (either CSS "lang", using Autofont, or through initial set-up e.g. <code>$this->mpdf = new \Mpdf\Mpdf(['mode' => 'ar']);</code>)
	no longer affects direction in any way.
	- config_cp.php has been changed as a result; any values of "dir" set here are now ineffective
- default text-align is now as per CSS spec: "a nameless value which is dependent on direction"
	NB default text-align removed from default stylesheet in config.php
- once text-align is specified, it is respected and inherited
	NB mPDF &lt;5.1 reversed the text-align property for all blocks when RTL set.
- the configurable value is depracated, as it is no longer required
- the algorithm for handling bidirectioal text was substantially re-written/improved in mPDF v 6.0
	</pre>

		<pagebreak/>

		<h3>Tables</h3>
		<p>Tables are automatically transposed when the direction is rtl:&#x200E;</p>
		<table lang="ar" class="bpmTopicC">
			<thead>
			<tr class="headerrow">
				<th>
					<indexentry content="&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;"/>&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;
				</th>
				<td>
					<p>&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;</p>
				</td>
				<td>&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;</td>
			</tr>
			</thead>
			<tbody>
			<tr class="oddrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			<tr class="evenrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>
					<p>&#x642;&#x627;&#x644; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633; &#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;
						<indexentry content="&#x62c;&#x648;&#x631;&#x62c;"/>&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;
						&#x641;&#x64a; &#x62d;&#x62f;&#x64a;&#x62b; &#x645;&#x62a;&#x644;&#x641;&#x632;
					</p>
				</td>
				<td>
					<p>&#x642;&#x627;&#x644; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633; &#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;
						&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634; &#x641;&#x64a; &#x62d;&#x62f;&#x64a;&#x62b;
						&#x645;&#x62a;&#x644;&#x641;&#x632;</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>
					<p>&#x642;&#x627;&#x644;</p>
				</th>
				<td>
					<p>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</p>
				</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			<tr class="evenrow">
				<th>
					<p>&#x642;&#x627;&#x644;</p>
					<p>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</p>
				</th>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
				<td>
					<p>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			<tr class="evenrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>
					<indexentry content="&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;"/>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;
				</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			<tr class="oddrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>
					<indexentry content="&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;"/>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;
				</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			<tr class="evenrow">
				<th>&#x642;&#x627;&#x644;</th>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
				<td>&#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</td>
			</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<h3>Lists</h3>

		<p>Lists will automatically reverse as well (note the use of list-style to set numbering):&#x200E;</p>
		<div style="background-color:#ddccff; padding:5pt;">
			<ol lang="ar" style="list-style-type: arabic-indic;">
				<li>&#x642;&#x627;&#x644; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;</li>
				<li>&#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;
					<ul>
						<li>&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634; &#x641;&#x64a; &#x62c;&#x648;&#x631;&#x62c;
							&#x628;&#x648;&#x634; &#x641;&#x64a; &#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;
							&#x641;&#x64a;
							<indexentry content="&#x62c;&#x648;&#x631;&#x62c;"/>&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;
							&#x641;&#x64a;
							<indexentry content="&#x62c;&#x648;&#x631;&#x62c;"/>&#x62c;&#x648;&#x631;&#x62c; &#x628;&#x648;&#x634;
							&#x641;&#x64a;
							<indexentry content="&#x62c;&#x648;&#x631;&#x62c;"/>&#x62c;&#x648;&#x631;&#x62c;
						</li>
						<li>&#x62d;&#x62f;&#x64a;&#x62b; &#x645;&#x62a;&#x644;&#x641;&#x632;
							<ul>
								<li>&#x641;&#x64a; &#x627;&#x644;&#x630;&#x643;&#x631;&#x649;
									<indexentry content="&#x627;&#x644;&#x631;&#x627;&#x628;&#x639;&#x629;"/>&#x627;&#x644;&#x631;&#x627;&#x628;&#x639;&#x629;
								</li>
								<li>&#x644;&#x644;&#x63a;&#x632;&#x648; &#x627;&#x644;&#x627;&#x645;&#x631;&#x64a;&#x643;&#x64a;</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>&#x644;&#x644;&#x639;&#x631;&#x627;&#x642; &#x627;&#x646; &#x627;&#x644;&#x627;&#x648;&#x644;&#x648;&#x64a;&#x629;
					&#x62d;&#x627;&#x644;&#x64a;&#x627;
				</li>
				<li>&#x644;&#x627;&#x639;&#x627;&#x62f;&#x629; &#x627;&#x644;&#x627;&#x645;&#x646; &#x644;&#x644;&#x639;&#x631;&#x627;&#x642;</li>
			</ol>
		</div>
		</body>
		<?php
		$html = ob_get_clean();

		// Set Header and Footer
		$h = array(
				'odd' =>
						array(
								'R' =>
										array(
												'content' => '{PAGENO}',
												'font-size' => 8,
												'font-style' => 'B',
										),
								'L' =>
										array(
												'content' => "\xd9\x82\xd8\xa7\xd9\x84 \xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3",
												'font-size' => 8,
												'font-style' => 'B',
										),
								'line' => 1,
						),
				'even' =>
						array(
								'L' =>
										array(
												'content' => '{PAGENO}',
												'font-size' => 8,
												'font-style' => 'B',
										),
								'R' =>
										array(
												'content' => "\xd9\x82\xd8\xa7\xd9\x84 \xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3",
												'font-size' => 8,
												'font-style' => 'B',
										),
								'line' => 1,
						),
		);

		$f = array(
				'odd' =>
						array(
								'L' =>
										array(
												'content' => '{nbpg}',
												'font-size' => 8,
												'font-style' => 'BI',
										),
								'C' =>
										array(
												'content' => '- {PAGENO} -',
												'font-size' => 8,
										),
								'R' =>
										array(
												'content' => "\xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3",
												'font-size' => 8,
										),
								'line' => 1,
						),
				'even' =>
						array(
								'L' =>
										array(
												'content' => "\xd8\xa7\xd9\x84\xd8\xb1\xd8\xa6\xd9\x8a\xd8\xb3",
												'font-size' => 8,
												'font-style' => 'B',
										),
								'C' =>
										array(
												'content' => '- {PAGENO} -',
												'font-size' => 8,
										),
								'R' =>
										array(
												'content' => '{nbpg}',
												'font-size' => 8,
												'font-style' => 'BI',
										),
								'line' => 1,
						),
		);

		$this->mpdf = new \Mpdf\Mpdf([
				'margin_left' => 32,
				'margin_right' => 25,
				'margin_top' => 27,
				'margin_bottom' => 25,
				'margin_header' => 16,
				'margin_footer' => 13,
				'mirrorMargins' => true,
				'autoLangToFont' => true,
				'defaultPageNumStyle' => 'arabic-indic'
		]);

		$this->mpdf->SetDirectionality('rtl');
		$this->mpdf->setHeader($h);
		$this->mpdf->setFooter($f);

		$this->mpdf->WriteHTML($html);
		$this->mpdf->AddPage();

		$this->mpdf->SetColumns(2, 'J');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetColumns(0);

		$html = '
<pagebreak type="next-odd" />
<h2>Index</h2>
<columns column-count="2" column-gap="5" />
<indexinsert usedivletters="on" links="on" collation="ar_SA.utf8" collation-group="Arabic_Saudi_Arabia" />
';

		$this->mpdf->WriteHTML($html);
	}
}
