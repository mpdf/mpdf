<?php

namespace Issues;

use Mockery;
use Mpdf\Color\ColorConverter;
use Mpdf\CssManager;
use Mpdf\Image\ImageProcessor;
use Mpdf\Image\Svg;
use Mpdf\Language\LanguageToFont;
use Mpdf\Language\ScriptToLanguage;
use Mpdf\Otl;
use Mpdf\SizeConverter;

/**
 * Class Issue450Test
 * @author Antonio Norman - softcodex.ch
 */
class Issue450Test extends \Mpdf\BaseMpdfTest
{

	/**
	 * SVGs with global CSS styles, had the styles ignored
	 */
	public function testSvgWithGlobalStyles()
	{
		// Copied from  Mpdf\Image\MetricsGeneratorTest
		$otl = Mockery::mock(Otl::class);
		$cssManager = Mockery::mock(CssManager::class);
		$imageProcessor = Mockery::mock(ImageProcessor::class);
		$this->sizeConverter = $this->createMock(SizeConverter::class); // Mockery::mock(ColorConverter::class); <-- doesn't work for me !??!
		$this->colorConverter = $this->createMock(ColorConverter::class); // Mockery::mock(ColorConverter::class); <-- doesn't work for me !??!
		$languageToFontInterface = Mockery::mock(LanguageToFont::class);
		$scriptToLanguageInterface = Mockery::mock(ScriptToLanguage::class);

		$svg = new Svg(
			$this->mpdf,
			$otl,
			$cssManager,
			$imageProcessor,
			$this->sizeConverter,
			$this->colorConverter,
			$languageToFontInterface,
			$scriptToLanguageInterface
		);

		// Load the SVG with the global styles
		$returnGlobalStyle = $svg->ImageSVG(
			file_get_contents(__DIR__ . '/../data/img/issue450_globalStyle.svg')
		);
		$this->assertNotFalse($returnGlobalStyle);

		// Load the SVG with the inline styles
		$returnInlineStyle = $svg->ImageSVG(
			file_get_contents(__DIR__ . '/../data/img/issue450_inlineStyle.svg')
		);
		$this->assertNotFalse($returnInlineStyle);

		// Check they are both the same!
		$this->assertEquals($returnGlobalStyle['data'], $returnInlineStyle['data']);
	}

}
