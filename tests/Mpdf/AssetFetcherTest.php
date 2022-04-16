<?php

namespace Mpdf;

use Mockery;

use Mpdf\File\LocalContentLoaderInterface;
use Mpdf\Http\ClientInterface;
use Psr\Log\NullLogger;

class AssetFetcherTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\AssetFetcher
	 */
	private $assetFetcher;

	protected function set_up()
	{
		parent::set_up();

		$mpdf = Mockery::mock(Mpdf::class);

		$mpdf->shouldIgnoreMissing();

		$mpdf->img_dpi = 72;
		$mpdf->whitelistStreamWrappers = ['http', 'file', 's3', 'phar'];
		$mpdf->showImageErrors = true;
		$mpdf->PDFAXwarnings = [];

		$contentLoader = Mockery::mock(LocalContentLoaderInterface::class);
		$http = Mockery::mock(ClientInterface::class);

		$this->assetFetcher = new AssetFetcher(
			$mpdf,
			$contentLoader,
			$http,
			new NullLogger()
		);
	}

	/**
	 * @dataProvider dataProviderStreamBlacklist
	 */
	public function testStreamBlacklist($filename, $message)
	{
		try {
			$this->assetFetcher->fetchDataFromPath($filename);
		} catch (\Mpdf\Exception\AssetFetchingException $e) {
			$this->assertSame($message, $e->getMessage());
		}
	}

	public function dataProviderStreamBlacklist()
	{
		$wrappers = stream_get_wrappers();

		foreach ($wrappers as $wrapper) {
			yield [$wrapper . '://', 'File contains an invalid stream. Only http, file, s3 streams are allowed.'];
		}
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
