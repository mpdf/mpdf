<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue779Test extends \Mpdf\BaseMpdfTest
{
	public function testOffsetsInHtmlTable()
	{
		$content = file_get_contents(__DIR__ . '/../data/html/issue779.html');
		$configs = [
			'mode' => 'c',
			'format' => 'A4',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_header' => 9,
			'margin_footer' => 9
		];

		$mpdf = new Mpdf($configs);

		$mpdf->SetTitle('issue779');
		$mpdf->WriteHTML($content);
		$output = $mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
