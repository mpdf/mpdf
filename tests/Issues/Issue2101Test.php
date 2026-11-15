<?php

namespace Issues;

class Issue2101Test extends \Mpdf\BaseMpdfTest
{

	public function testInvalidPageSizeThrows()
	{
		$this->expectException(\Mpdf\MpdfException::class);
		$this->expectExceptionMessage('Provided CSS page size results in zero or less');

		$this->mpdf->WriteHTML('<style>
			@page {
				size: 0mm;
				margin: 0;
			}
		</style>
		<table><tr><td>XXXXXXXXXXXXX</td></tr></table>');
	}
	
	public function testStandardizedPageSizeConverted()
	{
		$this->mpdf->WriteHTML('<style>
			@page {
				size: A4;
				margin: 0;
			}
		</style>
		<table><tr><td>XXXXXXXXXXXXX</td></tr></table>');
		
		$this->mpdf->WriteHTML('<style>
			@page {
				size: landscape;
				margin: 0;
			}
		</style>
		<table><tr><td>XXXXXXXXXXXXX</td></tr></table>');
	}
}
