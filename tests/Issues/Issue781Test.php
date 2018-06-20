<?php

namespace Issues;

class Issue781Test extends \Mpdf\BaseMpdfTest
{

	public function testNoNoticeWhenTocPageBreakByArray()
	{
		$this->mpdf->TOCpagebreakByArray([]);
		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
