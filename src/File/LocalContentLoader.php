<?php

namespace MpdfAnalize\File;

class LocalContentLoader implements \MpdfAnalize\File\LocalContentLoaderInterface
{

	public function load($path)
	{
		return file_get_contents($path);
	}

}
