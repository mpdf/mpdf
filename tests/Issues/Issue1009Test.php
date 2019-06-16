<?php

namespace Issues;

class Issue1009Test extends \PHPUnit_Framework_TestCase
{

	public function testImportantWarning()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<div style="padding: 0 0 0 0 !important"></div>
		');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
