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

	/**
	 * Verify OTL tables longer than one stream read are cached whole
	 */
	public function testOtlTablesAreCachedWholeFromAChunkedStream()
	{
		$file = __DIR__ . '/../../ttfonts/DejaVuSansCondensed.ttf';
		$fontkey = 'chunked' . uniqid();

		ChunkedReadStreamWrapper::register();
		$this->ttf->getMetrics(ChunkedReadStreamWrapper::url($file), $fontkey, 0, false, false, 0xFF);

		$gsub = $this->ttf->tables['GSUB'];
		$gpos = $this->ttf->tables['GPOS'];
		$this->assertGreaterThan(8192, $gpos['length']);

		$expected = file_get_contents($file, false, null, $gsub['offset'], $gsub['length'])
			. file_get_contents($file, false, null, $gpos['offset'], $gpos['length']);
		$this->assertSame($expected, file_get_contents(__DIR__ . '/tmp/mpdf/ttfontdata/' . $fontkey . '.GSUBGPOStables.dat'));
	}

	/**
	 * Verify a table longer than one stream read is read whole
	 */
	public function testGetTableReadsAWholeTableFromAChunkedStream()
	{
		$file = __DIR__ . '/../../ttfonts/DejaVuSansCondensed.ttf';

		ChunkedReadStreamWrapper::register();
		$this->ttf->fh = fopen(ChunkedReadStreamWrapper::url($file), 'rb');
		$this->ttf->seek(4);
		$this->ttf->readTableDirectory();
		$glyf = $this->ttf->get_table('glyf');
		fclose($this->ttf->fh);

		$table = $this->ttf->tables['glyf'];
		$this->assertGreaterThan(8192, $table['length']);
		$this->assertSame(file_get_contents($file, false, null, $table['offset'], $table['length']), $glyf);
	}

}
