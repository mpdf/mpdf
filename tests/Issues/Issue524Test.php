<?php

namespace Issues;

class Issue524Test extends \Mpdf\BaseMpdfTest
{

	public function testImportantCssDefinition()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml('<a href="#" style="text-shadow: none !important;}">Test Link</a>');

		$out = $this->mpdf->OutputBinaryData();

		$this->assertStringStartsWith('%PDF-', $out);
	}

}
