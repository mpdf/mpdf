<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue539Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedGetImageFunction()
	{

		$html = '<svg width="100" height="100">
			<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
			<image xlink:href="someImage.jpg" x="0" y="0" height="50px" width="50px"/>
			</svg>';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}

}
