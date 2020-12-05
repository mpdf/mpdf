<?php

namespace Mpdf\Barcode;

/**
 * @group unit
 */
class EanUpcTest extends \PHPUnit_Framework_TestCase
{

	public function testInit()
	{
		$barcode = new EanUpc('9783161484100', 13, 11, 7, 0.33, 25.93);
		$array = $barcode->getData();
		$this->assertInternalType('array', $array);
		$this->assertArrayHasKey('bcode', $array);
		$this->assertInternalType('array', $array['bcode']);
	}

	public function invalid13CodeProvider()
	{
		return [
			['foo'],
			['foo11bar'],
		];
	}

	/**
	 * @dataProvider invalid13CodeProvider
	 */
	public function testInvalid13Code($code)
	{
		$this->expectException(BarcodeException::class);
		$this->expectExceptionMessage('Invalid EAN UPC barcode value "' . $code . '"');

		new EanUpc($code, 13, 11, 7, 0.33, 25.93);
	}

	public function invalidCodeAProvider()
	{
		return [
			['0048200115438'],
			['foo11bar'],
		];
	}

	/**
	 * @dataProvider invalidCodeAProvider
	 */
	public function testInvalidACode($code)
	{
		$this->expectException(BarcodeException::class);
		$this->expectExceptionMessage('Invalid EAN UPC barcode value "' . $code . '"');

		new EanUpc($code, 12, 11, 7, 0.33, 25.93);
	}

}
