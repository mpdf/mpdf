<?php

namespace Issues;

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

		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
