<?php

namespace Issues;

class Issue1194Test extends \PHPUnit\Framework\TestCase
{

	public function testHandelUnknownTextAlign()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<table><tbody><tr><td style="text-align: inherit">foo</td></tr></tbody></table>');
	}

}
