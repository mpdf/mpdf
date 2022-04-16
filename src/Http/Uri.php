<?php

namespace Mpdf\Http;

/**
 * PSR-7 URI implementation ported from nyholm/psr7 and adapted for PHP 5.6
 *
 * @link https://github.com/Nyholm/psr7/blob/master/src/Uri.php
 */
final class Uri implements \Psr\Http\Message\UriInterface
{
	private static $schemes = ['http' => 80, 'https' => 443];

	const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';

	const CHAR_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

	/** @var string Uri scheme. */
	private $scheme = '';

	/** @var string Uri user info. */
	private $userInfo = '';

	/** @var string Uri host. */
	private $host = '';

	/** @var int|null Uri port. */
	private $port;

	/** @var string Uri path. */
	private $path = '';

	/** @var string Uri query string. */
	private $query = '';

	/** @var string Uri fragment. */
	private $fragment = '';

	public function __construct($uri = '')
	{
		if ('' !== $uri) {
			if (false === $parts = \parse_url($uri)) {
				throw new \InvalidArgumentException(\sprintf('Unable to parse URI: "%s"', $uri));
			}

			// Apply parse_url parts to a URI.
			$this->scheme = isset($parts['scheme']) ? \strtr($parts['scheme'], 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') : '';
			$this->userInfo = isset($parts['user']) ? $parts['user'] : '';
			$this->host = isset($parts['host']) ? \strtr($parts['host'], 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz') : '';
			$this->port = isset($parts['port']) ? $this->filterPort($parts['port']) : null;
			$this->path = isset($parts['path']) ? $this->filterPath($parts['path']) : '';
			$this->query = isset($parts['query']) ? $this->filterQueryAndFragment($parts['query']) : '';
			$this->fragment = isset($parts['fragment']) ? $this->filterQueryAndFragment($parts['fragment']) : '';
			if (isset($parts['pass'])) {
				$this->userInfo .= ':' . $parts['pass'];
			}
		}
	}

	public function __toString()
	{
		return self::createUriString($this->scheme, $this->getAuthority(), $this->path, $this->query, $this->fragment);
	}

	public function getScheme()
	{
		return $this->scheme;
	}

	public function getAuthority()
	{
		if ('' === $this->host) {
			return '';
		}

		$authority = $this->host;
		if ('' !== $this->userInfo) {
			$authority = $this->userInfo . '@' . $authority;
		}

		if (null !== $this->port) {
			$authority .= ':' . $this->port;
		}

		return $authority;
	}

	public function getUserInfo()
	{
		return $this->userInfo;
	}

	public function getHost()
	{
		return $this->host;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getFragment()
	{
		return $this->fragment;
	}

	public function withScheme($scheme)
	{
		if (!\is_string($scheme)) {
			throw new \InvalidArgumentException('Scheme must be a string');
		}

		if ($this->scheme === $scheme = \strtr($scheme, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')) {
			return $this;
		}

		$new = clone $this;
		$new->scheme = $scheme;
		$new->port = $new->filterPort($new->port);

		return $new;
	}

	public function withUserInfo($user, $password = null)
	{
		$info = $user;
		if (null !== $password && '' !== $password) {
			$info .= ':' . $password;
		}

		if ($this->userInfo === $info) {
			return $this;
		}

		$new = clone $this;
		$new->userInfo = $info;

		return $new;
	}

	public function withHost($host)
	{
		if (!\is_string($host)) {
			throw new \InvalidArgumentException('Host must be a string');
		}

		if ($this->host === $host = \strtr($host, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')) {
			return $this;
		}

		$new = clone $this;
		$new->host = $host;

		return $new;
	}

	public function withPort($port)
	{
		if ($this->port === $port = $this->filterPort($port)) {
			return $this;
		}

		$new = clone $this;
		$new->port = $port;

		return $new;
	}

	public function withPath($path)
	{
		if ($this->path === $path = $this->filterPath($path)) {
			return $this;
		}

		$new = clone $this;
		$new->path = $path;

		return $new;
	}

	public function withQuery($query)
	{
		if ($this->query === $query = $this->filterQueryAndFragment($query)) {
			return $this;
		}

		$new = clone $this;
		$new->query = $query;

		return $new;
	}

	public function withFragment($fragment)
	{
		if ($this->fragment === $fragment = $this->filterQueryAndFragment($fragment)) {
			return $this;
		}

		$new = clone $this;
		$new->fragment = $fragment;

		return $new;
	}

	/**
	 * Create a URI string from its various parts.
	 */
	private static function createUriString($scheme, $authority, $path, $query, $fragment)
	{
		$uri = '';
		if ('' !== $scheme) {
			$uri .= $scheme . ':';
		}

		if ('' !== $authority) {
			$uri .= '//' . $authority;
		}

		if ('' !== $path) {
			if ('/' !== $path[0]) {
				if ('' !== $authority) {
					// If the path is rootless and an authority is present, the path MUST be prefixed by "/"
					$path = '/' . $path;
				}
			} elseif (isset($path[1]) && '/' === $path[1]) {
				if ('' === $authority) {
					// If the path is starting with more than one "/" and no authority is present, the
					// starting slashes MUST be reduced to one.
					$path = '/' . \ltrim($path, '/');
				}
			}

			$uri .= $path;
		}

		if ('' !== $query) {
			$uri .= '?' . $query;
		}

		if ('' !== $fragment) {
			$uri .= '#' . $fragment;
		}

		return $uri;
	}

	/**
	 * Is a given port non-standard for the current scheme?
	 */
	private static function isNonStandardPort($scheme, $port)
	{
		return !isset(self::$schemes[$scheme]) || $port !== self::$schemes[$scheme];
	}

	private function filterPort($port)
	{
		if (null === $port) {
			return null;
		}

		$port = (int) $port;
		if (0 > $port || 0xffff < $port) {
			throw new \InvalidArgumentException(\sprintf('Invalid port: %d. Must be between 0 and 65535', $port));
		}

		return self::isNonStandardPort($this->scheme, $port) ? $port : null;
	}

	private function filterPath($path)
	{
		if (!\is_string($path)) {
			throw new \InvalidArgumentException('Path must be a string');
		}

		return \preg_replace_callback('/(?:[^' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . '%:@\/]++|%(?![A-Fa-f0-9]{2}))/', [__CLASS__, 'rawurlencodeMatchZero'], $path);
	}

	private function filterQueryAndFragment($str)
	{
		if (!\is_string($str)) {
			throw new \InvalidArgumentException('Query and fragment must be a string');
		}

		return \preg_replace_callback('/(?:[^' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . '%:@\/\?]++|%(?![A-Fa-f0-9]{2}))/', [__CLASS__, 'rawurlencodeMatchZero'], $str);
	}

	private static function rawurlencodeMatchZero(array $match)
	{
		return \rawurlencode($match[0]);
	}

}
