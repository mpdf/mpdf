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
					<p>Test svg with double quoted class attribute value<br>
						<svg class="test test2" width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Test svg with double quoted class attribute value containing single quote and escaped double quote<br>
						<svg class="tes\'t test2" width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Test svg with single quoted class attribute<br>
						<svg class=\'test test2\' width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Test svg with single quoted class attribute value containing double quote and escaped single quote<br>
						<svg class=\'tes"t t"est2\' width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Test svg without class attribute<br>
						<svg width="100" height="100">
							<circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
						</svg>
					</p>
					<p>Unquoted attribute values in svgs are not working: This will fail</p>
				</body>
			</html>';

		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}
}
