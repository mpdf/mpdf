<?php

namespace Mpdf\Barcode;

/**
 * @group unit
 */
class EanUpcTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testInit()
	{
		$barcode = new EanUpc('9783161484100', 13, 11, 7, 0.33, 25.93);
		$array = $barcode->getData();
		$this->assertIsArray($array);
		$this->assertArrayHasKey('bcode', $array);
		$this->assertIsArray($array['bcode']);
	}

	public function invalidCodeProvider()
	{
		return [
			['foo'],
			['foo11bar'],
		];
	}

	/**
	 * @dataProvider invalidCodeProvider
	 */
	public function testInvalidCode($code)
	{
		$this->expectException(\Mpdf\Barcode\BarcodeException::class);
		$this->expectExceptionMessage('Invalid EAN UPC barcode value');

		new EanUpc($code, 13, 11, 7, 0.33, 25.93);
	}

}
