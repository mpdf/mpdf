<?php

namespace Issues;

class Issue1526Test extends \Mpdf\BaseMpdfTest
{

	public function testDoNotThrowUndefinedOffsetError()
	{
		$this->mpdf->AddPage();

		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');
		$this->mpdf->MultiCell(4, 3, 'ISO 17 025 - Ensaios', 0, 'L');

		$result = $this->mpdf->OutputBinaryData();
		$this->assertNotNull($result);
	}

}
