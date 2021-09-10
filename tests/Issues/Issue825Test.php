<?php

namespace Issues;

class Issue825Test extends \Mpdf\BaseMpdfTest
{

	public function testLowercaseRomanPageNumbers()
	{
		$this->mpdf->setCompression(false);

		$this->mpdf->defaultPageNumStyle = 'i';

		$this->mpdf->setFooter('{PAGENO}');

		for ($i = 0; $i < 15; $i++) {
			$this->mpdf->AddPage();
		}

		$output = $this->mpdf->output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);

		$this->assertMatchesRegularExpression('/vii/', $output);
		$this->assertMatchesRegularExpression('/xii/', $output);
		$this->assertMatchesRegularExpression('/xiv/', $output);
	}

}
