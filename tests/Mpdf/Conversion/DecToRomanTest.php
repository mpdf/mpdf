<?php

namespace Mpdf\Conversion;

class DecToRomanTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function setUp()
	{
		parent::setUp();

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

	/**
	 * @expectedException \OutOfRangeException
	 */
	public function testLowerBound()
	{
		$this->converter->convert(0);
	}

	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testUpperBound()
	{
		$this->converter->convert(5000);
	}

}
