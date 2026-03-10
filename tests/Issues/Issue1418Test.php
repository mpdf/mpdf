<?php

namespace Issues;

class Issue1418Test extends \Mpdf\BaseMpdfTest
{

	public function testNoLangMetadata()
	{
		$this->mpdf->setCompression(false);

		$output = $this->mpdf->OutputBinaryData();

		$this->assertStringNotContainsString('/Lang', $output);
	}

	public function testUsLangMetadata()
	{
		$mpdf = new \Mpdf\Mpdf(['mode' => 'en_US']);
		$mpdf->setCompression(false);

		$output = $mpdf->OutputBinaryData();

		$this->assertStringContainsString('/Lang (en_US)', $output);
	}

	public function testCsLangMetadata()
	{
		$mpdf = new \Mpdf\Mpdf(['mode' => 'cs_CZ']);
		$mpdf->setCompression(false);

		$output = $mpdf->OutputBinaryData();

		$this->assertStringContainsString('/Lang (cs_CZ)', $output);
	}

	public function testAcjkMode()
	{
		$mpdf = new \Mpdf\Mpdf(['mode' => '+aCJK']);
		$mpdf->setCompression(false);

		$output = $mpdf->OutputBinaryData();

		$this->assertStringNotContainsString('/Lang', $output);
	}

}
