<?php

namespace Issues;

class Issue814Test extends \PHPUnit_Framework_TestCase
{

	public function testNoNoticeWithBorderColumns()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
		<columns column-count="2" />
		<div style="border-top-style:dotted; border-top-width:thin">Signature</div>
		');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
