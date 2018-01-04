<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue562Test extends \Mpdf\BaseMpdfTest
{

	public function testCountOtlWarning()
	{

		$html = '<!DOCTYPE html>
		<html>
			<head>
				<style>
					div, table {
					  font-size: 8.5pt;
					  font-family: "Arial";
					}
				</style>
			</head>
			<body>
			<div class="bill">
				<div class="header">
					<div class="logo">
						<img src="" />
					</div>
				</div>
			</div>
			</body>
		</html>';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}

}
