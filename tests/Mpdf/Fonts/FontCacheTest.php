<?php

namespace Mpdf\Fonts;

use Mpdf\Mpdf;
use Mpdf\Cache;

class FontCacheTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Mpdf\Fonts\FontCache
	 */
	private $fontCache;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf();
		$this->fontCache = new FontCache(new Cache($this->mpdf->tempDir . '/ttfontdata'));
	}

	public function testJson()
	{
		$filename = 'jsonCallTest.json';
		$this->fontCache->jsonWrite($filename, 'Output Text');
		$this->assertEquals('Output Text', $this->fontCache->jsonLoad($filename));

		/* Test loading from the in-memory cache now the file is loaded */
		$this->fontCache->remove($filename);
		$this->assertFalse($this->fontCache->has($filename));
		$this->assertTrue($this->fontCache->jsonHas($filename));
		$this->assertEquals('Output Text', $this->fontCache->jsonLoad($filename));

		/* Test the deletion of the JSON cache */
		$this->fontCache->write($filename, '');
		$this->assertTrue($this->fontCache->has($filename));
		$this->fontCache->jsonRemove($filename);
		$this->assertFalse($this->fontCache->jsonHas($filename));
	}
}
