<?php

namespace Mpdf\QrCode;

use Mockery;
use Mpdf\Mpdf;

/**
 * @group unit
 */
class QrCodeTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\QrCode\QrCode
	 */
	private $qrCode;

	protected function setUp()
	{
		$this->qrCode = new QrCode('Lorem ipsum dolor sit amet');
	}

	public function testQrCode()
	{
		$this->assertSame(33, $this->qrCode->getQrSize());

		$this->qrCode->disableBorder();

		$this->assertSame(25, $this->qrCode->getQrSize());
	}

	public function testHtmlOutput()
	{
		$this->expectOutputRegex('/^<table class="qr" cellpadding="0" cellspacing="0">/');
		$this->qrCode->displayHTML();
	}

	public function testPngOutput()
	{
		$output = __DIR__ . '/qr.png';
		$this->qrCode->displayPNG(100, [255, 255, 255], [0, 0, 0], $output);
		$this->assertFileExists($output);
		unlink($output);
	}

	public function testMpdfOutput()
	{
		$mpdf = Mockery::mock(Mpdf::class);

		$mpdf->shouldReceive('SetDrawColor')->once();
		$mpdf->shouldReceive('SetFillColor')->once();
		$mpdf->shouldReceive('Rect')->times(321);
		$mpdf->shouldReceive('SetFillColor')->once();
		$mpdf->shouldReceive('Rect')->once();

		/** @var \Mpdf\Mpdf $mpdf */
		$this->qrCode->displayFPDF($mpdf, 0, 0, 25);
	}

	/**
	 * @expectedException \Mpdf\QrCode\QrCodeException
	 */
	public function testInvalidLevel()
	{
		$this->qrCode = new QrCode('Lorem ipsum dolor sit amet', 'S');
	}

	/**
	 * @expectedException \Mpdf\QrCode\QrCodeException
	 */
	public function testNoData()
	{
		$this->qrCode = new QrCode('');
	}

}
