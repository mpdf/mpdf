<?php

namespace Mpdf\Conversion;

use Mockery;
use Mpdf\Mpdf;

class DecToOtherTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = Mockery::spy(Mpdf::class);
		$this->mpdf->CurrentFont = ['cw' => 0];

		$this->converter = new DecToOther(
			$this->mpdf
		);
	}

	/**
	 * @dataProvider conversionProvider
	 *
	 * @param string $input
	 * @param int $cp
	 * @param string $output
	 * @param bool $check
	 */
	public function testConvert($input, $cp, $output, $isDefined = true)
	{
		$this->mpdf->shouldReceive('_charDefined')->andReturn($isDefined);
		$this->assertSame($output, $this->converter->convert($input, $cp));
	}

	public function conversionProvider()
	{
		return [
			[1, 0x06F0, '۱'],
			[4, 0x06F0, '۴'],
			[5, 0x06F0, '۵'],
			[9, 0x0AE6, '૯'],
			[14, 0x0AE6, '૧૪'],
			[19, 0x0AE6, '૧૯'],
			[28, 0x0AE6, '૨૮'],
			[648, 0x0C66, '౬౪౮'],
			[649, 0x0C66, '౬౪౯'],
			[1582, 0x0C66, '౧౫౮౨'],
			[3999, 0x0C66, '౩౯౯౯'],
			[3999, 0x0C66, '3999', false],
		];
	}

	public function testGetCodePage()
	{
		$this->assertSame(0x09E6, $this->converter->getCodePage('bengali'));
		$this->assertSame(0x0D66, $this->converter->getCodePage('malayalam'));
		$this->assertSame(0x17E0, $this->converter->getCodePage('khmer'));
		$this->assertSame(0, $this->converter->getCodePage('unknown'));
	}

}
