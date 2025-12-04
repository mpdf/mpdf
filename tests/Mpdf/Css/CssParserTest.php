<?php

namespace Mpdf\Css;

use Mpdf\AssetFetcher;
use Mpdf\Cache;
use Mpdf\Color\ColorConverter;
use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;
use Mpdf\Mpdf;
use Mpdf\SizeConverter;
use Psr\Log\NullLogger;

class CssParserTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/** @var CssParser */
	private $parser;

	/** @var Mpdf */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$logger = new NullLogger();
		$this->mpdf = new Mpdf();
		$this->mpdf->setLogger($logger);

		$assetFetcher = $this->getMockBuilder(AssetFetcher::class)
			->disableOriginalConstructor()
			->getMock();

		$cache = $this->getMockBuilder(Cache::class)
			->disableOriginalConstructor()
			->getMock();

		$sizeConverter = new SizeConverter($this->mpdf->dpi, $this->mpdf->default_font_size, $this->mpdf, $logger);
		$colorModeConverter = new ColorModeConverter();
		$colorSpaceRestrictor = new ColorSpaceRestrictor($this->mpdf, $colorModeConverter);
		$colorConverter = new ColorConverter($this->mpdf, $colorModeConverter, $colorSpaceRestrictor);

		$this->parser = new CssParser(
			$this->mpdf,
			$cache,
			$sizeConverter,
			$colorConverter,
			$assetFetcher
		);
	}

	public function tear_down()
	{
		unset($this->mpdf, $this->parser);

		parent::tear_down();
	}

	public function testParseCssProperties()
	{
		$css = 'color: red; font-size: 14px';
		$result = $this->parser->parseCssProperties($css);

		$this->assertEquals('red', $result['COLOR']);
		$this->assertEquals('14px', $result['FONT-SIZE']);
	}

	public function testParseSimpleSelector()
	{
		$html = '<style>p { color: red; }</style>';
		$parsedHtml = $this->parser->parse($html);

		$this->assertIsString($parsedHtml);
		$this->assertStringNotContainsString('<style>', $parsedHtml);

		$css = $this->parser->getCss();

		$this->assertEquals('red', $css['P']['COLOR']);
	}

	public function testParseCascadedSelector()
	{
		$html = '<style>div p { color: blue; }</style>';
		$this->parser->parse($html);

		$cascade = $this->parser->getCascadeCss();
		$this->assertArrayHasKey('P', $cascade['DIV']);
		
		$properties = $cascade['DIV']['P'];
		$this->assertEquals('blue', $properties['COLOR']);
		$this->assertEquals(2, $properties['depth']);
	}
}
