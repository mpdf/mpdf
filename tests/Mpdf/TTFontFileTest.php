<?php

namespace Mpdf;

use Mpdf\Fonts\FontCache;

class TTFontFileTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var TTFontFile
	 */
	protected $ttf;

	protected $tmpPath;

	/**
	 * @throws MpdfException
	 */
	public function set_up()
	{
		parent::set_up();

		$this->tmpPath = __DIR__ . '/tmp/mpdf/ttfontdata';

		$this->ttf = new TTFontFile(
			new FontCache(
				new Cache($this->tmpPath)
			),
			'win'
		);
	}

	/**
	 * tearDown
	 */
	public function tear_down()
	{
		parent::tear_down();

		(new Cache($this->tmpPath))->clear();
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
	/**
	 * Verify fonts can be successfully parsed when useOTL is enabled and debugfont is enabled, without throwing any PHP notices/warnings
	 */
	public function testGetMetricWithOtlAndFontdebug()
	{
		(new Cache($this->tmpPath))->clear();
		$this->ttf->getMetrics(__DIR__ . '/../data/ttf/Poppins-Regular.ttf', (string) time(), 0, true, false, 0xFF);
		$this->assertSame('Poppins-Regular', $this->ttf->fullName);

		(new Cache($this->tmpPath))->clear();
		$this->ttf->getMetrics(__DIR__ . '/../data/ttf/NotoSans-Regular.ttf', (string) time(), 0, true, false, 0xFF);
		$this->assertSame('NotoSans-Regular', $this->ttf->fullName);
	}
}
