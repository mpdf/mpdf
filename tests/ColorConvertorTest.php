<?php

namespace Mpdf\Color;

use Mockery;

class ColorConvertorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConvertor
	 */
	private $convertor;

	protected function setUp()
	{
		parent::setUp();

		$this->convertor = new ColorConvertor(
			Mockery::mock('Mpdf\Mpdf'),
			[],
			[],
			false,
			false,
			false,
			false,
			0
		);
	}

	/**
	 * @dataProvider colorsProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testConvert($input, $output)
	{
		$this->assertSame(base64_decode($output), $this->convertor->convert($input));
	}

	public function colorsProvider()
	{
		return [
			['#aaaacc', 'M6qqzAAA'],
			['aqua', 'MwD//wAA'],
		];
	}

}
