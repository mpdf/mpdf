<?php

namespace Issues;

class Issue1925Test extends \Mpdf\BaseMpdfTest
{

	public function testNumberForInRowSpan()
	{
		$this->mpdf->WriteHTML('<table><tr><td colspan="9" rowspan=\"9\"></td></tr></table>');
		// $this->mpdf->WriteHTML('<table><tr><td colspan="9"></td></tr></table>');
	}
}
