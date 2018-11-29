<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue442Test extends \Mpdf\BaseMpdfTest
{

	public function testZeroContainingDiv()
	{
		$html = '<span style="font-size: 12pt; font-family: Arial; color: #000000; background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;">You have </span><span style="font-size: 12pt; font-family: Arial; color: #000000; background-color: transparent; font-weight: bold; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;">0</span><span style="font-size: 12pt; font-family: Arial; color: #000000; background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;"> public records. </span></div>';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml($html);

		$out = $this->mpdf->Output('', 'S');

		$pos = strpos($out, '(0) Tj ET Q');

		$this->assertGreaterThan(0, $pos);
	}

}
