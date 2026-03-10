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

	public function testMergeRecursiveUnique_WithSimpleArrays()
	{
		$array1   = ['a' => 1, 'b' => 2];
		$array2   = ['b' => 3, 'c' => 4];
		$result   = Arrays::uniqueRecursiveMerge($array1, $array2);
		$expected = ['a' => 1, 'b' => 3, 'c' => 4];
		$this->assertEquals($expected, $result);
	}

	public function testMergeRecursiveUnique_WithNestedArrays()
	{
		$array1   = ['a' => ['x' => 1, 'y' => 2]];
		$array2   = ['a' => ['y' => 3, 'z' => 4]];
		$result   = Arrays::uniqueRecursiveMerge($array1, $array2);
		$expected = ['a' => ['x' => 1, 'y' => 3, 'z' => 4]];
		$this->assertEquals($expected, $result);
	}

	public function testMergeRecursiveUnique_WithIntegerKeys()
	{
		$array1 = [0 => 'a', 1 => 'b'];
		$array2 = [0 => 'c', 1 => 'd'];
		$result = Arrays::uniqueRecursiveMerge($array1, $array2);
		$this->assertCount(4, $result);
		$this->assertContains('a', $result);
		$this->assertContains('c', $result);
	}
}
