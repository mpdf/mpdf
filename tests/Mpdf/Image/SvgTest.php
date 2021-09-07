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

class SvgTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Image\Svg
	 */
	private $svg;

	private $sizeConverter;

	private $colorConverter;

	protected function set_up()
	{
		parent::set_up();

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

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	public function testSvgImage()
	{
		$data = file_get_contents(__DIR__ . '/../../data/img/demo.svg');

		$this->sizeConverter->shouldReceive('convert')->twice()->andReturn(0);
		$this->colorConverter->shouldReceive('convert')->times(140)->andReturn(0);

		$this->svg->ImageSVG($data);
	}

	public function testLogoManageroneSvgImage()
	{
		$data = file_get_contents(__DIR__ . '/../../data/img/logo_managerone.svg');

		$this->sizeConverter->shouldReceive('convert')->times(2)->andReturn(0);
		$this->colorConverter->shouldReceive('convert')->times(1)->andReturn(0);

		$this->svg->ImageSVG($data);
	}

	public function testLogoLivingparisianSvgImage()
	{
		$data = file_get_contents(__DIR__ . '/../../data/img/logo_livingparisian.svg');

		$this->colorConverter->shouldReceive('convert')->times(28)->andReturn(0);

		$this->svg->ImageSVG($data);
	}

}
