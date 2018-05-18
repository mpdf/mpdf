<?php

namespace Mpdf\QrCode;

use Mpdf\BaseMpdfTest;

/**
 * @group unit
 */
class QrCodeColorTest extends BaseMpdfTest
{
	public function testColor2()
	{
		$html = '<html><body><barcode type="QR" code="A" style="background-color: blueviolet; color: darkolivegreen;" /></body></html>';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml($html);

		$out = $this->mpdf->Output('', 'S');

		$pos = strpos($out, '0.537 0.169 0.882 rg'); //Background color
		$this->assertTrue($pos > 0);

		$pos = strpos($out, '0.329 0.420 0.180 RG'); //Foreground color
		$this->assertTrue($pos > 0);
	}
}
