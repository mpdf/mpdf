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
			<p><img src="//localhost/image.jpg"></a></p>
		</body></html>');

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage The HTML code size is larger than pcre.backtrack_limit
	 */
	public function testAdjustHtmlTooLargeHtml()
	{
		$this->mpdf->AdjustHTML(str_repeat('a', ini_get('pcre.backtrack_limit') + 1));
	}

	public function testPdfAssociatedFilesPath()
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
		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertRegExp('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n\/UF \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertRegExp('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
	}

	public function testPdfAssociatedFilesContent()
	{
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
		$this->mpdf->SetAssociatedFiles([[
			'name' => 'public_filename.xml',
			'mime' => 'text/xml',
			'description' => 'some description',
			'AFRelationship' => 'Alternative',
			'content' => '<?xml version="1.0" encoding="UTF-8"?><note><body>Hello World</body></note>'
		]]);

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertRegExp('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n\/UF \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertRegExp('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
	}

	public function testPdfAdditionalXmpRdf()
	{
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
		$this->mpdf->SetAdditionalXmpRdf($this->ZugferdXmpRdf());

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertRegExp('/<zf:DocumentFileName>ZUGFeRD-invoice\.xml<\/zf:DocumentFileName>/', $output);
	}

	private function ZugferdXmpRdf()
	{
		$s  = '<rdf:Description rdf:about="" xmlns:zf="urn:ferd:pdfa:CrossIndustryDocument:invoice:1p0#">'."\n";
		$s .= '  <zf:DocumentType>INVOICE</zf:DocumentType>'."\n";
		$s .= '  <zf:DocumentFileName>ZUGFeRD-invoice.xml</zf:DocumentFileName>'."\n";
		$s .= '  <zf:Version>1.0</zf:Version>'."\n";
		$s .= '  <zf:ConformanceLevel>BASIC</zf:ConformanceLevel>'."\n";
		$s .= '</rdf:Description>'."\n";
		return $s;
	}

}
