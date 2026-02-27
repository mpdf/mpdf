<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;

class MeterTest extends BaseTagTestCase
{
	/**
	 * @var Meter
	 */
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Meter::class);
	}

	public function testOpen_BasicMeter()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		// Verify textbuffer was populated with meter object
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$this->assertStringContainsString(Mpdf::OBJECT_IDENTIFIER . 'type=image', $this->mpdf->textbuffer[0][0]);
		
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('image', $objattr['type']);
		$this->assertArrayHasKey('width', $objattr);
		$this->assertArrayHasKey('height', $objattr);
	}

	public function testOpen_ValueClamping()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// Test VALUE < MIN (should clamp to MIN)
		$attr = ['MIN' => '10', 'MAX' => '100', 'VALUE' => '5'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify VALUE was clamped to MIN by checking SVG file
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
	
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('file', $objattr);
		$svgContent = file_get_contents($objattr['file']);
		// For value=5 (clamped to MIN=10): width should be 0
		// This verifies the value was clamped to MIN
		$this->assertStringContainsString('width="0"', $svgContent);
		
		// Test VALUE > MAX (should clamp to MAX)
		$this->mpdf->inMeter = false;
		$this->mpdf->textbuffer = [];
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '150'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify VALUE was clamped to MAX by checking SVG file
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
	   
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('file', $objattr);
		$svgContent = file_get_contents($objattr['file']);
		// For value=150 (clamped to MAX=100): width should be 50 (default width)
		// This verifies the value was clamped to MAX
		$this->assertStringContainsString('width="50"', $svgContent);
	}

	public function testOpen_ValueZero()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// VALUE='0' should be accepted
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '0'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);

		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('image', $objattr['type']);
	}

	public function testOpen_LowHighOptimum()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'LOW' => '20',
			'HIGH' => '80',
			'OPTIMUM' => '60'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Extract and verify object attributes
		$objectStr = $this->mpdf->textbuffer[0][0];
		$this->assertStringContainsString('type=image', $objectStr);

		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$this->assertEquals('image', $objattr['type']);
		
		// Verify SVG content for color (Green for optimum in normal range)
		// Optimum=60, Low=20, High=80. Value=50.
		// Optimum (60) is between Low (20) and High (80).
		// Value (50) is between Low and High. -> Green.
		$this->assertArrayHasKey('file', $objattr);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent);
	}

	public function testOpen_Colors()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// Case 1: Optimum < Low
		// Value < Low -> Green
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '10', 'LOW' => '20', 'HIGH' => '80', 'OPTIMUM' => '10'];
		$ahtml = [];
		$ihtml = 0;
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent, 'Optimum < Low, Value < Low should be Green');

		// Value > High -> Red
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '90';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrRED)"', $svgContent, 'Optimum < Low, Value > High should be Red');
		
		// Else -> Orange
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '50';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrORANGE)"', $svgContent, 'Optimum < Low, Value between Low/High should be Orange');

		// Case 2: Optimum > High
		// Value < Low -> Red
		$this->mpdf->textbuffer = [];
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '10', 'LOW' => '20', 'HIGH' => '80', 'OPTIMUM' => '90'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrRED)"', $svgContent, 'Optimum > High, Value < Low should be Red');

		// Value > High -> Green
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '90';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent, 'Optimum > High, Value > High should be Green');

		// Else -> Orange
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '50';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrORANGE)"', $svgContent, 'Optimum > High, Value between Low/High should be Orange');

		// Case 3: Optimum between Low and High
		// Value < Low -> Orange
		$this->mpdf->textbuffer = [];
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '10', 'LOW' => '20', 'HIGH' => '80', 'OPTIMUM' => '50'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrORANGE)"', $svgContent, 'Optimum normal, Value < Low should be Orange');

		// Value > High -> Orange
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '90';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrORANGE)"', $svgContent, 'Optimum normal, Value > High should be Orange');

		// Else -> Green
		$this->mpdf->textbuffer = [];
		$attr['VALUE'] = '50';
		$this->tag->open($attr, $ahtml, $ihtml);
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent, 'Optimum normal, Value normal should be Green');
	}

	public function testOpen_OptimumClamping()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// OPTIMUM < MIN should clamp to MIN
		// If clamped to MIN (10), it equals LOW (10), so it's treated as "normal" optimum.
		// Value (50) is between LOW (10) and HIGH (100), so it should be Green.
		// If NOT clamped (5), it would be < LOW, and Value (50) would be > LOW and < HIGH, so it would be Orange.
		$attr = ['MIN' => '10', 'MAX' => '100', 'VALUE' => '50', 'OPTIMUM' => '5'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);

		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$this->assertEquals('image', $objattr['type']);
		
		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent, 'Optimum should be clamped to MIN, resulting in Green bar');
		
		// OPTIMUM > MAX should clamp to MAX
		// If clamped to MAX (100), it equals HIGH (100), so it's treated as "normal" optimum.
		// Value (50) is between LOW (0) and HIGH (100), so it should be Green.
		// If NOT clamped (150), it would be > HIGH, and Value (50) would be > LOW and < HIGH, so it would be Orange.
		$this->mpdf->inMeter = false;
		$this->mpdf->textbuffer = [];
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50', 'OPTIMUM' => '150'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		$this->assertEquals('image', $objattr['type']);

		$svgContent = file_get_contents($objattr['file']);
		$this->assertStringContainsString('fill="url(#GrGREEN)"', $svgContent, 'Optimum should be clamped to MAX, resulting in Green bar');
	}

	public function testOpen_DisplayNone()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// display: none should cause early return
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50', 'STYLE' => 'display: none'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// inMeter is set at beginning of open() before display check, so it will be true
		// but the function returns early, so textbuffer should be empty
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_WithMargins()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->FontSize = 12;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'margin-top: 10px; margin-bottom: 5px; margin-left: 3px; margin-right: 4px'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify object was created with margins
		$objectStr = $this->mpdf->textbuffer[0][0];
		$this->assertStringContainsString('objattr=', $objectStr);
		
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$sizeConverter = $this->getService('sizeConverter');
		$expectedTop = $sizeConverter->convert('10px', 100, 12, false);
		$expectedBottom = $sizeConverter->convert('5px', 100, 12, false);
		$expectedLeft = $sizeConverter->convert('3px', 100, 12, false);
		$expectedRight = $sizeConverter->convert('4px', 100, 12, false);

		$this->assertEquals($expectedTop, $objattr['margin_top'], 'Margin Top mismatch');
		$this->assertEquals($expectedBottom, $objattr['margin_bottom'], 'Margin Bottom mismatch');
		$this->assertEquals($expectedLeft, $objattr['margin_left'], 'Margin Left mismatch');
		$this->assertEquals($expectedRight, $objattr['margin_right'], 'Margin Right mismatch');
	}

	public function testOpen_WithPadding()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->FontSize = 12;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'padding: 5px'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);

		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$sizeConverter = $this->getService('sizeConverter');
		$expectedPadding = $sizeConverter->convert('5px', 100, 12, false);

		$this->assertEquals($expectedPadding, $objattr['padding_top'], 'Padding Top mismatch');
		$this->assertEquals($expectedPadding, $objattr['padding_bottom'], 'Padding Bottom mismatch');
		$this->assertEquals($expectedPadding, $objattr['padding_left'], 'Padding Left mismatch');
		$this->assertEquals($expectedPadding, $objattr['padding_right'], 'Padding Right mismatch');
	}

	public function testOpen_WithBorders()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->FontSize = 12;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'border: 1px solid black'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);

		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		// Border width '1px'
		$sizeConverter = $this->getService('sizeConverter');
		$expectedBorder = $sizeConverter->convert('1px', 100, 12, false);

		$this->assertEquals($expectedBorder, $objattr['border_top']['w'], 'Border Top Width mismatch');
		$this->assertEquals($expectedBorder, $objattr['border_bottom']['w'], 'Border Bottom Width mismatch');
		$this->assertEquals($expectedBorder, $objattr['border_left']['w'], 'Border Left Width mismatch');
		$this->assertEquals($expectedBorder, $objattr['border_right']['w'], 'Border Right Width mismatch');
	}

	public function testOpen_WithDimensions()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 200;
		$this->mpdf->FontSize = 12;
		
		// Width and height from STYLE
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'width: 100px; height: 20px'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify dimensions are in object
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('width', $objattr);
		$this->assertArrayHasKey('height', $objattr);
		$this->assertGreaterThan(0, $objattr['width']);
		$this->assertGreaterThan(0, $objattr['height']);
		
		// Width and height from attributes
		$this->mpdf->inMeter = false;
		$this->mpdf->textbuffer = [];
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'WIDTH' => '80',
			'HEIGHT' => '15'
		];
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);

		// Verify dimensions are in object
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('width', $objattr);
		$this->assertArrayHasKey('height', $objattr);
		$this->assertGreaterThan(0, $objattr['width']);
		$this->assertGreaterThan(0, $objattr['height']);
	}

	public function testOpen_WithOpacity()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'opacity: 0.5'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify opacity was set
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('opacity', $objattr);
		$this->assertEquals(0.5, $objattr['opacity']);
	}

	public function testOpen_WithInternalLink()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->HREF = 'section1';
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertArrayHasKey('section1', $this->mpdf->internallink);
		$this->assertNotEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_WithExternalLink()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->HREF = 'http://example.com';
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify link was set in object
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
	   
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('link', $objattr);
		$this->assertEquals('http://example.com', $objattr['link']);
	}

	public function testOpen_Type2()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50', 'TYPE' => '2'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		// Type 2 creates a wider meter (160px vs 50px default)
		
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('image', $objattr['type']);
		// Verify it's using the wider dimensions (160px -> approx 50.8 units)
		$this->assertGreaterThan(40, $objattr['width']);
	}

	public function testOpen_Type3()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50', 'TYPE' => '3'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		// Type 3 creates a 100px wide meter

		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('image', $objattr['type']);
		// Verify it's using the Type 3 dimensions (100px -> approx 31.76 units)
		$this->assertGreaterThan(20, $objattr['width']);
	}

	public function testOpen_InTableContext()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->tableLevel = 1;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 0];
		
		$attr = ['MIN' => '0', 'MAX' => '100', 'VALUE' => '50'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		// Verify cell width was updated
		$this->assertGreaterThan(0, $this->mpdf->cell[0][0]['s']);
		// In table context, object goes to cell buffer not textbuffer
		$this->assertNotEmpty($this->mpdf->cell[0][0]['textbuffer']);
	}

	public function testOpen_VisibilityHidden()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		$this->mpdf->visibility = 'visible';
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'visibility: hidden'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify visibility was set
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
	   
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('visibility', $objattr);
		$this->assertEquals('hidden', $objattr['visibility']);
	}

	public function testOpen_VerticalAlign()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		$attr = [
			'MIN' => '0',
			'MAX' => '100',
			'VALUE' => '50',
			'STYLE' => 'vertical-align: middle'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->inMeter);
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify vertical-align was set
		$objectStr = $this->mpdf->textbuffer[0][0];
		preg_match('/objattr=(.*)' . preg_quote(Mpdf::OBJECT_IDENTIFIER) . '/', $objectStr, $matches);
	
		$objattr = unserialize($matches[1]);
		$this->assertArrayHasKey('vertical-align', $objattr);
		// 'middle' usually corresponds to 'M' in Mpdf
		$this->assertEquals('M', $objattr['vertical-align']);
	}

	public function testClose_Meter()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->mpdf->inMeter = true;
		$this->mpdf->ignorefollowingspaces = true;

		$this->tag->close($ahtml, $ihtml);

		$this->assertFalse($this->mpdf->inMeter);
		$this->assertFalse($this->mpdf->ignorefollowingspaces);
	}
}
