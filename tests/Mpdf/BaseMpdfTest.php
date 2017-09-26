<?php

namespace Mpdf;

use Mpdf\Mpdf;

abstract class BaseMpdfTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	protected $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf(['mode' => 'c']);
	}

	protected function tearDown()
	{
		parent::tearDown();

		$this->mpdf->cleanup();
	}

}
