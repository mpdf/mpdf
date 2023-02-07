<?php

namespace Issues;

class Issue1817Test extends \Mpdf\BaseMpdfTest
{
	public function testDisableAutoScriptOnLangForElement()
	{
		$this->mpdf->autoScriptToLang = true;
		$this->mpdf->autoLangToFont = true;

		$this->mpdf->WriteHtml('
			<body>
				<table>
				<tr>
					<td lang="ja" disableAutoScriptToLang>ンターJA健康ー康</td>
				</tr>
				</table>
				<div>Hello 健康</div>
			</body>');

		$output = $this->mpdf->Output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
