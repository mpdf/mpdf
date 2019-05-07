<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue993Test extends \Mpdf\BaseMpdfTest
{

	public function testFailingJPEG()
	{
		$data = file_get_contents(__DIR__ . '/../data/img/issue993.jpeg');
		$src = 'data:image/jpg;base64,'.base64_encode($data);
		$html = '<img src="'. $src .'"/>';

		$this->mpdf->showImageErrors = true;
		$this->mpdf->WriteHtml($html);

		$out = $this->mpdf->Output('', 'S');
	}
}
