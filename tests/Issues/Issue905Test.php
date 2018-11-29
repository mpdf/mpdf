<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue905Test extends \Mpdf\BaseMpdfTest
{

	public function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf([
			'biDirectional' => true,
		]);
	}

	public function testOtlPhpNotice()
	{
		$html = '<div class="tick" style="font-family: DejavuSansCondensed; font-size: 20pt; line-height: 20pt">&#10004;</div> &nbsp;';
		$this->mpdf->WriteFixedPosHTML($html, 10, 10, 5, 5, 'visible');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
