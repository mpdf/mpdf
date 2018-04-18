<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue524Test extends \Mpdf\BaseMpdfTest
{

	public function testImportantCssDefinition()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml('<a href="#" style="text-shadow: none !important;}">Test Link</a>');

		$out = $this->mpdf->output('', 'S');

		$this->assertStringStartsWith('%PDF-', $out);
	}

}
