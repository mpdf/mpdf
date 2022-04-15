<?php

namespace Issues;

class Issue1525Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testWebPSupport()
	{
		if (!function_exists('imagecreatefromwebp')) {
			$this->markTestSkipped('WEBP support not available');
		}

		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<img src="' . __DIR__ . '/../data/img/tiger.webp" />');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertStringContainsString('JFIF', $output);
	}

}
