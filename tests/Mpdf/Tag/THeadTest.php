<?php

namespace Mpdf\Tag;

class THeadTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(THead::class);
	}

	public function testOpen_THead()
	{
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('THEAD', $this->mpdf->lastoptionaltag);
	}

	public function testOpen_THead_WithInlineStyle()
	{
		$attr = ['STYLE' => 'font-weight: bold; vertical-align: bottom;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('THEAD', $this->mpdf->lastoptionaltag);
		// Verify CSS properties were applied to THead specific properties
		$this->assertEquals('B', $this->mpdf->thead_font_weight);
		$this->assertEquals('bottom', $this->mpdf->thead_valign_default);
	}

	public function testClose_THead()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->tag->close($ahtml, $ihtml);

		$this->assertEquals('', $this->mpdf->lastoptionaltag);
		$this->assertTrue($this->mpdf->tabletheadjustfinished);
	}
}
