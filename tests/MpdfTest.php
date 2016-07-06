<?php

namespace Mpdf;

class MpdfTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	public function setup()
	{
		parent::setup();

		$this->mpdf = new Mpdf();
	}

	public function testPdfOutput()
	{
		$this->mpdf->WriteHTML('<html><body>
			<h1>Test</h1>
		</body></html>');

		$output = $this->mpdf->Output(NULL, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testDefaultSettings()
	{
		$mpdf = new Mpdf();

		$this->assertSame('1.4', $mpdf->pdf_version);
		$this->assertSame(2000, $mpdf->maxTTFFilesize);
		$this->assertFalse($mpdf->autoPadding);
	}

	public function testOverwrittenSettings()
	{
		$mpdf = new Mpdf([
			'pdf_version' => '1.5',
			'autoPadding' => true,
			'nonexisting_key' => true
		]);

		$this->assertSame('1.5', $mpdf->pdf_version);
		$this->assertTrue($mpdf->autoPadding);
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage The HTML code size is larger than pcre.backtrack_limit
	 */
	public function testAdjustHtmlTooLargeHtml()
	{
		$this->mpdf->AdjustHTML(str_repeat('a', ini_get('pcre.backtrack_limit') + 1));
	}

}
