<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;
use Mpdf\Mpdf;
use Mpdf\SizeConverter;
use Mpdf\Color\ColorConverter;
use Psr\Log\NullLogger;

class InlineStyleParserTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var \Mpdf\Css\InlineStyleParser
	 */
	private $inlineStyleParser;

	/**
	 * @var \Mpdf\Css\NormalizeProperties
	 */
	private $normalizeProperties;

	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();
		$logger = new NullLogger();
		$sizeConverter = new SizeConverter(96, 11, $this->mpdf, $logger);
		$colorModeConverter = new ColorModeConverter();
		$colorSpaceRestrictor = new ColorSpaceRestrictor($this->mpdf, $colorModeConverter);
		$colorConverter = new ColorConverter($this->mpdf, $colorModeConverter, $colorSpaceRestrictor);

		$this->normalizeProperties = new NormalizeProperties($this->mpdf, $sizeConverter, $colorConverter);

		$this->inlineStyleParser = new InlineStyleParser($this->normalizeProperties);
	}

	protected function tear_down()
	{
		unset($this->mpdf, $this->normalizeProperties, $this->inlineStyleParser);

		parent::tear_down();
	}

	public function testParse_WithBasicProperties()
	{
		$html = 'color: red; font-size: 10px;';

		$result = $this->inlineStyleParser->parse($html);

		$this->assertEquals('red', $result['COLOR']);
		$this->assertEquals('10px', $result['FONT-SIZE']);
	}

	public function testParse_WithUrls()
	{
		$html = 'background-image: url("image.png");';
		$result = $this->inlineStyleParser->parse($html);

		$this->assertEquals('image.png', $result['BACKGROUND-IMAGE']);
	}

	public function testProcessUrls_WithParenthesesAndSemicolons()
	{
		$css = 'background: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\'%3E%3C/svg%3E");';

		$result = $this->inlineStyleParser->processUrlsInCss($css);
		
		$this->assertStringContainsString('svg+xml%ZZ', $result);
		$this->assertStringNotContainsString('svg+xml;', $result);
	}
	
	public function testParse_WithWebkitGradient()
	{
		$html = 'background: -webkit-gradient(linear, left top, left bottom, from(#ccc), to(#000));';

		// Should ignore webkit gradient and return empty array if no other properties
		$result = $this->inlineStyleParser->parse($html);
		$this->assertEmpty($result);
	}

	public function testParse_WithUrlsContainingSpecialChars()
	{
		$css = 'background: url("http://example.com/image.jpg?param=value")';
		$result = $this->inlineStyleParser->parse($css);

		// Should handle URLs with special characters
		$this->assertIsArray($result);
		$this->assertArrayHasKey('BACKGROUND-IMAGE', $result);
		$this->assertEquals('http://example.com/image.jpg?param=value', $result['BACKGROUND-IMAGE']);
	}
}
