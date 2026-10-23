<?php

namespace Mpdf\Tag;

use Mpdf\TableBox;

class TdTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Td::class);
	}

	public function testOpen_Td()
	{
		$attr = ['ALIGN' => 'CENTER'];
		$ahtml = [];
		$ihtml = 0;

		// Initialize table context
		$this->mpdf->tableLevel = 1;
		$this->mpdf->tbctr = [1 => 0];
		$table = new TableBox(1, 0);
		$table->cellLineHeight = '';
		$table->cellLineStackingShift = 'disregard';
		$this->mpdf->table[1][0] = $table;
		$this->mpdf->row = 0;
		$this->mpdf->col = -1;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TD', $this->mpdf->lastoptionaltag);
		$this->assertEquals(0, $this->mpdf->col);
		$this->assertNotEmpty($this->mpdf->cell[0][0]);
	}

	public function testOpen_Td_WithInlineStyle()
	{
		$attr = ['STYLE' => 'text-align: right; vertical-align: middle;'];
		$ahtml = [];
		$ihtml = 0;

		// Initialize table context
		$this->mpdf->tableLevel = 1;
		$this->mpdf->tbctr = [1 => 0];
		$table = new TableBox(1, 0);
		$table->cellLineHeight = '';
		$table->cellLineStackingShift = 'disregard';
		$this->mpdf->table[1][0] = $table;
		$this->mpdf->row = 0;
		$this->mpdf->col = -1;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('TD', $this->mpdf->lastoptionaltag);
		// Verify CSS properties were applied
		// Td tag applies these to cell properties or table context
		$this->assertNotEmpty($this->mpdf->cell[0][0]);
		
		// 'R' for Right align
		$this->assertEquals('R', $this->mpdf->cell[0][0]['a']);
		// 'M' for Middle vertical align
		$this->assertEquals('M', $this->mpdf->cell[0][0]['va']);
	}

	public function testClose_Td()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->mpdf->tableLevel = 1;
		$this->mpdf->tdbegin = true;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 10, 'maxs' => 10];

		$this->tag->close($ahtml, $ihtml);

		$this->assertEquals('TR', $this->mpdf->lastoptionaltag);
		$this->assertFalse($this->mpdf->tdbegin);
	}
}
