<?php

namespace Issues;

class Issue2129Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testMetaDataWriterEscaping()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->watermark_font = 'original_font';

        $wm = new \Mpdf\WatermarkText('Sample Watermark', 96, 45, 0, 0.2, 'CustomFont');

        $mpdf->SetWatermarkText($wm);

        $this->assertSame('CustomFont', $mpdf->watermark_font);
	}

}
