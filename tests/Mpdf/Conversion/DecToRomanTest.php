<?php

namespace Mpdf\Conversion;

class DecToRomanTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function set_up()
	{
		parent::set_up();

		$this->converter = new DecToRoman();
	}

	/**
	 * @dataProvider conversionProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testConvert($input, $output)
	{
		$this->assertSame($output, $this->converter->convert($input));
	}

	public function conversionProvider()
	{
		return [
			[1, 'I'],
			[4, 'IV'],
			[5, 'V'],
			[9, 'IX'],
			[14, 'XIV'],
			[19, 'XIX'],
			[28, 'XXVIII'],
			[648, 'DCXLVIII'],
			[649, 'DCXLIX'],
			[1582, 'MDLXXXII'],
			[3999, 'MMMCMXCIX'],
		];
	}

	public function testLowerBound()
	{
		$this->expectException(\OutOfRangeException::class);

		$this->converter->convert(0);
	}

	public function testUpperBound()
	{
		$this->expectException(\OutOfBoundsException::class);

		$this->converter->convert(5000);
	}

}
