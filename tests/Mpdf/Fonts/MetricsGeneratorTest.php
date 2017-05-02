<?php

namespace Mpdf\Fonts;

use Mockery;

class MetricsGeneratorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Fonts\MetricsGenerator
	 */
	private $generator;

	/**
	 * @var \Mpdf\Fonts\FontCache
	 */
	private $fontCache;

	protected function setUp()
	{
		parent::setUp();

		$this->fontCache = Mockery::mock(FontCache::class);
		$this->generator = new MetricsGenerator($this->fontCache, 'win');
	}

	public function testGenerateMetrics()
	{
		$this->fontCache->shouldReceive('write')->with('angerthas.mtx.php', Mockery::any())->once();
		$this->fontCache->shouldReceive('binaryWrite')->with('angerthas.cw.dat', Mockery::any())->once();
		$this->fontCache->shouldReceive('binaryWrite')->with('angerthas.gid.dat', Mockery::any())->once();

		$this->fontCache->shouldReceive('has')->times(4)->andReturn(true);
		$this->fontCache->shouldReceive('remove')->times(4);

		$file = __DIR__ . '/../../data/ttf/angerthas.ttf';
		$this->generator->generateMetrics($file, stat($file), 'angerthas', 0, false, false, false, false);
	}


}
