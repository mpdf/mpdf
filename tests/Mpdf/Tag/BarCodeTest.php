<?php

namespace Mpdf\Tag;

class BarCodeTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(BarCode::class);
	}

	public function testOpen_BasicBarCode()
	{
		$attr = ['CODE' => '123456789012', 'TYPE' => 'EAN13'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify barcode was added to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$this->assertStringContainsString('type=barcode', $content);
		$this->assertStringContainsString('code";s:12:"123456789012"', $content);
		$this->assertStringContainsString('btype";s:5:"EAN13"', $content);
	}

	public function testOpen_HiddenBarCode()
	{
		// Use display: none to hide
		$attr = ['CODE' => '123456789012', 'STYLE' => 'display: none'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify no output to textbuffer
		$this->assertEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_BarCode_WithInlineStyle()
	{
		$attr = [
			'CODE' => '123456789012',
			'TYPE' => 'EAN13',
			'STYLE' => 'margin: 5px; border: 1px solid black; color: blue;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$this->assertStringContainsString('type=barcode', $content);
		
		// Extract objattr to check properties
		$parts = explode('objattr=', $content);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Verify margin (5px converted)
		// 5 * 25.4 / 96 = 1.3229166666667
		$expectedMargin = 5 * (25.4 / 96);
		$this->assertEqualsWithDelta($expectedMargin, $objattr['margin_top'], 0.001);
		
		// Verify color (blue)
		$this->assertArrayHasKey('color', $objattr);
		$this->assertEquals(3, $objattr['color'][0]);
		$this->assertEquals(0, ord($objattr['color'][1]));
		$this->assertEquals(0, ord($objattr['color'][2]));
		$this->assertEquals(255, ord($objattr['color'][3]));
		
		$this->assertFalse($this->mpdf->ignorefollowingspaces);
	}
}
