<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue872Test extends \Mpdf\BaseMpdfTest
{

	public function testSetHeader()
	{
		$this->mpdf->SetHeader([
			'Not empty array'
		]);

		$this->mpdf->SetHeader([
			'Not empty array'
		], 'O');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
