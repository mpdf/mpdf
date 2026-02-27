<?php

namespace Mpdf\Tag;

class SpecialTagsTest extends BaseTagTestCase
{
	public function testAnnotation_Open()
	{
		$tag = $this->createTag(Annotation::class);

		$attr = ['CONTENT' => 'Test annotation'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Annotation is a marker tag, doesn't create blocks
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify it adds to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$lastItem = end($this->mpdf->textbuffer);
		$this->assertStringContainsString('type=annot', $lastItem[0]);
		$this->assertStringContainsString('Test annotation', $lastItem[0]);
	}

	public function testAnnotation_WithIcon()
	{
		$tag = $this->createTag(Annotation::class);

		$attr = ['CONTENT' => 'Test annotation', 'ICON' => 'Note'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Annotation with ICON still doesn't create blocks
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify it adds to textbuffer with icon
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$lastItem = end($this->mpdf->textbuffer);
		$this->assertStringContainsString('type=annot', $lastItem[0]);
		$this->assertStringContainsString('ICON";s:4:"Note"', $lastItem[0]);
	}

	public function testBookmark_Open()
	{
		$tag = $this->createTag(Bookmark::class);

		$attr = ['CONTENT' => 'Test bookmark'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Bookmark is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify it adds to textbuffer
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$lastItem = end($this->mpdf->textbuffer);
		$this->assertStringContainsString('type=bookmark', $lastItem[0]);
		$this->assertStringContainsString('Test bookmark', $lastItem[0]);
	}

	public function testBookmark_WithLevel()
	{
		$tag = $this->createTag(Bookmark::class);

		$attr = ['CONTENT' => 'Test bookmark', 'LEVEL' => '1'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Bookmark with LEVEL still marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify it adds to textbuffer with level
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$lastItem = end($this->mpdf->textbuffer);
		$this->assertStringContainsString('type=bookmark', $lastItem[0]);
		$this->assertStringContainsString('bklevel";s:1:"1"', $lastItem[0]);
	}

	public function testPre_Open()
	{
		$tag = $this->createTag(Pre::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
	}

	public function testWatermarkImage_Open()
	{
		$tag = $this->createTag(WatermarkImage::class);

		$attr = ['SRC' => 'test.jpg'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// WatermarkImage is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify watermark properties set
		$this->assertEquals('test.jpg', $this->mpdf->watermarkImage);
	}

	public function testWatermarkText_Open()
	{
		$tag = $this->createTag(WatermarkText::class);

		$attr = ['CONTENT' => 'DRAFT'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// WatermarkText is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify watermark properties set
		$this->assertEquals('DRAFT', $this->mpdf->watermarkText);
	}

	public function testWatermarkText_WithAlpha()
	{
		$tag = $this->createTag(WatermarkText::class);

		$attr = ['CONTENT' => 'DRAFT', 'ALPHA' => '0.3'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// WatermarkText with ALPHA still marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify watermark properties set
		$this->assertEquals('DRAFT', $this->mpdf->watermarkText);
		$this->assertEquals(0.3, $this->mpdf->watermarkTextAlpha);
	}

	public function testTta_Open()
	{
		$tag = $this->createTag(Tta::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Tta (text transform all-caps) is inline, doesn't create blocks
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify tta property set
		$this->assertTrue($this->mpdf->tta);
	}

	public function testTts_Open()
	{
		$tag = $this->createTag(Tts::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Tts (text transform small-caps) is inline
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify tts property set
		$this->assertTrue($this->mpdf->tts);
	}

	public function testTtz_Open()
	{
		$tag = $this->createTag(Ttz::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Ttz (text transform) is inline
		$this->assertEquals(0, $this->mpdf->blklvl);
		// Verify ttz property set
		$this->assertTrue($this->mpdf->ttz);
	}
}
