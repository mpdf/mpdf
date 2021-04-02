<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1404Test extends \Mpdf\BaseMpdfTest
{

	public function testRetainClassAttributeDuringSvg2Img()
	{
		$html = '<!doctype html>
			<html>
				<head>
				<style>
					.test {border: 1px solid red;}
				</style>
				</head>
				<body>
					<p>Test svg with class attribute<br>
						<svg class="test" width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Test svg without class attribute<br>
						<svg width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
				</body>
			</html>';

		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}

}
