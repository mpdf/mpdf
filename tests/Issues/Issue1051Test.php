<?php

namespace Issues;

class Issue1051Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testNoticeOnSetColumns()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->SetColumns(2);

		$mpdf->WriteHTML('Some text...');
		$mpdf->AddColumn();
		$mpdf->WriteHTML('Next column...');

		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
