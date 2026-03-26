<?php

namespace Issues;

class Issue2056Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testTransformRotateWithNonNumericValue()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<html><body><div style="transform: rotate(revert)">hello</div></body></html>');

		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
