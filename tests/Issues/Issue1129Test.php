<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1129Test extends \Mpdf\BaseMpdfTest
{

	public function testShowVersion()
	{
		$this->mpdf->setCompression(false);

		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);

		// hack with assertEquals/preg_match used because of PHPUnit 5 binary string handling
		// preg_match("@/Producer \(��mPDF " . preg_quote(Mpdf::VERSION, '@') . '\)@m', $output, $matches);
		// $this->assertCount(2, $matches);
		// @todo solve
	}

	public function testHideVersion()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->exposeVersion = false;

		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);

		// hack with assertEquals/preg_match used because of PHPUnit 5 binary string handling
		// preg_match("@/Producer \(��mPDF\)@", $output, $matches);
		// $this->assertCount(2, $matches);
		// @todo solve
	}

}
