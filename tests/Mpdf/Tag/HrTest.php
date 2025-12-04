<?php

namespace Mpdf\Tag;

class HrTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Hr::class);
	}

	public function testOpen_BasicHr()
	{
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify hr was added to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$this->assertStringContainsString('type=image', $content); // HR uses type=image internally with type=hr in objattr
		
		$parts = explode('objattr=', $content);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		$this->assertEquals('hr', $objattr['type']);
	}

	public function testOpen_StyledHr()
	{
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['inner_width'] = 100;
		
		// Use STYLE to ensure width overrides default CSS (which might be 100%)
		$attr = ['STYLE' => 'width: 50px; color: red'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify properties
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$parts = explode('objattr=', $content);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		$this->assertEquals('hr', $objattr['type']);
		
		// 50px converted to mm (96 DPI)
		// 50 * 25.4 / 96 = 13.229166666667
		$this->assertEqualsWithDelta(13.229, $objattr['width'], 0.001);
		
		// Color red
		$this->assertArrayHasKey('color', $objattr);
		$this->assertEquals(3, $objattr['color'][0]);
		$this->assertEquals(255, ord($objattr['color'][1]));
		$this->assertEquals(0, ord($objattr['color'][2]));
		$this->assertEquals(0, ord($objattr['color'][3]));
	}

	public function testOpen_Hr_WithInlineStyle()
	{
		$attr = ['STYLE' => 'width: 80%; height: 2px; color: blue; background-color: blue;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		// Hr tag restores properties, so we check the textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		// Extract objattr
		$parts = explode('objattr=', $content);
		$this->assertCount(2, $parts);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Color is blue (RGB: 0, 0, 255)
		// Hr tag stores color in 'color' key in objattr
		$this->assertArrayHasKey('color', $objattr);
		$color = $objattr['color'];
		
		$this->assertEquals(3, $color[0]);
		$this->assertEquals(0, ord($color[1]));
		$this->assertEquals(0, ord($color[2]));
		$this->assertEquals(255, ord($color[3]));
	}
}
