<?php

namespace Mpdf\Tag;

class ATest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(A::class);
	}

	public function testOpen_Link()
	{
		$attr = ['HREF' => 'http://example.com'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('http://example.com', $this->mpdf->HREF);
	}

	public function testClose_Link()
	{
		$ahtml = [];
		$ihtml = 0;

		// Call open first to properly initialize InlineProperties
		$attr = ['HREF' => 'http://example.com'];
		$this->tag->open($attr, $ahtml, $ihtml);

		// Now close should work properly
		$this->tag->close($ahtml, $ihtml);

		$this->assertEquals('', $this->mpdf->HREF);
		$this->assertArrayNotHasKey('A', $this->mpdf->InlineProperties);
	}

	public function testOpen_Anchor()
	{
		$attr = ['NAME' => 'anchor'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify anchor was added to textbuffer
		// A.php calls _saveTextBuffer($e, '', $attr['NAME']);
		// _saveTextBuffer stores the name in index 7
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferItem = end($this->mpdf->textbuffer);
		
		// Index 7 should contain the anchor name
		$this->assertArrayHasKey(7, $bufferItem);
		$this->assertEquals('anchor', $bufferItem[7]);
	}

	public function testOpen_Link_WithInlineStyle()
	{
		$attr = [
			'HREF' => 'http://example.com',
			'STYLE' => 'color: #FF0000; text-decoration: underline;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify HREF is set
		$this->assertEquals('http://example.com', $this->mpdf->HREF);
		
		// Verify CSS was applied - color should be set to red (RGB: 255, 0, 0)
		// Mode 3 is RGB
		$this->assertEquals(3, $this->mpdf->colorarray[0]);
		$this->assertEquals(255, ord($this->mpdf->colorarray[1]));
		$this->assertEquals(0, ord($this->mpdf->colorarray[2]));
		$this->assertEquals(0, ord($this->mpdf->colorarray[3]));
	}

	public function testOpen_Link_WithClass()
	{
		// Add a CSS rule for links
		$cssManager = $this->getService('cssManager');
		$cssManager->CSS['a']['COLOR'] = '#0000FF';
		
		$attr = ['HREF' => 'http://example.com', 'CLASS' => 'test-link'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		// Verify HREF is set
		$this->assertEquals('http://example.com', $this->mpdf->HREF);
		
		// Verify InlineProperties were saved
		$this->assertArrayHasKey('A', $this->mpdf->InlineProperties);
	}
}
