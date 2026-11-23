<?php

namespace Mpdf\Language;

use Mpdf\MpdfException;

class LanguageToFontRegistry implements LanguageToFontInterface
{
	/**
	 * @var LanguageToFontInterface[]
	 */
	private $register = [];

	public function __construct($classes = [])
	{
		$classes = is_array($classes) ? $classes : [$classes];

		foreach ($classes as $languageClass) {
			if (!$languageClass instanceof LanguageToFontInterface) {
				throw new MpdfException('The LanguageToFontRegistry only accepts classes that implement LanguageToFontInterface: ' . get_class($languageClass));
			}

			$this->add($languageClass);
		}
	}

	public function add(LanguageToFontInterface $class)
	{
		$this->register = [get_class($class) => $class] + $this->register;
	}

	public function remove($key)
	{
		if (!isset($this->register[$key])) {
			throw new MpdfException('Could not find language package in registry');
		}

		unset($this->register[$key]);
	}

	public function getAll()
	{
		return $this->register;
	}

	public function getLanguageOptions($mode, $adobeCJK)
	{
		foreach ($this->getAll() as $languageClass) {
			$font = $languageClass->getLanguageOptions($mode, $adobeCJK);
			if (!empty($font)) {
				return is_array($font) ? $font : [false, $font];
			}
		}

		return [false, ''];
	}
}
