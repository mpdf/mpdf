<?php

namespace Mpdf\Fonts;

use Mpdf\FileSystem;

class FontFileFinder
{
    private $fileSystem;

	private $directories;

	public function __construct($directories, FileSystem $fileSystem)
	{
		$this->setDirectories($directories);
		$this->fileSystem = $fileSystem;
	}

	public function setDirectories($directories)
	{
		if (!is_array($directories)) {
			$directories = [$directories];
		}

		$this->directories = $directories;
	}

	public function findFontFile($name)
	{
		foreach ($this->directories as $directory) {
			$filename = $directory . '/' . $name;
			if ($this->fileSystem->file_exists($filename)) {
				return $filename;
			}
		}

		throw new \Mpdf\MpdfException(sprintf('Cannot find TTF TrueType font file "%s" in configured font directories.', $name));
	}
}
