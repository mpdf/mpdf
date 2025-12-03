<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class JustifySnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'justify';
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
		<h1>mPDF</h1>
		<h2>Justification</h2>

		<h4>Tables</h4>
		<p>Text can be justified in table cells using in-line or stylesheet CSS. (Note that &lt;p&gt; tags are removed within cells along with any style definition or attributes.)</p>
		<table class="bpmTopnTailC"><thead>
			<tr class="headerrow"><th>Col/Row Header</th>
				<td>
					<p>Second column header p</p>
				</td>
				<td>Third column header</td>
			</tr>
			</thead><tbody>
			<tr class="oddrow"><th>Row header 1</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow"><th>Row header 2</th>
				<td>
					<p>This is data p</p>
				</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow"><th>
					<p>Row header 3</p>
				</th>
				<td>
					<p>This is long data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow"><th>
					<p>Row header 4</p>
					<p>&lt;th&gt; cell acting as header</p>
				</th>
				<td style="text-align:justify;"><p>Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </p></td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow"><th>Row header 5</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow"><th>Row header 6</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="oddrow"><th>Row header 7</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow"><th>Row header 8</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody></table>
		<p>&nbsp;</p>

		<h4>Testing Justification with Long Words</h4>
		<p><a href="http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL&amp;snd=4356&amp;dnd=4356">http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL&amp;snd=4356&amp;dnd=4356</a></p>
		<h5>Should not split</h5>
		<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplit</i> neque sit amet erat</p>
		<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend et <i>verylongwordthatwontsplit</i> neque sit amet erat</p>

		<h5>Non-breaking Space &amp;nbsp;</h5><p>The next example has a non-breaking space between <i>eleifend</i> and the very long word.</p><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend&nbsp;verylongwordthatwontsplitanywhere neque sit amet erat</p><p>Nbsp will only work in fonts that have a glyph to represent the character i.e. not in the CJK languages nor some Unicode fonts.</p>



		<h4>Testing Justification with mixed Styles</h4>
		<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
		<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s> and then more text.
		</p>
		<p><big>Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
		</p>
		<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small>
		</p>

		<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
		<p style="font-size:7pt;">This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s> then more text.
		</p>
		<p></p>
		<p style="font-size:7pt;">
			<big>Repeated in BIG: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
		</p>

		<style>
			body { font-family: 'DejaVu Sans Condensed'; font-size: 11pt;  }
			p { 	text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

			table {font-family: 'DejaVu Sans Condensed'; font-size: 9pt; line-height: 1.2;
				margin-top: 2pt; margin-bottom: 5pt;
				border-collapse: collapse; }

			thead {	font-weight: bold; vertical-align: bottom; }
			tfoot {	font-weight: bold; vertical-align: top; }
			thead td { font-weight: bold; }
			tfoot td { font-weight: bold; }

			.headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
			.footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

			th {	font-weight: bold;
				vertical-align: top;
				padding-left: 2mm;
				padding-right: 2mm;
				padding-top: 0.5mm;
				padding-bottom: 0.5mm;
			}

			td {	padding-left: 2mm;
				vertical-align: top;
				padding-right: 2mm;
				padding-top: 0.5mm;
				padding-bottom: 0.5mm;
			}

			th p { margin:0pt;  }
			td p { margin:0pt;  }

			table.widecells td {
				padding-left: 5mm;
				padding-right: 5mm;
			}
			table.tallcells td {
				padding-top: 3mm;
				padding-bottom: 3mm;
			}

			hr {	width: 70%; height: 1px;
				text-align: center; color: #999999;
				margin-top: 8pt; margin-bottom: 8pt; }

			a {	color: #000066; font-style: normal; text-decoration: underline;
				font-weight: normal; }

			pre { font-family: 'DejaVu Sans Mono'; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

			h1 {	font-weight: normal; font-size: 26pt; color: #000066;
				font-family: 'DejaVu Sans Condensed'; margin-top: 18pt; margin-bottom: 6pt;
				border-top: 0.075cm solid #000000; border-bottom: 0.075cm solid #000000;
				text-align: ; page-break-after:avoid; }
			h2 {	font-weight: bold; font-size: 12pt; color: #000066;
				font-family: 'DejaVu Sans Condensed'; margin-top: 6pt; margin-bottom: 6pt;
				border-top: 0.07cm solid #000000; border-bottom: 0.07cm solid #000000;
				text-align: ;  text-transform:uppercase; page-break-after:avoid; }
			h3 {	font-weight: normal; font-size: 26pt; color: #000000;
				font-family: 'DejaVu Sans Condensed'; margin-top: 0pt; margin-bottom: 6pt;
				border-top: 0; border-bottom: 0;
				text-align: ; page-break-after:avoid; }
			h4 {	font-weight: ; font-size: 13pt; color: #9f2b1e;
				font-family: 'DejaVu Sans Condensed'; margin-top: 10pt; margin-bottom: 7pt; font-variant: small-caps;
				text-align: ;  margin-collapse:collapse; page-break-after:avoid; }
			h5 {	font-weight: bold; font-style:italic; ; font-size: 11pt; color: #000044;
				font-family: 'DejaVu Sans Condensed'; margin-top: 8pt; margin-bottom: 4pt;
				text-align: ;  page-break-after:avoid; }
			h6 {	font-weight: bold; font-size: 9.5pt; color: #333333;
				font-family: 'DejaVu Sans Condensed'; margin-top: 6pt; margin-bottom: ;
				text-align: ;  page-break-after:avoid; }

			.breadcrumb {
				text-align: right; font-size: 8pt; font-family: 'DejaVu Serif Condensed'; color: #666666;
				font-weight: bold; font-style: normal; margin-bottom: 6pt; }

			.bpmTopic tbody tr:nth-child(even) { background-color: #f5f8f5; }
			.bpmTopicC tbody tr:nth-child(even) { background-color: #f5f8f5; }
			.bpmNoLines tbody tr:nth-child(even) { background-color: #f5f8f5; }
			.bpmNoLinesC tbody tr:nth-child(even) { background-color: #f5f8f5; }
			.bpmTopnTail tbody tr:nth-child(even) { background-color: #f5f8f5; }
			.bpmTopnTailC tbody tr:nth-child(even) { background-color: #f5f8f5; }

			.evenrow td, .evenrow th { background-color: #f5f8f5; }
			.oddrow td, .oddrow th { background-color: #e3ece4; }

			.bpmTopic {	background-color: #e3ece4; }
			.bpmTopicC { background-color: #e3ece4; }
			.bpmNoLines { background-color: #e3ece4; }
			.bpmNoLinesC { background-color: #e3ece4; }
			.bpmClear {		}
			.bpmClearC { text-align: center; }
			.bpmTopnTail { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
			.bpmTopnTailC { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
			.bpmTopnTailClear { topntail: 0.02cm solid #495b4a; }
			.bpmTopnTailClearC { topntail: 0.02cm solid #495b4a; }

			.bpmTopicC td, .bpmTopicC td p { text-align: center; }
			.bpmNoLinesC td, .bpmNoLinesC td p { text-align: center; }
			.bpmClearC td, .bpmClearC td p { text-align: center; }
			.bpmTopnTailC td, .bpmTopnTailC td p { text-align: center;  }
			.bpmTopnTailClearC td, .bpmTopnTailClearC td p {  text-align: center;  }

			.pmhMiddleCenter { text-align:center; vertical-align:middle; }
			.pmhMiddleRight {	text-align:right; vertical-align:middle; }
			.pmhBottomCenter { text-align:center; vertical-align:bottom; }
			.pmhBottomRight {	text-align:right; vertical-align:bottom; }
			.pmhTopCenter {	text-align:center; vertical-align:top; }
			.pmhTopRight {	text-align:right; vertical-align:top; }
			.pmhTopLeft {	text-align:left; vertical-align:top; }
			.pmhBottomLeft {	text-align:left; vertical-align:bottom; }
			.pmhMiddleLeft {	text-align:left; vertical-align:middle; }

			.infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

			.bpmTopic td, .bpmTopic th  {	border-top: 1px solid #FFFFFF; }
			.bpmTopicC td, .bpmTopicC th  {	border-top: 1px solid #FFFFFF; }
			.bpmTopnTail td, .bpmTopnTail th  {	border-top: 1px solid #FFFFFF; }
			.bpmTopnTailC td, .bpmTopnTailC th  {	border-top: 1px solid #FFFFFF; }
		</style>
		<?php
		$html = ob_get_clean();

		$this->mpdf = new \Mpdf\Mpdf([
				'margin_left' => 32,
				'margin_right' => 25,
				'margin_top' => 27,
				'margin_bottom' => 25,
				'margin_header' => 16,
				'margin_footer' => 13
		]);
		$this->mpdf->SetBasePath(__DIR__ . '/../data');
		$this->mpdf->WriteHTML($html);

		// SPACING
		$this->mpdf->WriteHTML("<h4>Spacing</h4><p>mPDF uses both letter- and word-spacing for text justification. The default is a mixture of both, set by the configurable values jSWord and jSmaxChar. (Only word spacing is used when cursive languages such as Arabic or Indic are detected.) </p>");

		$this->mpdf->jSWord = 0;	// Proportion (/1) of space (when justifying margins) to allocate to Word vs. Character
		$this->mpdf->jSmaxChar = 0;	// Maximum spacing to allocate to character spacing. (0 = no maximum)
		$this->mpdf->WriteHTML("<h5>Character spacing</h5><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");

// Back to default settings
		$this->mpdf->jSWord = 0.4;
		$this->mpdf->jSmaxChar = 2;
		$this->mpdf->WriteHTML("<h5>Word spacing</h5><p style=\"letter-spacing:0\">Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");

		$this->mpdf->WriteHTML("<h5>Mixed Character and Word spacing</h5><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");
	}
}
