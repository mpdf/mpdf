<?php

namespace Mpdf\Color;

use Mockery;

class ColorConverterTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	private $modeConverter;

	private $restrictor;

	protected function setUp()
	{
		parent::setUp();

		$this->restrictor = Mockery::mock('Mpdf\Color\ColorSpaceRestrictor');
		$this->modeConverter = Mockery::mock('Mpdf\Color\ColorModeConverter');
		$this->converter = new ColorConverter(
			Mockery::spy('Mpdf\Mpdf'),
			$this->modeConverter,
			$this->restrictor
		);
	}

	/**
	 * @dataProvider convertColorsProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testConvert($input, $output, $method = NULL, $times = NULL, $result = NULL)
	{
		if ($method) {
			$this->modeConverter->shouldReceive($method)->times($times)->andReturn($result);
		}

		$this->assertSame($output, $this->converter->convert($input));
	}

	public function convertColorsProvider()
	{
		return [

			['#220044', "3\"\x00D\x00\x00"],
			[255, "1\xff\x00\x00\x00\x00"],
			[85, "1U\x00\x00\x00\x00"],
			[0, "1\x00\x00\x00\x00\x00"],

			['#CCC', "3\xcc\xcc\xcc\x00\x00"],
			['#aaaacc', "3\xaa\xaa\xcc\x00\x00"],

			['aqua', "3\x00\xff\xff\x00\x00"],

			['rgb(123, 147, 156)', "3{\x93\x9c\x00\x00"],
			['rgb(66%, 80%, 20%)', "3\xa8\xcc3\x00\x00"],

			['rgba(123, 147, 156, 0.55)', "5{\x93\x9c7\x00"],

			['cmyk(84, 74, 68, 74)', "4TJDJ\x00"],
			['cmyka(46%, 20%, 88%, 22%, 0.6)', "6.\x14X\x16<"],

			['device-cmyk(84, 74, 68, 74)', "4TJDJ\x00"],
			['device-cmyka(46%, 20%, 88%, 22%, 0.33)', "6.\x14X\x16!"],

			['hsl(123, 55%, 20%)', "3\x17O\x1a\x00\x00", 'hsl2rgb', 1, [23, 79, 26]],
			['hsla(123, 55%, 20%, 0.25)', "5\x17O\x1a\x19\x00", 'hsl2rgb', 1, [23, 79, 26]],
			['hsl(66%, 80%, 20%)', "3T\\\n\x00\x00", 'hsl2rgb', 1, [84, 92, 10]],

			['spot(PANTONE 534 EC, 100%, 85, 65, 47, 9)', "2\x00d\x00\x00\x00"],

			['inherit', false],
			['transparent', false],

		];
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage Undefined spot color "PANTONE 534 EC"
	 */
	public function testConvertUnknownSpotColor()
	{
		$this->converter->convert('spot(PANTONE 534 EC, 100%, 85, 65, 47)');
	}

	/**
	 * @dataProvider colAtoStringProvider
	 *
	 * @param string $output
	 * @param string $input
	 */
	public function testColAtoString($output, $input)
	{
		$this->assertSame($output, $this->converter->colAtoString($input));
	}

	public function colAtoStringProvider()
	{
		return [
			['rgb(255, 255, 255)', "1\xff\x00\x00\x00\x00"],
			['rgb(85, 85, 85)', "1U\x00\x00\x00\x00"],
			['rgb(0, 0, 0)', "1\x00\x00\x00\x00\x00"],
			['rgb(204, 204, 204)', "3\xcc\xcc\xcc\x00\x00"],
			['rgb(170, 170, 204)', "3\xaa\xaa\xcc\x00\x00"],
			['rgb(0, 255, 255)', "3\x00\xff\xff\x00\x00"],
			['rgb(123, 147, 156)', "3{\x93\x9c\x00\x00"],
			['rgb(168, 204, 51)', "3\xa8\xcc3\x00\x00"],
			['rgba(123, 147, 156, 0.55)', "5{\x93\x9c7\x00"],
			['cmyk(84, 74, 68, 74)', "4TJDJ\x00"],
			['cmyka(46, 20, 88, 22, 0.60)', "6.\x14X\x16<"],
			['rgb(23, 79, 26)', "3\x17O\x1a\x00\x00"],
			['rgba(23, 79, 26, 0.25)', "5\x17O\x1a\x19\x00"],
			['rgb(84, 92, 10)', "3T\\\n\x00\x00"],
			['cmyk(84, 74, 68, 74)', "4TJDJ\x00"],
			['cmyka(46, 20, 88, 22, 0.33)', "6.\x14X\x16!"],
			['spot(0, 100)', "2\x00d\x00\x00\x00"],
		];
	}

	/**
	 * @dataProvider lightenColorsProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testLighten($input, $output, $method = NULL, $times = NULL, $result = NULL, $method2 = NULL, $times2 = NULL, $result2 = NULL)
	{
		if ($method) {
			$this->modeConverter->shouldReceive($method)->times($times)->andReturn($result);
		}

		if ($method2) {
			$this->modeConverter->shouldReceive($method2)->times($times2)->andReturn($result2);
		}

		$this->assertSame($output, $this->converter->lighten($input));
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 */
	public function testLightenWithArray()
	{
		$this->converter->lighten([]);
	}

	public function lightenColorsProvider()
	{
		return [
			["3\x00\x00\xff\x00\x00", "3\xcc\xcc\xcc\x00\x00", 'rgb2hsl', 1, '123456789', 'hsl2rgb', 1, [204, 204, 204]],
			["4TJDJ\x00", "4@606\x00"],
			["1\xff\x00\x00\x00\x00", "1\xff\x00\x00\x00\x00"],
			["1U\x00\x00\x00\x00", "1u\x00\x00\x00\x00"],
			["1\x00\x00\x00\x00\x00", "1 \x00\x00\x00\x00"],
		];
	}

	/**
	 * @dataProvider darkenColorsProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testDarken($input, $output, $method = NULL, $times = NULL, $result = NULL, $method2 = NULL, $times2 = NULL, $result2 = NULL)
	{
		if ($method) {
			$this->modeConverter->shouldReceive($method)->times($times)->andReturn($result);
		}

		if ($method2) {
			$this->modeConverter->shouldReceive($method2)->times($times2)->andReturn($result2);
		}

		$this->assertSame($output, $this->converter->darken($input));
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 */
	public function testDarkenWithArray()
	{
		$this->converter->darken([]);
	}

	public function darkenColorsProvider()
	{
		return [
			["3\x00\x00\xff\x00\x00", "3TTT\x00\x00", 'rgb2hsl', 1, '123456789', 'hsl2rgb', 1, [84, 84, 84]],
			["4TJDJ\x00", "4d^X^\x00"],
			["1\xff\x00\x00\x00\x00", "1\xdf\x00\x00\x00\x00"],
			["1U\x00\x00\x00\x00", "15\x00\x00\x00\x00"],
			["1\x00\x00\x00\x00\x00", "1\x00\x00\x00\x00\x00"],
		];
	}

	/**
	 * @dataProvider invertColorsProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testInvert($input, $output)
	{
		$this->assertSame($output, $this->converter->invert($input));
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 */
	public function testInvertWithArray()
	{
		$this->converter->invert([]);
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 */
	public function testInvertNonRgb()
	{
		$this->converter->invert("2\x00d\x00\x00\x00");
	}

	public function invertColorsProvider()
	{
		return [
			["3\x00\x00\xff\x00\x00", [3, 255, 255, 0]],
			["4TJDJ\x00", [4, 16, 26, 32, 26]],
			["3\xaa\xaa\xcc\x00\x00", [3, 85, 85, 51]],
			["1\xff\x00\x00\x00\x00", [1, 0]],
			["1U\x00\x00\x00\x00", [1, 170]],
			["1\x00\x00\x00\x00\x00", [1, 255]],

		];
	}

}
