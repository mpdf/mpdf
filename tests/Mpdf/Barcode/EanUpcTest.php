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

	public function invalidCodeProvider()
	{
		return [
			['foo'],
			['foo11bar'],
		];
	}

	/**
	 * @dataProvider invalidCodeProvider
	 * @expectedException \Mpdf\Barcode\BarcodeException
	 * @expectedExceptionMessage Invalid EAN UPC barcode value
	 */
	public function testInvalidCode($code)
	{
		new EanUpc($code, 13, 11, 7, 0.33, 25.93);
	}

}
