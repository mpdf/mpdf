<?php

namespace Issues;

class Issue635Test extends \Mpdf\BaseMpdfTest
{

	public function testBorderRadiusUndefinedNotice()
	{
		$this->mpdf->autoPadding = true;
		$html = '<div>Block</div>';
		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
