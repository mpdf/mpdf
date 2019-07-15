<?php

namespace Mpdf;

use DirectoryIterator;

class Cache
{

	private $basePath;

	private $cleanupInterval;

	private $fileSystem;

	public function __construct($basePath, FileSystem $fileSystem, $cleanupInterval = 3600)
	{
		$this->basePath = $basePath;
		$this->fileSystem = $fileSystem;
		$this->cleanupInterval = $cleanupInterval;

		if (!$this->createBasePath($basePath)) {
			throw new \Mpdf\MpdfException(sprintf('Temporary files directory "%s" is not writable', $basePath));
		}
	}

	protected function createBasePath($basePath)
	{
		if (!$this->fileSystem->file_exists($basePath)) {
			if (!$this->createBasePath(dirname($basePath))) {
				return false;
			}

			if (!$this->createDirectory($basePath)) {
				return false;
			}
		}

		if (!$this->fileSystem->is_writable($basePath) || !$this->fileSystem->is_dir($basePath)) {
			return false;
		}

		return true;
	}

	protected function createDirectory($basePath)
	{
		if (!$this->fileSystem->mkdir($basePath)) {
			return false;
		}

		if (!$this->fileSystem->chmod($basePath, 0777)) {
			return false;
		}

		return true;
	}

	public function tempFilename($filename)
	{
		return $this->getFilePath($filename);
	}

	public function has($filename)
	{
		return $this->fileSystem->file_exists($this->getFilePath($filename));
	}

	public function load($filename)
	{
		return $this->fileSystem->file_get_contents($this->getFilePath($filename));
	}

	public function write($filename, $data)
	{
		$path = $this->getFilePath($filename);

		$this->fileSystem->file_put_contents($path, $data);

		return $path;
	}

	public function remove($filename)
	{
		return $this->fileSystem->unlink($this->getFilePath($filename));
	}

	public function clearOld()
	{
		$iterator = new DirectoryIterator($this->basePath);

		/** @var \DirectoryIterator $item */
		foreach ($iterator as $item) {
			if (!$item->isDot()
					&& $item->isFile()
					&& !$this->isDotFile($item)
					&& $this->isOld($item)) {
				$this->fileSystem->unlink($item->getPathname());
			}
		}
	}

	private function getFilePath($filename)
	{
		return $this->basePath . '/' . $filename;
	}

	private function isOld(DirectoryIterator $item)
	{
		return $item->getMTime() + $this->cleanupInterval < time();
	}

	public function isDotFile(DirectoryIterator $item)
	{
		return substr($item->getFilename(), 0, 1) === '.';
	}
}
