<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorConverter;
use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;
use Mpdf\Mpdf;
use Mpdf\SizeConverter;
use Psr\Log\NullLogger;

class NormalizePropertiesTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Css\NormalizeProperties
	 */
	private $normalizeProperties;

	private $mpdf;

	public function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();
		$logger = new NullLogger();
		$sizeConverter = new SizeConverter(96, 11, $this->mpdf, $logger);
		$colorModeConverter = new ColorModeConverter();
		$colorSpaceRestrictor = new ColorSpaceRestrictor($this->mpdf, $colorModeConverter);
		$colorConverter = new ColorConverter($this->mpdf, $colorModeConverter, $colorSpaceRestrictor);

		$this->normalizeProperties = new NormalizeProperties($this->mpdf, $sizeConverter, $colorConverter);
	}

	public function tear_down()
	{
		unset($this->normalizeProperties, $this->mpdf);

		parent::tear_down();
	}

	public function testNormalize()
	{
		$prop = [
			'MARGIN' => '10px',
			'PADDING' => '5px 10px',
			'BORDER' => '1px solid #000000',
			'BACKGROUND' => '#ffffff url(bg.jpg) no-repeat top left',
			'FONT' => '12px/1.5 Arial, sans-serif'
		];

		$expected = [
			'MARGIN-TOP' => '10px',
			'MARGIN-RIGHT' => '10px',
			'MARGIN-BOTTOM' => '10px',
			'MARGIN-LEFT' => '10px',
			'PADDING-TOP' => '5px',
			'PADDING-RIGHT' => '10px',
			'PADDING-BOTTOM' => '5px',
			'PADDING-LEFT' => '10px',
			'BORDER-TOP' => '1px solid #000000',
			'BORDER-RIGHT' => '1px solid #000000',
			'BORDER-BOTTOM' => '1px solid #000000',
			'BORDER-LEFT' => '1px solid #000000',
			'BACKGROUND-COLOR' => '#ffffff',
			'BACKGROUND-IMAGE' => 'bg.jpg',
			'BACKGROUND-REPEAT' => 'no-repeat',
			'BACKGROUND-POSITION' => '0% 0%',
			'FONT-FAMILY' => 'arial,sans-serif',
			'FONT-SIZE' => '12px',
			'LINE-HEIGHT' => '1.5',
			'FONT-STYLE' => 'normal',
			'FONT-WEIGHT' => 'normal',
		];

		$result = $this->normalizeProperties->normalize($prop);

		foreach ($expected as $k => $v) {
			$this->assertArrayHasKey($k, $result);
			$this->assertEquals($v, $result[$k]);
		}
	}

	public function testNormalizeEmpty()
	{
		$this->assertEquals([], $this->normalizeProperties->normalize([]));
		$this->assertEquals([], $this->normalizeProperties->normalize(null));
	}

	/**
	 * @dataProvider providerBorderRadius
	 */
	public function testNormalizeBorderRadius($prop, $expected)
	{
		$result = $this->normalizeProperties->normalize($prop);

		foreach ($expected as $k => $v) {
			$this->assertArrayHasKey($k, $result, "Missing key: $k");
			$this->assertEquals($v, $result[$k], "Mismatch for key: $k");
		}
	}

	public function providerBorderRadius()
	{
		return [
			[
				['BORDER-RADIUS' => '10px'],
				[
					'BORDER-TOP-LEFT-RADIUS-H' => '10px', 'BORDER-TOP-LEFT-RADIUS-V' => '10px',
					'BORDER-TOP-RIGHT-RADIUS-H' => '10px', 'BORDER-TOP-RIGHT-RADIUS-V' => '10px',
					'BORDER-BOTTOM-RIGHT-RADIUS-H' => '10px', 'BORDER-BOTTOM-RIGHT-RADIUS-V' => '10px',
					'BORDER-BOTTOM-LEFT-RADIUS-H' => '10px', 'BORDER-BOTTOM-LEFT-RADIUS-V' => '10px',
				]
			],
			[
				['BORDER-RADIUS' => '10px 20px'],
				[
					'BORDER-TOP-LEFT-RADIUS-H' => '10px', 'BORDER-TOP-LEFT-RADIUS-V' => '10px',
					'BORDER-TOP-RIGHT-RADIUS-H' => '20px', 'BORDER-TOP-RIGHT-RADIUS-V' => '20px',
					'BORDER-BOTTOM-RIGHT-RADIUS-H' => '10px', 'BORDER-BOTTOM-RIGHT-RADIUS-V' => '10px',
					'BORDER-BOTTOM-LEFT-RADIUS-H' => '20px', 'BORDER-BOTTOM-LEFT-RADIUS-V' => '20px',
				]
			],
			[
				['BORDER-RADIUS' => '10px 20px / 5px 15px'],
				[
					'BORDER-TOP-LEFT-RADIUS-H' => '10px', 'BORDER-TOP-LEFT-RADIUS-V' => '5px',
					'BORDER-TOP-RIGHT-RADIUS-H' => '20px', 'BORDER-TOP-RIGHT-RADIUS-V' => '15px',
					'BORDER-BOTTOM-RIGHT-RADIUS-H' => '10px', 'BORDER-BOTTOM-RIGHT-RADIUS-V' => '5px',
					'BORDER-BOTTOM-LEFT-RADIUS-H' => '20px', 'BORDER-BOTTOM-LEFT-RADIUS-V' => '15px',
				]
			],
			[
				['BORDER-RADIUS' => '10px 20px 30px'],
				[
					'BORDER-TOP-LEFT-RADIUS-H' => '10px', 'BORDER-TOP-LEFT-RADIUS-V' => '10px',
					'BORDER-TOP-RIGHT-RADIUS-H' => '20px', 'BORDER-TOP-RIGHT-RADIUS-V' => '20px',
					'BORDER-BOTTOM-RIGHT-RADIUS-H' => '30px', 'BORDER-BOTTOM-RIGHT-RADIUS-V' => '30px',
					'BORDER-BOTTOM-LEFT-RADIUS-H' => '20px', 'BORDER-BOTTOM-LEFT-RADIUS-V' => '20px',
				]
			],
			[
				['BORDER-RADIUS' => '10px 20px 30px 40px'],
				[
					'BORDER-TOP-LEFT-RADIUS-H' => '10px', 'BORDER-TOP-LEFT-RADIUS-V' => '10px',
					'BORDER-TOP-RIGHT-RADIUS-H' => '20px', 'BORDER-TOP-RIGHT-RADIUS-V' => '20px',
					'BORDER-BOTTOM-RIGHT-RADIUS-H' => '30px', 'BORDER-BOTTOM-RIGHT-RADIUS-V' => '30px',
					'BORDER-BOTTOM-LEFT-RADIUS-H' => '40px', 'BORDER-BOTTOM-LEFT-RADIUS-V' => '40px',
				]
			],
		];
	}

	public function testNormalizeListStyle()
	{
		$prop = ['LIST-STYLE' => 'square inside url(bullet.png)'];
		$expected = [
			'LIST-STYLE-TYPE' => 'square',
			'LIST-STYLE-POSITION' => 'inside',
			'LIST-STYLE-IMAGE' => 'bullet.png'
		];

		$result = $this->normalizeProperties->normalize($prop);

		foreach ($expected as $k => $v) {
			$this->assertEquals($v, $result[$k]);
		}
	}

	public function testNormalizeTextAlign()
	{
		$prop = ['TEXT-ALIGN' => 'center'];
		$result = $this->normalizeProperties->normalize($prop);
		$this->assertEquals('center', $result['TEXT-ALIGN']);

		$prop = ['TEXT-ALIGN' => 'decimal "DP"'];
		$result = $this->normalizeProperties->normalize($prop);
		$this->assertEquals('decimal "dp"', $result['TEXT-ALIGN']);
	}

	/**
	 * @dataProvider providerMarginShorthand
	 */
	public function testMarginShorthand($input, $expected)
	{
		$result = $this->normalizeProperties->normalize(['MARGIN' => $input]);
		$this->assertEquals($expected['T'], $result['MARGIN-TOP']);
		$this->assertEquals($expected['R'], $result['MARGIN-RIGHT']);
		$this->assertEquals($expected['B'], $result['MARGIN-BOTTOM']);
		$this->assertEquals($expected['L'], $result['MARGIN-LEFT']);
	}

	public function providerMarginShorthand()
	{
		return [
			['10px', ['T' => '10px', 'R' => '10px', 'B' => '10px', 'L' => '10px']],
			['10px 20px', ['T' => '10px', 'R' => '20px', 'B' => '10px', 'L' => '20px']],
			['10px 20px 30px', ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '20px']],
			['10px 20px 30px 40px', ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '40px']],
			['10px 20px 30px 40px 50px', ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '40px']],
		];
	}

	/**
	 * @dataProvider providerBorderString
	 */
	public function testBorderStringNormalization($input, $expected)
	{
		$result = $this->normalizeProperties->normalize(['BORDER-TOP' => $input]);
		$this->assertEquals($expected, $result['BORDER-TOP']);
	}

	public function providerBorderString()
	{
		return [
			['solid', 'medium solid #000000'],
			['#ff0000', '#ff0000 none #000000'], // Note: internal logic might produce this weird output, verified from CssManagerTest
			['2px', '2px none #000000'],
			['2px solid', '2px solid #000000'],
			['solid #ff0000', 'medium solid #ff0000'],
			['2px #ff0000', '2px none #ff0000'],
			['2px solid #ff0000', '2px solid #ff0000'],
			['#ff0000 2px solid', '2px solid #ff0000'],
			['none', 'medium none #000000'],
		];
	}

	public function testParseCSSbackground()
	{
		// Color only
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => '#ff0000']);
		$this->assertEquals('#ff0000', $res['BACKGROUND-COLOR']);
		$this->assertEquals('', $res['BACKGROUND-IMAGE']);

		// URL
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => 'url(image.jpg)']);
		$this->assertEquals('image.jpg', $res['BACKGROUND-IMAGE']);
		$this->assertEquals('transparent', $res['BACKGROUND-COLOR']);

		// URL and Color
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => '#fff url(bg.png)']);
		$this->assertEquals('#fff', $res['BACKGROUND-COLOR']);
		$this->assertEquals('bg.png', $res['BACKGROUND-IMAGE']);

		// URL and Repeat
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => 'url(bg.png) repeat-x']);
		$this->assertEquals('bg.png', $res['BACKGROUND-IMAGE']);
		$this->assertEquals('repeat-x', $res['BACKGROUND-REPEAT']);

		// URL and Position
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => 'url(bg.png) center top']);
		$this->assertEquals('bg.png', $res['BACKGROUND-IMAGE']);
		$this->assertEquals('50% 0%', $res['BACKGROUND-POSITION']);

		// Gradient
		$gradient = 'linear-gradient(to bottom, #fff, #000)';
		$res = $this->normalizeProperties->normalize(['BACKGROUND' => $gradient]);
		$this->assertEquals($gradient, $res['BACKGROUND-IMAGE']);
	}

	public function testNonExistentFontFamily()
	{
		$result = $this->normalizeProperties->normalize(['FONT-FAMILY' => 'abc']);
		$this->assertArrayNotHasKey('FONT-FAMILY', $result);
	}

}
