<?php

namespace Mpdf;

class AddFontTest extends \PHPUnit_Framework_TestCase
{

	private $mpdf;

	public function setup()
	{
		parent::setup();

		$this->mpdf = new Mpdf();
	}

	public function testAddFont()
	{
		$this->mpdf->addFont('sun-exta');
	}

	/**
	 * @expectedException Mpdf\MpdfException
	 * @expectedExceptionMessage Font "font" is not supported
	 */
	public function testAddUnsupportedFont()
	{
		$this->mpdf->addFont('font');
	}

}
