<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1199Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedVariableInFooter()
	{
		$mpdf = new Mpdf([
			'printers_info' => true,
		]);

		$mpdf->WriteHTML('some html');
		$mpdf->OutputBinaryData();

		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
