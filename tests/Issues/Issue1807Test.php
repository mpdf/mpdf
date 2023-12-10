<?php

namespace Issues;

class Issue1807Test extends \Mpdf\BaseMpdfTest
{
	public function testJapaneseFontInWatermark()
	{
		$this->mpdf->autoScriptToLang = true;
		$this->mpdf->autoLangToFont = true;
		$this->mpdf->showWatermarkText = true;
		$this->mpdf->watermark_font = 'SJIS';

		$this->mpdf->WriteHtml('<html><body><watermarktext content="御 見 積 書" alpha="0.2" />Some content</body></html>');
		$out = $this->mpdf->Output('', 'S');
	}
}
