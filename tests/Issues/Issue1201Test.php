<?php

namespace Issues;

class Issue1201Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPageNumberMyanmarLanguage()
	{
		$mpdf = new \Mpdf\Mpdf(
			[
			'default_font' => 'Tharlon',
			'defaultPageNumStyle' => 'myanmar'
			]
		);
		$mpdf->setFooter('{PAGENO}');
		$mpdf->AddPage();
		$mpdf->AddPage();
		$mpdf->AddPage();

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
