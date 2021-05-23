<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1433Test extends \Mpdf\BaseMpdfTest
{

	public function testOtfArrayError()
	{
		$this->mpdf->WriteHTML('<!DOCTYPE html>
			<head>
			<title>Test</title>
			</head>
			<body>
			<htmlpageheader name="firstpageheader" style="display:none">
			</htmlpageheader>

			<htmlpagefooter name="firstpagefooter" style="display:none">
			</htmlpagefooter>

			<htmlpageheader name="otherpageheader" style="display:none">
			</htmlpageheader>

			<htmlpagefooter name="otherpagefooter" style="display:none">
				<div class="footer">{PAGENO}</div>
			</htmlpagefooter>
			<h3>Hello World</h3>
			</body>
			</html>
		');

		$string = $this->mpdf->Output('', 'S');

		preg_match_all('/%PDF-1.4/', $string, $matches);

		$this->assertArrayHasKey(0, $matches);
		$this->assertCount(1, $matches[0]);
	}

}
