<?php

namespace Mpdf;

/**
 * A pass-through stream wrapper answering each read with at most 8192 bytes,
 * as PHP does for userspace and compressed streams (phar://, stream-wrapping test tools).
 */
class ChunkedReadStreamWrapper
{

	const PROTOCOL = 'mpdfchunked';

	/**
	 * @var resource|null
	 */
	public $context;

	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * @var bool
	 */
	private static $registered = false;

	public static function register()
	{
		if (!self::$registered) {
			stream_wrapper_register(self::PROTOCOL, __CLASS__);
			self::$registered = true;
		}
	}

	public static function url($path)
	{
		return self::PROTOCOL . '://' . $path;
	}

	private static function path($url)
	{
		return substr($url, strlen(self::PROTOCOL . '://'));
	}

	public function stream_open($path, $mode, $options, &$openedPath)
	{
		$this->handle = fopen(self::path($path), $mode);

		return $this->handle !== false;
	}

	public function stream_read($count)
	{
		return fread($this->handle, min($count, 8192));
	}

	public function stream_eof()
	{
		return feof($this->handle);
	}

	public function stream_seek($offset, $whence = SEEK_SET)
	{
		return fseek($this->handle, $offset, $whence) === 0;
	}

	public function stream_tell()
	{
		return ftell($this->handle);
	}

	public function stream_stat()
	{
		return fstat($this->handle);
	}

	public function url_stat($path, $flags)
	{
		$file = self::path($path);

		return file_exists($file) ? stat($file) : false;
	}

	public function stream_close()
	{
		fclose($this->handle);
	}

}
