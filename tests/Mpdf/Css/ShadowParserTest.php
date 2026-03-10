<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorConverter;
use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;
use Mpdf\Mpdf;
use Mpdf\SizeConverter;
use Psr\Log\NullLogger;

class ShadowParserTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var \Mpdf\Css\ShadowParser
	 */
	private $shadowParser;
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

		$this->shadowParser = new ShadowParser($this->mpdf, $sizeConverter, $colorConverter);
	}

	public function tear_down()
	{
		unset($this->shadowParser, $this->mpdf);
		parent::tear_down();
	}

	public function testNormalizeShadowColors()
	{
		$input = '1px 1px 1px rgba(0, 0, 0, 0.5), 2px 2px #fff';
		$expected = '1px 1px 1px rgba(0*0*0*0.5), 2px 2px #fff';
		$this->assertEquals($expected, $this->shadowParser->normalizeShadowColors($input));
	}

	public function testParseBoxShadowWithBasicShadow()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->shadowParser->parseBoxShadow('2px 2px');
		$this->assertCount(1, $result);
		// 2px = 0.529 mm
		$this->assertEqualsWithDelta(0.529, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.529, $result[0]['y'], 0.001);
		$this->assertEquals(0, $result[0]['blur']);
		$this->assertFalse($result[0]['inset']);
	}

	public function testParseBoxShadowWithBlurAndSpread()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->shadowParser->parseBoxShadow('2px 2px 4px 1px #000');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.529, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.529, $result[0]['y'], 0.001);
		$this->assertEqualsWithDelta(1.058, $result[0]['blur'], 0.001);
		$this->assertEqualsWithDelta(0.264, $result[0]['spread'], 0.001);
	}

	public function testParseBoxShadowWithInset()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->shadowParser->parseBoxShadow('inset 5px 5px 10px #000');
		$this->assertCount(1, $result);
		$this->assertTrue($result[0]['inset']);
	}

	public function testParseBoxShadowWithMultipleShadows()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->shadowParser->parseBoxShadow('2px 2px #000, 4px 4px #fff');
		$this->assertCount(2, $result);
	}

	public function testParseTextShadowWithBasicShadow()
	{
		$result = $this->shadowParser->parseTextShadow('1px 1px');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.264, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.264, $result[0]['y'], 0.001);
		$this->assertEquals(0, $result[0]['blur']);
	}

	public function testParseTextShadowWithBlur()
	{
		$this->mpdf->blk = [];

		$result = $this->shadowParser->parseTextShadow('2px 2px 3px #000');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.793, $result[0]['blur'], 0.001);
	}
}
