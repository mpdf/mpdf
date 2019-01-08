<?php

namespace Mpdf\Image;

use Mockery;

use Mpdf\Color\ColorModeConverter;
use Mpdf\Cache;
use Mpdf\RemoteContentFetcher;
use Psr\Log\NullLogger;
use Mpdf\CssManager;
use Mpdf\Color\ColorConverter;
use Mpdf\Language\LanguageToFont;
use Mpdf\Language\ScriptToLanguage;
use Mpdf\Mpdf;
use Mpdf\Otl;
use Mpdf\SizeConverter;

class ImageProcessorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Image\ImageProcessor
	 */
	private $image;

	protected function setUp()
	{
		parent::setUp();

		$mpdf = Mockery::mock(Mpdf::class);

		$mpdf->shouldIgnoreMissing();

		$mpdf->img_dpi = 72;
		$mpdf->showImageErrors = true;
		$mpdf->PDFAXwarnings = [];

		$otl = Mockery::mock(Otl::class);
		$cssManager = Mockery::mock(CssManager::class);
		$sizeConverter = Mockery::mock(SizeConverter::class);
		$colorConverter = Mockery::mock(ColorConverter::class);
		$colorModeConverter = Mockery::mock(ColorModeConverter::class);
		$cache = Mockery::mock(Cache::class);
		$languageToFont = Mockery::mock(LanguageToFont::class);
		$scriptToLanguage = Mockery::mock(ScriptToLanguage::class);
		$remoteContentFetcher = Mockery::mock(RemoteContentFetcher::class);
		$logger = Mockery::mock(NullLogger::class);

		$this->image = new ImageProcessor(
			$mpdf,
			$otl,
			$cssManager,
			$sizeConverter,
			$colorConverter,
			$colorModeConverter,
			$cache,
			$languageToFont,
			$scriptToLanguage,
			$remoteContentFetcher,
			$logger
		);
	}

	/**
	 * @dataProvider dataProviderStreamBlacklist
	 */
	public function testStreamBlacklist($filename, $match)
	{
		try {
			$this->image->getImage($filename);
		} catch (\Exception $e) {

		}

		$this->assertRegExp($match, $e->getMessage());
	}

	public function dataProviderStreamBlacklist()
	{
		$testData = [];

		$wrappers = stream_get_wrappers();
		foreach ($wrappers as $wrapper) {
			if (in_array($wrapper, ['http', 'https', 'file'])) {
				$testData[] = [$wrapper . '://', '/does not exist on this mock object/'];
			} else {
				$testData[] = [$wrapper . '://', '/File contains an invalid stream./'];
			}
		}

		return $testData;
	}
}
