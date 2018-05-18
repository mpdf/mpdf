<?php

namespace Issues;

class Issue665Test extends \Mpdf\BaseMpdfTest
{

	public function testMultipleTocPageBreak()
	{

		$html = '
		<tocpagebreak links="on" name="first" />
		<tocpagebreak links="on" name="second" />

		<tocentry content="Heading 1" name="first" />
		<tocentry content="Alternate 1" name="second" />';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
