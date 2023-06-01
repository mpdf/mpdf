<?php

namespace MpdfAnalize\File;

interface LocalContentLoaderInterface
{

	/**
	 * @return string|null
	 */
	public function load($path);

}
