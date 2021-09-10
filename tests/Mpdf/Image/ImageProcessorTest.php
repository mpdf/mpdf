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

class ImageProcessorTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Image\ImageProcessor
	 */
	private $image;

	protected function set_up()
	{
		parent::set_up();

		$mpdf = Mockery::mock(Mpdf::class);

		$mpdf->shouldIgnoreMissing();

		$mpdf->img_dpi = 72;
		$mpdf->whitelistStreamWrappers = ['http', 'file', 's3', 'phar'];
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

		$this->assertMatchesRegularExpression($match, $e->getMessage());
	}

	public function dataProviderStreamBlacklist()
	{
		$testData = [];

		$wrappers = stream_get_wrappers();
		foreach ($wrappers as $wrapper) {
			if (in_array($wrapper, ['http', 'file', 's3'])) {
				$testData[] = [$wrapper . '://', '/no expectations were specified/'];
			} else {
				$testData[] = [$wrapper . '://', '/File contains an invalid stream./'];
			}
		}

		return $testData;
	}
}

function stream_get_wrappers()
{
	return [
		'php',
		'file',
		'http',
		'ftp',
		'https',
		's3',
		'phar',
		'compress.bzip2'
	];
}
