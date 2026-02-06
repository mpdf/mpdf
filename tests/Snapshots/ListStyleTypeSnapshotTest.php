<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class ListStyleTypeSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'list-style-type';
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
			body { font-size: 10pt; }
			h1 { font-size: 18pt; color: #222; }
			h2 { font-size: 14pt; color: #333; border-bottom: 1px solid #ccc; margin-top: 10pt; }
			h3 { font-size: 12pt; color: #555; margin-top: 8pt; }
			h4 { font-size: 10pt; color: #666; margin-top: 6pt; margin-bottom: 2pt; }
			table.list-table { border-collapse: collapse; width: 100%; }
			table.list-table td, table.list-table th {
				border: 1px solid #999;
				padding: 4px;
				vertical-align: top;
				font-size: 9pt;
			}
			table.list-table th { background-color: #eee; }
			.label { font-weight: bold; width: 120px; }
			li { margin-bottom: 2pt; }
		</style>

		<h1>List Style Type - Comprehensive Snapshot Test</h1>

		<!-- ==========================================
		     Section 1: Lists in Normal DIVs
		     ========================================== -->
		<h2>Section 1: Lists in Normal DIVs</h2>

		<h3>1.1 Standard Types via CSS list-style-type</h3>

		<h4>decimal</h4>
		<ol style="list-style-type: decimal;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>upper-roman</h4>
		<ol style="list-style-type: upper-roman;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>lower-roman</h4>
		<ol style="list-style-type: lower-roman;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>upper-latin</h4>
		<ol style="list-style-type: upper-latin;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>lower-latin</h4>
		<ol style="list-style-type: lower-latin;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>upper-alpha</h4>
		<ol style="list-style-type: upper-alpha;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>lower-alpha</h4>
		<ol style="list-style-type: lower-alpha;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>disc</h4>
		<ul style="list-style-type: disc;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ul>

		<h4>circle</h4>
		<ul style="list-style-type: circle;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ul>

		<h4>square</h4>
		<ul style="list-style-type: square;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ul>

		<h4>none</h4>
		<ol style="list-style-type: none;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h3>1.2 Standard Types via HTML type Attribute</h3>

		<h4>type="1" (decimal)</h4>
		<ol type="1">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>type="A" (upper-latin)</h4>
		<ol type="A">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>type="a" (lower-latin)</h4>
		<ol type="a">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>type="I" (upper-roman)</h4>
		<ol type="I">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>type="i" (lower-roman)</h4>
		<ol type="i">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h3>1.3 International Scripts via CSS</h3>

		<h4>arabic-indic</h4>
		<ol style="list-style-type: arabic-indic;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>bengali</h4>
		<ol style="list-style-type: bengali;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>cambodian</h4>
		<ol style="list-style-type: cambodian;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>cjk-decimal</h4>
		<ol style="list-style-type: cjk-decimal; font-family: sun-exta;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>devanagari</h4>
		<ol style="list-style-type: devanagari;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>gujarati</h4>
		<ol style="list-style-type: gujarati;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>gurmukhi</h4>
		<ol style="list-style-type: gurmukhi;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>hebrew</h4>
		<ol style="list-style-type: hebrew; font-family: taameydavidclm;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>kannada</h4>
		<ol style="list-style-type: kannada;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>khmer</h4>
		<ol style="list-style-type: khmer;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>lao</h4>
		<ol style="list-style-type: lao;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>malayalam</h4>
		<ol style="list-style-type: malayalam;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>oriya</h4>
		<ol style="list-style-type: oriya;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>persian</h4>
		<ol style="list-style-type: persian;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>telugu</h4>
		<ol style="list-style-type: telugu;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>thai</h4>
		<ol style="list-style-type: thai;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>urdu</h4>
		<ol style="list-style-type: urdu;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h4>tamil</h4>
		<ol style="list-style-type: tamil;">
			<li>Item One</li><li>Item Two</li><li>Item Three</li>
		</ol>

		<h3>1.4 Custom Marker</h3>

		<h4>U+263A with red color</h4>
		<ul style="list-style-type: U+263Argb(255,0,0);">
			<li>Smiley Item One</li><li>Smiley Item Two</li><li>Smiley Item Three</li>
		</ul>

		<pagebreak/>

		<!-- ==========================================
		     Section 2: Lists in Table Cells (TD and TH)
		     ========================================== -->
		<h2>Section 2: Lists in Table Cells (TD and TH)</h2>

		<h3>2.1 Standard Types via CSS</h3>
		<table class="list-table">
			<thead>
			<tr>
				<th class="label">Type</th>
				<th>In TD (CSS)</th>
				<th>In TH (CSS)</th>
				<th>In TD (HTML attr)</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="label">decimal</td>
				<td><ol style="list-style-type: decimal;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: decimal;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td><ol type="1"><li>One</li><li>Two</li><li>Three</li></ol></td>
			</tr>
			<tr>
				<td class="label">upper-roman</td>
				<td><ol style="list-style-type: upper-roman;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: upper-roman;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td><ol type="I"><li>One</li><li>Two</li><li>Three</li></ol></td>
			</tr>
			<tr>
				<td class="label">lower-roman</td>
				<td><ol style="list-style-type: lower-roman;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: lower-roman;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td><ol type="i"><li>One</li><li>Two</li><li>Three</li></ol></td>
			</tr>
			<tr>
				<td class="label">upper-latin</td>
				<td><ol style="list-style-type: upper-latin;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: upper-latin;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td><ol type="A"><li>One</li><li>Two</li><li>Three</li></ol></td>
			</tr>
			<tr>
				<td class="label">lower-latin</td>
				<td><ol style="list-style-type: lower-latin;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: lower-latin;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td><ol type="a"><li>One</li><li>Two</li><li>Three</li></ol></td>
			</tr>
			<tr>
				<td class="label">upper-alpha</td>
				<td><ol style="list-style-type: upper-alpha;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: upper-alpha;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td>N/A</td>
			</tr>
			<tr>
				<td class="label">lower-alpha</td>
				<td><ol style="list-style-type: lower-alpha;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: lower-alpha;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td>N/A</td>
			</tr>
			<tr>
				<td class="label">disc</td>
				<td><ul style="list-style-type: disc; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: disc; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></th>
				<td>N/A</td>
			</tr>
			<tr>
				<td class="label">circle</td>
				<td><ul style="list-style-type: circle; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: circle; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></th>
				<td>N/A</td>
			</tr>
			<tr>
				<td class="label">square</td>
				<td><ul style="list-style-type: square; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: square; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></th>
				<td>N/A</td>
			</tr>
			<tr>
				<td class="label">none</td>
				<td><ol style="list-style-type: none;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: none;"><li>One</li><li>Two</li><li>Three</li></ol></th>
				<td>N/A</td>
			</tr>
			</tbody>
		</table>

		<h3>2.2 International Scripts in Table Cells</h3>
		<table class="list-table">
			<thead>
			<tr>
				<th class="label">Type</th>
				<th>In TD (CSS)</th>
				<th>In TH (CSS)</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="label">arabic-indic</td>
				<td><ol style="list-style-type: arabic-indic;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: arabic-indic;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">bengali</td>
				<td><ol style="list-style-type: bengali;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: bengali;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">cambodian</td>
				<td><ol style="list-style-type: cambodian;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: cambodian;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">cjk-decimal</td>
				<td><ol style="list-style-type: cjk-decimal; font-family: sun-exta;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: cjk-decimal; font-family: sun-exta;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">devanagari</td>
				<td><ol style="list-style-type: devanagari;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: devanagari;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">gujarati</td>
				<td><ol style="list-style-type: gujarati;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: gujarati;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">gurmukhi</td>
				<td><ol style="list-style-type: gurmukhi;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: gurmukhi;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">hebrew</td>
				<td><ol style="list-style-type: hebrew; font-family: taameydavidclm;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: hebrew; font-family: taameydavidclm;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">kannada</td>
				<td><ol style="list-style-type: kannada;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: kannada;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">khmer</td>
				<td><ol style="list-style-type: khmer;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: khmer;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">lao</td>
				<td><ol style="list-style-type: lao;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: lao;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">malayalam</td>
				<td><ol style="list-style-type: malayalam;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: malayalam;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">oriya</td>
				<td><ol style="list-style-type: oriya;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: oriya;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">persian</td>
				<td><ol style="list-style-type: persian;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: persian;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">telugu</td>
				<td><ol style="list-style-type: telugu;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: telugu;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">thai</td>
				<td><ol style="list-style-type: thai;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: thai;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">urdu</td>
				<td><ol style="list-style-type: urdu;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: urdu;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			<tr>
				<td class="label">tamil</td>
				<td><ol style="list-style-type: tamil;"><li>One</li><li>Two</li><li>Three</li></ol></td>
				<th><ol style="list-style-type: tamil;"><li>One</li><li>Two</li><li>Three</li></ol></th>
			</tr>
			</tbody>
		</table>

		<h3>2.3 Custom Marker in Table Cells</h3>
		<table class="list-table">
			<thead>
			<tr>
				<th class="label">Type</th>
				<th>In TD</th>
				<th>In TH</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="label">U+263A (red)</td>
				<td><ul style="list-style-type: U+263Argb(255,0,0);"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: U+263Argb(255,0,0);"><li>One</li><li>Two</li><li>Three</li></ul></th>
			</tr>
			<tr>
				<td class="label">U+2605 (blue)</td>
				<td><ul style="list-style-type: U+2605rgb(0,0,255); font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: U+2605rgb(0,0,255); font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></th>
			</tr>
			<tr>
				<td class="label">U+2713 (no color)</td>
				<td><ul style="list-style-type: U+2713; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></td>
				<th><ul style="list-style-type: U+2713; font-family: dejavusans;"><li>One</li><li>Two</li><li>Three</li></ul></th>
			</tr>
			</tbody>
		</table>

		<pagebreak/>

		<!-- ==========================================
		     Section 3: Lists in Nested Tables
		     ========================================== -->
		<h2>Section 3: Lists in Nested Tables</h2>

		<table class="list-table">
			<thead>
			<tr>
				<th>Outer Cell</th>
				<th>Outer Cell with Nested Table</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<ol style="list-style-type: decimal;"><li>Outer decimal One</li><li>Outer decimal Two</li><li>Outer decimal Three</li></ol>
				</td>
				<td>
					<table class="list-table">
						<tr>
							<td>
								<ol style="list-style-type: upper-alpha;"><li>Nested upper-alpha One</li><li>Nested upper-alpha Two</li><li>Nested upper-alpha Three</li></ol>
							</td>
							<td>
								<ul style="list-style-type: disc; font-family: dejavusans;"><li>Nested disc One</li><li>Nested disc Two</li><li>Nested disc Three</li></ul>
							</td>
						</tr>
						<tr>
							<td>
								<ul style="list-style-type: circle; font-family: dejavusans;"><li>Nested circle One</li><li>Nested circle Two</li><li>Nested circle Three</li></ul>
							</td>
							<td>
								<ul style="list-style-type: square; font-family: dejavusans;"><li>Nested square One</li><li>Nested square Two</li><li>Nested square Three</li></ul>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<ol style="list-style-type: lower-roman;"><li>Outer lower-roman One</li><li>Outer lower-roman Two</li><li>Outer lower-roman Three</li></ol>
				</td>
				<td>
					<table class="list-table">
						<tr>
							<td>
								<ol style="list-style-type: arabic-indic;"><li>Nested arabic-indic One</li><li>Nested arabic-indic Two</li><li>Nested arabic-indic Three</li></ol>
							</td>
							<td>
								<ol style="list-style-type: hebrew; font-family: taameydavidclm;"><li>Nested hebrew One</li><li>Nested hebrew Two</li><li>Nested hebrew Three</li></ol>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</tbody>
		</table>

		<h3>Nested Lists within Nested Table Cells</h3>
		<table class="list-table">
			<tbody>
			<tr>
				<td>
					<table class="list-table">
						<tr>
							<td>
								<ol style="list-style-type: decimal;">
									<li>Level 1 decimal
										<ol style="list-style-type: lower-alpha;">
											<li>Level 2 lower-alpha</li>
											<li>Level 2 lower-alpha
												<ol style="list-style-type: lower-roman;">
													<li>Level 3 lower-roman</li>
													<li>Level 3 lower-roman</li>
												</ol>
											</li>
										</ol>
									</li>
									<li>Level 1 decimal</li>
								</ol>
							</td>
							<td>
								<ul style="list-style-type: disc; font-family: dejavusans;">
									<li>Level 1 disc
										<ul style="list-style-type: circle;">
											<li>Level 2 circle</li>
											<li>Level 2 circle
												<ul style="list-style-type: square;">
													<li>Level 3 square</li>
													<li>Level 3 square</li>
												</ul>
											</li>
										</ul>
									</li>
									<li>Level 1 disc</li>
								</ul>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			</tbody>
		</table>

		<pagebreak/>

		<!-- ==========================================
		     Section 4: Lists in Floated DIVs
		     ========================================== -->
		<h2>Section 4: Lists in Floated DIVs</h2>

		<div style="float: left; width: 48%; border: 1px solid #ccc; padding: 5px;">
			<h3>Float Left</h3>

			<h4>decimal</h4>
			<ol style="list-style-type: decimal;">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ol>

			<h4>upper-roman</h4>
			<ol style="list-style-type: upper-roman;">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ol>

			<h4>disc</h4>
			<ul style="list-style-type: disc;">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ul>

			<h4>circle</h4>
			<ul style="list-style-type: circle;">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ul>

			<h4>arabic-indic</h4>
			<ol style="list-style-type: arabic-indic;">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ol>

			<h4>type="A" (HTML attr)</h4>
			<ol type="A">
				<li>Float left One</li><li>Float left Two</li><li>Float left Three</li>
			</ol>
		</div>

		<div style="float: right; width: 48%; border: 1px solid #ccc; padding: 5px;">
			<h3>Float Right</h3>

			<h4>lower-roman</h4>
			<ol style="list-style-type: lower-roman;">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ol>

			<h4>upper-latin</h4>
			<ol style="list-style-type: upper-latin;">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ol>

			<h4>square</h4>
			<ul style="list-style-type: square;">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ul>

			<h4>none</h4>
			<ol style="list-style-type: none;">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ol>

			<h4>hebrew</h4>
			<ol style="list-style-type: hebrew; font-family: taameydavidclm;">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ol>

			<h4>type="i" (HTML attr)</h4>
			<ol type="i">
				<li>Float right One</li><li>Float right Two</li><li>Float right Three</li>
			</ol>
		</div>

		<div style="clear: both;"></div>

		<pagebreak/>

		<!-- ==========================================
		     Section 5: Lists in Fixed-Position DIVs
		     ========================================== -->
		<h2>Section 5: Lists in Fixed-Position DIVs</h2>

		<p>The following lists are rendered inside fixed-position containers.</p>

		<div style="position: fixed; top: 50mm; left: 15mm; width: 80mm; border: 1px solid #999; padding: 5px;">
			<h4>Fixed top-left: decimal</h4>
			<ol style="list-style-type: decimal;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ol>

			<h4>Fixed top-left: upper-latin</h4>
			<ol style="list-style-type: upper-latin;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ol>

			<h4>Fixed top-left: disc</h4>
			<ul style="list-style-type: disc;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ul>
		</div>

		<div style="position: fixed; top: 50mm; right: 15mm; width: 80mm; border: 1px solid #999; padding: 5px;">
			<h4>Fixed top-right: lower-roman</h4>
			<ol style="list-style-type: lower-roman;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ol>

			<h4>Fixed top-right: square</h4>
			<ul style="list-style-type: square;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ul>

			<h4>Fixed top-right: thai</h4>
			<ol style="list-style-type: thai;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ol>
		</div>

		<div style="position: fixed; bottom: 20mm; left: 50mm; width: 100mm; border: 1px solid #999; padding: 5px;">
			<h4>Fixed bottom: hebrew</h4>
			<ol style="list-style-type: hebrew; font-family: taameydavidclm;">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ol>

			<h4>Fixed bottom: U+263A custom marker (red)</h4>
			<ul style="list-style-type: U+263Argb(255,0,0);">
				<li>Fixed One</li><li>Fixed Two</li><li>Fixed Three</li>
			</ul>
		</div>

		<?php
		$html = ob_get_clean();

		$this->mpdf = new \Mpdf\Mpdf([
			'autoScriptToLang' => true,
			'autoLangToFont' => true,
		]);

		$this->mpdf->SetBasePath(__DIR__ . '/../data');
		$this->mpdf->WriteHTML($html);
	}
}
