<?php

namespace Mpdf\Fonts;

use Mpdf\MpdfException;

class FontRegistry
{
	/**
	 * @var FontRegistrationInterface[]
	 */
	protected $register = [];

	/**
	 * @var bool Whether to autoload the font aliases, backup subs, BMPonly, and font family substitution list
	 */
	protected $autoloadConfig = true;

	/**
	 * FontRegistry constructor.
	 *
	 * @param FontRegistrationInterface[]|FontRegistrationInterface|null $classes
	 */
	public function __construct($classes = null, $composerLockPath = null)
	{
		/* Manually load the font packages */
		if (!is_null($classes)) {
			$classes = is_array($classes) ? $classes : [$classes];
			foreach ($classes as $class) {
				$this->add($class);
			}

			return;
		}

		/* Automatically load the font packages from composer */
		if (is_null($composerLockPath)) {
			/* step up a directory until the lock file is found */
			$targetParent = dirname(__DIR__);
			while ($targetParent !== '.' && !is_file($targetParent . '/composer.lock')) {
				$targetParent = dirname($targetParent);
			}

			$composerLockPath = $targetParent . '/composer.lock';
		}

		if (!is_file($composerLockPath) || !is_readable($composerLockPath)) {
			throw new MpdfException('Composer lock file not found/readable');
		}

		$this->autoloadFonts($composerLockPath);
	}

	/**
	 * Parse the composer.lock file and autoload mPDF font packages
	 *
	 * @param string $composerLockPath
	 * @return void
	 * @throws MpdfException
	 */
	protected function autoloadFonts($composerLockPath)
	{
		$jsonData = @file_get_contents($composerLockPath);

		if (false === $jsonData) {
			throw new MpdfException($composerLockPath);
		}

		$data = json_decode($jsonData, true);
		$jsonError = json_last_error();
		if (JSON_ERROR_NONE !== $jsonError) {
			throw new MpdfException($composerLockPath, $jsonError);
		}

		$fontPackages = [];
		foreach (['packages', 'packages-dev'] as $composerKeys) {
			if (!isset($data[$composerKeys]) || !is_array($data[$composerKeys])) {
				continue;
			}

			foreach ($data[$composerKeys] as $packageData) {
				if (!isset($packageData['extra']['mpdf']['fonts']) || !class_exists($packageData['extra']['mpdf']['fonts'])) {
					continue;
				}

				$order = isset($packageData['extra']['mpdf']['fontOrder']) ? (int) $packageData['extra']['mpdf']['fontOrder'] : 0;

				$fontPackages[$order][] = $packageData['extra']['mpdf']['fonts'];
			}
		}

		ksort($fontPackages);
		foreach ($fontPackages as $packages) {
			foreach ($packages as $package) {
				$this->add(new $package());
			}
		}
	}

	/**
	 * Add a Font Package
	 *
	 * @param FontRegistrationInterface $class
	 */
	public function add(FontRegistrationInterface $class)
	{
		$this->register = [get_class($class) => $class] + $this->register;
	}

	/**
	 * Remove a Font Package by Name
	 *
	 * @param string $name
	 *
	 * @throws MpdfException
	 */
	public function remove($name)
	{
		if (!isset($this->register[$name])) {
			throw new MpdfException('Could not find font package in registry');
		}

		unset($this->register[$name]);
	}

	/**
	 * Get all registered Font Packages
	 *
	 * @return FontRegistrationInterface[]
	 */
	public function getAll()
	{
		return $this->register;
	}

	/**
	 * @param bool $autoloadConfig
	 * @return void
	 */
	public function setAutoloadConfigSetting($autoloadConfig)
	{
		$this->autoloadConfig = (bool) $autoloadConfig;
	}

	/**
	 * @return bool
	 */
	public function getAutoloadConfigSetting()
	{
		return $this->autoloadConfig;
	}
}
