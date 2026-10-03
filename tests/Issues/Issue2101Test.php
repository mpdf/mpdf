<?php

namespace Issues;

class Issue2101Test extends \Mpdf\BaseMpdfTest
{

	public function testInvalidPageSizeDoesNotCauseDivisionByZero()
	{
		$this->mpdf->WriteHTML('<style>
			@page {
				size: A4;
				margin: 0;
			}
		</style>
		<table><tr><td>XXXXXXXXXXXXX</td></tr></table>');

		$this->assertSame(210, (int) round($this->mpdf->pgwidth));

		$this->mpdf->OutputBinaryData();
	}

}
