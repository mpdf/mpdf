<?php

namespace Mpdf\Utils;

class UtfStringTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @dataProvider hexadecimalReferenceProvider
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testHexadecimalCharacterReferencesAreConverted($input, $expected)
	{
		$this->assertSame($expected, UtfString::strcode2utf($input));
	}

	public function hexadecimalReferenceProvider()
	{
		return [
			['&#x41;', 'A'],
			['&#x20AC;', "\xE2\x82\xAC"],
			['&#x1F600;', "\xF0\x9F\x98\x80"],
			['&#x200000;', '?'],
			['&#x7FFFFFFFFFFFFFFF;', '?'],
			['&#x10000000000000000;', '?'],
			['&#xFFFFFFFFFFFFFFFFFFFFFFFF;', '?'],
		];
	}

}
