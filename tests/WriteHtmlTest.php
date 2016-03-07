<?php

class WriteHtmlTests extends PHPUnit_Framework_TestCase
{
	private $mpdf;

	public function setup()
	{
		parent::setup();

		$this->mpdf = new mPDF();
	}

	/**
	 * Verify what types of variables are accepted to $mpdf->WriteHTML()
	 *
	 * @dataProvider providerCastType
	 *
	 * @param boolean $exception Whether we expect an exception or not
	 * @param mixed   $html      The variable to test
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
		return array(
			array(false, 'This is my string'),
			array(false, 20),
			array(false, 125.52),
			array(false, false),
			array(true, array('item', 'item2')),
			array(true, new WriteHtmlClass()),
			array(false, new WriteHtmlStringClass()),
			array(true, null),
			array(false, ''),
		);
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

