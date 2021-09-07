<?php

namespace Mpdf;

use Mpdf\Mpdf;

abstract class BaseMpdfTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	protected $mpdf;

	protected function setUp(): void
	{
		parent::setUp();

		$this->mpdf = new Mpdf(['mode' => 'c']);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->mpdf->cleanup();
	}

}
