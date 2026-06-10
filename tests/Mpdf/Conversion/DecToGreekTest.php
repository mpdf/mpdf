<?php

namespace Mpdf\Conversion;

class DecToGreekTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Conversion\DecToGreek
	 */
	private $converter;

	protected function set_up()
	{
		parent::set_up();

		$this->converter = new DecToGreek();
	}

	/**
	 * @dataProvider conversionProvider
	 *
	 * @param int $input
	 * @param string $output
	 */
	public function testConvert($input, $output)
	{
		$this->assertSame($output, $this->converter->convert($input));
	}

	public function conversionProvider()
	{
		return [
			[-1, '-1'],
			[0, '0'],
			[1, 'α'],
			[2, 'β'],
			[17, 'ρ'],
			[18, 'σ'], // sigma, not final sigma (ς)
			[24, 'ω'],
			[25, 'αα'],
			[48, 'αω'],
			[49, 'βα'],
			[600, 'ωω'],
			[601, 'ααα'],
			[14424, 'ωωω'],
		];
	}

}
