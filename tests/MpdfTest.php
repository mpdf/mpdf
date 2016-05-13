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

	public function testDefaultSettings()
	{
		$mpdf = new Mpdf('', 'A4', 0, '', 15, 15, 16, 16, 9, 9, 'P', array());

		$this->assertSame('1.4', $mpdf->pdf_version);
		$this->assertSame(2000, $mpdf->maxTTFFilesize);
		$this->assertFalse($mpdf->autoPadding);
	}

	public function testOverwrittenSettings()
	{
		$mpdf = new Mpdf('', 'A4', 0, '', 15, 15, 16, 16, 9, 9, 'P', array(
			'pdf_version' => '1.5',
			'autoPadding' => true,
			'nonexisting_key' => true
		));

		$this->assertSame('1.5', $mpdf->pdf_version);
		$this->assertTrue($mpdf->autoPadding);
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
