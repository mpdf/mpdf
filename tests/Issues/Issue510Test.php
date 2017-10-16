<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue510Test extends \Mpdf\BaseMpdfTest
{

	public function testCssCalc()
	{
		$html = '<!DOCTYPE html>
		<html>
		<head>
			<style media="all">
			#test {
				border: 1px solid #ccc;
				border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
				border-top-left-radius: 10px;
			}
			</style>
		</head>
		<body>
		<div id="test">test</div>
		</body>
		</html>';

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('', 'S');
	}

}
