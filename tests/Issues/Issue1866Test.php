<?php

namespace Issues;

class Issue1866Test extends \Mpdf\BaseMpdfTest
{

	public function testWatermark()
	{
		$this->mpdf->SetWatermarkText(new \Mpdf\WatermarkText('Watermark text', 100, 90, '#996633', 0.4));
		$this->mpdf->showWatermarkText = true;

		$this->mpdf->WriteHtml('Example text');

		$this->assertStringStartsWith('%PDF-', $this->mpdf->OutputBinaryData());
	}

	public function testWatermarkImage()
	{
		$this->mpdf->SetWatermarkImage(new \Mpdf\WatermarkImage('../data/img/issue1609.png', 100, 90, '#996633', 0.4));
		$this->mpdf->showWatermarkImage = true;

		$this->mpdf->WriteHtml('Example text');

		$this->assertStringStartsWith('%PDF-', $this->mpdf->OutputBinaryData());
	}

}
