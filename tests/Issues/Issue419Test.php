<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue419Test extends \Mpdf\BaseMpdfTest
{

	public function testCssImport()
	{
		$html = '<style>
			@import url("http://localhost/css/theme1.css?yoloswag");
			@import "http://localhost/css/theme2.css?yoloswag";
			@import url("http://localhost/css/theme3.css");
			@import "http://localhost/css/theme4.css";
			@import "//localhost/css/theme5.css"
		</style>';

		$this->mpdf->WriteHtml($html);
	}

}
