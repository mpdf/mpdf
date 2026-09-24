<?php

namespace Mpdf\Fonts\Fixtures;

use Mpdf\Fonts\FontRegistry;

/**
 * Reaches the protected search the constructor uses when it is given no lock file path
 */
class ExposedFontRegistry extends FontRegistry
{
	/**
	 * @param string $directory
	 *
	 * @return string
	 */
	public function search($directory)
	{
		return $this->findComposerLock($directory);
	}
}
