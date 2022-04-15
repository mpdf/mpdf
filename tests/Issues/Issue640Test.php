<?php

namespace Issues;

class Issue640Test extends \Mpdf\BaseMpdfTest
{

	public function testBottomMargin()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				footer: html_myFooter1;
				margin-footer: 0;
			}

			#bottom {
			height: 25mm;
			background:red;
			}
		</style>

		<htmlpagefooter name="myFooter1">
			<div id="bottom">test</div>
		</htmlpagefooter>');

		$this->mpdf->Close();

		$this->assertMatchesRegularExpression('/1.0000 0.0000 0.0000 1.0000 0.0000 -725.6979 cm/', $this->mpdf->pages[1]);
	}

}
