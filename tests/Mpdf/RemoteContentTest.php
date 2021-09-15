<?php

namespace Mpdf;

use Mockery;

class RemoteContentTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var \Mpdf\RemoteContentFetcher
	 */
	protected $remoteContentFetcher;

	/**
	 * @var \Mpdf\TestLogger
	 */
	protected $logger;

	/**
	 * @var \Mpdf\Mpdf|\Mockery\MockInterface
	 */
	protected $mpdf;

	/**
	 * @inheritDoc
	 */
	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = Mockery::mock(Mpdf::class);
		$this->mpdf->shouldIgnoreMissing();

		$this->logger = new TestLogger();

		$this->remoteContentFetcher = new RemoteContentFetcher($this->mpdf, $this->logger);
	}

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	protected function resetLogger()
	{
		$this->logger->reset();
		$this->logger->recordsByLevel = [];
	}

	public function testErrorLogging()
	{
		// Logger clean?
		$this->assertFalse($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasDebugRecords());

		// Success!
		$data = $this->remoteContentFetcher->getFileContentsByCurl('http:200 error: url:https://ok.example.com/');

		$this->assertFalse($this->logger->hasErrorRecords());
		$this->assertTrue($this->logger->hasDebugRecords());
		// Double check the mock...
		$this->assertStringMatchesFormat('Some content from %s!', $data);

		// cURL failed!
		$this->remoteContentFetcher->getFileContentsByCurl('http:0 error:Connection failed! url:https://curl-error.example.com/');

		$this->assertTrue($this->logger->hasErrorRecords());
		$this->assertTrue($this->logger->hasError('cURL error: "Connection failed!"'));
		$this->assertTrue($this->logger->hasError('HTTP error: 0'));
		$this->resetLogger();

		// Server error.
		$this->remoteContentFetcher->getFileContentsByCurl('http:500 error: url:https://http-error.example.com/');

		$this->assertTrue($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasErrorThatContains('cURL error'));
		$this->assertTrue($this->logger->hasError('HTTP error: 500'));
		$this->resetLogger();

		// Redirect without following.
		$this->mpdf->curlFollowLocation = false;
		$this->remoteContentFetcher->getFileContentsByCurl('http:301 error: url:https://redir.example.com/');

		$this->assertTrue($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasErrorThatContains('cURL error'));
		$this->assertTrue($this->logger->hasError('HTTP error: 301'));
		$this->resetLogger();

		// Redirect found but following location.
		$this->mpdf->curlFollowLocation = true;
		$this->remoteContentFetcher->getFileContentsByCurl('http:301 error: url:https://redir.example.com/');

		$this->assertFalse($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasErrorThatContains('cURL error'));
		$this->assertFalse($this->logger->hasErrorThatContains('HTTP error'));
	}

	public function testCURLException()
	{
		// Logger clean?
		$this->assertFalse($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasDebugRecords());

		$this->expectExceptionMessage('cURL error: "Connection failed!"');
		$this->expectException(\Mpdf\MpdfException::class);

		// Exception!
		$this->mpdf->debug = true;
		$this->remoteContentFetcher->getFileContentsByCurl('http:0 error:Connection failed! url:https://curl-error.example.com/');
	}

	public function testHTTPException()
	{
		// Logger clean?
		$this->assertFalse($this->logger->hasErrorRecords());
		$this->assertFalse($this->logger->hasDebugRecords());

		$this->expectExceptionMessage('HTTP error: 500');
		$this->expectException(\Mpdf\MpdfException::class);

		// Exception!
		$this->mpdf->debug = true;
		$this->remoteContentFetcher->getFileContentsByCurl('http:500 error: url:https://http-error.example.com/');
	}

}

// Mock some cURL functions.
// This works because of the namespace and because we know RemoteContentFetcher
// doesn't use backslashes while calling these functions.
function curl_init($url)
{
	$return = [
		'code' => 200,
		'error' => false,
		'url' => 'http://success/'
	];

	$match = [];
	if (preg_match('/^http:(?<code>\d+)\serror:(?<error>.*)\surl:(?<url>.+)$/', $url, $match)) {
		$return['code'] = (int) $match['code'];
		$return['error'] = $match['error'];
		$return['url'] = $match['url'];
	}

	return $return;
}

function curl_setopt(&$ch, $option, $value)
{
	if ($option === CURLOPT_FOLLOWLOCATION && $value) {
		if ($ch['code'] >= 300 && $ch['code'] < 400) {
			$ch['code'] = 200;
			$ch['error'] = '';
			$ch['url'] = 'https://redirected.example.com/';
		}
	}
}

function curl_exec($ch)
{
	return sprintf('Some content from %s!', $ch['url']);
}

function curl_error($ch)
{
	return $ch['error'];
}

function curl_getinfo($ch)
{
	return [
		'http_code' => $ch['code'],
	];
}

function curl_close()
{
	// nothing here
}
