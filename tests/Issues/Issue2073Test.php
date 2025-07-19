<?php

namespace Issues;

class Issue2073Test extends \Mpdf\BaseMpdfTest
{

	public function testSvgAttributeStrokeDasharrayEmpty()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<!DOCTYPE html>
<body>	
	<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
		<line x1="0" y1="45" x2="100" y2="45" stroke="#000000" stroke-dasharray="" stroke-width="10"></line>
	</svg>	
</body>
</html>');

		$this->mpdf->Output('', 'S');
	}

}
