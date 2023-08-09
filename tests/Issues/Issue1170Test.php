<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue1170Test extends \Mpdf\BaseMpdfTest
{
	public function testFooterLinksWithPortraitAndLandscapePages()
	{
		$this->mpdf->SetHTMLFooter('<a href="https://example.org">Footer Link</a>');
		$this->mpdf->WriteHTML('Page 1 portrait');
		$this->mpdf->AddPage('L');
		$this->mpdf->WriteHTML('Page 2 Landscape');

		$this->assertCount(0, $this->mpdf->PageLinks);

		$this->mpdf->Output('', DESTINATION::STRING_RETURN);

		$this->assertCount(2, $this->mpdf->PageLinks);
		$this->assertCount(1, $this->mpdf->PageLinks[1]);
		$this->assertCount(5, $this->mpdf->PageLinks[1][0]);
		$this->assertEquals(42, floor($this->mpdf->PageLinks[1][0][0]));
		$this->assertEquals(38, floor($this->mpdf->PageLinks[1][0][1]));
		$this->assertEquals(52, floor($this->mpdf->PageLinks[1][0][2]));
		$this->assertEquals(12, floor($this->mpdf->PageLinks[1][0][3]));
		$this->assertEquals('https://example.org', $this->mpdf->PageLinks[1][0][4]);
		$this->assertCount(1, $this->mpdf->PageLinks[2]);
		$this->assertCount(5, $this->mpdf->PageLinks[2][0]);
		$this->assertEquals(42, floor($this->mpdf->PageLinks[2][0][0]));
		$this->assertEquals(38, floor($this->mpdf->PageLinks[2][0][1]));
		$this->assertEquals(52, floor($this->mpdf->PageLinks[2][0][2]));
		$this->assertEquals(12, floor($this->mpdf->PageLinks[2][0][3]));
		$this->assertEquals('https://example.org', $this->mpdf->PageLinks[2][0][4]);
	}
}
