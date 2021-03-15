<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue1397Test extends \Mpdf\BaseMpdfTest
{
	public function testCustomFontSizeWatermark()
	{
		$mpdf = new Mpdf([
			'watermark_font_size' => 120
		]);
		$mpdf->WriteHTML('');

		$mpdf->SetWatermarkText('TEST', 0.5);
		$mpdf->showWatermarkText = true;

		$output = $mpdf->output('', Destination::STRING_RETURN);

		/** @var \PHPUnit_Framework_Assert */
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
