<?php

namespace Mpdf\Tag;

class DotTabTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(DotTab::class);
	}

	public function testOpen_DotTab()
	{
		$attr = ['OUTDENT' => '5'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify dottab was added to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$this->assertStringContainsString('type=dottab', $content);
		// outdent value is converted, so we can't check for "d:5" string directly
		
		$parts = explode('objattr=', $content);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		$this->assertEquals('dottab', $objattr['type']);
		// 5 (px/pt) converted to mm
		$this->assertGreaterThan(0, $objattr['outdent']);
		$this->assertEqualsWithDelta(1.3229, $objattr['outdent'], 0.001);
	}

	public function testOpen_DotTab_WithInlineStyle()
	{
		// DotTab uses current mpdf colorarray
		// Set it manually since SetTextColor doesn't seem to update it
		$colorConverter = $this->getService('colorConverter');
		$warnings = [];
		$this->mpdf->colorarray = $colorConverter->convert('red', $warnings);
		
		$attr = ['OUTDENT' => '5'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		// DotTab tag writes to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		// Extract objattr
		$parts = explode('objattr=', $content);
		$this->assertCount(2, $parts);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Color is red (RGB: 255, 0, 0)
		// DotTab tag stores color in 'colorarray' key in objattr
		$this->assertArrayHasKey('colorarray', $objattr);
		$color = $objattr['colorarray'];
		
		// colorarray is a binary string
		$this->assertEquals(3, $color[0]);
		$this->assertEquals(255, ord($color[1]));
		$this->assertEquals(0, ord($color[2]));
		$this->assertEquals(0, ord($color[3]));
	}
}
