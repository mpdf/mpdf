<?php

namespace Issues;

class Issue730Test extends \Mpdf\BaseMpdfTest
{

	public function testIncorrectLeftPaddingAfterFloat()
	{
		$this->mpdf->WriteHTML('
		<style>
			.label {
				float: left;
				width: 25%;
			}

			.value {
				margin-left: 35%;
				background: #EEE;
				padding: 2mm;
			}
		</style>

		<div class="label">Label</div>
		<div class="value">Value</div>
		');

		$this->assertMatchesRegularExpression('/q 0.000 g  0 Tr BT 226.773 780.129 Td/', $this->mpdf->pages[1]);
	}

}
