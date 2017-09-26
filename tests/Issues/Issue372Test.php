<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue372Test extends \Mpdf\BaseMpdfTest
{

	public function testWatermarkOnImport()
	{
		$this->mpdf->SetImportUse();
		$this->mpdf->SetSourceFile(__DIR__ . '/../data/pdfs/2-Page-PDF_1_4.pdf');
		$tplId = $this->mpdf->ImportPage(1);
		$this->mpdf->UseTemplate($tplId);
		$this->mpdf->SetWatermarkImage(__DIR__ . '/../data/img/bayeux2.jpg', 1, '', [160, 10]);
		$this->mpdf->showWatermarkImage = true;
		$output = $this->mpdf->Output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
