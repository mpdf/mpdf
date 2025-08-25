<?php

namespace Mpdf\Http;

use Mpdf\Log\Context as LogContext;
use Mpdf\Mpdf;
use Mpdf\PsrHttpMessageShim\Response;
use Mpdf\PsrHttpMessageShim\Stream;
use Mpdf\PsrLogAwareTrait\PsrLogAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

class CurlHttpClient implements \Mpdf\Http\ClientInterface, \Psr\Log\LoggerAwareInterface
{
	use PsrLogAwareTrait;

	private $mpdf;

	public function __construct(Mpdf $mpdf, LoggerInterface $logger)
	{
		$this->mpdf = $mpdf;
		$this->logger = $logger;
	}

	public function sendRequest(RequestInterface $request)
	{
		if (null === $request->getUri()) {
			return (new Response());
		}

		$url = $request->getUri();

		$this->logger->debug(sprintf('Fetching (cURL) content of remote URL "%s"', $url), ['context' => LogContext::REMOTE_CONTENT]);

		$response = new Response();

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERAGENT, $this->mpdf->curlUserAgent);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->mpdf->curlTimeout);

		if ($this->mpdf->curlExecutionTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->mpdf->curlExecutionTimeout);
		}

		if ($this->mpdf->curlFollowLocation) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}

		if ($this->mpdf->curlAllowUnsafeSslRequests) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}

		if ($this->mpdf->curlCaCertificate && is_file($this->mpdf->curlCaCertificate)) {
			curl_setopt($ch, CURLOPT_CAINFO, $this->mpdf->curlCaCertificate);
		}

		if ($this->mpdf->curlProxy) {
			curl_setopt($ch, CURLOPT_PROXY, $this->mpdf->curlProxy);
			if ($this->mpdf->curlProxyAuth) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->mpdf->curlProxyAuth);
			}
		}

		curl_setopt(
			$ch,
			CURLOPT_HEADERFUNCTION,
			static function ($curl, $header) use (&$response) {
				$len = strlen($header);
				$header = explode(':', $header, 2);
				if (count($header) < 2) { // ignore invalid headers
					return $len;
				}

				$response = $response->withHeader(trim($header[0]), trim($header[1]));

				return $len;
			}
		);

		$data = curl_exec($ch);

		if (curl_error($ch)) {
			$message = sprintf('cURL error: "%s"', curl_error($ch));
			$this->logger->error($message, ['context' => LogContext::REMOTE_CONTENT]);

			if ($this->mpdf->debug) {
				throw new \Mpdf\MpdfException($message);
			}

			$this->closeCurl($ch);

			return $response;
		}

		$info = curl_getinfo($ch);
		if (isset($info['http_code']) && !str_starts_with((string) $info['http_code'], '2')) {
			$message = sprintf('HTTP error: %d', $info['http_code']);
			$this->logger->error($message, ['context' => LogContext::REMOTE_CONTENT]);

			if ($this->mpdf->debug) {
				throw new \Mpdf\MpdfException($message);
			}

			$this->closeCurl($ch);

			return $response->withStatus($info['http_code']);
		}

		$this->closeCurl($ch);

		return $response
			->withStatus($info['http_code'])
			->withBody(Stream::create($data));
	}

	private function closeCurl($ch)
	{
		if (PHP_VERSION_ID < 80000) {
			curl_close($ch);
		}
	}
}
