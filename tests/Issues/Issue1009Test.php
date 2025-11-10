<?php

namespace Issues;

class Issue1009Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testImportantWarning()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<div style="padding: 0 0 0 0 !important"></div>
		');

		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
