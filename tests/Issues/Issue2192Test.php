<?php

namespace Issues;

class Issue2192Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPageNumberAliasesAreReplacedInBodyWithSubsetFont()
	{
		$mpdf = new \Mpdf\Mpdf(['default_font' => 'freemono']);
		$mpdf->SetCompression(false);

		$mpdf->WriteHTML('<p>{PAGENO} of {nbpg}</p><pagebreak /><p>{PAGENO} of {nb}</p>');

		$output = $mpdf->OutputBinaryData();

		$this->assertTrue($mpdf->fonts['freemono']['smp']);

		preg_match_all('/<([0-9A-F]+)> Tj/', $output, $matches);

		$this->assertSame(['1 of 2', '2 of 2'], array_map('hex2bin', $matches[1]));
	}

}
