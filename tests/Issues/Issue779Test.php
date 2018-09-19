<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue779Test extends \Mpdf\BaseMpdfTest
{
	public function testOffsetsInHtmlTable()
	{
		$html = '<html lang="pt-br">
			<head></head>
			<body>
			<table style="page-break-after: always;">
			<tbody>
			<tr><td class="center" colspan="10" rowspan="3"></td>
			</tr>
			</tbody>
			</table>
			</body>
			</html>';

		$this->mpdf->WriteHTML($html);
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
