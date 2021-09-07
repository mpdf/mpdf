<?php
namespace Mpdf\Barcode;

/**
 * @group unit
 */
class Code128Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testInit()
	{
		$barcode = new Code128('103 33 99   12  ', 'RAW');
		$array = $barcode->getData();
		$this->assertIsArray($array);
		$this->assertArrayHasKey('bcode', $array);
		$this->assertIsArray($array['bcode']);
	}

	public function invalidCodeProvider()
	{
		return [
			['RAW','103 33 99 106 11'],
			['RAW','102 33 99 11'],
			['RAW','10.2 33 99 11'],
			['RAW','10,2 33 99 11'],
			['RAW','a 33 99 11'],
			['C','a12345'],
			['C','a123456'],
			['C','123456789'],
			['C','123456a'],
			['C','1234-6'],
			['A','1234a6'],
		];
	}

	/**
	 * @dataProvider invalidCodeProvider
	 */
	public function testInvalidCode($SubType, $code)
	{
		$this->expectException(\Mpdf\Barcode\BarcodeException::class);

		new Code128($code, $SubType);
	}

}
