<?php

namespace Mpdf;

class AddFontTest extends \PHPUnit_Framework_TestCase
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

	public function testAddFont()
	{
		$this->mpdf->AddFont('sun-exta');
	}

	/**
	 * @expectedException \Mpdf\MpdfException
	 * @expectedExceptionMessage Font "font" is not supported
	 */
	public function testAddUnsupportedFont()
	{
		$this->mpdf->AddFont('font');
	}

	public function testDejavuSerifCondensed()
	{
		$ttf = $this->mpdf->FontFiles['dejavuserifcondensed'];
		$this->assertEquals(334040, $ttf['length1']);
		$this->assertSame('TTF', $ttf['type']);
		$this->assertFalse($ttf['sip']);
		$this->assertFalse($ttf['smp']);

		$ttf = $this->mpdf->fonts['dejavuserifcondensed'];
		$this->assertEquals(729, $ttf['desc']['CapHeight']);
		$this->assertEquals(519, $ttf['desc']['XHeight']);
		$this->assertSame('[-693 -347 1512 1109]', $ttf['desc']['FontBBox']);
		$this->assertSame(' 0 0 2 6 6 6 5 6 5 2 2 4', $ttf['panose']);
		$this->assertEquals(2048, $ttf['unitsPerEm']);
		$this->assertEquals(-63, $ttf['up']);
		$this->assertEquals(44, $ttf['ut']);
		$this->assertEquals(50, $ttf['strs']);
		$this->assertEquals(259, $ttf['strp']);
		$this->assertFalse($ttf['used']);
		$this->assertFalse($ttf['sip']);
		$this->assertSame('', $ttf['sipext']);
		$this->assertFalse($ttf['smp']);
		$this->assertFalse($ttf['useOTL']);
		$this->assertEquals(0, $ttf['TTCfontID']);
		$this->assertFalse($ttf['useKashida']);
		$this->assertCount(0, $ttf['GSUBScriptLang']);
		$this->assertCount(0, $ttf['GSUBFeatures']);
		$this->assertCount(0, $ttf['GSUBLookups']);
		$this->assertCount(0, $ttf['GPOSScriptLang']);
		$this->assertCount(0, $ttf['GPOSFeatures']);
		$this->assertCount(0, $ttf['GPOSLookups']);
		$this->assertSame('', $ttf['rtlPUAstr']);
		$this->assertSame('', $ttf['glyphIDtoUni']);
		$this->assertTrue($ttf['haskerninfo']);
		$this->assertFalse($ttf['haskernGPOS']);
		$this->assertFalse($ttf['hassmallcapsGSUB']);
		$this->assertCount(96, $ttf['subset']);
		$this->assertCount(103, $ttf['kerninfo']);
	}

	public function testFontWithoutCache()
	{
		$this->ttfAssertions($this->mpdf->fonts['dejavuserifcondensed']);
	}

	public function testFontCache()
	{
		/* Preloading the font cache */
		$fontName = 'dejavusanscondensed';
		$this->mpdf->AddFont($fontName);

		$this->mpdf = new Mpdf();
		$this->mpdf->AddFont($fontName);

		$this->ttfAssertions($this->mpdf->fonts[$fontName]);
	}

	protected function ttfAssertions($ttf)
	{
		$this->assertTrue(is_numeric($ttf['i']));
		$this->assertTrue(is_string($ttf['name']));
		$this->assertTrue(is_string($ttf['type']));

		$this->assertCount(10, $ttf['desc']);
		$this->assertTrue(is_numeric($ttf['desc']['CapHeight']));
		$this->assertTrue(is_numeric($ttf['desc']['XHeight']));
		$this->assertTrue(is_string($ttf['desc']['FontBBox']));
		$this->assertTrue(is_numeric($ttf['desc']['Flags']));
		$this->assertTrue(is_numeric($ttf['desc']['Ascent']));
		$this->assertTrue(is_numeric($ttf['desc']['Descent']));
		$this->assertTrue(is_numeric($ttf['desc']['Leading']));
		$this->assertTrue(is_numeric($ttf['desc']['ItalicAngle']));
		$this->assertTrue(is_numeric($ttf['desc']['StemV']));
		$this->assertTrue(is_numeric($ttf['desc']['MissingWidth']));

		$this->assertTrue(is_string($ttf['panose']));
		$this->assertTrue(is_numeric($ttf['unitsPerEm']));
		$this->assertTrue(is_numeric($ttf['up']));
		$this->assertTrue(is_numeric($ttf['ut']));
		$this->assertTrue(is_numeric($ttf['strs']));
		$this->assertTrue(is_numeric($ttf['strp']));
		$this->assertArrayHasKey('cw', $ttf);
		$this->assertFileExists($ttf['ttffile']);
		$this->assertTrue(is_string($ttf['fontkey']));
		$this->assertTrue(is_bool($ttf['used']));
		$this->assertTrue(is_bool($ttf['sip']));
		$this->assertTrue(is_string($ttf['sipext']));
		$this->assertTrue(is_bool($ttf['smp']));

		$this->assertTrue(is_numeric($ttf['TTCfontID']));
		$this->assertTrue(is_numeric($ttf['useOTL']) || $ttf['useOTL'] === false);
		$this->assertTrue(is_numeric($ttf['useKashida']) || $ttf['useKashida'] === false);
		$this->assertTrue(is_array($ttf['GSUBScriptLang']));
		$this->assertTrue(is_array($ttf['GSUBFeatures']));
		$this->assertTrue(is_array($ttf['GSUBLookups']));
		$this->assertTrue(is_array($ttf['GPOSScriptLang']));
		$this->assertTrue(is_array($ttf['GPOSFeatures']));
		$this->assertTrue(is_array($ttf['GPOSLookups']));
		$this->assertTrue(is_string($ttf['rtlPUAstr']));
		$this->assertTrue(is_string($ttf['glyphIDtoUni']));
		$this->assertTrue(is_bool($ttf['haskerninfo']));
		$this->assertTrue(is_bool($ttf['haskernGPOS']));
		$this->assertTrue(is_bool($ttf['hassmallcapsGSUB']));
		$this->assertTrue(is_array($ttf['subset']));
		$this->assertTrue(is_array($ttf['kerninfo']));
	}

}
