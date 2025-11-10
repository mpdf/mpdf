<?php

namespace Issues;

class Issue520Test extends \Mpdf\BaseMpdfTest
{

	public function testDollarInFooterCoreFonts()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->setHtmlFooter('$123.45');

		$out = $this->mpdf->OutputBinaryData();

		$this->assertStringContainsString('$123.45', $out);
	}

}
