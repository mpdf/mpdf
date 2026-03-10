<?php

namespace Issues;

class Issue1748Test extends \Mpdf\BaseMpdfTest
{

	public function testDeletingPages()
	{
		$this->mpdf->WriteHTML(str_repeat('abcde ', 4000));
		$this->mpdf->DeletePages(2);

		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
