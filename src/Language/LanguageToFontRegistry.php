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
		foreach( $classes as $key => $class ) {
			$this->add($key, $class);
		}
	}

	/**
	 * Add a LanguageToFont Package
	 *
	 * @param string                  $name
	 * @param LanguageToFontInterface $class
	 */
	public function add($name, LanguageToFontInterface $class)
	{
		$this->register[$name] = $class;
	}

	/**
	 * Remove a anguageToFont Package by Name
	 *
	 * @param string $name
	 *
	 * @throws MpdfException
	 * @since 9.0
	 */
	public function remove($name)
	{
		if ( ! isset($this->register[$name])) {
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
		if ( ! isset($this->register[$name])) {
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
		$font         = '';
		$coreSuitable = false;

		foreach ($this->getAll() as $class) {
			$results = $class->getLanguageOptions($llcc, $adobeCJK);

			if (is_array($results)) {
				$font         = strlen($results[0]) > 0 ? $results[0] : $font;
				$coreSuitable = $results[1] === true ? true : false;
			} elseif (strlen($results) > 0) {
				$font = $results;
			}
		}

		return [$font, $coreSuitable];
	}
}