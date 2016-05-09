<?php

namespace Mpdf;

class MpdfTest extends \PHPUnit_Framework_TestCase
{

	private $mpdf;

	public function setup()
	{
		parent::setup();

		$this->mpdf = new Mpdf();
	}

	public function testPdfOutput()
	{
		$this->mpdf->writeHtml('<html><body>
			<h1>Test</h1>
		</body></html>');

		$output = $this->mpdf->Output(NULL, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}

	/**
	 * @expectedException Mpdf\MpdfException
	 * @expectedExceptionMessage The HTML code size is larger than pcre.backtrack_limit
	 */
	public function testAdjustHtmlTooLargeHtml()
	{
		$this->mpdf->AdjustHtml(str_repeat('a', ini_get('pcre.backtrack_limit') + 1));
	}

}
