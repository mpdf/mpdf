<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue1928Test extends \Mpdf\BaseMpdfTest
{
	public function testPNG()
	{
		$this->mpdf->WriteHTML('<html><head></head><body><img src="' . __DIR__ . '/../data/img/Issue1928Test.png" /></body></html>');
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		file_put_contents(__DIR__."/../data/pdfs/Issue1928Test.pdf", $output);
	}
}
