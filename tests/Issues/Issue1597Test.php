<?php

namespace Issues;

class Issue1597Test extends \Mpdf\BaseMpdfTest
{

	public function testListNumberingPageBreakAvoid()
	{
		$this->mpdf->WriteHTML('<table><tr><td colspan=\"9\"></td></tr></table>');
		// $this->mpdf->WriteHTML('<table><tr><td colspan="9"></td></tr></table>');
	}
}
