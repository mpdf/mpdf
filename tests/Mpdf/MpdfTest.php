<?php

namespace Mpdf;

use Mockery;

class MpdfTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();
	}

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	public function testPdfOutput()
	{
		$this->mpdf->WriteHTML('<html><body>
			<h1>Test</h1>
			<p><img src="//localhost/image.jpg"></a></p>
		</body></html>');

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$dateRegex = '\(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)';
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\n\/Producer \((.*?)\)\n\/CreationDate ' . $dateRegex . '\n\/ModDate ' . $dateRegex . '/', $output);
	}

	public function testAdjustHtmlTooLargeHtml()
	{
		$this->expectException(\Mpdf\MpdfException::class);
		$this->expectExceptionMessage('The HTML code size is larger than pcre.backtrack_limit');

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
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertMatchesRegularExpression('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n\/UF \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertMatchesRegularExpression('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
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
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\/F \(public_filename\.xml\)\n\/Desc \(some description\)/', $output);
		$this->assertMatchesRegularExpression('/\/Type \/Filespec\n\/EF <<\n\/F \d+ 0 R\n\/UF \d+ 0 R\n>>\n\/AFRelationship \/Alternative/', $output);
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\/Type \/EmbeddedFile\n\/Subtype \/text#2Fxml\n\/Length \d+\n\/Filter \/FlateDecode\n\/Params \<\<\/ModDate \(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)/', $output);
		$this->assertMatchesRegularExpression('/\/AF \d+ 0 R\n\/Names << \/EmbeddedFiles << \/Names \[\(public_filename\.xml\) \d+ 0 R\]/', $output);
	}

	public function testPdfAdditionalXmpRdf()
	{
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
		$this->mpdf->SetAdditionalXmpRdf($this->ZugferdXmpRdf());

		$this->mpdf->writeHtml('<html><body>hello world</body></html>');
		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertMatchesRegularExpression('/<zf:DocumentFileName>ZUGFeRD-invoice\.xml<\/zf:DocumentFileName>/', $output);
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
