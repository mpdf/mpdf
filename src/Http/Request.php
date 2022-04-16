<?php

namespace Mpdf\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * PSR-7 URI implementation ported from nyholm/psr7 and adapted for PHP 5.6
 *
 * @link https://github.com/Nyholm/psr7/blob/master/src/Uri.php
 */
class Request implements \Psr\Http\Message\RequestInterface
{

	/** @var string */
	private $method;

	/** @var null|string */
	private $requestTarget;

	/** @var null|UriInterface */
	private $uri;

	/** @var array Map of all registered headers, as original name => array of values */
	private $headers = [];

	/** @var array Map of lowercase header name => original name at registration */
	private $headerNames = [];

	/** @var string */
	private $protocol;

	/** @var StreamInterface */
	private $stream;

	/**
	 * @param string                               $method  HTTP method
	 * @param string|UriInterface                  $uri     URI
	 * @param array                                $headers Request headers
	 * @param string|null|resource|StreamInterface $body    Request body
	 * @param string                               $version Protocol version
	 */
	public function __construct(
		$method,
		$uri,
		array $headers = [],
		$body = null,
		$version = '1.1'
	) {
		if (!($uri instanceof UriInterface)) {
			$uri = new Uri($uri);
		}

		$this->method = $method;
		$this->uri = $uri;
		$this->setHeaders($headers);
		$this->protocol = $version;

		if (!$this->hasHeader('Host')) {
			$this->updateHostFromUri();
		}

		if ($body !== '' && $body !== null) {
			$this->stream = Stream::create($body);
		}
	}

	public function getRequestTarget()
	{
		if ($this->requestTarget !== null) {
			return $this->requestTarget;
		}

		$target = $this->uri->getPath();
		if ($target == '') {
			$target = '/';
		}
		if ($this->uri->getQuery() != '') {
			$target .= '?'.$this->uri->getQuery();
		}

		return $target;
	}

	public function withRequestTarget($requestTarget)
	{
		if (preg_match('#\s#', $requestTarget)) {
			throw new \InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
		}

		$new = clone $this;
		$new->requestTarget = $requestTarget;

		return $new;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function withMethod($method)
	{
		$new = clone $this;
		$new->method = $method;

		return $new;
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		if ($uri === $this->uri) {
			return $this;
		}

		$new = clone $this;
		$new->uri = $uri;

		if (!$preserveHost || !$this->hasHeader('Host')) {
			$new->updateHostFromUri();
		}

		return $new;
	}

	private function updateHostFromUri()
	{
		$host = $this->uri->getHost();

		if ($host == '') {
			return;
		}

		if (($port = $this->uri->getPort()) !== null) {
			$host .= ':'.$port;
		}

		if (isset($this->headerNames['host'])) {
			$header = $this->headerNames['host'];
		} else {
			$header = 'Host';
			$this->headerNames['host'] = 'Host';
		}
		// Ensure Host is the first header.
		// See: http://tools.ietf.org/html/rfc7230#section-5.4
		$this->headers = [$header => [$host]] + $this->headers;
	}

	public function getProtocolVersion()
	{
		return $this->protocol;
	}

	public function withProtocolVersion($version)
	{
		if ($this->protocol === $version) {
			return $this;
		}

		$new = clone $this;
		$new->protocol = $version;

		return $new;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function hasHeader($header)
	{
		return isset($this->headerNames[strtolower($header)]);
	}

	public function getHeader($header)
	{
		$header = strtolower($header);

		if (!isset($this->headerNames[$header])) {
			return [];
		}

		$header = $this->headerNames[$header];

		return $this->headers[$header];
	}

	public function getHeaderLine($header)
	{
		return implode(', ', $this->getHeader($header));
	}

	public function withHeader($header, $value)
	{
		if (!is_array($value)) {
			$value = [$value];
		}

		$value = $this->trimHeaderValues($value);
		$normalized = strtolower($header);

		$new = clone $this;
		if (isset($new->headerNames[$normalized])) {
			unset($new->headers[$new->headerNames[$normalized]]);
		}
		$new->headerNames[$normalized] = $header;
		$new->headers[$header] = $value;

		return $new;
	}

	public function withAddedHeader($header, $value)
	{
		if (!is_array($value)) {
			$value = [$value];
		}

		$value = $this->trimHeaderValues($value);
		$normalized = strtolower($header);

		$new = clone $this;
		if (isset($new->headerNames[$normalized])) {
			$header = $this->headerNames[$normalized];
			$new->headers[$header] = array_merge($this->headers[$header], $value);
		} else {
			$new->headerNames[$normalized] = $header;
			$new->headers[$header] = $value;
		}

		return $new;
	}

	public function withoutHeader($header)
	{
		$normalized = strtolower($header);

		if (!isset($this->headerNames[$normalized])) {
			return $this;
		}

		$header = $this->headerNames[$normalized];

		$new = clone $this;
		unset($new->headers[$header], $new->headerNames[$normalized]);

		return $new;
	}

	public function getBody()
	{
		if (!$this->stream) {
			$this->stream = Stream::create('');
			$this->stream->rewind();
		}

		return $this->stream;
	}

	public function withBody(StreamInterface $body)
	{
		if ($body === $this->stream) {
			return $this;
		}

		$new = clone $this;
		$new->stream = $body;

		return $new;
	}

	private function setHeaders(array $headers)
	{
		$this->headerNames = $this->headers = [];
		foreach ($headers as $header => $value) {
			if (!is_array($value)) {
				$value = [$value];
			}

			$value = $this->trimHeaderValues($value);
			$normalized = strtolower($header);
			if (isset($this->headerNames[$normalized])) {
				$header = $this->headerNames[$normalized];
				$this->headers[$header] = array_merge($this->headers[$header], $value);
			} else {
				$this->headerNames[$normalized] = $header;
				$this->headers[$header] = $value;
			}
		}
	}

	/**
	 * Trims whitespace from the header values.
	 *
	 * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
	 *
	 * header-field = field-name ":" OWS field-value OWS
	 * OWS          = *( SP / HTAB )
	 *
	 * @param string[] $values Header values
	 *
	 * @return string[] Trimmed header values
	 *
	 * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
	 */
	private function trimHeaderValues(array $values)
	{
		return array_map(function ($value) {
			return trim($value, " \t");
		}, $values);
	}

}
