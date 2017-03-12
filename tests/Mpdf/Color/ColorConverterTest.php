<?php

namespace Mpdf\Color;

use Mockery;

class ColorConverterTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function setUp()
	{
		parent::setUp();

		$this->converter = new ColorConverter(
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
		$this->assertSame(base64_decode($output), $this->converter->convert($input));
	}

	public function colorsProvider()
	{
		return [
			['#aaaacc', 'M6qqzAAA'],
			['aqua', 'MwD//wAA'],
		];
	}

}
