<?php

namespace Issues;

use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;

class Issue1561Test extends TestCase
{
	public function testDoNotThrowUndefinedOffsetError()
	{
		$pdf = new Mpdf();
		$pdf->autoScriptToLang = true;
		$pdf->autoLangToFont = true;
		$pdf->WriteHTML('✿્᭄͜͡‍');
	}
}
