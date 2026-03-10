<?php

namespace Issues;

class Issue637Test extends \Mpdf\BaseMpdfTest
{

	public function testEnableImportWithNoPdfImported()
	{
		$this->mpdf->enableImports = true;
		$this->mpdf->WriteHTML('<div>Content</div>');
		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
