<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue419Test extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf();
	}

	public function testCssImport()
	{
		$html = '<style>
			@import url("http://www.mysite.com/css/theme1.css?yoloswag");
			@import "http://www.mysite.com/css/theme2.css?yoloswag";
			@import url("http://www.mysite.com/css/theme3.css");
			@import "http://www.mysite.com/css/theme4.css";
			@import "//www.mysite.com/css/theme5.css"
		</style>';

		$this->mpdf->WriteHtml($html);
	}

}

