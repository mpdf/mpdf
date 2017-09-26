<?php

namespace Mpdf\Conversion;

class DecToCjkTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $converter;

	protected function setUp()
	{
		parent::setUp();

		$this->converter = new DecToCjk();
	}

	/**
	 * @dataProvider conversionProvider
	 *
	 * @param string $input
	 * @param string $output
	 */
	public function testConvert($input, $output)
	{
		$this->assertSame($output, $this->converter->convert($input));
	}

	public function conversionProvider()
	{
		return [
			[0, '〇'],
			[1, '一'],
			[22, '二二'],
			[158, '一五八'],
			[8456, '八四五六'],
			[11248, '一一二四八'],
			[14578, '一四五七八'],
			[18278, '一八二七八'],
			[18279, '一八二七九'],
		];
	}

}
