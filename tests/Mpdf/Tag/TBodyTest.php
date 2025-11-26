<?php

namespace Mpdf\Tag;

class TBodyTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(TBody::class);
	}

	public function testOpen_TBody()
	{
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TBODY', $this->mpdf->lastoptionaltag);
	}

	public function testOpen_TBody_WithInlineStyle()
	{
		$attr = ['STYLE' => 'background-color: #CCCCCC;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TBODY', $this->mpdf->lastoptionaltag);
		// Verify CSS cascade level increased
		$this->assertEquals(1, $this->getService('cssManager')->tbCSSlvl);
	}

	public function testClose_TBody()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->tag->close($ahtml, $ihtml);

		$this->assertEquals('', $this->mpdf->lastoptionaltag);
	}
}
