<?php

namespace Mpdf;

use Mpdf\Mpdf;

abstract class BaseMpdfTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	protected $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf(['mode' => 'c']);
	}

	protected function tear_down()
	{
		parent::tear_down();

		$this->mpdf->cleanup();
	}

}
