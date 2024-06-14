<?php

namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

class Issue1760Test extends BaseMpdfTest
{
	public function testGenerateQrCodeWithDecodedEntities()
	{
		$originalCode = '{"t":1,"d":5}';
		$this->mpdf->WriteHTML('<barcode code="'.htmlspecialchars($originalCode).'" type="qr"/>', HTMLParserMode::DEFAULT_MODE, true, false);

		$barcodeObj = $this->mpdf->_getObjAttr($this->mpdf->textbuffer[0][0]);

		$this->assertSame($originalCode, $barcodeObj['code']);
	}
}
