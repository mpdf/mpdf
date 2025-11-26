<?php

namespace Mpdf\Tag;

class ImgTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Img::class);
	}

	public function testOpen_BasicImg()
	{
		$attr = ['SRC' => __DIR__ . '/../../data/img/bayeux2.jpg'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		// Img tag writes to textbuffer
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		// Extract objattr
		$parts = explode('objattr=', $content);

		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Verify vertical-align is baseline ('BS') by default
		$this->assertArrayHasKey('vertical-align', $objattr);
		$this->assertEquals('BS', $objattr['vertical-align']);
	}

	public function testOpen_HiddenImg()
	{
		$attr = ['SRC' => __DIR__ . '/../../data/img/bayeux2.jpg', 'STYLE' => 'display: none'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify CSS properties were processed
		// display: none should result in no output to textbuffer
		$this->assertEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_Img_WithInlineStyle()
	{
		$attr = ['SRC' => __DIR__ . '/../../data/img/bayeux2.jpg', 'STYLE' => 'margin: 10px; border: 1px solid black;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify CSS properties were processed
		// Img tag processes margins and borders into objattr
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		$content = $bufferItem[0];
		
		// Extract objattr
		$parts = explode('objattr=', $content);

		$serialized = substr($parts[1], 0, strpos($parts[1], \Mpdf\Mpdf::OBJECT_IDENTIFIER));
		$objattr = unserialize($serialized);
		
		// Verify margins are set (10px converted to mm at 96 DPI)
		// 10 * 25.4 / 96 = 2.6458333333333
		$expectedMargin = 10 * (25.4 / 96);
		$this->assertSame($expectedMargin, $objattr['margin_top']);
		$this->assertSame($expectedMargin, $objattr['margin_bottom']);
		$this->assertSame($expectedMargin, $objattr['margin_left']);
		$this->assertSame($expectedMargin, $objattr['margin_right']);

		// Verify borders are set (1px converted to mm at 96 DPI)
		// 1 * 25.4 / 96 = 0.26458333333333
		$expectedBorder = 1 * (25.4 / 96);
		$this->assertSame($expectedBorder, $objattr['border_top']['w']);
		$this->assertSame($expectedBorder, $objattr['border_bottom']['w']);
		$this->assertSame($expectedBorder, $objattr['border_left']['w']);
		$this->assertSame($expectedBorder, $objattr['border_right']['w']);
	}
}
