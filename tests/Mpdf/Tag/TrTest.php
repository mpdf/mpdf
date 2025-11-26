<?php

namespace Mpdf\Tag;

class TrTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Tr::class);
	}

	public function testOpen_Tr()
	{
		$attr = ['BGCOLOR' => 'red'];
		$ahtml = [];
		$ihtml = 0;

		// Initialize table context
		$this->mpdf->tableLevel = 1;
		$this->mpdf->tbctr = [1 => 0];
		$this->mpdf->table[1][0] = ['nr' => 0, 'bgcolor' => []];
		$this->mpdf->row = 0;
		$this->mpdf->col = -1;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TR', $this->mpdf->lastoptionaltag);
		$this->assertEquals(1, $this->mpdf->row);
		$this->assertEquals(-1, $this->mpdf->col);
		$this->assertEquals('red', $this->mpdf->table[1][0]['bgcolor'][1]);
	}

	public function testOpen_Tr_WithInlineStyle()
	{
		$attr = ['STYLE' => 'background-color: blue;'];
		$ahtml = [];
		$ihtml = 0;

		// Initialize table context
		$this->mpdf->tableLevel = 1;
		$this->mpdf->tbctr = [1 => 0];
		$this->mpdf->table[1][0] = ['nr' => 0, 'bgcolor' => []];
		$this->mpdf->row = 0;
		$this->mpdf->col = -1;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TR', $this->mpdf->lastoptionaltag);
		$this->assertNotEmpty($this->mpdf->table[1][0]['bgcolor']);
		// Blue is RGB: 0, 0, 255
		// The structure might be complex, but let's check the last added bgcolor
		$bgcolor = end($this->mpdf->table[1][0]['bgcolor']);
		
		// Tr tag stores raw color string if not converted
		// In this case it seems to be 'blue'
		$this->assertEquals('blue', $bgcolor);
	}

	public function testClose_Tr()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->mpdf->row = 1;
		$this->mpdf->col = 0;
		$this->mpdf->cell[1][0] = ['borderbin' => ''];

		$this->tag->close($ahtml, $ihtml);

		$this->assertEquals('', $this->mpdf->lastoptionaltag);
	}
}
