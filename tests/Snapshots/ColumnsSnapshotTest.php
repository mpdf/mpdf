<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class ColumnsSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'columns';
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

		$html = '
<h1>mPDF</h1>
<h2>Columns</h2>
';

		$loremH = "<h4>Lectus facilisis</h4>
<p>Sed auctor viverra diam. In lacinia lectus.</p>
<p>Praesent tincidunt massa in dolor. Morbi viverra leo quis ipsum.&nbsp;In vitae velit. In aliquam nulla nec mi. Sed accumsan, justo id congue fringilla, diam mauris volutpat ligula, sed aliquet elit diam at felis. Quisque et velit sed eros convallis posuere.</p>
<h5>Nunc tincidunt</h5>
<p>Nunc diam ipsum, consectetuer nec, hendrerit vitae, malesuada a, ante. Nulla ornare aliquet ante. Maecenas in lectus. Morbi porttitor mauris. Praesent ut.</p>
<p>Pede quis ante tincidunt <a href=\"http://www.stlucia.org\">blandit</a>. Maecenas bibendum erat. Curabitur sit amet ante quis velit ultricies facilisis. Ut hendrerit dolor commodo magna. In nec ligula a purus tincidunt adipiscing. Etiam non ante. </p><div>Suspendisse potenti. <indexentry content=\"Inline indexentry &lt;B&gt;\" />Suspendisse accumsan euismod lectus. Nunc commodo pede et turpis. Pellentesque porta mauris sed lorem. Ut nec augue vitae elit eleifend eleifend.Quisque ornare feugiat diam. Duis nulla metus, tempus sit amet, scelerisque a, rutrum at, nisl. Nulla facilisi. Duis metus turpis, molestie nec, laoreet tincidunt, ultrices et, purus. Nullam faucibus aliquam nisi.</div><a href=\"http://www.stlucia.org\"><img src=\"img/tiger.webp\" /></a><p>Ut leo. Etiam tempus interdum tortor. Donec porta, arcu vel tincidunt placerat, lacus lorem iaculis diam, id sagittis sapien metus eu nunc. Morbi vitae nunc.<br />Mauris sapien. Phasellus elementum velit sed sapien. Nullam ante diam, consectetuer commodo, dignissim vitae, tempor vel, magna. Donec dictum. <i>Nullam</i> ultrices leo volutpat magna. Mauris blandit purus nec turpis. <a href=\"http://www.stlucia.org\">Curabitur</a> nunc. Aliquam condimentum eleifend<sup>32</sup> lectus. Praesent vitae nibh <b>et libero ullamcorper</b> scelerisque. Nullam auctor. Mauris ipsum nulla, malesuada id, aliquet at, feugiat vitae, eros.</p>

<div style=\"background-color:#DDDDBB; text-align:center; padding:3px; border:1px solid #880000;  \">Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede.
<div style=\"background-color:#ADDBBF; text-align:center; padding:3px; border:1px solid #880000;  \">Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </div>
 Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </div>
<p>Maecenas arcu justo, malesuada eu, dapibus ac, adipiscing vitae, turpis. Fusce mollis. Aliquam egestas. In purus dolor, facilisis at, fermentum nec, molestie et, metus. Vestibulum feugiat, orci at imperdiet tincidunt, mauris erat facilisis urna, sagittis ultricies dui nisl et lectus. Sed lacinia, lectus vitae dictum sodales, elit ipsum ultrices orci, non euismod arcu diam non metus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In suscipit turpis vitae odio. Integer convallis dui at metus. Fusce magna. Sed sed lectus vitae enim tempor cursus. Cras eu erat vel libero sodales congue. Sed erat est, interdum nec, elementum eleifend, pretium at, nibh. Praesent massa diam, adipiscing id, mollis sed, posuere et, urna. Quisque ut leo. Aliquam interdum hendrerit tortor. Vestibulum elit. Vestibulum et arcu at diam mattis commodo. Nam ipsum sem, ultricies at, rutrum sit amet, posuere nec, velit. Sed molestie mollis dui. </p>
";

		$this->mpdf = new \Mpdf\Mpdf([
				'mode' => 'c',
				'margin_left' => 32,
				'margin_right' => 25,
				'margin_top' => 27,
				'margin_bottom' => 25,
				'margin_header' => 16,
				'margin_footer' => 13
		]);

		$this->mpdf->SetBasePath(__DIR__ . '/../data');

		$this->mpdf->WriteHTML(
			'
          body { font-family: DejaVuSansCondensed, sans-serif; font-size: 11pt;  }
		p { 	text-align: justify; margin-bottom: 4pt;  margin-top:0pt; }

		hr {	width: 70%; height: 1px; 
			text-align: center; color: #999999; 
			margin-top: 8pt; margin-bottom: 8pt; }

		a {	color: #000066; font-style: normal; text-decoration: underline; 
			font-weight: normal; }

		pre { font-family: DejaVuSansMono, monospaced; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

		h1 {	font-weight: normal; font-size: 26pt; color: #000066; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 18pt; margin-bottom: 6pt; 
			border-top: 0.075cm solid #000000; border-bottom: 0.075cm solid #000000; 
			text-align: ; page-break-after:avoid; }
		h2 {	font-weight: bold; font-size: 12pt; color: #000066; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 6pt; margin-bottom: 6pt; 
			border-top: 0.07cm solid #000000; border-bottom: 0.07cm solid #000000; 
			text-align: ;  text-transform: uppercase; page-break-after:avoid; }
		h3 {	font-weight: normal; font-size: 26pt; color: #000000; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 0pt; margin-bottom: 6pt; 
			border-top: 0; border-bottom: 0; 
			text-align: ; page-break-after:avoid; }
		h4 {	font-weight: ; font-size: 13pt; color: #9f2b1e; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 10pt; margin-bottom: 7pt; 
			font-variant: small-caps;
			text-align: ;  margin-collapse:collapse; page-break-after:avoid; }
		h5 {	font-weight: bold; font-style:italic; ; font-size: 11pt; color: #000044; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 8pt; margin-bottom: 4pt; 
			text-align: ;  page-break-after:avoid; }
		h6 {	font-weight: bold; font-size: 9.5pt; color: #333333; 
			font-family: DejaVuSansCondensed, sans-serif; margin-top: 6pt; margin-bottom: ; 
			text-align: ;  page-break-after:avoid; }


		.breadcrumb {
			text-align: right; font-size: 8pt; font-family: DejaVuSerifCondensed, serif; color: #666666;
			font-weight: bold; font-style: normal; margin-bottom: 6pt; }

		.infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

		.big { font-size: 1.5em; }
		.red { color: #880000; }
		.slanted { font-style: italic; }',
			1
		);

		// Bullets in columns are probably best not indented
		$this->mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

		$this->mpdf->max_colH_correction = 1.1;

		$this->mpdf->WriteHTML($html, 2);
		$this->mpdf->WriteHTML($loremH, 2);

		// consider reducing lineheight when using columns - especially if vAligned justify
		$this->mpdf->SetDefaultBodyCSS('line-height', 1.2);

		$this->mpdf->SetColumns(3, 'J');
		$this->mpdf->WriteHTML($loremH, 2);

		$this->mpdf->SetColumns(0);
		$this->mpdf->WriteHTML('<hr />');

		$this->mpdf->SetColumns(2, 'J');
		$this->mpdf->WriteHTML($loremH, 2);
		$this->mpdf->WriteHTML('<hr />');
		$this->mpdf->SetColumns(0);
		$this->mpdf->WriteHTML('<hr />');

		$this->mpdf->SetColumns(3, 'J');
		$this->mpdf->WriteHTML($loremH, 2);

		$this->mpdf->SetColumns(0);
		$this->mpdf->WriteHTML('<hr />');
		$this->mpdf->SetColumns(2, 'J');
		$this->mpdf->WriteHTML($loremH, 2);
	}
}
