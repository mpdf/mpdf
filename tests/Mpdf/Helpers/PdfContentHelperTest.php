<?php

namespace Mpdf\Helpers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfContentHelperTest extends \PHPUnit_Framework_TestCase
{
	public function testFindText()
	{
		$mpdf = new Mpdf();
		$mpdf->WriteHTML('<h1>Header page1</h1>');
		$mpdf->AddPage();
		$mpdf->WriteHTML('<p>body page2</p>');
		$mpdf->AddPage();
		$mpdf->WriteHTML('<h3>Small Header page3</h3>');

		$pdf = $mpdf->Output('', Destination::STRING_RETURN);

		$helper = new PdfContentHelper($pdf);

		// Look for page1 in the 1st page
		$this->assertEquals(1, count($helper->findText('page1', 1)));

		// Look for page1 in the 2nd page
		$this->assertEquals(0, count($helper->findText('page1', 2)));

		// Look for page2 in the 2nd page
		$this->assertEquals(1, count($helper->findText('page2', 2)));

		// Look for Header in all pages
		$this->assertEquals(2, count($helper->findText('Header')));
	}

	public function testPageCount()
	{
		$mpdf = new Mpdf();
		$mpdf->WriteHTML('<h1>Header page1</h1>');
		$mpdf->AddPage();
		$mpdf->WriteHTML('<p>body page2</p>');
		$mpdf->AddPage();
		$mpdf->WriteHTML('<h3>Small Header page3</h3>');

		$pdf = $mpdf->Output('', Destination::STRING_RETURN);

		$helper = new PdfContentHelper($pdf);

		// Look for page1 in the 1st page
		$this->assertEquals(3, $helper->pageCount());
	}
}
