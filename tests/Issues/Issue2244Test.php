<?php

namespace Issues;

use Mpdf\Container\SimpleContainer;
use Mpdf\Http\ClientInterface;
use Mpdf\Mpdf;
use Mpdf\PsrHttpMessageShim\Response;

class Issue2244Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/** @var string */
	private $docRoot;

	/** @var bool */
	private $hadHttpHost;

	/** @var mixed */
	private $originalHttpHost;

	/** @var bool */
	private $hadDocumentRoot;

	/** @var mixed */
	private $originalDocumentRoot;

	protected function set_up()
	{
		parent::set_up();

		$this->hadHttpHost = array_key_exists('HTTP_HOST', $_SERVER);
		$this->originalHttpHost = $this->hadHttpHost ? $_SERVER['HTTP_HOST'] : null;

		$this->hadDocumentRoot = array_key_exists('DOCUMENT_ROOT', $_SERVER);
		$this->originalDocumentRoot = $this->hadDocumentRoot ? $_SERVER['DOCUMENT_ROOT'] : null;

		$this->docRoot = sys_get_temp_dir() . '/mpdf_issue2244_' . uniqid('', true);
		mkdir($this->docRoot, 0777, true);
		file_put_contents($this->docRoot . '/style.css', 'p { color: #cc0000; }');

		$_SERVER['HTTP_HOST'] = 'example.test';
		$_SERVER['DOCUMENT_ROOT'] = $this->docRoot;
	}

	protected function tear_down()
	{
		if ($this->hadHttpHost) {
			$_SERVER['HTTP_HOST'] = $this->originalHttpHost;
		} else {
			unset($_SERVER['HTTP_HOST']);
		}

		if ($this->hadDocumentRoot) {
			$_SERVER['DOCUMENT_ROOT'] = $this->originalDocumentRoot;
		} else {
			unset($_SERVER['DOCUMENT_ROOT']);
		}

		if (is_file($this->docRoot . '/style.css')) {
			unlink($this->docRoot . '/style.css');
		}
		if (is_dir($this->docRoot)) {
			rmdir($this->docRoot);
		}

		parent::tear_down();
	}

	public function testExternalStylesheetFallsBackToDocumentRootWhenBasepathIsLocal()
	{
		$output = $this->renderPdfWithFailingHttpClient(
			'<html><head><link rel="stylesheet" href="https://example.test/style.css"></head>'
			. '<body><p>Styled text</p></body></html>'
		);

		$this->assertStringContainsString('0.800 0.000 0.000 rg', $output);
	}

	public function testExternalStylesheetDoesNotReadDocumentRootWhenBasepathIsNotLocal()
	{
		$output = $this->renderPdfWithFailingHttpClient(
			'<html><head><base href="https://cdn.other.test/"><link rel="stylesheet" href="https://cdn.other.test/style.css"></head>'
			. '<body><p>Unstyled text</p></body></html>'
		);

		$this->assertStringNotContainsString('0.800 0.000 0.000 rg', $output);
	}

	/**
	 * @param string $html
	 * @return string
	 */
	private function renderPdfWithFailingHttpClient($html)
	{
		$httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
		$httpClient->expects($this->once())
			->method('sendRequest')
			->willReturn(new Response(500));

		$mpdf = new Mpdf(['mode' => 'c'], new SimpleContainer(['httpClient' => $httpClient]));
		try {
			$mpdf->SetCompression(false);
			$mpdf->WriteHTML($html);

			return $mpdf->OutputBinaryData();
		} finally {
			$mpdf->cleanup();
		}
	}
}
