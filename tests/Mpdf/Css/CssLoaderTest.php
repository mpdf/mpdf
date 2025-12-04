<?php

namespace Mpdf\Css;

use Mpdf\AssetFetcher;
use Mpdf\Cache;
use Mpdf\Mpdf;

class CssLoaderTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	private $mpdf;
	private $assetFetcher;
	private $cache;

	/**
	 * @var CssLoader
	 */
	private $cssLoader;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();

		$this->assetFetcher = $this->getMockBuilder(AssetFetcher::class)
			->disableOriginalConstructor()
			->getMock();

		$this->cache = $this->getMockBuilder(Cache::class)
			->disableOriginalConstructor()
			->getMock();

		$this->cssLoader = new CssLoader($this->mpdf, $this->assetFetcher, $this->cache);
	}

	public function tear_down()
	{
		unset($this->mpdf, $this->cssLoader, $this->assetFetcher, $this->cache);
		parent::tear_down();
	}

	public function testLoadStylesheetSuccess()
	{
		$this->assetFetcher->expects($this->once())
			->method('fetchDataFromPath')
			->with('style.css')
			->willReturn('body { color: red; }');

		$css = $this->cssLoader->loadStylesheet('style.css');
		$this->assertEquals('body { color: red; }', $css);
	}

	public function testLoadStylesheetRetry()
	{
		$this->assetFetcher->expects($this->exactly(2))
			->method('fetchDataFromPath')
			->withConsecutive(['style.css'], [$this->anything()]) // 2nd call uses normalized path
			->willReturnOnConsecutiveCalls(false, 'body { color: blue; }');

		$css = $this->cssLoader->loadStylesheet('style.css');
		$this->assertEquals('body { color: blue; }', $css);
	}

	public function testExtractExternalStylesheetUrls()
	{
		$html = '
			<link rel="stylesheet" href="style1.css">
			<link href="style2.css" rel="stylesheet">
			<style>@import url(style3.css);</style>
			<style>@import "style4.css";</style>
		';

		$urls = $this->cssLoader->extractExternalStylesheetUrls($html);

		$this->assertCount(4, $urls);
		$this->assertContains('style1.css', $urls);
		$this->assertContains('style2.css', $urls);
		$this->assertContains('style3.css', $urls);
		$this->assertContains('style4.css', $urls);
	}

	public function testResolveBackgroundUrls()
	{
		$css = 'body { background: url(images/bg.jpg); }';
		$basePath = 'http://example.com/assets/';
		$resolved = $this->cssLoader->resolveBackgroundUrls($css, $basePath);

		$this->assertStringContainsString('url(http://example.com/assets/images/bg.jpg)', $resolved);
	}

	public function testProcessDataUriImages()
	{
		$css = 'div { background-image: url(data:image/png;base64,ABCDEF); }';
		
		$this->cache->expects($this->once())
			->method('write')
			->willReturn('temp/file.png');

		$processed = $this->cssLoader->processDataUriImages($css);

		$this->assertEquals('div { background-image: url("temp/file.png"); }', $processed);
	}
}
