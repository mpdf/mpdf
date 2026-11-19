<?php

namespace Mpdf;

class DirectWriteTest extends BaseMpdfTest
{

	public function testJustifiedWriteWithCoreFont()
	{
		$this->mpdf->AddPage();
		$this->mpdf->SetFont('helvetica');
		$this->mpdf->Write(6, str_repeat('Justified text written directly across several lines. ', 10), 0, '', 'ltr', 'J');

		$this->assertStringContainsString(' Tw ET', $this->mpdf->pages[1]);
	}

	public function testJustifiedWriteWithUnicodeFont()
	{
		$mpdf = new Mpdf();
		$mpdf->AddPage();
		$mpdf->Write(6, str_repeat('Justified text written directly across several lines. ', 10), 0, '', 'ltr', 'J');

		$this->assertStringContainsString(' Tw ET', $mpdf->pages[1]);
	}

}
