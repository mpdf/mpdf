<?php

namespace Issues;

class Issue2024Test extends \Mpdf\BaseMpdfTest
{

	public function testTranslateYParsing()
	{
		$mpdf = new \Mpdf\Mpdf();
		
		$mpdf->WriteHTML('<img src="../data/img/bayeux2.jpg" style="width: 20px; height: 20px; transform: scale(0.5,1) skew(45deg,-45deg) translatey(80mm)"></div>');
		
		$this->mpdf->Output('', 'S');
	}

}
