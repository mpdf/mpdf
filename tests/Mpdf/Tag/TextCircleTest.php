<?php

namespace Mpdf\Tag;

class TextCircleTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(TextCircle::class);
	}

	public function testOpen_BasicTextCircle()
	{
		$attr = ['R' => '20', 'TOP-TEXT' => 'Top', 'BOTTOM-TEXT' => 'Bottom'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify textcircle was added to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		$this->assertStringContainsString('type=image', $content); // TextCircle uses type=image internally with type=textcircle in objattr
		
		$parts = explode('objattr=', $content);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		$this->assertEquals('textcircle', $objattr['type']);
		$this->assertEquals('Top', $objattr['top-text']);
		$this->assertEquals('Bottom', $objattr['bottom-text']);
	}

	public function testOpen_HiddenTextCircle()
	{
		// Use display: none
		$attr = ['STYLE' => 'display: none'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify no output to textbuffer
		$this->assertEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_TextCircle_WithInlineStyle()
	{
		$attr = [
			'R' => '20',
			'TOP-TEXT' => 'Top',
			'BOTTOM-TEXT' => 'Bottom',
			'STYLE' => 'margin: 10px; padding: 5px;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		// TextCircle tag writes to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		// Extract objattr
		$parts = explode('objattr=', $content);
		$this->assertCount(2, $parts);
		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Verify margin/padding
		// TextCircle converts padding to padding_top etc.
		$this->assertArrayHasKey('padding_top', $objattr);
		// Padding 5px converted to points/mm.
		// We just check it's set and > 0
		$this->assertGreaterThan(0, $objattr['padding_top']);
	}
}
