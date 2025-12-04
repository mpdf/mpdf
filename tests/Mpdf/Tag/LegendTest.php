<?php

namespace Mpdf\Tag;

class LegendTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Legend::class);
	}

	public function testOpen_Legend()
	{
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertArrayHasKey('LEGEND', $this->mpdf->InlineProperties);
	}

	public function testOpen_Legend_WithInlineStyle()
	{
		$attr = ['STYLE' => 'color: #FF0000; font-size: 14pt;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertArrayHasKey('LEGEND', $this->mpdf->InlineProperties);
		
		// Verify CSS was applied
		// Color should be red (RGB: 255, 0, 0)
		$this->assertEquals(3, $this->mpdf->colorarray[0]);
		$this->assertEquals(255, ord($this->mpdf->colorarray[1]));
		$this->assertEquals(0, ord($this->mpdf->colorarray[2]));
		$this->assertEquals(0, ord($this->mpdf->colorarray[3]));
		$this->assertEquals(14, $this->mpdf->FontSizePt);
	}

	public function testClose_Legend()
	{
		$ahtml = [];
		$ihtml = 0;

		// Call open first to properly initialize InlineProperties
		$attr = [];
		$this->tag->open($attr, $ahtml, $ihtml);

		// Now close should work properly
		$this->tag->close($ahtml, $ihtml);

		// Just verify that close was called successfully
		$this->assertTrue($this->mpdf->ignorefollowingspaces);
	}
}
