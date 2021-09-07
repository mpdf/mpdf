<?php

namespace Issues;

class Issue1136Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testArrayAccessOnNull()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<div style="z-index: 1; position: fixed; top: 800px; left: 2000px; width: 250px; height: 250px;"></div>');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
