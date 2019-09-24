<?php

namespace Mpdf\Color;

use Mockery;

class ColorModeConverterTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function setUp()
	{
		parent::setUp();

		$this->converter = new ColorModeConverter();
	}

	/**
	 * @dataProvider hsl2rgbProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testHsl2rgb($input, $output)
	{
		$this->assertEquals($output, $this->converter->hsl2rgb(...$input));
	}

	public function hsl2rgbProvider()
	{
		return [

			[[1.23, 0.55, 0.20], [58, 79, 23]],
			[[0.3416666666666667, 0.55, 0.2], [23, 79, 26]],
			[[0.18333333333333332, 0.8, 0.2], [84, 92, 10]],
			[[0, 0, 0.8], [204, 204, 204]],
			[[0, 1, 0.5], [255, 0, 0]],

		];
	}

	/**
	 * @dataProvider rgb2hslProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testRgb2hsl($input, $output)
	{
		$this->assertEquals($output, $this->converter->rgb2hsl(...$input));
	}

	public function rgb2hslProvider()
	{
		return [

			[[58, 79, 23], [0.22916666666666652, -0.56, 51]],
			[[23, 79, 26], [0.34226190476190477, -0.56, 51]],
			[[84, 92, 10], [0.18292682926829273, -0.82, 51]],
			[[204, 204, 204], [0, 0, 204]],

		];
	}

	/**
	 * @dataProvider rgb2grayProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testRgb2gray($input, $output)
	{
		$this->assertEquals($output, $this->converter->rgb2gray($input));
	}

	public function rgb2grayProvider()
	{
		return [

			[[ColorConverter::MODE_RGB, 255, 124, 175], [1, 153.83999999999997]],

		];
	}

	/**
	 * @dataProvider cmyk2grayProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testCmyk2gray($input, $output)
	{
		$this->assertEquals($output, $this->converter->cmyk2gray($input));
	}

	public function cmyk2grayProvider()
	{
		return [

			[[ColorConverter::MODE_CMYK, 75, 12, 75, 74], [1, 45.66]],

		];
	}

	/**
	 * @dataProvider rgb2cmykProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testRgb2cmyk($input, $output)
	{
		$this->assertEquals($output, $this->converter->rgb2cmyk($input));
	}

	public function rgb2cmykProvider()
	{
		return [

			[[ColorConverter::MODE_RGB, 75, 12, 75], [ColorConverter::MODE_CMYK, 0, 83.99999999999999, 0.0, 70.58823529411764]],
			[[ColorConverter::MODE_RGBA, 75, 12, 75, 0.5], [ColorConverter::MODE_CMYKA, 0.0, 83.99999999999999, 0.0, 70.58823529411764, 0.5]],
			[[ColorConverter::MODE_RGB, 16, 58, 16], [ColorConverter::MODE_CMYK, 72.41379310344828, 0.0, 72.41379310344828, 77.25490196078432]],

		];
	}

	/**
	 * @dataProvider cmyk2rgbProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testCmyk2rgb($input, $output)
	{
		$this->assertEquals($output, $this->converter->cmyk2rgb($input));
	}

	public function cmyk2rgbProvider()
	{
		return [

			[[ColorConverter::MODE_CMYK, 75, 12, 75, 74], [3, 16, 58, 16]],

		];
	}

	/**
	 * @dataProvider hue2rgbProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testHue2rgb($input, $output)
	{
		$this->assertEquals($output, $this->converter->hue2rgb(...$input));
	}

	public function hue2rgbProvider()
	{
		return [

			[[75, 12, 75], 75],

		];
	}

}
