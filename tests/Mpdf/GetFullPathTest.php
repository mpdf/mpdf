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

	/**
	 * @dataProvider dataProviderRemoteBasepath
	 */
	public function testGetFullPathRemoteBasepath($path, $result, $basePath)
	{
		$mpdf = new Mpdf();
		$mpdf->basepath = 'http://test.com';

		$mpdf->GetFullPath($path, $basePath);
		$this->assertEquals($result, $path);
	}

	/**
	 * @dataProvider dataProviderLocalBasepath
	 */
	public function testGetFullPathLocalBasepath($path, $result, $basePath)
	{
		$mpdf = new Mpdf();
		$mpdf->basepath = '/var/www/test';

		$mpdf->GetFullPath($path, $basePath);
		$this->assertEquals($result, $path);
	}

	public function dataProviderRemoteBasepath()
	{
		return [
			['simple-path', 'http://test.comsimple-path', null],
			['/absolute-path', 'http://test.com/absolute-path', null],
			['../relative-path', 'http://test.com/relative-path', null],
			['../../../multi-level-relative-path', 'http://test.com/multi-level-relative-path', null],
			['https://absolute.url', 'https://absolute.url', null],
			['file://local.url', 'file://local.url', null],
		];
	}

	public function dataProviderLocalBasepath()
	{
		return [
			['simple-path', '/var/www/testsimple-path', null],
			['/absolute-path', '/absolute-path', null],
			['../relative-path', '/var/www/relative-path', null],
			['../../../multi-level-relative-path', '/var/www/multi-level-relative-path', null], // @todo
			['https://absolute.url', 'https://absolute.url', null],
			['file://local.url', 'file://local.url', null],
		];
	}

}
