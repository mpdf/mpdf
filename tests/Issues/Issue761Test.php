<?php

namespace Issues;

class Issue761Test extends \Mpdf\BaseMpdfTest
{

	public function testFontSizeWithoutLeadingZero()
	{
		$this->mpdf->WriteHTML('<div style="font-size: .8cm">This text shall be big</div>');
		$this->mpdf->Close();
		$this->assertContains('/F1 22.677 Tf', $this->mpdf->pages[1]);
	}

}
