<?php

namespace Mpdf\Language;

use Mockery;

class LanguageToFontTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	public function testExtendedImplimentation()
	{
		$language = new LanguageToFontTestImplimentation();
		$impliments = class_implements($language);

		$this->assertArrayHasKey('Mpdf\Language\LanguageToFontInterface', $impliments);
	}

	/**
	 * @param string $llcc The language string being passed in
	 * @param boolean $adobeCJK The adobeCJK value
	 * @param boolean $coreSuitable The coreSuitable value
	 * @param string $returnedFont The expected font being returned
	 *
	 * @dataProvider providerOverrideFont
	 */
	public function testOverrideFont($llcc, $adobeCJK, $coreSuitable, $returnedFont)
	{
		$language = new LanguageToFontTestImplimentation();

		$results = $language->getLanguageOptions($llcc, $adobeCJK);
		$this->assertEquals($coreSuitable, $results[0]);
		$this->assertEquals($returnedFont, $results[1]);
	}

	public function providerOverrideFont()
	{
		return [
			['fake', false, false, 'fake-font'],
			['und-fake', false, false, 'fake-font-script'],
			['en', false, true, ''],
			['und-latn', false, false, 'dejavusanscondensed'],
			['und-latn', false, false, 'dejavusanscondensed'],
			['zh', false, false, 'sun-exta'],
			['zh', true, false, 'gb'],
			['zh-HK', true, false, 'big5'],
			['und-kali', false, false, 'freemono'],
		];
	}
}
