<?php

namespace Issues;

class Issue1134Test extends \PHPUnit_Framework_TestCase
{

	public function testBorderDetailsDefaultValue()
	{
		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<table><tbody><tr><td style="border-left-color: rgb(207, 207, 207);">test</td></tr></tbody></table>
		<table><tbody><tr><td style="border-left-color: rgb(207, 207, 207);">test</td></tr></tbody></table>
		';

		$mpdf->WriteHTML($html);
		$output = $mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}

}
