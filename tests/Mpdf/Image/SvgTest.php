<?php

namespace Mpdf\Image;

use Mockery;

use Mpdf\CssManager;
use Mpdf\Color\ColorConverter;
use Mpdf\Language\LanguageToFont;
use Mpdf\Language\ScriptToLanguage;
use Mpdf\Mpdf;
use Mpdf\Otl;
use Mpdf\SizeConverter;

class MetricsGeneratorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Image\Svg
	 */
	private $svg;

	private $sizeConverter;

	private $colorConverter;

	protected function setUp()
	{
		parent::setUp();

		$mpdf = Mockery::mock(Mpdf::class);

		$mpdf->shouldIgnoreMissing();

		$mpdf->img_dpi = 72;
		$mpdf->PDFAXwarnings = [];

		$otl = Mockery::mock(Otl::class);
		$cssManager = Mockery::mock(CssManager::class);
		$imageProcessor = Mockery::mock(ImageProcessor::class);
		$this->sizeConverter = Mockery::mock(SizeConverter::class);
		$this->colorConverter = Mockery::mock(ColorConverter::class);
		$languageToFontInterface = Mockery::mock(LanguageToFont::class);
		$scriptToLanguageInterface = Mockery::mock(ScriptToLanguage::class);

		$this->svg = new Svg(
			$mpdf,
			$otl,
			$cssManager,
			$imageProcessor,
			$this->sizeConverter,
			$this->colorConverter,
			$languageToFontInterface,
			$scriptToLanguageInterface
		);
	}

	public function testSvgImage()
	{
		$data = file_get_contents(__DIR__ . '/../../data/img/demo.svg');

		$this->sizeConverter->shouldReceive('convert')->twice()->andReturn(0);
		$this->colorConverter->shouldReceive('convert')->times(140)->andReturn(0);

		$this->svg->ImageSVG($data);
	}

}
