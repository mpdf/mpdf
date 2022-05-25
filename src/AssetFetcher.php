<?php

namespace Mpdf;

use Mpdf\File\LocalContentLoaderInterface;
use Mpdf\File\StreamWrapperChecker;
use Mpdf\Http\ClientInterface;
use Mpdf\Http\Request;
use Mpdf\Log\Context as LogContext;
use Psr\Log\LoggerInterface;

class AssetFetcher implements \Psr\Log\LoggerAwareInterface
{

	private $mpdf;

	private $contentLoader;

	private $http;

	private $logger;

	public function __construct(Mpdf $mpdf, LocalContentLoaderInterface $contentLoader, ClientInterface $http, LoggerInterface $logger)
	{
		$this->mpdf = $mpdf;
		$this->contentLoader = $contentLoader;
		$this->http = $http;
		$this->logger = $logger;
	}

	public function fetchDataFromPath($path, $originalSrc = null)
	{
		/**
		 * Prevents insecure PHP object injection through phar:// wrapper
		 * @see https://github.com/mpdf/mpdf/issues/949
		 * @see https://github.com/mpdf/mpdf/issues/1381
		 */
		$wrapperChecker = new StreamWrapperChecker($this->mpdf);

		if ($wrapperChecker->hasBlacklistedStreamWrapper($path)) {
			throw new \Mpdf\Exception\AssetFetchingException('File contains an invalid stream. Only ' . implode(', ', $wrapperChecker->getWhitelistedStreamWrappers()) . ' streams are allowed.');
		}

		if ($originalSrc && $wrapperChecker->hasBlacklistedStreamWrapper($originalSrc)) {
			throw new \Mpdf\Exception\AssetFetchingException('File contains an invalid stream. Only ' . implode(', ', $wrapperChecker->getWhitelistedStreamWrappers()) . ' streams are allowed.');
		}

		// Try if the original file path exists relative to the directory the script is run in, prefer if available
		if ($this->isPathRelative($originalSrc) && $this->localContentExists(getcwd() . '/' . $originalSrc)) {
			$this->logger->debug(sprintf('Path to asset %s resolved as an existing file, preferring', getcwd() . '/' . $originalSrc), ['context' => LogContext::REMOTE_CONTENT]);

			return $this->fetchLocalContent($path, $originalSrc);
		}

		// Initialize the full path according to basepath and possible existing domain the script runs on
		$this->mpdf->GetFullPath($path);

		return $this->isPathLocal($path)
			? $this->fetchLocalContent($path, $originalSrc)
			: $this->fetchRemoteContent($path);
	}

	public function fetchLocalContent($path, $originalSrc)
	{
		$data = '';

		if ($originalSrc && $this->mpdf->basepathIsLocal && $check = @fopen($originalSrc, 'rb')) {
			fclose($check);
			$path = $originalSrc;
			$this->logger->debug(sprintf('Fetching content of file "%s" with local basepath', $path), ['context' => LogContext::REMOTE_CONTENT]);

			return $this->contentLoader->load($path);
		}

		if ($path && $check = @fopen($path, 'rb')) {
			fclose($check);
			$this->logger->debug(sprintf('Fetching content of file "%s" with non-local basepath', $path), ['context' => LogContext::REMOTE_CONTENT]);

			return $this->contentLoader->load($path);
		}

		return $data;
	}

	public function fetchRemoteContent($path)
	{
		$data = '';

		try {

			$this->logger->debug(sprintf('Fetching remote content of file "%s"', $path), ['context' => LogContext::REMOTE_CONTENT]);

			/** @var \Mpdf\Http\Response $response */
			$response = $this->http->sendRequest(new Request('GET', $path));

			if ($response->getStatusCode() !== 200) {

				$message = sprintf('Non-OK HTTP response "%s" on fetching remote content "%s" because of an error', $response->getStatusCode(), $path);
				if ($this->mpdf->debug) {
					throw new \Mpdf\MpdfException($message);
				}

				$this->logger->info($message);

				return $data;
			}

			$data = $response->getBody()->getContents();

		} catch (\InvalidArgumentException $e) {
			$message = sprintf('Unable to fetch remote content "%s" because of an error "%s"', $path, $e->getMessage());
			if ($this->mpdf->debug) {
				throw new \Mpdf\MpdfException($message, 0, $e);
			}

			$this->logger->warning($message);
		}

		return $data;
	}

	public function isPathLocal($path)
	{
		return strpos($path, '://') === false; // @todo More robust implementation
	}

	public function isPathRelative($path)
	{
		return strpos($path, '../') === 0;
	}

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	private function localContentExists($path)
	{
		return file_exists($path) && is_file($path) && is_readable($path);
	}

}
