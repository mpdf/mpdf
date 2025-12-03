<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class TableSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'table';
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
		<link href="css/tables.css" rel="stylesheet">
		<style>
			table.outer2 {
				border-collapse: separate;
				border: 4px solid #088000;
				padding: 3px;
				margin: 10px 0px;
				empty-cells: hide;
				background-color: yellow;
			}

			table.outer2 td {
				font-family: Times;
			}

			table.inner {
				border-collapse: collapse;
				border: 2px solid #000088;
				padding: 3px;
				margin: 5px;
				empty-cells: show;
				background-color:#FFCCFF;
			}

			table.inner td {
				border: 1px solid #000088;
				padding: 0px;
				font-family: monospace;
				font-style: italic;
				font-weight: bold;
				color: #880000;
				background-color:#FFECDF;
			}

			table.collapsed {
				border-collapse: collapse;
			}

			table.collapsed td {
				background-color:#EDFCFF;
			}

			table.table2 {
				border: 2mm solid aqua;
				border-collapse: collapse;
			}

			table.layout {
				border: 0mm solid black;
				border-collapse: collapse;
			}

			td.layout {
				text-align: center;
				border: 0mm solid black;
			}

			td.redcell {
				border: 3mm solid red;
			}

			td.redcell2 {
				border: 2mm solid red;
			}
		</style>

		<h1>mPDF</h1>
		<h2>Tables</h2>
		<h3>CSS Styles</h3>
		<p>The CSS properties for tables and cells is increased over that in html2fpdf. It includes recognition of
			THEAD, TFOOT and TH.<br/>See below for other facilities such as autosizing, and rotation.</p>
		<table border="1">
			<tbody>
			<tr>
				<td>Row 1</td>
				<td>This is data</td>
				<td>This is data</td>
			</tr>

			<tr>
				<td>Row 2</td>

				<td style="background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;">
					<p>This is data p</p>
					This is data out of p
					<p style="font-weight:bold; font-size:20pt; background-color:#FFBBFF;">This is bold data p</p>
					<b>This is bold data out of p</b><br/>
					This is normal data after br
					<h3>H3 in a table</h3>
					<div>This is data div</div>
					This is data out of div
					<div style="font-weight:bold;">This is data div (bold)</div>
					This is data out of div
				</td>


				<td><p>More data</p>
					<p style="font-size:12pt;">This is large text</p></td>
			</tr>
			<tr>
				<td><p>Row 3</p></td>
				<td><p>This is long data</p></td>
				<td>This is data</td>
			</tr>
			<tr>
				<td><p>Row 4 &lt;td&gt; cell</p></td>
				<td>This is data</td>
				<td><p>This is data</p></td>
			</tr>
			<tr>
				<td>Row 5</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 6</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 7</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 8</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>

		<p>This table has padding-left and -right set to 5mm i.e. padding within the cells. Also border colour and
			style, font family and size are set by <acronym>CSS</acronym>.</p>
		<table align="right" style="border: 1px solid #880000; font-family: Mono; font-size: 7pt; " class="widecells">
			<tbody>
			<tr>
				<td>Row 1</td>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>Row 2</td>
				<td><p>This is data p</p></td>
				<td><p>More data</p></td>
			</tr>
			<tr>
				<td><p>Row 3</p></td>
				<td><p>This is long data</p></td>
				<td>This is data</td>
			</tr>
			<tr>
				<td><p>Row 4 &lt;td&gt; cell</p></td>
				<td>This is data</td>
				<td><p>This is data</p></td>
			</tr>
			<tr>
				<td>Row 5</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 6</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 7</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 8</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>

		<p>This table has padding-top and -bottom set to 3mm i.e. padding within the cells. Also background-, border
			colour and style, font family and size are set by in-line <acronym>CSS</acronym>.</p>
		<table style="border: 1px solid #880000; background-color: #BBCCDD; font-family: Mono; font-size: 7pt; "
			   class="tallcells">
			<tbody>
			<tr>
				<td>Row 1</td>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>Row 2</td>
				<td><p>This is data p</p></td>
				<td><p>More data</p></td>
			</tr>
			<tr>
				<td><p>Row 3</p></td>
				<td><p>This is long data</p></td>
				<td>This is data</td>
			</tr>
			</tbody>
		</table>


		<h3 style="margin-top: 20pt; margin-collapse:collapse;">Table Styles</h3>
		<p>The style sheet used for these examples shows some of the table styles I use on my website. The property
			\'topntail\' defined by a border-type definition e.g. "1px solid #880000" puts a border at the top and
			bottom of the table, and also below a header row (thead) if defined. Note also that &lt;thead&gt; will
			automatically turn on the header-repeat i.e. reproduce the header row at the top of each page.</p>
		<p>bpmTopic Class</p>
		<table class="bpmTopic">
			<thead></thead>
			<tbody>
			<tr>
				<td>Row 1</td>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>Row 2</td>
				<td>
					<p>This is data p</p>
				</td>
				<td>
					<p>More data</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>Row 3</p>
				</td>
				<td>
					<p>This is long data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>
					<p>Row 4 &lt;td&gt; cell</p>
				</td>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr>
				<td>Row 5</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 6</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 7</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 8</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<p>bpmTopic<b>C</b> Class (centered) Odd and Even rows</p>
		<table class="bpmTopicC">
			<thead>
			<tr class="headerrow">
				<th>Col/Row Header</th>
				<td>
					<p>Second column header p</p>
				</td>
				<td>Third column header</td>
			</tr>
			</thead>
			<tbody>
			<tr class="oddrow">
				<th>Row header 1</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 2</th>
				<td>
					<p>This is data p</p>
				</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>
					<p>Row header 3</p>
				</th>
				<td>
					<p>This is long data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>
					<p>Row header 4</p>
					<p>&lt;th&gt; cell acting as header</p>
				</th>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>Row header 5</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 6</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="oddrow">
				<th>Row header 7</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 8</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<p>bpmTopnTail Class </p>
		<table class="bpmTopnTail">
			<thead></thead>
			<tbody>
			<tr>
				<td>Row 1</td>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>Row 2</td>
				<td>
					<p>This is data p</p>
				</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>Row 3</p>
				</td>
				<td>
					<p>This is long data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr>
				<td>
					<p>Row 4 &lt;td&gt; cell</p>
				</td>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr>
				<td>Row 5</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 6</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 7</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<td>Row 8</td>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>
		<p>&nbsp;</p>
		<p>bpmTopnTail<b>C</b> Class (centered) Odd and Even rows</p>
		<table class="bpmTopnTailC">
			<thead>
			<tr class="headerrow">
				<th>Col/Row Header</th>
				<td>
					<p>Second column header p</p>
				</td>
				<td>Third column header</td>
			</tr>
			</thead>
			<tbody>
			<tr class="oddrow">
				<th>Row header 1</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 2</th>
				<td>
					<p>This is data p</p>
				</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>
					<p>Row header 3</p>
				</th>
				<td>
					<p>This is long data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>
					<p>Row header 4</p>
					<p>&lt;th&gt; cell acting as header</p>
				</th>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th>Row header 5</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 6</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="oddrow">
				<th>Row header 7</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 8</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<p>TopnTail Class</p>
		<table class="bpmTopnTail">
			<thead>
			<tr class="headerrow">
				<th>Col and Row Header</th>
				<td>
					<p>Second</p>
					<p>column</p>
				</td>
				<td class="pmhTopRight">Top right align</td>
			</tr>
			</thead>
			<tbody>
			<tr class="oddrow">
				<th>
					<p>Row header 1 p</p>
				</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 2</th>
				<td class="pmhBottomRight"><b><i>Bottom right align</i></b></td>
				<td>
					<p>This is data. Can use</p>
					<p><b>bold</b> <i>italic </i><sub>sub</sub> or <sup>sup</sup> text</p>
				</td>
			</tr>
			<tr class="oddrow">
				<th class="pmhBottomRight">
					<p>Bottom right align</p>
				</th>
				<td class="pmhMiddleCenter" style="border: #000000 1px solid">
					<p>This is data. This cell</p>
					<p>uses Cell Styles to set</p>
					<p>the borders.</p>
					<p>All borders are collapsible</p>
					<p>in mPDF.</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 4</th>
				<td>
					<p>This is data p</p>
				</td>
				<td>More data</td>
			</tr>
			<tr class="oddrow">
				<th>Row header 5</th>
				<td colspan="2" class="pmhTopCenter">Also data merged and centered</td>
			</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<h4>Lists in a Table</h4>
		<table class="bpmTopnTail">
			<thead>
			<tr class="headerrow">
				<th>Col and Row Header</th>
				<td>
					<p>Second</p>
					<p>column</p>
				</td>
				<td class="pmhTopRight">Top right align</td>
			</tr>
			</thead>
			<tbody>
			<tr class="oddrow">
				<th>
					<p>Row header 1 p</p>
				</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr class="evenrow">
				<th>Row header 2</th>
				<td>
					<ol>
						<li>Item 1</li>
						<li>Item 2
							<ol type="a">
								<li>Subitem of ordered list</li>
								<li>Subitem 2
									<ol type="i">
										<li>Level 3 subitem</li>
										<li>Level 3 subitem</li>
									</ol>
								</li>
							</ol>
						</li>
						<li>Item 3</li>
						<li>Another Item</li>
						<li>Subitem
							<ol>
								<li>Level 3 subitem</li>
							</ol>
						</li>
						<li>Another Item</li>
					</ol>
				</td>
				<td>
					Unordered list:
					<ul>
						<li>Item 1</li>
						<li>Item 2
							<ul>
								<li>Subitem of unordered list</li>
								<li>Subitem 2
									<ul>
										<li>Level 3 subitem</li>
										<li>Level 3 subitem</li>
										<li>Level 3 subitem</li>
									</ul>
								</li>
							</ul>
						</li>
						<li>Item 3</li>
					</ul>
				</td>
			</tr>
			</tbody>
		</table>
		<p>&nbsp;</p>


		<h4>Automatic Column Width</h4>
		<table class="bpmTopnTail">
			<tbody>
			<tr>
				<td>Causes</td>
				<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. <br/>
					Ut a eros at ligula vehicula pretium; maecenas feugiat pede vel risus.<br/>
					Suspendisse potenti
				</td>
			</tr>
			<tr>
				<td>Mechanisms</td>
				<td>Ut magna ipsum, tempus in, condimentum at, rutrum et, nisl. Vestibulum interdum luctus sapien.
					Quisque viverra. Etiam id libero at magna pellentesque aliquet. Nulla sit amet ipsum id enim tempus
					dictum. Maecenas consectetuer eros quis massa. Mauris semper velit vehicula purus. Duis lacus.
					Aenean pretium consectetuer mauris. Ut purus sem, consequat ut, fermentum sit amet, ornare sit amet,
					ipsum. Donec non nunc. Maecenas fringilla. Curabitur libero. In dui massa, malesuada sit amet,
					hendrerit vitae, viverra nec, tortor. Donec varius. Ut ut dolor et tellus adipiscing adipiscing.
				</td>
			</tr>
			</tbody>
		</table>


		<h4>ColSpan & Rowspan</h4>
		<table class="bpmTopnTail">
			<tbody>
			<tr>
				<td rowspan="2">Causes</td>
				<td colspan="2">Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. <br/>
					Ut a eros at ligula vehicula pretium; maecenas feugiat pede vel risus.<br/>
					Suspendisse potenti
				</td>
			</tr>
			<tr>
				<td>Fusce eleifend neque sit amet erat.<br/>
					Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et
					risus.
				</td>
				<td>Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla.<br/>
					Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et
					sapien.
				</td>
			</tr>
			</tbody>
		</table>


		<h4>Table Header & Footer Rows</h4>
		<p>A table using a header row should repeat the header row across pages:</p>
		<p>bpmTopic<b>C</b> Class</p>
		<table class="bpmTopicC">
			<thead>
			<tr class="headerrow">
				<th>Col and Row Header</th>
				<td>
					<p>Second column header</p>
				</td>
				<td>Third column header</td>
			</tr>
			</thead>
			<tfoot>
			<tr class="footerrow">
				<th>Col and Row Footer</th>
				<td>
					<p>Second column footer</p>
				</td>
				<td>Third column footer</td>
			</tr>
			</tfoot>
			<tbody>
			<tr>
				<th>Row header 1</th>
				<td>This is data</td>
				<td>This is data</td>
			</tr>
			<tr>
				<th>Row header 2</th>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr>
				<th>
					<p>Row header 3</p>
				</th>
				<td>
					<p>This is data</p>
				</td>
				<td>This is data</td>
			</tr>
			<tr>
				<th>Row header 4</th>
				<td>This is data</td>
				<td>
					<p>This is data</p>
				</td>
			</tr>
			<tr>
				<th>Row header 5</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Row header 6</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Row header 7</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Row header 8</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Row header 9</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			<tr>
				<th>Another Row header</th>
				<td>Also data</td>
				<td>Also data</td>
			</tr>
			</tbody>
		</table>
		<p>&nbsp;</p>

		<h3>Autosizing Tables</h3>
		<p>Periodic Table of elements. Tables are set by default to reduce font size if complete words will not fit
			inside each cell, to a maximum of 1/1.4 * the set font-size. This value can be changed by setting
			$mpdf->shrink_tables_to_fit=1.8 or using html attribute &lt;table autosize="1.8"&gt;.</p>

		<h5>Periodic Table</h5>

		<table style="border:1px solid #000000;" cellPadding="9">
			<thead>
			<tr>
				<th>1A</th>
				<th>2A</th>
				<th>3B</th>
				<th>4B</th>
				<th>5B</th>
				<th>6B</th>
				<th>7B</th>
				<th>8B</th>
				<th>8B</th>
				<th>8B</th>
				<th>1B</th>
				<th>2B</th>
				<th>3A</th>
				<th>4A</th>
				<th>5A</th>
				<th>6A</th>
				<th>7A</th>
				<th>8A</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="18"></td>
			</tr>
			<tr>
				<td>H</td>
				<td colspan="16"></td>
				<td>He</td>
			</tr>
			<tr>
				<td>Li</td>
				<td>Be</td>
				<td colspan="10"></td>
				<td>B</td>
				<td>C</td>
				<td>N</td>
				<td>O</td>
				<td>F</td>
				<td>Ne</td>
			</tr>
			<tr>
				<td>Na</td>
				<td>Mg</td>
				<td colspan="10"></td>
				<td>Al</td>
				<td>Si</td>
				<td>P</td>
				<td>S</td>
				<td>Cl</td>
				<td>Ar</td>
			</tr>
			<tr>
				<td>K</td>
				<td>Ca</td>
				<td>Sc</td>
				<td>Ti</td>
				<td>V</td>
				<td>Cr</td>
				<td>Mn</td>
				<td>Fe</td>
				<td>Co</td>
				<td>Ni</td>
				<td>Cu</td>
				<td>Zn</td>
				<td>Ga</td>
				<td>Ge</td>
				<td>As</td>
				<td>Se</td>
				<td>Br</td>
				<td>Kr</td>
			</tr>
			<tr>
				<td>Rb</td>
				<td>Sr</td>
				<td>Y</td>
				<td>Zr</td>
				<td>Nb</td>
				<td>Mo</td>
				<td>Tc</td>
				<td>Ru</td>
				<td>Rh</td>
				<td>Pd</td>
				<td>Ag</td>
				<td>Cd</td>
				<td>In</td>
				<td>Sn</td>
				<td>Sb</td>
				<td>Te</td>
				<td>I</td>
				<td>Xe</td>
			</tr>
			<tr>
				<td>Cs</td>
				<td>Ba</td>
				<td>La</td>
				<td>Hf</td>
				<td>Ta</td>
				<td>W</td>
				<td>Re</td>
				<td>Os</td>
				<td>Ir</td>
				<td>Pt</td>
				<td>Au</td>
				<td>Hg</td>
				<td>Tl</td>
				<td>Pb</td>
				<td>Bi</td>
				<td>Po</td>
				<td>At</td>
				<td>Rn</td>
			</tr>
			<tr>
				<td>Fr</td>
				<td>Ra</td>
				<td>Ac</td>
				<td colspan="15"></td>
			</tr>
			<tr>
				<td colspan="18"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Ce</td>
				<td>Pr</td>
				<td>Nd</td>
				<td>Pm</td>
				<td>Sm</td>
				<td>Eu</td>
				<td>Gd</td>
				<td>Tb</td>
				<td>Dy</td>
				<td>Ho</td>
				<td>Er</td>
				<td>Tm</td>
				<td>Yb</td>
				<td>Lu</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Th</td>
				<td>Pa</td>
				<td>U</td>
				<td>Np</td>
				<td>Pu</td>
				<td>Am</td>
				<td>Cm</td>
				<td>Bk</td>
				<td>Cf</td>
				<td>Es</td>
				<td>Fm</td>
				<td>Md</td>
				<td>No</td>
				<td>Lr</td>
				<td></td>
			</tr>
			</tbody>
		</table>

		<pagebreak/>

		<h3>Rotating Tables</h3>
		<p>This is set to rotate -90 degrees (counterclockwise).</p>

		<h5>Periodic Table</h5>
		<p>
		<table rotate="-90" class="bpmClearC">
			<thead>
			<tr>
				<th>1A</th>
				<th>2A</th>
				<th>3B</th>
				<th>4B</th>
				<th>5B</th>
				<th>6B</th>
				<th>7B</th>
				<th>8B</th>
				<th>8B</th>
				<th>8B</th>
				<th>1B</th>
				<th>2B</th>
				<th>3A</th>
				<th>4A</th>
				<th>5A</th>
				<th>6A</th>
				<th>7A</th>
				<th>8A</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td></td>
				<td colspan="18"></td>
			</tr>
			<tr>
				<td>H</td>
				<td colspan="15"></td>
				<td></td>
				<td>He</td>
			</tr>
			<tr>
				<td>Li</td>
				<td>Be</td>
				<td colspan="10"></td>
				<td>B</td>
				<td>C</td>
				<td>N</td>
				<td>O</td>
				<td>F</td>
				<td>Ne</td>
			</tr>
			<tr>
				<td>Na</td>
				<td>Mg</td>
				<td colspan="10"></td>
				<td>Al</td>
				<td>Si</td>
				<td>P</td>
				<td>S</td>
				<td>Cl</td>
				<td>Ar</td>
			</tr>
			<tr>
				<td>K</td>
				<td>Ca</td>
				<td>Sc</td>
				<td>Ti</td>
				<td>V</td>
				<td>Cr</td>
				<td>Mn</td>
				<td>Fe</td>
				<td>Co</td>
				<td>Ni</td>
				<td>Cu</td>
				<td>Zn</td>
				<td>Ga</td>
				<td>Ge</td>
				<td>As</td>
				<td>Se</td>
				<td>Br</td>
				<td>Kr</td>
			</tr>
			<tr>
				<td>Rb</td>
				<td>Sr</td>
				<td>Y</td>
				<td>Zr</td>
				<td>Nb</td>
				<td>Mo</td>
				<td>Tc</td>
				<td>Ru</td>
				<td>Rh</td>
				<td>Pd</td>
				<td>Ag</td>
				<td>Cd</td>
				<td>In</td>
				<td>Sn</td>
				<td>Sb</td>
				<td>Te</td>
				<td>I</td>
				<td>Xe</td>
			</tr>
			<tr>
				<td>Cs</td>
				<td>Ba</td>
				<td>La</td>
				<td>Hf</td>
				<td>Ta</td>
				<td>W</td>
				<td>Re</td>
				<td>Os</td>
				<td>Ir</td>
				<td>Pt</td>
				<td>Au</td>
				<td>Hg</td>
				<td>Tl</td>
				<td>Pb</td>
				<td>Bi</td>
				<td>Po</td>
				<td>At</td>
				<td>Rn</td>
			</tr>
			<tr>
				<td>Fr</td>
				<td>Ra</td>
				<td>Ac</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="18"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Ce</td>
				<td>Pr</td>
				<td>Nd</td>
				<td>Pm</td>
				<td>Sm</td>
				<td>Eu</td>
				<td>Gd</td>
				<td>Tb</td>
				<td>Dy</td>
				<td>Ho</td>
				<td>Er</td>
				<td>Tm</td>
				<td>Yb</td>
				<td>Lu</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Th</td>
				<td>Pa</td>
				<td>U</td>
				<td>Np</td>
				<td>Pu</td>
				<td>Am</td>
				<td>Cm</td>
				<td>Bk</td>
				<td>Cf</td>
				<td>Es</td>
				<td>Fm</td>
				<td>Md</td>
				<td>No</td>
				<td>Lr</td>
				<td></td>
			</tr>
			</tbody>
		</table>
		<p>&nbsp;</p>

		<pagebreak/>
		<h3>Rotated text in Table Cells</h3>

		<h5>Periodic Table</h5>
		<table>
			<thead>
			<tr text-rotate="45">
				<th><p>Element type 1A</p>
					<p>Second line</p>
				<th><p>Element type longer 2A</p></th>
				<th>Element type 3B</th>
				<th>Element type 4B</th>
				<th>Element type 5B</th>
				<th>Element type 6B</th>
				<th>7B</th>
				<th>8B</th>
				<th>Element type 8B R</th>
				<th>8B</th>
				<th>Element <span>type</span> 1B</th>
				<th>2B</th>
				<th>Element type 3A</th>
				<th>Element type 4A</th>
				<th>Element type 5A</th>
				<th>Element type 6A</th>
				<th>7A</th>
				<th>Element type 8A</th>
			</tr>
			</thead>

			<tbody>
			<tr>
				<td>H</td>
				<td colspan="15"></td>
				<td></td>
				<td>He</td>
			</tr>
			<tr>
				<td>Li</td>
				<td>Be</td>
				<td colspan="10"></td>
				<td>B</td>
				<td>C</td>
				<td>N</td>
				<td>O</td>
				<td>F</td>
				<td>Ne</td>
			</tr>
			<tr>
				<td>Na</td>
				<td>Mg</td>
				<td colspan="10"></td>
				<td>Al</td>
				<td>Si</td>
				<td>P</td>
				<td>S</td>
				<td>Cl</td>
				<td>Ar</td>
			</tr>
			<tr style="text-rotate: 45">
				<td>K</td>
				<td>Ca</td>
				<td>Sc</td>
				<td>Ti</td>
				<td>Va</td>
				<td>Cr</td>
				<td>Mn</td>
				<td>Fe</td>
				<td>Co</td>
				<td>Ni</td>
				<td>Cu</td>
				<td>Zn</td>
				<td>Ga</td>
				<td>Ge</td>
				<td>As</td>
				<td>Se</td>
				<td>Br</td>
				<td>Kr</td>
			</tr>
			<tr>
				<td>Rb</td>
				<td>Sr</td>
				<td>Y</td>
				<td>Zr</td>
				<td>Nb</td>
				<td>Mo</td>
				<td>Tc</td>
				<td>Ru</td>
				<td style="text-align:right; ">Rh</td>
				<td>Pd</td>
				<td>Ag</td>
				<td>Cd</td>
				<td>In</td>
				<td>Sn</td>
				<td>Sb</td>
				<td>Te</td>
				<td>I</td>
				<td>Xe</td>
			</tr>
			<tr>
				<td>Cs</td>
				<td>Ba</td>
				<td>La</td>
				<td>Hf</td>
				<td>Ta</td>
				<td>W</td>
				<td>Re</td>
				<td>Os</td>
				<td>Ir</td>
				<td>Pt</td>
				<td>Au</td>
				<td>Hg</td>
				<td>Tl</td>
				<td>Pb</td>
				<td>Bi</td>
				<td>Po</td>
				<td>At</td>
				<td>Rn</td>
			</tr>
			<tr>
				<td>Fr</td>
				<td>Ra</td>
				<td colspan="16">Ac</td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Ce</td>
				<td>Pr</td>
				<td>Nd</td>
				<td>Pm</td>
				<td>Sm</td>
				<td>Eu</td>
				<td>Gd</td>
				<td>Tb</td>
				<td>Dy</td>
				<td>Ho</td>
				<td>Er</td>
				<td>Tm</td>
				<td>Yb</td>
				<td>Lu</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td>Th</td>
				<td>Pa</td>
				<td>U</td>
				<td>Np</td>
				<td>Pu</td>
				<td>Am</td>
				<td>Cm</td>
				<td>Bk</td>
				<td>Cf</td>
				<td>Es</td>
				<td>Fm</td>
				<td>Md</td>
				<td>No</td>
				<td>Lr</td>
				<td></td>
			</tr>
			</tbody>
		</table>
		<p>&nbsp;</p>

		<pagebreak/>

		<h2>Tables - Nested</h2>

		<div style="border: 2px solid #000088; background-color: #DDDDFF; padding: 2mm;">
			Text before table

			<div style="border: 2px solid #008888; background-color: #DCAFCF; padding: 2mm;">

				<table cellSpacing="2" rotate="-90" align="center" autosize="1.5">
					<tbody>
					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>

							<table cellSpacing="2">
								<tbody>
								<tr>
									<td>Row A</td>
									<td>A2</td>
									<td>A3</td>
									<td>A4</td>
								</tr>

								<tr>
									<td>Row B</td>
									<td>B2</td>
									<td>B3</td>
									<td>B4</td>
								</tr>

								<tr>
									<td>Row C</td>
									<td>C2</td>
									<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse
										potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus.
										Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla
										non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id <a
											href="http://www.dummy.com">euismod auctor</a>, neque metus pellentesque
										risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat,
										lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et
										sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla.
										Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis
										orci.
									</td>
									<td>C4</td>
								</tr>

								<tr>
									<td>Row D</td>
									<td>D2</td>
									<td>D3</td>
									<td>D4</td>
								</tr>

								</tbody>
							</table>


						</td>
						<td>This is data</td>
					</tr>
					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>

							<table cellSpacing="2">
								<tbody>
								<tr>
									<td>Row A</td>
									<td>A2</td>
									<td>A3</td>
									<td>A4</td>
								</tr>

								<tr>
									<td>Row B</td>
									<td>B2</td>
									<td>B3</td>
									<td>B4</td>
								</tr>

								<tr>
									<td>Row C</td>
									<td>C2</td>
									<td style="background: transparent url('img/bg.jpg') repeat scroll right top;">
										Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse
										potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus.
										Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla
										non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod
										auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.
										Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus
										tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac,
										suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus
										luctus euismod. Donec et nulla. Sed quis orci.
									</td>
									<td>C4</td>
								</tr>

								<tr>
									<td>Row D</td>
									<td>D2</td>
									<td>D3</td>
									<td>D4</td>
								</tr>

								</tbody>
							</table>


						</td>
						<td>This is data</td>
					</tr>

					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>

							<table cellSpacing="2">
								<tbody>
								<tr>
									<td>Row A</td>
									<td>A2</td>
									<td>A3</td>
									<td>A4</td>
								</tr>

								<tr>
									<td>Row B</td>
									<td>B2</td>
									<td>B3</td>
									<td>B4</td>
								</tr>

								<tr>
									<td>Row C</td>
									<td>C2</td>
									<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse
										potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus.
										Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla
										non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod
										auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.
										Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus
										tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac,
										suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus
										luctus euismod. Donec et nulla. Sed quis orci.
									</td>
									<td>C4</td>
								</tr>

								<tr>
									<td>Row D</td>
									<td>D2</td>
									<td>D3</td>
									<td>D4</td>
								</tr>

								</tbody>
							</table>


						</td>
						<td>This is data</td>
					</tr>

					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>

							<table cellSpacing="2">
								<tbody>
								<tr>
									<td>Row A</td>
									<td>A2</td>
									<td>A3</td>
									<td>A4</td>
								</tr>

								<tr>
									<td>Row B</td>
									<td>B2</td>
									<td>B3</td>
									<td>B4</td>
								</tr>

								<tr>
									<td>Row C</td>
									<td>C2</td>
									<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse
										potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus.
										Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla
										non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod
										auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus.
										Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus
										tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac,
										suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus
										luctus euismod. Donec et nulla. Sed quis orci.
									</td>
									<td>C4</td>
								</tr>

								<tr>
									<td>Row D</td>
									<td>D2</td>
									<td>D3</td>
									<td>D4</td>
								</tr>

								</tbody>
							</table>


						</td>
						<td>This is data</td>
					</tr>


					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>This is data</td>
						<td>This is data</td>
					</tr>

					<tr>
						<td>This is data</td>
						<td></td>
						<td>This is data</td>
						<td>This is data</td>
					</tr>

					<tr>
						<td>This is data</td>
						<td>This is data</td>
						<td>This is data</td>
						<td>This is data</td>
					</tr>


					</tbody>
				</table>

			</div>

			<p>Text before table</p>

			<table cellSpacing="2" class="outer2" autosize="3" style="page-break-inside:avoid">
				<tbody>
				<tr>
					<td>Row 1</td>
					<td>This is data</td>
					<td style="text-align: right;">
						Text before table

						<table cellSpacing="2" class="inner" width="80%">
							<tbody>
							<tr>
								<td>Row A</td>
								<td>A2</td>
								<td>A3</td>
								<td>A4</td>
							</tr>

							<tr>
								<td>Row B</td>
								<td>B2</td>
								<td>B3</td>
								<td>B4</td>
							</tr>

							<tr>
								<td>Row C</td>
								<td>C2</td>
								<td>C3</td>
								<td>C4</td>
							</tr>

							<tr>
								<td>Row D</td>
								<td>D2</td>
								<td>D3</td>
								<td>D4</td>
							</tr>

							</tbody>
						</table>
						<p>Text after table</p>


					</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 2</td>
					<td>This is data</td>
					<td>This is data</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 3</td>
					<td style="text-align: center; vertical-align: middle;">

						<table cellSpacing="2" class="inner" width="80%">
							<tbody>
							<tr>
								<td>Row A</td>
								<td>A2</td>
								<td>A3</td>
								<td>A4</td>
							</tr>

							<tr>
								<td>Row B</td>
								<td>B2</td>
								<td style="text-align:center;"><img src=img/bayeux2.jpg" width="84"
																	style="border:3px solid #44FF44; vertical-align:top; "/>
								</td>
								<td>B4</td>
							</tr>

							<tr>
								<td>Row C</td>
								<td>C2</td>
								<td>

									<table cellSpacing="2">
										<tbody>
										<tr>
											<td>F1</td>
											<td>F2</td>
										</tr>
										<tr>
											<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit.
												Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas
												feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet
												erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor.
												Cras odio. Donec et nulla. Sed quis orci.
											</td>
											<td>G2</td>
										</tr>
										</tbody>
									</table>

								</td>
								<td>C4</td>
							</tr>

							<tr>
								<td>Row D</td>
								<td>D2</td>
								<td>D3</td>
								<td>D4</td>
							</tr>

							</tbody>
						</table>


					</td>
					<td style="vertical-align: bottom; ">
						<table cellSpacing="2" class="inner" align="right">
							<tbody>
							<tr>
								<td>Row A</td>
								<td>A2</td>
								<td>A3</td>
								<td>A4</td>
							</tr>

							<tr>
								<td>Row B</td>
								<td>B2</td>
								<td>B3</td>
								<td>B4</td>
							</tr>

							<tr>
								<td>Row C</td>
								<td>C2</td>
								<td>C3</td>
								<td>C4</td>
							</tr>

							<tr>
								<td>Row D</td>
								<td>D2</td>
								<td>D3</td>
								<td>D4</td>
							</tr>

							</tbody>
						</table>
					</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 4</td>
					<td>This is data</td>
					<td>
						<table cellSpacing="2" class="inner">
							<tbody>
							<tr>
								<td>Row A</td>
								<td>A2</td>
								<td>A3</td>
								<td>A4</td>
							</tr>

							<tr>
								<td>Row B</td>
								<td>B2</td>
								<td style="text-align:center;"><img src=img/bayeux2.jpg" width="84"
																	style="border:3px solid #44FF44; vertical-align:top; "/>
								</td>
								<td>B4</td>
							</tr>

							<tr>
								<td>Row C</td>
								<td>C2</td>
								<td>

									<table cellSpacing="2">
										<tbody>
										<tr>
											<td>F1</td>
											<td>F2</td>
										</tr>
										<tr>
											<td>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit.
												Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas
												feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet
												erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor.
												Cras odio. Donec et nulla. Sed quis orci.
											</td>
											<td>G2</td>
										</tr>
										</tbody>
									</table>

								</td>
								<td>C4</td>
							</tr>

							<tr>
								<td>Row D</td>
								<td>D2</td>
								<td>D3</td>
								<td>D4</td>
							</tr>

							</tbody>
						</table>

					</td>
					<td>This is data</td>
				</tr>


				</tbody>
			</table>


		</div>

		<p>&nbsp;</p>

		<div style="border: 1px solid #000088; background-color: #DDDDFF; padding: 5mm;">
			Text before table

			<table cellSpacing="2" class="separate">
				<tbody>
				<tr>
					<td style="background-color:#FFCCFF;">Row 1</td>
					<td>This is data</td>
					<td>

						NO NESTING
					</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 2</td>
					<td>This is data</td>
					<td>This is data</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 3</td>
					<td>This is data</td>
					<td>This is data</td>
					<td>This is data</td>
				</tr>

				<tr>
					<td>Row 4</td>
					<td>This is data</td>
					<td>This is data</td>
					<td>This is data</td>
				</tr>

				</tbody>
			</table>

		</div>

		<pagebreak/>

		<h2>Borders</h2>

		Border conflict resolution in tables with border-collapse set to "collapse". mPDF follows the rules set by CSS as well as possible.

		<table class="layout">

			<tr>
				<td class="layout">mPDF</td>
			</tr>

			<tr>
				<td class="layout">
					<table>
						<tr>
							<td style="border:5mm solid green">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" class="redcell" style="border:5mm solid teal">1</td>
							<td style="border:3mm solid pink">1</td>
							<td style="border:5mm solid purple">1</td>
						</tr>
						<tr>
							<td style="border:2mm solid gray">1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>



				</td>
			</tr>

			<tr>
				<td class="layout" style="text-align: left">

					<table style="border: 2.5mm solid aqua">
						<tr>
							<td class="redcell">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" class="redcell" style="border:5mm solid green">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td class="layout">
					<table>
						<tr>
							<td class="redcell">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" >1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td style="border:5mm solid yellow">1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>


		<pagebreak />


		<table class="layout">

			<tr>
				<td class="layout">mPDF</td>
			</tr>

			<tr>
				<td class="layout">
					<table class="table2">
						<tr>
							<td style="border:2mm solid green">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" class="redcell2" style="border:2mm solid teal">1</td>
							<td style="border:2mm solid pink">1</td>
							<td style="border:2mm solid purple">1</td>
						</tr>
						<tr>
							<td style="border:2mm solid gray">1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell2">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td class="layout" style="text-align: left">

					<table style="border: 2mm solid aqua" class="table2">
						<tr>
							<td class="redcell2">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" class="redcell2" style="border:2mm solid green">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell2">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td class="layout">

					<table class="table2">
						<tr>
							<td class="redcell2">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td rowspan="2" >1</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td style="border:2mm solid yellow">1</td>
							<td>1</td>
						</tr>
						<tr>
							<td class="redcell2">1</td>
							<td>1</td>
							<td>1</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<pagebreak />

		<h4>mPDF</h4>

		<table style="border: 10px solid orange">
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px double red">double red</td>
				<td style="border: 10px dashed yellow">dashed yellow</td>
				<td style="border: 10px dotted green">dotted green</td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px hidden orange">hidden </td>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px none orange">none</td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px ridge blue">ridge blue</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px groove pink">groove pink</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px inset gray">inset gray</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px outset purple">outset purple</td>
				<td style="border: 10px none orange">none </td>
			</tr>
		</table>

		<pagebreak />

		<div>mPDF</div>

		<table style="border: 10px solid orange; border-collapse: separate;">
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px double red">double red</td>
				<td style="border: 10px dashed yellow">dashed yellow</td>
				<td style="border: 10px dotted green">dotted green</td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px hidden orange">hidden </td>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px none orange">none</td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px ridge blue">ridge blue</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px solid orange">Data</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px groove pink">groove pink</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px solid orange">Data</td>
			</tr>
			<tr>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px inset gray">inset gray</td>
				<td style="border: 10px none orange">none </td>
				<td style="border: 10px outset purple">outset purple</td>
				<td style="border: 10px none orange">none </td>
			</tr>
		</table>

		<br>

		<table style="border: 5px inset teal">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>

				<td style="border: 0px none black">none</td>

				<td style="border: 5px double red">double red</td>

				<td style="border: 0px none black">none</td>

				<td style="border: 5px inset gray">inset gray</td>

				<td style="border: 0px none black">none</td>

				<td style="border: 5px outset purple">outset purple</td>

				<td style="border: 0px none black">none</td>

				<td style="border: 5px groove pink">groove pink</td>

				<td style="border: 0px none black">none</td>

				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>

		<table style="border: 5px inset gray; border-collapse: separate;">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px double red">double red</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px inset gray">inset gray</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px outset purple">outset purple</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px groove pink">groove pink</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>

		<table style="border: 5px outset purple; border-collapse: separate;">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px double red">double red</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px inset gray">inset gray</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px outset purple">outset purple</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px groove pink">groove pink</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>

		<table style="border: 5px groove pink; border-collapse: separate;">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px double red">double red</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px inset gray">inset gray</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px outset purple">outset purple</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px groove pink">groove pink</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>
		
		<table style="border: 5px ridge blue; border-collapse: separate;">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px double red">double red</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px inset gray">inset gray</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px outset purple">outset purple</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px groove pink">groove pink</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>


		<table style="border: 5px double red; border-collapse: separate;">
			<tr>
				<td style="border: 5px solid orange">solid orange</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px double red">double red</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px inset gray">inset gray</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px outset purple">outset purple</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px groove pink">groove pink</td>
				<td style="border: 0px none black">none</td>
				<td style="border: 5px ridge blue">ridge blue</td>
			</tr>
		</table>

		<?php
		$html = ob_get_clean();

		$this->mpdf = new \Mpdf\Mpdf(
			[
			'mode'          => 'c',
			'margin_left'   => 32,
			'margin_right'  => 25,
			'margin_top'    => 27,
			'margin_bottom' => 25,
			'margin_header' => 16,
			'margin_footer' => 13,
			]
		);

		$this->mpdf->SetBasePath(__DIR__ . '/../data');
		$this->mpdf->WriteHTML($html);
	}
}
