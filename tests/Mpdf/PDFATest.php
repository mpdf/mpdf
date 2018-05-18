<?php

namespace Mpdf;

class PDFATest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		$this->mpdf = new Mpdf();
		$this->mpdf->writeHtml('<html><body>PDFA Test</body></html>');
		$this->mpdf->PDFA = true;
		$this->mpdf->PDFAauto = true;
	}

	public function testOriginalPDFA_1B()
	{
		$output = $this->mpdf->Output(null, 'S');
		$output = preg_replace('/rdf:about="uuid:[\w-]+"/', 'rdf:about="uuid:fake-uuid"', $output);

		$expected = '   <rdf:Description rdf:about="uuid:fake-uuid" xmlns:pdfaid="http://www.aiim.org/pdfa/ns/id/" >' . "\n";
		$expected .= '    <pdfaid:part>1</pdfaid:part>' . "\n";
		$expected .= '    <pdfaid:conformance>B</pdfaid:conformance>' . "\n";
		$expected .= '    <pdfaid:amd>2005</pdfaid:amd>' . "\n";
		$expected .= '   </rdf:Description>' . "\n";

		$this->assertContains($expected, $output);
	}

	public function testPDFA_Version_Fail()
	{
		$this->mpdf->PDFAversion = '11';
		try {
			$this->mpdf->Output(null, 'S');
		} catch (\Exception $e) {
			$this->assertSame('PDFA version (11) is not valid. (Use: 1-B, 3-B, etc.)', $e->getMessage());
		}
	}

	public function testOriginalPDFA_3B()
	{
		$this->mpdf->PDFAversion = '3-B';

		$output = $this->mpdf->Output(null, 'S');
		$output = preg_replace('/rdf:about="uuid:[\w-]+"/', 'rdf:about="uuid:fake-uuid"', $output);

		$expected = '   <rdf:Description rdf:about="uuid:fake-uuid" xmlns:pdfaid="http://www.aiim.org/pdfa/ns/id/" >' . "\n";
		$expected .= '    <pdfaid:part>3</pdfaid:part>' . "\n";
		$expected .= '    <pdfaid:conformance>B</pdfaid:conformance>' . "\n";
		$expected .= '   </rdf:Description>' . "\n";

		$this->assertContains($expected, $output);
	}

}
