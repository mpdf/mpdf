<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue339Test extends \Mpdf\BaseMpdfTest
{

	public function testListNumberingPageBreakAvoid()
	{

		$body = '<ol><li>1</li><li>2</li><li>3</li><li>4</li></ol>

		<table>
			<tr>
				<td><ol><li>1</li><li>2</li><li>3</li><li>4</li></ol></td>
			</tr>
		</table>

		';
		$style = 'li { page-break-inside: avoid; }';

		$this->mpdf->setCompression(false);

		$this->mpdf->WriteHTML($style, 1);
		$this->mpdf->WriteHTML($body, 2);

		$pdf = $this->mpdf->Output(null, 'S');

		$this->assertStringContainsString('(1.)', $pdf);
		$this->assertStringContainsString('(4.)', $pdf);
		$this->assertStringNotContainsString('(6.)', $pdf);
		$this->assertStringNotContainsString('(8.)', $pdf);
	}
}
