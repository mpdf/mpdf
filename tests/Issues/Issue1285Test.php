<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1285Test extends \Mpdf\BaseMpdfTest
{

	public function testNoUndefinedIndex()
	{
		$this->mpdf = new Mpdf();

		$html = base64_decode("PGh0bWw+CiAgIDxib2R5PgogICAgICAg2KfYqeKArNiMIAogICA8L2JvZHk+CjwvaHRtbD4=");

		$this->mpdf->WriteHtml($html, 2);
		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
