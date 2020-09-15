<?php

namespace Mpdf\Language;

use Mpdf\MpdfException;

class LanguageToFontRegistry implements LanguageToFontInterface
{
	/**
	 * @var LanguageToFontInterface[]
	 * @since 9.0
	 */
	private $register = [];

	/**
	 * LanguageToFontRegistry constructor.
	 *
	 * @param $classes
	 *
	 * @since 9.0
	 */
	public function __construct(array $classes)
	{
		foreach ($classes as $key => $class) {
			$this->add($key, $class);
		}
	}

	/**
	 * Add a LanguageToFont Package
	 *
	 * @param string $name
	 * @param LanguageToFontInterface $class
	 */
	public function add($name, LanguageToFontInterface $class)
	{
		$this->register[$name] = $class;
	}

	/**
	 * Remove a LanguageToFont Package by Name
	 *
	 * @param string $name
	 *
	 * @throws MpdfException
	 * @since 9.0
	 */
	public function remove($name)
	{
		if (!isset($this->register[$name])) {
			throw new MpdfException('Could not find LanguageToFont package in registry');
		}

		unset($this->register[$name]);
	}

	/**
	 * Get all registered Font Packages
	 *
	 * @return LanguageToFontInterface[]
	 * @since 9.0
	 */
	public function getAll()
	{
		return $this->register;
	}

	/**
	 * Get a LanguageToFont Package by Name
	 *
	 * @param string $name
	 *
	 * @return LanguageToFontInterface
	 * @throws MpdfException
	 * @since 9.0
	 */
	public function getByName($name)
	{
		if (!isset($this->register[$name])) {
			throw new MpdfException('Could not find LanguageToFont package in registry');
		}

		return $this->register[$name];
	}

	/**
	 * @param string $llcc
	 * @param string $adobeCJK
	 *
	 * @return array
	 * @since 9.0
	 */
	public function getLanguageOptions($llcc, $adobeCJK)
	{
		$coreSuitable = false;
		$fontName = '';

		foreach ($this->getAll() as $class) {
			$languageOptions = $class->getLanguageOptions($llcc, $adobeCJK);

			if (!is_array($languageOptions)) {
				$languageOptions = [$coreSuitable, $languageOptions];
			}

			$coreSuitable = $languageOptions[0] === true;
			$fontName = strlen($languageOptions[1]) > 0 ? $languageOptions[1] : $fontName;
		}

		return [$fontName, $coreSuitable];
	}
}