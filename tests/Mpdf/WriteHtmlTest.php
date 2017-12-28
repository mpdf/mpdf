<?php

namespace Mpdf;

class WriteHtmlTest extends \PHPUnit_Framework_TestCase
{

	private $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf();
	}

	/**
	 * Verify what types of variables are accepted to $mpdf->WriteHTML()
	 *
	 * @dataProvider providerCastType
	 *
	 * @param boolean $exception Whether we expect an exception or not
	 * @param mixed $html The variable to test
	 */
	public function testCastType($exception, $html)
	{
		$thrown = '';

		try {
			$this->mpdf->WriteHTML($html);
		} catch (MpdfException $e) {
			$thrown = $e->getMessage();
		}

		if ($exception) {
			$this->assertEquals('WriteHTML() requires $html be an integer, float, string, boolean or an object with the __toString() magic method.', $thrown);
		} else {
			$this->assertEquals('', $thrown);
		}
	}

	/**
	 * @return array
	 */
	public function providerCastType()
	{
		return [
			[false, 'This is my string'],
			[false, 20],
			[false, 125.52],
			[false, false],
			[true, ['item', 'item2']],
			[true, new WriteHtmlClass()],
			[false, new WriteHtmlStringClass()],
			[true, null],
			[false, ''],
		];
	}

}

class WriteHtmlClass
{

}

class WriteHtmlStringClass
{
	public function __toString()
	{
		return 'special';
	}
}

