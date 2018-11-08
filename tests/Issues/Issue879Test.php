<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue879Test extends \Mpdf\BaseMpdfTest
{
	public function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf([

		]);
	}

	public function testOtlPhpNotice()
	{
		$str = 'تجربة 5%';
		$this->mpdf->WriteHTML('<p>' . $str . '<br /></p>');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
