<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PHPUnit\Framework\TestCase;

class Issue1526Test extends TestCase
{

	public function testDoNotThrowUndefinedOffsetError()
	{
		$pdf = new Mpdf();
		$pdf->AddPage();
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$pdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$result = $pdf->Output(null, Destination::STRING_RETURN);
		$this->assertNotNull($result);
	}

}
