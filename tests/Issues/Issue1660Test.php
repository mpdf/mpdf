<?php

namespace Issues;

class Issue1660Test extends \Mpdf\BaseMpdfTest
{
	public function testTwoColumnSpill()
	{
		$mpdf = new \Mpdf\Mpdf([ 'mode' => 'c', 'format' => 'Letter', 'margin_top' => 135, 'margin_bottom' => 135, 'margin_header' => 0, 'margin_footer' => 0 ]);

		$mpdf->SetColumns(2);

		$mpdf->WriteHTML('<html><body><table><tr><td>X</td></tr><tr><td>X</td></tr><tr><td>X</td></tr></table></body></html>');

		$output = $mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}
}
