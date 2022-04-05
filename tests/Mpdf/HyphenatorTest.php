<?php

namespace Mpdf;

use Mockery;

class HyphenatorTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Hyphenator
	 */
	private $hyphenator;

	protected function set_up()
	{
		parent::set_up();

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

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
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
			['http://yoursite.com', 5, -1],
			['https://yoursite.com', 5, -1],
			['www.yoursite.com', 5, -1],
			['name@mail.com', 5, -1],
			['first-name.last_name+suffix@mail.co.uk', 5, -1],
		];
	}

}
