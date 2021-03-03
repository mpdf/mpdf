<?php

namespace Issues;

class Issue1395Test extends \PHPUnit_Framework_TestCase
{

	public function testHeaderByName()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<img src="' . __DIR__ . '/../data/img/issue1395.svg" />');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
