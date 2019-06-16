<?php

namespace Issues;

use Mpdf\Output\Destination;
use setasign\Fpdi\PdfParser\StreamReader;

class Issue900Test extends \Mpdf\BaseMpdfTest
{
	public function testMergePdfWithLinks()
	{
		$this->mpdf->WriteHTML('<a href="https://example.org">My Link</a>');
		$output = StreamReader::createByString($this->mpdf->Output('', DESTINATION::STRING_RETURN));

		// Reset MPDF
		$this->mpdf = new \Mpdf\Mpdf(['mode' => 'c']);
		$this->mpdf->SetCompression(false);
		$this->mpdf->setSourceFile($output);

		$pageId = $this->mpdf->importPage(1);
		$this->mpdf->useTemplate($pageId);

		/* Standard layout */
		$this->assertCount(1, $this->mpdf->PageLinks[1]);
		$this->assertEquals(42, floor($this->mpdf->PageLinks[1][0][0]));
		$this->assertEquals(783, floor($this->mpdf->PageLinks[1][0][1]));

		/* Offset */
		$this->mpdf->AddPage();
		$this->mpdf->useTemplate($pageId, 50, 50);

		$this->assertCount(1, $this->mpdf->PageLinks[2]);
		$this->assertEquals(184, floor($this->mpdf->PageLinks[2][0][0]));
		$this->assertEquals(641, floor($this->mpdf->PageLinks[2][0][1]));

		/* Offset with alternate page size */
		$this->mpdf->AddPage();
		$this->mpdf->useTemplate($pageId, 50, 50, 150);

		$this->assertCount(1, $this->mpdf->PageLinks[3]);
		$this->assertEquals(172, floor($this->mpdf->PageLinks[3][0][0]));
		$this->assertEquals(658, floor($this->mpdf->PageLinks[3][0][1]));

		$this->mpdf->Output('test.pdf', Destination::FILE);
	}
}
