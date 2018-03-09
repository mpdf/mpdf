<?php

namespace Issues;

class Issue677Test extends \Mpdf\BaseMpdfTest
{

	public function testFooterHeightNotices()
	{
		$this->mpdf->SetHTMLFooter('<div style="height:20mm;"></div>');
		$this->mpdf->WriteHTML('<div id="container">' . str_repeat('Item<br>', 60) . '</div>');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
