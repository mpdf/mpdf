<?php


namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue1963Test extends BaseMpdfTest
{
	protected function set_up()
	{
		$this->mpdf = new Mpdf([
			'mode' => '-aCJK',
			'autoScriptToLang' => true,
			'autoLangToFont' => true,
			'default_font' => 'dejavusans',
		]);
	}

	public function testNoWarning()
	{
		$this->mpdf->WriteHTML('<p>न्</p>');
		$output = $this->mpdf->Output('my.pdf', Destination::STRING_RETURN);

		$this->assertStringStartsWith('%PDF-', $output);
	}
}
