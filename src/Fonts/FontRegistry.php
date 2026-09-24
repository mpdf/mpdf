<?php

namespace Mpdf\Fonts;

use Mpdf\MpdfException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * FontRegistry constructor.
	 *
	 * @param FontRegistrationInterface[]|FontRegistrationInterface|null $classes
	 * @param string|null $composerLockPath
	 * @param LoggerInterface|null $logger
	 */
	public function __construct($classes = null, $composerLockPath = null, $logger = null)
	{
		$this->logger = $logger ?: new NullLogger();

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
			$composerLockPath = $this->findComposerLock(dirname(__DIR__));
		}

		if (!is_file($composerLockPath) || !is_readable($composerLockPath)) {
			/* Distributions that strip composer.lock get an empty registry, not a fatal */
			$this->logger->warning(sprintf('Composer lock file "%s" not found/readable; no font packages registered', $composerLockPath));

			return;
		}

		$this->autoloadFonts($composerLockPath);
	}

	/**
	 * Step up from $directory until a composer.lock is found
	 *
	 * dirname() is its own fixed point at the root - dirname('/') is '/', dirname('.') is '.' - so
	 * the walk stops when the parent stops changing rather than when it reaches any one name. The
	 * caller still checks the path it gets back; an installation with no lock file above it is given
	 * the candidate at the root, which does not exist, and falls through to an empty registry.
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	protected function findComposerLock($directory)
	{
		while (!is_file($directory . '/composer.lock')) {
			$parent = dirname($directory);

			if ($parent === $directory) {
				break;
			}

			$directory = $parent;
		}

		return $directory . '/composer.lock';
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
	 * Packages are read back most-recently-added first, and one already registered under the same
	 * id is replaced rather than duplicated.
	 *
	 * @param FontRegistrationInterface $class
	 */
	public function add(FontRegistrationInterface $class)
	{
		$this->register = [$class->getId() => $class] + $this->register;
	}

	/**
	 * Remove a Font Package by its id
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
