<?php

namespace Mpdf;

use fpdi_pdf_parser;
use pdf_parser;
use ReflectionClass;

/**
 * PHPUnit Testing for the MPDI (FPDI) Functionality
 *
 * @package    mPDF
 * @author     Blue Liquid Designs <admin@blueliquiddesigns.com.au>
 * @copyright  2015 Blue Liquid Designs
 * @license    GPLv2
 */

/**
 * The FPDI/MPDI testing suite
 *
 * @group mpdi
 */
class FpdiTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \pdf_parser
	 */
	private $parser;

	/**
	 * @var \fpdi_pdf_parser
	 */
	private $fpdi_parser;

	/**
	 * Set up our common testing PDFs
	 */
	protected function setUp()
	{
		parent::setUp();

		/* Set up our test objects */
		$this->mpdf = new Mpdf();
		$this->fpdi_parser = new fpdi_pdf_parser(__DIR__ . '/../data/pdfs/2-Page-PDF_1_4.pdf', $this->mpdf);
		$this->parser = new pdf_parser(__DIR__ . '/../data/pdfs/2-Page-PDF_1_4.pdf');
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	protected function invokeMethod(&$object, $methodName, array $parameters = [])
	{
		$reflection = new ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}

	/**
	 * Retrieve protected/private properties of a class.
	 *
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $propertyName Property name to retreve
	 *
	 * @return mixed
	 */
	protected function getProperty(&$object, $propertyName)
	{
		$reflection = new ReflectionClass(get_class($object));
		$property = $reflection->getProperty($propertyName);
		$property->setAccessible(true);

		return $property->getValue($object);
	}

	/**
	 * Check the xref ofset value is accurate
	 */
	public function testPdfFindXref()
	{
		$this->assertEquals(116, $this->invokeMethod($this->parser, '_findXref'));
	}

	/**
	 * Check the standardised xref return value based on our testing PDF
	 */
	public function testPdfReadXref()
	{
		$xref = [];

		$this->invokeMethod($this->parser, '_readXref', [& $xref, $this->invokeMethod($this->parser, '_findXref')]);

		/* Verify the xref array */
		$this->assertArrayHasKey('xrefLocation', $xref);
		$this->assertArrayHasKey('maxObject', $xref);
		$this->assertArrayHasKey('xref', $xref);
		$this->assertArrayHasKey('trailer', $xref);

		/* Check the xref array data integrity */
		$this->assertEquals(116, $xref['xrefLocation']);
		$this->assertEquals(27, $xref['maxObject']);
		$this->assertTrue(is_array($xref['xref']));
		$this->assertTrue(is_array($xref['trailer']));

		/* Check the $xref['xref'] array data integrity */
		$this->assertEquals(16, $xref['xref'][9][0]);
		$this->assertEquals(857, $xref['xref'][10][0]);
		$this->assertEquals(935, $xref['xref'][11][0]);
		$this->assertEquals(1166, $xref['xref'][12][0]);
		$this->assertEquals(1347, $xref['xref'][13][0]);
		$this->assertEquals(1742, $xref['xref'][14][0]);
		$this->assertEquals(1792, $xref['xref'][15][0]);
		$this->assertEquals(1837, $xref['xref'][16][0]);
		$this->assertEquals(1914, $xref['xref'][17][0]);
		$this->assertEquals(2137, $xref['xref'][18][0]);
		$this->assertEquals(2699, $xref['xref'][19][0]);
		$this->assertEquals(3069, $xref['xref'][20][0]);
		$this->assertEquals(3284, $xref['xref'][21][0]);
		$this->assertEquals(3319, $xref['xref'][22][0]);
		$this->assertEquals(14059, $xref['xref'][23][0]);
		$this->assertEquals(16751, $xref['xref'][24][0]);
		$this->assertEquals(17706, $xref['xref'][25][0]);
		$this->assertEquals(656, $xref['xref'][26][0]);

		$this->assertEquals(0, $xref['xref'][0]['65535']);
		$this->assertEquals(18010, $xref['xref'][1][0]);
		$this->assertEquals(18238, $xref['xref'][2][0]);
		$this->assertEquals(18418, $xref['xref'][3][0]);
		$this->assertEquals(18980, $xref['xref'][4][0]);
		$this->assertEquals(19014, $xref['xref'][5][0]);
		$this->assertEquals(19038, $xref['xref'][6][0]);
		$this->assertEquals(19096, $xref['xref'][7][0]);
		$this->assertEquals(22433, $xref['xref'][8][0]);

		/* Check the $xref['trailer'] array */
		$this->assertEquals(5, $xref['trailer'][0]);
		$this->assertTrue(is_array($xref['trailer'][1]));

		$this->assertEquals(1, $xref['trailer'][1]['/Size'][0]);
		$this->assertEquals(27, $xref['trailer'][1]['/Size'][1]);

		$this->assertEquals(6, $xref['trailer'][1]['/ID'][0]);

		$this->assertEquals(3, $xref['trailer'][1]['/ID'][1][0][0]);
		$this->assertEquals('F34FF699722057ED0305FD8963F8BEE6', $xref['trailer'][1]['/ID'][1][0][1]);

		$this->assertEquals(3, $xref['trailer'][1]['/ID'][1][1][0]);
		$this->assertEquals('37D4D0052D959F40A6C7109C3CED2DB5', $xref['trailer'][1]['/ID'][1][1][1]);

		$this->assertEquals(8, $xref['trailer'][1]['/Root'][0]);
		$this->assertEquals(10, $xref['trailer'][1]['/Root'][1]);
		$this->assertEquals(0, $xref['trailer'][1]['/Root'][2]);

		$this->assertEquals(8, $xref['trailer'][1]['/Info'][0]);
		$this->assertEquals(8, $xref['trailer'][1]['/Info'][1]);
		$this->assertEquals(0, $xref['trailer'][1]['/Info'][2]);

		$this->assertEquals(1, $xref['trailer'][1]['/Prev'][0]);
		$this->assertEquals(22585, $xref['trailer'][1]['/Prev'][1]);
	}

	/**
	 * Check the standardised Trailer /Root value is found
	 */
	public function testPdfReadRoot()
	{
		$root = $this->getProperty($this->parser, '_root');

		$this->assertEquals(9, $root[0]);
		$this->assertEquals(true, is_array($root[1]));
		$this->assertArrayNotHasKey(2, $root);
	}

	/**
	 * Check the standardised resolve object functions
	 */
	public function testResolveObject()
	{
		$resolved = $this->parser->resolveObject($this->getProperty($this->parser, '_root'));

		/* Check for the correct results */
		$this->assertEquals(9, $resolved[0]);
		$this->assertEquals(10, $resolved['obj']);
		$this->assertEquals(0, $resolved['gen']);
		$this->assertTrue(is_array($resolved[1]));

		$this->assertEquals(5, $resolved[1][0]);
		$this->assertTrue(is_array($resolved[1][1]));

		$this->assertArrayHasKey('/Metadata', $resolved[1][1]);
		$this->assertArrayHasKey('/PageLabels', $resolved[1][1]);
		$this->assertArrayHasKey('/Pages', $resolved[1][1]);
		$this->assertArrayHasKey('/Type', $resolved[1][1]);

		$this->assertEquals(8, $resolved[1][1]['/Metadata'][0]);
		$this->assertEquals(7, $resolved[1][1]['/Metadata'][1]);
		$this->assertEquals(0, $resolved[1][1]['/Metadata'][2]);

		$this->assertEquals(8, $resolved[1][1]['/PageLabels'][0]);
		$this->assertEquals(4, $resolved[1][1]['/PageLabels'][1]);
		$this->assertEquals(0, $resolved[1][1]['/PageLabels'][2]);

		$this->assertEquals(8, $resolved[1][1]['/Pages'][0]);
		$this->assertEquals(6, $resolved[1][1]['/Pages'][1]);
		$this->assertEquals(0, $resolved[1][1]['/Pages'][2]);

		$this->assertEquals(2, $resolved[1][1]['/Type'][0]);
		$this->assertEquals('/Catalog', $resolved[1][1]['/Type'][1]);
	}

	/**
	 * Check the standardised FPDI PDF loader (the construct)
	 */
	public function testFpdiPdfParser()
	{
		$this->assertSame(2, $this->fpdi_parser->getPageCount());

		$_pages = $this->getProperty($this->fpdi_parser, '_pages');

		$page1 = $_pages[0];
		$page2 = $_pages[1];

		/* Check Page 1 as the appropriate values */
		$this->assertEquals(9, $page1[0]);
		$this->assertEquals(11, $page1['obj']);
		$this->assertEquals(0, $page1['gen']);

		/* Check Page 2 as the appropriate values */
		$this->assertEquals(5, $page1[1][0]);
		$this->assertTrue(is_array($page1[1][1]));

		$this->assertArrayHasKey('/ArtBox', $page1[1][1]);
		$this->assertArrayHasKey('/BleedBox', $page1[1][1]);
		$this->assertArrayHasKey('/Contents', $page1[1][1]);
		$this->assertArrayHasKey('/CropBox', $page1[1][1]);
		$this->assertArrayHasKey('/MediaBox', $page1[1][1]);
		$this->assertArrayHasKey('/Parent', $page1[1][1]);
		$this->assertArrayHasKey('/Resources', $page1[1][1]);
		$this->assertArrayHasKey('/Rotate', $page1[1][1]);
		$this->assertArrayHasKey('/TrimBox', $page1[1][1]);
		$this->assertArrayHasKey('/Type', $page1[1][1]);

		/* Check ArtBox */
		$artbox = $page1[1][1]['/ArtBox'];
		$this->assertEquals(6, $artbox[0]);

		$this->assertEquals(1, $artbox[1][0][0]);
		$this->assertEquals(0, $artbox[1][0][1]);

		$this->assertEquals(12, $artbox[1][1][0]);
		$this->assertEquals(0.071, $artbox[1][1][1]);

		$this->assertEquals(12, $artbox[1][2][0]);
		$this->assertEquals(595.02, $artbox[1][2][1]);

		$this->assertEquals(12, $artbox[1][3][0]);
		$this->assertEquals(841.789, $artbox[1][3][1]);

		/* Check BleedBox */
		$bleedbox = $page1[1][1]['/BleedBox'];
		$this->assertEquals(6, $bleedbox[0]);

		$this->assertEquals(1, $bleedbox[1][0][0]);
		$this->assertEquals(0, $bleedbox[1][0][1]);

		$this->assertEquals(12, $bleedbox[1][1][0]);
		$this->assertEquals(0.211, $bleedbox[1][1][1]);

		$this->assertEquals(12, $bleedbox[1][2][0]);
		$this->assertEquals(595.02, $bleedbox[1][2][1]);

		$this->assertEquals(12, $bleedbox[1][3][0]);
		$this->assertEquals(841.929, $bleedbox[1][3][1]);

		/* Check Contents */
		$contents = $page1[1][1]['/Contents'];
		$this->assertEquals(8, $contents[0]);
		$this->assertEquals(18, $contents[1]);
		$this->assertEquals(0, $contents[2]);

		/* Check CropBox */
		$cropbox = $page1[1][1]['/CropBox'];
		$this->assertEquals(6, $cropbox[0]);

		$this->assertEquals(1, $cropbox[1][0][0]);
		$this->assertEquals(0, $cropbox[1][0][1]);

		$this->assertEquals(1, $cropbox[1][1][0]);
		$this->assertEquals(0, $cropbox[1][1][1]);

		$this->assertEquals(12, $cropbox[1][2][0]);
		$this->assertEquals(595.22, $cropbox[1][2][1]);

		$this->assertEquals(1, $cropbox[1][3][0]);
		$this->assertEquals(842, $cropbox[1][3][1]);

		/* Check MediaBox */
		$mediabox = $page1[1][1]['/MediaBox'];
		$this->assertEquals(6, $mediabox[0]);

		$this->assertEquals(1, $mediabox[1][0][0]);
		$this->assertEquals(0, $mediabox[1][0][1]);

		$this->assertEquals(1, $mediabox[1][1][0]);
		$this->assertEquals(0, $mediabox[1][1][1]);

		$this->assertEquals(12, $mediabox[1][2][0]);
		$this->assertEquals(595.22, $mediabox[1][2][1]);

		$this->assertEquals(1, $mediabox[1][3][0]);
		$this->assertEquals(842, $mediabox[1][3][1]);

		/* Check Parent */
		$parent = $page1[1][1]['/Parent'];
		$this->assertEquals(8, $parent[0]);
		$this->assertEquals(6, $parent[1]);
		$this->assertEquals(0, $parent[2]);

		/* Check Resources */
		$resources = $page1[1][1]['/Resources'];
		$this->assertEquals(8, $resources[0]);
		$this->assertEquals(12, $resources[1]);
		$this->assertEquals(0, $resources[2]);

		/* Check Rotate */
		$rotate = $page1[1][1]['/Rotate'];
		$this->assertEquals(1, $rotate[0]);
		$this->assertEquals(0, $rotate[1]);

		/* Check TrimBox */
		$trimbox = $page1[1][1]['/TrimBox'];
		$this->assertEquals(6, $trimbox[0]);

		$this->assertEquals(1, $trimbox[1][0][0]);
		$this->assertEquals(0, $trimbox[1][0][1]);

		$this->assertEquals(12, $trimbox[1][1][0]);
		$this->assertEquals(0.211, $trimbox[1][1][1]);

		$this->assertEquals(12, $trimbox[1][2][0]);
		$this->assertEquals(595.02, $trimbox[1][2][1]);

		$this->assertEquals(12, $trimbox[1][3][0]);
		$this->assertEquals(841.929, $trimbox[1][3][1]);

		/* Check Type */
		$type = $page1[1][1]['/Type'];
		$this->assertEquals(2, $type[0]);
		$this->assertEquals('/Page', $type[1]);

		/* Check the basics for page 2 */
		$this->assertArrayHasKey('/ArtBox', $page2[1][1]);
		$this->assertArrayHasKey('/BleedBox', $page2[1][1]);
		$this->assertArrayHasKey('/Contents', $page2[1][1]);
		$this->assertArrayHasKey('/CropBox', $page2[1][1]);
		$this->assertArrayHasKey('/MediaBox', $page2[1][1]);
		$this->assertArrayHasKey('/Parent', $page2[1][1]);
		$this->assertArrayHasKey('/Resources', $page2[1][1]);
		$this->assertArrayHasKey('/Rotate', $page2[1][1]);
		$this->assertArrayHasKey('/TrimBox', $page2[1][1]);
		$this->assertArrayHasKey('/Type', $page2[1][1]);
	}

	/**
	 * Check the standardised FPDI PDF _getPageResources method
	 */
	public function testFpdiGetPageResources()
	{

		$_pages = $this->getProperty($this->fpdi_parser, '_pages');
		$resources = $this->invokeMethod($this->fpdi_parser, '_getPageResources', [$_pages[0]]);

		/* Run our tests */
		$this->assertEquals(5, $resources[0]);
		$this->assertTrue(is_array($resources[1]));

		$this->assertArrayHasKey('/ColorSpace', $resources[1]);
		$this->assertArrayHasKey('/ExtGState', $resources[1]);
		$this->assertArrayHasKey('/Font', $resources[1]);
		$this->assertArrayHasKey('/ProcSet', $resources[1]);
		$this->assertArrayHasKey('/XObject', $resources[1]);

		/* Test Color Space */
		$color = $resources[1]['/ColorSpace'];
		$this->assertEquals(5, $color[0]);

		$this->assertEquals(8, $color[1]['/Cs6'][0]);
		$this->assertEquals(21, $color[1]['/Cs6'][1]);
		$this->assertEquals(0, $color[1]['/Cs6'][2]);

		$this->assertEquals(8, $color[1]['/Cs8'][0]);
		$this->assertEquals(14, $color[1]['/Cs8'][1]);
		$this->assertEquals(0, $color[1]['/Cs8'][2]);

		$this->assertEquals(8, $color[1]['/Cs9'][0]);
		$this->assertEquals(15, $color[1]['/Cs9'][1]);
		$this->assertEquals(0, $color[1]['/Cs9'][2]);

		/* Test ExtGState */
		$ext = $resources[1]['/ExtGState'];
		$this->assertEquals(5, $ext[0]);

		$this->assertEquals(8, $ext[1]['/GS1'][0]);
		$this->assertEquals(16, $ext[1]['/GS1'][1]);
		$this->assertEquals(0, $ext[1]['/GS1'][2]);

		/* Test Font */
		$font = $resources[1]['/Font'];
		$this->assertEquals(5, $font[0]);

		$this->assertEquals(8, $font[1]['/TT2'][0]);
		$this->assertEquals(13, $font[1]['/TT2'][1]);
		$this->assertEquals(0, $font[1]['/TT2'][2]);

		$this->assertEquals(8, $font[1]['/TT4'][0]);
		$this->assertEquals(19, $font[1]['/TT4'][1]);
		$this->assertEquals(0, $font[1]['/TT4'][2]);

		/* Test ProcSet */
		$proc = $resources[1]['/ProcSet'];
		$this->assertEquals(6, $proc[0]);

		$this->assertEquals(2, $proc[1][0][0]);
		$this->assertEquals('/PDF', $proc[1][0][1]);

		$this->assertEquals(2, $proc[1][1][0]);
		$this->assertEquals('/Text', $proc[1][1][1]);

		$this->assertEquals(2, $proc[1][2][0]);
		$this->assertEquals('/ImageC', $proc[1][2][1]);

		$this->assertEquals(2, $proc[1][3][0]);
		$this->assertEquals('/ImageI', $proc[1][3][1]);

		/* Test XObject */
		$x = $resources[1]['/XObject'];
		$this->assertEquals(5, $x[0]);

		$this->assertEquals(8, $x[1]['/Im1'][0]);
		$this->assertEquals(22, $x[1]['/Im1'][1]);
		$this->assertEquals(0, $x[1]['/Im1'][2]);

		/**
		 * Check for basics on Page 2
		 */
		$resources = $this->invokeMethod($this->fpdi_parser, '_getPageResources', [$_pages[1]]);

		/* Run our tests */
		$this->assertEquals(5, $resources[0]);
		$this->assertTrue(is_array($resources[1]));

		$this->assertArrayHasKey('/ColorSpace', $resources[1]);
		$this->assertArrayHasKey('/ExtGState', $resources[1]);
		$this->assertArrayHasKey('/Font', $resources[1]);
		$this->assertArrayHasKey('/ProcSet', $resources[1]);
		$this->assertArrayHasKey('/XObject', $resources[1]);
	}

	/**
	 * Check the standardised FPDI PDF getContent() method
	 */
	public function testGetContent()
	{
		/* Set Page 1*/
		$this->fpdi_parser->setPageNo(1);

		/* Get contents */
		$content = $this->fpdi_parser->getContent();

		/* Check if contains specific text */
		$this->assertNotSame(false, strpos($content, 'MAIN HEADING'));
		$this->assertNotSame(false, strpos($content, 'Secondary Heading'));
		$this->assertNotSame(false, strpos($content, 'Blue Liquid Designs'));
		$this->assertSame(false, strpos($content, 'String Not In PDF'));
	}

	/**
	 * Check the standardised FPDI PDF getPageBox() method
	 */
	public function testGetPageBox()
	{
		$_pages = $this->getProperty($this->fpdi_parser, '_pages');
		$box = $this->invokeMethod($this->fpdi_parser, '_getPageBox', [$_pages[0], '/TrimBox', Mpdf::SCALE]);

		$this->assertEquals('0', $box['x']);
		$this->assertEquals('0.074436111111111', $box['y']);
		$this->assertEquals('209.90983333333', $box['w']);
		$this->assertEquals('296.93940555556', $box['h']);
	}

	/**
	 * Check the standardised FPDI PDF getPageBoxes() method
	 */
	public function testGetPageBoxes()
	{
		$boxes = $this->fpdi_parser->getPageBoxes(1, Mpdf::SCALE);

		$this->assertArrayHasKey('/MediaBox', $boxes);
		$this->assertArrayHasKey('/CropBox', $boxes);
		$this->assertArrayHasKey('/BleedBox', $boxes);
		$this->assertArrayHasKey('/TrimBox', $boxes);
		$this->assertArrayHasKey('/ArtBox', $boxes);
	}

	/**
	 * Check the standardised FPDI PDF getPageRotation() method
	 */
	public function testGetPageRotation()
	{
		$rotation = $this->fpdi_parser->getPageRotation(1);

		$this->assertEquals(1, $rotation[0]);
		$this->assertEquals(0, $rotation[1]);
	}
}
