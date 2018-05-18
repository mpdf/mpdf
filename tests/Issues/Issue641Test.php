<?php

namespace Issues;

class Issue641Test extends \Mpdf\BaseMpdfTest
{

	public function testCountPagebreakWarning()
	{

		$html = 'Test

				<tocpagebreak paging="on" links="on" />
				
				<h1>Heading 1</h1>
				
				<h2>Heading 2</h2>
				
				<h2>Heading 2</h2>
				
				<h2>Heading 2</h2>
				
				<pagebreak/>
				
				<h1>Heading 1</h1>';

		$this->mpdf->h2toc = array('H1' => 0, 'H2' => 1);
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
