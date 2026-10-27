<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue2212Test extends \Mpdf\BaseMpdfTest
{
	public function testNoWarningForDecomposedVietnameseText()
	{
		$mpdf = new Mpdf();

		$mpdf->WriteHTML('<p style="font-family: Calibri; font-size: 11pt">T&#x1ED4;NG HA&#x309;I SA&#x309;N</p>');
		$output = $mpdf->OutputBinaryData();

		$this->assertStringStartsWith('%PDF-', $output);
	}
}
