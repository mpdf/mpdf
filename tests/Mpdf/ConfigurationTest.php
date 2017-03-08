<?php

namespace Mpdf;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

	public function testDefaultSettings()
	{
		$mpdf = new Mpdf();

		$this->assertSame('1.4', $mpdf->pdf_version);
		$this->assertSame(2000, $mpdf->maxTTFFilesize);
		$this->assertFalse($mpdf->autoPadding);
	}

	public function testOverwrittenSettings()
	{
		$mpdf = new Mpdf([
			'pdf_version' => '1.5',
			'autoPadding' => true,
			'nonexisting_key' => true,
		]);

		$this->assertSame('1.5', $mpdf->pdf_version);
		$this->assertTrue($mpdf->autoPadding);
		$this->assertFalse(property_exists($mpdf, 'nonexisting_key'));
	}

	public function testFontSettings()
	{
		$mpdf = new Mpdf([
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

		$mpdf = new Mpdf([
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

}
