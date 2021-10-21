<?php

namespace Issues;

class Issue581Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testHeaderByName()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<img src="' . __DIR__ . '/../data/img/issue581_gradientPercentage.svg" />');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
