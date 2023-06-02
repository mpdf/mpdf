<?php

namespace MpdfAnalize;

class ConfigurationTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testDefaultSettings()
	{
		$mpdf = new MpdfAnalize();

		$this->assertSame('1.4', $mpdf->pdf_version);
		$this->assertSame(2000, $mpdf->maxTTFFilesize);
		$this->assertFalse($mpdf->autoPadding);
	}

	public function testOverwrittenSettings()
	{
		$mpdf = new MpdfAnalize([
			'pdf_version' => '1.5',
			'autoPadding' => true,
			'nonexisting_key' => true,
		]);

		$this->assertSame('1.5', $mpdf->pdf_version);
		$this->assertTrue($mpdf->autoPadding);
		$this->assertObjectNotHasAttribute('nonexisting_key', $mpdf);
	}

	public function testFontSettings()
	{
		$mpdf = new MpdfAnalize([
			'fontDir' => [
				__DIR__ . '/../../ttfonts',
				__DIR__ . '/../data/ttf',
			],
			'fontdata' => ['angerthas' => [
				'R' => 'angerthas.ttf',
			]],
			'default_font' => 'angerthas'
		]);

		$this->assertArrayHasKey('angerthas', $mpdf->fontdata);
		$this->assertSame('angerthas', $mpdf->default_font);
	}

	public function testFontSettingsWithDefaults()
	{
		$defaultFontConfig = (new Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		$mpdf = new MpdfAnalize([
			'fontDir' => [
				__DIR__ . '/../../ttfonts',
				__DIR__ . '/../data/ttf',
			],
			'fontdata' => $fontData + ['angerthas' => [
				'R' => 'angerthas.ttf',
			]]
		]);

		$this->assertArrayHasKey('dejavusanscondensed', $mpdf->fontdata);
		$this->assertArrayHasKey('angerthas', $mpdf->fontdata);
	}

	public function testOrientationSettings()
	{
		$format = 'A4';
		$format_size = PageFormat::getSizeFromName($format);

		// Set format to A4 and orientation to L
		$mpdf = new MpdfAnalize([
			'format' => $format.'-L',
		]);

		$this->assertSame('L', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to P
		$mpdf = new MpdfAnalize([
			'format' => $format.'-P',
		]);

		$this->assertSame('P', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to P
		$mpdf = new MpdfAnalize([
			'format' => $format,
		]);

		$this->assertSame('P', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to L, ignoring "orientation" key
		$mpdf = new MpdfAnalize([
			'format' => $format.'-L',
			'orientation' => 'P',
		]);

		$this->assertSame('L', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to L, ignoring "orientation" key
		$mpdf = new MpdfAnalize([
			'format' => $format.'-P',
			'orientation' => 'L',
		]);

		$this->assertSame('P', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to P
		$mpdf = new MpdfAnalize([
			'format' => $format,
			'orientation' => 'P',
		]);

		$this->assertSame('P', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);

		// Set format to A4 and orientation to L
		$mpdf = new MpdfAnalize([
			'format' => $format,
			'orientation' => 'L',
		]);

		$this->assertSame('L', $mpdf->DefOrientation);
		$this->assertSame($format_size[0], $mpdf->fwPt);
		$this->assertSame($format_size[1], $mpdf->fhPt);
	}

}
