<?php

namespace Issues;

use Mockery;

use Mpdf\Log\Context as LogContext;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use Psr\Log\NullLogger;

class Issue779Test extends \Mpdf\BaseMpdfTest
{

	public function testOffsetsInHtmlTable()
	{
		$logMock = Mockery::mock(NullLogger::class)->shouldIgnoreMissing();
		$logMock->shouldReceive('debug')->twice()
			->with('Possible non-wellformed HTML markup in a table', ['context' => LogContext::HTML_MARKUP]);

		$this->mpdf->setLogger($logMock);

		$html = '<html>
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
