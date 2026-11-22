<?php

namespace Issues;

class Issue2008Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testSingleQuotedAttributeValueContainingDoubleQuotesIsPreserved()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<bookmark content=\'He said "hi"\' />Body');

		$this->assertSame('He said "hi"', $mpdf->BMoutlines[0]['t']);
	}

	public function testBarcodeCodeInSingleQuotesContainingDoubleQuotesIsNotTruncated()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML(
			'<barcode code=\'{"t":1,"d":5}\' type="qr"/>',
			\Mpdf\HTMLParserMode::DEFAULT_MODE,
			true,
			false
		);

		$textBuffer = '';
		foreach ($mpdf->textbuffer as $text) {
			$textBuffer .= $text[0];
		}

		$this->assertStringContainsString('s:4:"code";s:13:"{"t":1,"d":5}"', $textBuffer);
		$this->assertStringContainsString('s:5:"btype";s:2:"QR"', $textBuffer);
	}

}
