<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue557Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedDomIndex()
	{

		$html = '<style>
			table {border-collapse: collapse;}
			table tbody td {
				border-right: 1px solid #ccc;
				border-bottom: 1px solid #ccc;
			}
			table thead th {
				border-bottom: 1px solid #333;
			}
			.warning {
				border: 1px solid #cc9933;
				background-color: #ffffcc !important;
			}
		</style>
		<table>
			<thead>
				<tr>
					<th>Contact Person</th>
				</tr>
			</thead>
			<tbody>
				<tr class="warning">
					<td>John Doe</td>
				</tr>
			</tbody>
		</table>';

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}

}
