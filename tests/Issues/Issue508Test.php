<?php

namespace Issues;

class Issue508Test extends \Mpdf\BaseMpdfTest
{

	public function testHrInTable()
	{
		$html = '<table><hr></table>';

		$this->mpdf->ignore_table_percents = false;

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml($html);

		$out = $this->mpdf->OutputBinaryData();
		$this->assertGreaterThan(0, strlen($out));
	}

}
