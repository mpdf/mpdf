<?php

namespace Issues;

class Issue626Test extends \PHPUnit_Framework_TestCase
{

	public function testOverWriteHandleAllStream()
	{
		$mpdf = new \Mpdf\Mpdf(['mode' => '-c']);
		$mpdf->SetImportUse();
		$mpdf->percentSubset = 0;

		$search = array(
			'MAIN HEADING'
		);

		$replacement = array(
			'replacement'
		);

		$output = $mpdf->OverWrite(__DIR__ . '/../data/pdfs/2-Page-PDF_1_4.pdf', $search, $replacement, 'S');
		
		$this->assertNotSame($output, file_get_contents(__DIR__ . '/../data/pdfs/2-Page-PDF_1_4.pdf'));
	}

}
