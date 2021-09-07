<?php

namespace Mpdf\Conversion;

class DecToAlphaTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function set_up()
	{
		parent::set_up();

		$this->converter = new DecToAlpha();
	}

	/**
	 * @dataProvider conversionProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testConvert($input, $output, $toUpper = true)
	{
		$this->assertSame($output, $this->converter->convert($input, $toUpper));
	}

	public function conversionProvider()
	{
		return [
			[0, '?'],
			[1, 'A'],
			[1, 'a', false],
			[22, 'V'],
			[158, 'FB'],
			[8456, 'lmf', false],
			[11248, 'PPP'],
			[14578, 'UNR'],
			[18278, 'ZZZ'],
			[18279, '?'],
		];
	}

}
