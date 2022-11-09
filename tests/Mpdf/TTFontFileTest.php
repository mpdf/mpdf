<?php

namespace Mpdf;

use Mpdf\Fonts\FontCache;

class TTFontFileTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var TTFontFile
	 */
	protected $ttf;

	/**
	 * @throws MpdfException
	 */
	public function set_up()
	{
		parent::set_up();

		$this->ttf = new TTFontFile(
			new FontCache(
				new Cache(__DIR__ . '/tmp/mpdf/ttfontdata')
			),
			'win'
		);
	}

	/**
	 * Verify fonts can be successfully parsed when useOTL is enabled, without throwing any PHP notices/warnings
	 */
	public function testGetMetricWithOtl()
	{
		$this->ttf->getMetrics(__DIR__ . '/../data/ttf/Poppins-Regular.ttf', (string) time(), 0, false, false, 0xFF);
		$this->assertSame('Poppins-Regular', $this->ttf->fullName);

		$this->ttf->getMetrics(__DIR__ . '/../data/ttf/NotoSans-Regular.ttf', (string) time(), 0, false, false, 0xFF);
		$this->assertSame('NotoSans-Regular', $this->ttf->fullName);
	}

}
