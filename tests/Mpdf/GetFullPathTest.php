<?php

namespace Mpdf;

class GetFullPathTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testGetFullPath()
	{
		$originalImagePath = $path = __DIR__ . '/../data/img/demo.svg';
		$originalImagePath = str_replace("\\", '/', $originalImagePath); //Fix path if on Windows

		$mpdf = new Mpdf();
		$mpdf->basepath = 'http://test.com';

		/* Test absolute path is returned */
		$mpdf->GetFullPath($path);
		$this->assertEquals($originalImagePath, $path);

		/* Test URL is returned using $mpdf->basepath */
		$localImage = $path = 'path/for/empty/image.jpg';
		$mpdf->GetFullPath($path);
		$this->assertEquals($mpdf->basepath . $localImage, $path);

		/* Test URL is returned using $mpdf->basepath */
		$localImage2 = $path = '/path/for/empty/image.jpg';
		$mpdf->GetFullPath($path);
		$this->assertEquals($mpdf->basepath . $localImage2, $path);
	}
}
