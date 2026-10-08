<?php

namespace Mpdf\Language;

use Mpdf\Fonts\FontRegistry;

/**
 * The font a language or script resolves to, with the font packages installed.
 *
 * LanguageToFontRegistry returns the first non-empty answer across the registered packages, so a package
 * that answers for a script it does not own wins on registration order alone. These are the mappings the
 * AutoFont snapshot depends on.
 */
class LanguageToFontPackagesTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var LanguageToFontRegistry
	 */
	private $registry;

	protected function set_up()
	{
		parent::set_up();

		$this->registry = new LanguageToFontRegistry();

		foreach ((new FontRegistry())->getAll() as $package) {
			$languageToFont = $package->getLanguageToFont();
			if ($languageToFont) {
				$this->registry->add($languageToFont);
			}
		}
	}

	/**
	 * @param string $tag
	 * @param string $expected
	 *
	 * @dataProvider providerLanguageFonts
	 */
	public function testLanguageResolvesToFont($tag, $expected)
	{
		list($coreSuitable, $font) = $this->registry->getLanguageOptions($tag, false);

		$this->assertSame($expected, $font);
	}

	public function providerLanguageFonts()
	{
		return [
			/* Scripts, as autoScriptToLang passes them when the text carries no language */
			'Latin script' => ['und-Latn', 'dejavusanscondensed'],
			'Cyrillic script' => ['und-Cyrl', 'dejavusanscondensed'],
			'Braille script' => ['und-Brai', 'dejavusans'],
			'Ogham script' => ['und-Ogam', 'dejavusans'],
			'Runic script' => ['und-Runr', 'sun-exta'],
			'Tifinagh script' => ['und-Tfng', 'dejavusans'],
			'Glagolitic script' => ['und-Glag', 'mph2bdamase'],
			'Coptic script' => ['cop', 'quivira'],

			/* Languages */
			'Bulgarian' => ['bg', 'dejavusanscondensed'],
			'Russian' => ['ru', 'dejavusanscondensed'],
			'Greek' => ['el', 'dejavusanscondensed'],
			'Vietnamese' => ['vi', 'dejavusanscondensed'],
			'Armenian' => ['hy', 'dejavusans'],
			'Georgian' => ['ka', 'dejavusans'],
			'Vai' => ['vai', 'freesans'],
			'Hindi' => ['hi', 'freeserif'],
		];
	}
}
