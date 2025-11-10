<?php

namespace Issues;

class Issue529Test extends \Mpdf\BaseMpdfTest
{
	public function testTwoColumns()
	{
		$this->mpdf->WriteHTML('<columns column-count="2" />left<columnbreak />right');
		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
