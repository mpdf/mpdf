<?php

namespace Mpdf\Fonts;

use Mpdf\MpdfException;

class FontRegistry
{
	/**
	 * @var FontRegistrationInterface[]
	 * @since 9.0
	 */
	private $register = [];

	/**
	 * FontRegistry constructor.
	 *
	 * @param FontRegistrationInterface[]|FontRegistrationInterface $classes
	 * @since 9.0
	 */
	public function __construct($classes)
	{
		$classes = is_array($classes) ? $classes : [$classes];

		foreach ($classes as $class) {
			$this->add($class);
		}
	}

	/**
	 * Add a Font Package
	 *
	 * @param FontRegistrationInterface $class
	 * @since 9.0
	 */
	public function add(FontRegistrationInterface $class)
	{
		$this->register[$class->getName()] = $class;
	}

	/**
	 * Remove a Font Package by Name
	 *
	 * @param string $name
	 *
	 * @throws MpdfException
	 * @since 9.0
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
	 * @since 9.0
	 */
	public function getAll()
	{
		return $this->register;
	}

	/**
	 * Get a Font Package by Name
	 *
	 * @param string $name
	 *
	 * @return FontRegistrationInterface
	 * @throws MpdfException
	 * @since 9.0
	 */
	public function getByName($name)
	{
		if (!isset($this->register[$name])) {
			throw new MpdfException('Could not find font package in registry');
		}

		return $this->register[$name];
	}
}