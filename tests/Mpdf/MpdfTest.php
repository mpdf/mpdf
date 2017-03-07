<?php

namespace Mpdf;

class MpdfTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		parent::setUp();

		$this->mpdf = new Mpdf();
	}

	public function testPdfOutput()
	{
		$this->mpdf->WriteHTML('<html><body>
			<h1>Test</h1>
		</body></html>');

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testDefaultSettings()
	{
		$mpdf = new Mpdf();

		$this->assertSame('1.4', $mpdf->pdf_version);
		$this->assertSame(2000, $mpdf->maxTTFFilesize);
		$this->assertFalse($mpdf->autoPadding);
	}

	public function testOverwrittenSettings()
	{
		$mpdf = new Mpdf([
			'pdf_version' => '1.5',
			'autoPadding' => true,
			'nonexisting_key' => true,
		]);

		$this->assertSame('1.5', $mpdf->pdf_version);
		$this->assertTrue($mpdf->autoPadding);
		$this->assertFalse(property_exists($mpdf, 'nonexisting_key'));
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage The HTML code size is larger than pcre.backtrack_limit
	 */
	public function testAdjustHtmlTooLargeHtml()
	{
		$this->mpdf->AdjustHTML(str_repeat('a', ini_get('pcre.backtrack_limit') + 1));
	}

	public function testPdfAssociatedFiles()
	{
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
		$this->mpdf->SetAssociatedFiles([[
			'name' => 'public_filename.xml',
			'mime' => 'text/xml',
			'description' => 'some description',
			'AFRelationship' => 'Alternative',
			'path' => __DIR__ . '/../data/xml/test.xml'
		]]);

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(NULL, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertRegExp('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertRegExp('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
	}

}
