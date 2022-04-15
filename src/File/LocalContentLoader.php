<?php

namespace Mpdf\File;

class LocalContentLoader implements \Mpdf\File\LocalContentLoaderInterface
{

	public function load($path)
	{
		return file_get_contents($path);
	}

}
