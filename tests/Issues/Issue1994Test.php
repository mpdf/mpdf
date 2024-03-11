<?php

namespace Issues;

class Issue1994Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedGetImageFunction()
	{

		$html = '<html lang="de">
		<head>
			<style type="text/css">.fakeinput { border: 1px solid lightgrey; }</style>
		</head>
		<body>
			<table>
				<tr>
					<td>
						<div>
							KV mit Prognose: <span class="fakeinput bold">0</span>
						</div>
					</td>
				</tr>
			</table>
		</body>
		</html>';

		$this->mpdf->WriteHTML($html);

		$this->mpdf->Output('', 'S');
	}

}
