<?php

namespace Mpdf\Utils;

class StringsTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testIncrementalString()
	{
		$this->assertEquals('AB', Strings::incrementString('AA'));
		$this->assertEquals('AC', Strings::incrementString('AB'));
	}
}
