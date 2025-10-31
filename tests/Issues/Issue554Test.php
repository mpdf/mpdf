<?php

namespace Issues;


class Issue554Test extends \Mpdf\BaseMpdfTest
{

	public function testDollarInHeaderCoreFonts()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->setHtmlHeader('$123.45');

		$out = $this->mpdf->OutputBinaryData();

		$this->assertStringContainsString('$123.45', $out);
	}

}
