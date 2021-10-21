<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue897Test extends \Mpdf\BaseMpdfTest
{

	public function testSetHeader()
	{
		$this->mpdf->WriteHTML('
		<style>
			.radio {
				color: green;
			}
		</style>

    	<input class="radio" type="radio" checked="checked"/>

    	<pagebreak/>

    	<input type="radio" checked="checked"/>
		');

		$this->mpdf->Close();

		$this->assertMatchesRegularExpression('/0.000 0.502 0.000 rg/', $this->mpdf->pages[1]);
		$this->assertMatchesRegularExpression('/0.000 0.502 0.000 RG/', $this->mpdf->pages[1]);

		$this->assertDoesNotMatchRegularExpression('/0.000 0.502 0.000 rg/', $this->mpdf->pages[2]);
		$this->assertDoesNotMatchRegularExpression('/0.000 0.502 0.000 RG/', $this->mpdf->pages[2]);
	}

}
