<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1430Test extends \Mpdf\BaseMpdfTest
{

	public function testHTMLQuietZone()
	{
		$this->mpdf->WriteHTML('
		    <barcode code="1234" type="EAN128C" quiet_zone_left="0" quiet_zone_right="0" /><barcode code="5678" type="EAN128B" quiet_zone_left="0" quiet_zone_right="0" />
		');

		$string = $this->mpdf->Output('', 'S');

		preg_match_all('/%PDF-1.4/', $string, $matches);

		$this->assertArrayHasKey(0, $matches);
		$this->assertCount(1, $matches[0]);
	}

}
