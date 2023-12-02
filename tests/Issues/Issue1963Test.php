<?php

namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue1963Test extends BaseMpdfTest
{
	public function testNoWarning()
	{
		$mpdf = new Mpdf([
			'mode' => '-aCJK',
			'autoScriptToLang' => true,
			'autoLangToFont' => true,
			'default_font' => 'dejavusans',
		]);

		$mpdf->WriteHTML('<p>न्</p>');
		$output = $mpdf->OutputBinaryData();

		$this->assertStringStartsWith('%PDF-', $output);
	}
}
