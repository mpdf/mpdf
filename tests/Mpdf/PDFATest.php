<?php

namespace Mpdf;

class PDFATest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function set_up()
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

		$this->assertStringContainsString($expected, $output);
	}

	public function testPDFA_1B_DoesNotSetCatalogVersion()
	{
		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringNotContainsString('/Version /1.7', $output);
	}

	public function testPDFA_2B_SetsCatalogVersion17()
	{
		$this->mpdf->PDFAversion = '2-B';

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringContainsString('/Version /1.7', $output);
	}

	public function testPDFA_3B_SetsCatalogVersion17()
	{
		$this->mpdf->PDFAversion = '3-B';

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringContainsString('/Version /1.7', $output);
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

		$this->assertStringContainsString($expected, $output);
	}

	public function testPDFA_3B_SvgImageFormXObjectHasResources()
	{
		$mpdf = new Mpdf();
		$mpdf->PDFA = true;
		$mpdf->PDFAauto = true;
		$mpdf->PDFAversion = '3-B';
		$mpdf->compress = false;

		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">'
			. '<rect width="10" height="10" fill="rgb(206,37,32)" opacity="0.5"/>'
			. '</svg>';
		$svgFile = sys_get_temp_dir() . '/mpdf-fwtest-' . uniqid('', true) . '.svg';
		file_put_contents($svgFile, $svg);

		try {
			$mpdf->writeHtml('<img src="' . $svgFile . '" style="width:30mm">');
			$output = $mpdf->Output(null, 'S');
		} finally {
			@unlink($svgFile);
		}

		preg_match_all('/\d+\s+\d+\s+obj\s*(.*?)endobj/s', $output, $matches);
		$formObjects = array_values(array_filter($matches[1], function ($object) {
			return strpos($object, '/Subtype /Form') !== false;
		}));

		$this->assertNotEmpty($formObjects, 'Expected at least one Form XObject for the embedded SVG image');
		$this->assertMatchesRegularExpression(
			'#/Resources\s*<<.*?/ExtGState\s*<<.*?/GS\d+\s+\d+\s+0\s+R#s',
			$formObjects[0],
			'Form-XObject lacks an explicit /Resources dictionary listing the GS-states it references'
		);
	}

}
