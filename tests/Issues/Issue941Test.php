<?php

namespace Issues;

class Issue941Test extends \PHPUnit_Framework_TestCase
{

	public function testMultiCellDoesNotFailOtl()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->AddPage();
		$mpdf->MultiCell(100, 20, 'This is a text string, just for testing');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
