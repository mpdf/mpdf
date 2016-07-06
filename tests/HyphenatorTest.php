<?php

namespace Mpdf;

use Mockery;

class HyphenatorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Hyphenator
	 */
	private $hyphenator;

	protected function setUp()
	{
		parent::setUp();

		/** @var \Mpdf\Mpdf $mpdf */
		$mpdf = Mockery::mock('Mpdf\Mpdf');

		$mpdf->hyphenationDictionaryFile = __DIR__ . '/../data/patterns/dictionary.txt';

		$mpdf->debug = false;
		$mpdf->usingCoreFont = false;

		$mpdf->SHYlanguages = ['en'];
		$mpdf->SHYlang = 'en';
		$mpdf->SHYleftmin = 2;
		$mpdf->SHYrightmin = 2;
		$mpdf->SHYcharmin = 2;
		$mpdf->SHYcharmax = 10;

		$this->hyphenator = new Hyphenator($mpdf);
	}

	/**
	 * @dataProvider wordsProvider
	 *
	 * @param string $input
	 * @param int $ptr
	 * @param string $output
	 */
	public function testHyphenation($input, $ptr, $output)
	{
		$this->assertSame($output, $this->hyphenator->hyphenateWord($input, $ptr));
	}

	public function wordsProvider()
	{
		return [
			['disestablishmentarianism', 4, 3],
			['disestablishmentarianism', 50, 21],
			['capabilities', 5, 4],
			['animation', 5, 5],
		];
	}

}
