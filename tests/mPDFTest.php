<?php

class mPDFTest extends PHPUnit_Framework_TestCase
{

	private $mpdf;

	public function setup()
	{
		parent::setup();

		$this->mpdf = new mPDF();
	}

	public function testPdfOutput()
	{
		$this->mpdf->writeHtml('<html><body>
			<h1>Test</h1>
		</body></html>');

		$output = $this->mpdf->Output(NULL, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
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
			'path' => _MPDF_PATH . 'tests/data/xml/test.xml'
		]]);

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(NULL, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertRegExp('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertRegExp('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertRegExp('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
	}

	public function testPdfAdditionalXmpRdf()
	{
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
		$this->mpdf->SetAdditionalXmpRdf($this->ZugferdXmpRdf());

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(NULL, 'S');

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
