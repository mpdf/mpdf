<?php


namespace Mpdf\Css;

use Mpdf\Helpers\PdfContentHelper;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class HeaderAndFooterTest extends \PHPUnit_Framework_TestCase
{
	public function testNamedPageFirst()
	{
		// Create the PDF
		$mpdf = new Mpdf();
		$mpdf->SetCompression(true);

		$html = $this->getHtml(
			<<<css
		@page P_mpdf_page_1 {
			margin-top: 50mm;
			odd-header-name: H_mpdf_section_2;
			even-header-name: H_mpdf_section_2;
			odd-footer-name: H_mpdf_section_4;
			even-footer-name: H_mpdf_section_4;
		}
		@page P_mpdf_page_1 :first {
			margin-top: 50mm;
			odd-header-name: H_mpdf_section_1;
			even-header-name: H_mpdf_section_1;
			odd-footer-name: H_mpdf_section_3;
			even-footer-name: H_mpdf_section_3;
		}
		#mpdf_page_1 {
			page: P_mpdf_page_1;
		}
css
			,
			<<<html
<section id="mpdf_page_1">
	<htmlpageheader name="H_mpdf_section_1"><h1>HEADER PAGE 1</h1></htmlpageheader>
	<htmlpageheader name="H_mpdf_section_2"><h1>HEADER ANY PAGE</h1></htmlpageheader>
	<htmlpagefooter name="H_mpdf_section_3"><h1>FOOTER PAGE 1</h1></htmlpagefooter>
	<htmlpagefooter name="H_mpdf_section_4"><h1>FOOTER ANY PAGE</h1></htmlpagefooter>
	<p>BODY page 1</p>
	<pagebreak>
	<p>BODY page 2</p>
	<pagebreak>
	<p>BODY page 3</p>
</section>
html
		);

		$mpdf->WriteHTML($html);
		$pdfString = $mpdf->output('', Destination::STRING_RETURN);

		$helper = new PdfContentHelper($pdfString);

		// Check page 1 has the correct content
		$this->assertEquals(1, count($helper->findText('HEADER PAGE 1', 1)), 'Page 1 is missing header');
		$this->assertEquals(0, count($helper->findText('HEADER ANY PAGE', 1)), 'Page 1 has wrong header');
		$this->assertEquals(1, count($helper->findText('BODY page 1', 1)), 'Page 1 is missing body');
		$this->assertEquals(0, count($helper->findText('BODY page 2', 1)), 'Page 1 has wrong body');
		$this->assertEquals(1, count($helper->findText('FOOTER PAGE 1', 1)), 'Page 1 is missing footer');
		$this->assertEquals(0, count($helper->findText('FOOTER ANY PAGE', 1)), 'Page 1 has wrong footer');

		// Check page 2 has the correct content
		$this->assertEquals(1, count($helper->findText('HEADER ANY PAGE', 2)), 'Page 2 is missing header');
		$this->assertEquals(0, count($helper->findText('HEADER PAGE 1', 2)), 'Page 2 has wrong header');
		$this->assertEquals(1, count($helper->findText('BODY page 2', 2)), 'Page 2 is missing body');
		$this->assertEquals(0, count($helper->findText('BODY page 1', 2)), 'Page 2 has wrong body');
		$this->assertEquals(1, count($helper->findText('FOOTER ANY PAGE', 2)), 'Page 2 is missing footer');
		$this->assertEquals(0, count($helper->findText('FOOTER PAGE 1', 2)), 'Page 2 has wrong footer');


		// Check page 3 has the correct content
		$this->assertEquals(1, count($helper->findText('HEADER ANY PAGE', 3)), 'Page 3 is missing header');
		$this->assertEquals(0, count($helper->findText('HEADER PAGE 1', 3)), 'Page 3 has wrong header');
		$this->assertEquals(1, count($helper->findText('BODY page 3', 3)), 'Page 3 is missing body');
		$this->assertEquals(0, count($helper->findText('BODY page 1', 3)), 'Page 3 has wrong body');
		$this->assertEquals(0, count($helper->findText('BODY page 2', 3)), 'Page 3 has wrong body');
		$this->assertEquals(1, count($helper->findText('FOOTER ANY PAGE', 3)), 'Page 3 is missing footer');
		$this->assertEquals(0, count($helper->findText('FOOTER PAGE 1', 3)), 'Page 3 has wrong footer');
		$this->assertEquals(0, count($helper->findText('FOOTER PAGE 2', 3)), 'Page 3 has wrong footer');

		// Check the number of pages is correct
		$this->assertEquals(3, $helper->pageCount());
	}

	private function getHtml($cssHeader = '', $htmlBody = '')
	{
		return <<<HTML
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>title</title>
	<style>
		@page {
			size: 210mm 297mm;
			margin-top: 10mm;
			margin-right: 10mm;
			margin-bottom: 10mm;
			margin-left: 10mm;
		}
		{$cssHeader}
		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
	</style>
</head>
<body>
	{$htmlBody}
</body>
</html>
HTML;
	}
}
