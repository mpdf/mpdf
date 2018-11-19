<?php

namespace Issues;

class Issue645Test extends \Mpdf\BaseMpdfTest
{

	public function testFixSelfClosingTag()
	{
		$this->mpdf->WriteHTML('
		Page 1
		
		<pagebreak />
		
		Page 2
		
		<pagebreak/>
		
		Page 3
		
		<pagebreak>
		
		Page 4
		');

		$this->mpdf->Close();

		$this->assertCount(4, $this->mpdf->pages);
	}

}
