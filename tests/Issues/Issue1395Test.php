<?php

namespace Issues;

class Issue1395Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testHeaderByName()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<img src="' . __DIR__ . '/../data/img/issue1395.svg" />');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
