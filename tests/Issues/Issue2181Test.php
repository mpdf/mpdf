<?php

namespace Issues;

class Issue2181Test extends \Mpdf\BaseMpdfTest
{

	public function testSettingWatermarkFontWithObject()
	{
		$this->mpdf->WriteHTML('<style>
			@page {
				sheet-size: A4-L;
			}
		</style>');

		$this->mpdf->OutputBinaryData();
	}

}
