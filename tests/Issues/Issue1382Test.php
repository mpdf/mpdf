<?php

namespace Issues;

class Issue1382Test extends \Mpdf\BaseMpdfTest
{

	public function testSVGGetImageFunction()
	{

		$html = '<svg width="292px" height="83px" style="margin: 0 auto; display: block;" viewBox="0 0 292 83">
			<image xlink:href="var:my_image" width="292px" height="83px" />
		</svg>';

		$this->mpdf->setCompression(false);
		$this->mpdf->showImageErrors = true;
		$this->mpdf->imageVars['my_image'] = file_get_contents(__DIR__ . '/../data/img/bayeux2.jpg');
		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
