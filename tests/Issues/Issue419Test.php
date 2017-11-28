<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue419Test extends \Mpdf\BaseMpdfTest
{

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
