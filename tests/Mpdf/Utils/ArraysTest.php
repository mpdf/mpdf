<?php

namespace Mpdf\Utils;

class ArraysTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testAllCombinationsOfTwoItemsAreReturned()
	{
		$this->assertEquals(
			[
				['a', 'b'],
				['a', 'c'],
				['a', 'd'],
				['b', 'c'],
				['b', 'd'],
				['c', 'd'],
			],
			Arrays::combinations(['a', 'b', 'c', 'd'], 2)
		);
	}

	public function testAllCombinationsOfThreeItemsAreReturned()
	{
		$this->assertEquals(
			[
				['a', 'b', 'c'],
				['a', 'b', 'd'],
				['a', 'b', 'e'],
				['a', 'c', 'd'],
				['a', 'c', 'e'],
				['a', 'd', 'e'],
				['b', 'c', 'd'],
				['b', 'c', 'e'],
				['b', 'd', 'e'],
				['c', 'd', 'e'],
			],
			Arrays::combinations(['a', 'b', 'c', 'd', 'e'], 3)
		);
	}
}
