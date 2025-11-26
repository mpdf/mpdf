<?php

namespace Mpdf\Tag;

use Mpdf\Css\Border;
use Mpdf\Mpdf;

class TableTest extends BaseTagTestCase
{
	/**
	 * @var Table
	 */
	private $tag;

	protected function set_up()
	{
		parent::set_up();
		$this->tag = $this->createTag(Table::class);
	}

	public function testOpenBasicTable()
	{
		$attr = ['WIDTH' => '100%'];
		$ahtml = [];
		$ihtml = 0;

		$initialTableLevel = $this->mpdf->tableLevel;
		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals($initialTableLevel + 1, $this->mpdf->tableLevel);
		$this->assertArrayHasKey($this->mpdf->tableLevel, $this->mpdf->table);
		$this->assertEquals(1, $this->mpdf->tbctr[$this->mpdf->tableLevel]);
	}

	public function testOpenNestedTable()
	{
		$ahtml = [];
		$ihtml = 0;

		// Open first table
		$this->tag->open([], $ahtml, $ihtml);
		$this->assertEquals(1, $this->mpdf->tableLevel);

		// Simulate being in a cell
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = [
			'direction' => 'ltr',
			'a' => 'L',
			'cellLineHeight' => 1.2,
			'cellLineStackingStrategy' => 'inline-line-height',
			'cellLineStackingShift' => 'consider-shifts',
		];

		// Open nested table
		$this->tag->open([], $ahtml, $ihtml);
		$this->assertEquals(2, $this->mpdf->tableLevel);
		
		// Check inheritance
		$table = $this->mpdf->table[2][1];
		$this->assertEquals('ltr', $table['direction']);
		$this->assertEquals('L', $table['txta']);
		$this->assertEquals(1.2, $table['cellLineHeight']);
		
		// Verify nested position tracking
		$this->assertArrayHasKey('nestedpos', $table);
		$this->assertEquals([0, 0, 1], $table['nestedpos']);
	}

	public function testCssProperties()
	{
		$attr = [
			'STYLE' => 'width: 50%; direction: rtl; background-color: #ff0000; border: 1px solid black; font-family: serif; font-size: 14pt; font-weight: bold; font-style: italic; color: blue; letter-spacing: 2px; word-spacing: 5px; line-height: 1.5;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertEquals('rtl', $table['direction']);
		$this->assertEquals('#ff0000', $table['bgcolor'][-1]);
		$this->assertEquals(Border::ALL, $table['border']);
		$this->assertEquals('BOLD', $this->mpdf->base_table_properties['FONT-WEIGHT']);
		$this->assertEquals('ITALIC', $this->mpdf->base_table_properties['FONT-STYLE']);
		$this->assertEquals('blue', $this->mpdf->base_table_properties['COLOR']);
		$this->assertEquals('2px', $this->mpdf->base_table_properties['LETTER-SPACING']);
		$this->assertEquals('5px', $this->mpdf->base_table_properties['WORD-SPACING']);
	}

	public function testAttributes()
	{
		$attr = [
			'WIDTH' => '80%',
			'ALIGN' => 'center',
			'DIR' => 'rtl',
			'BGCOLOR' => '#00ff00',
			'CELLPADDING' => '5',
			'BORDER' => '1'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertEquals('C', $table['a']);
		$this->assertEquals('rtl', $table['direction']);
		$this->assertEquals('#00ff00', $table['bgcolor'][-1]);
		$this->assertEquals('5', $table['cell_padding']);
		$this->assertEquals(Border::ALL, $table['border']);
	}

	public function testRotation()
	{
		$attr = ['ROTATE' => '90'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertEquals(90, $this->mpdf->table_rotate);
	}

	public function testAutosize()
	{
		$attr = ['AUTOSIZE' => '2.5'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertEquals(2.5, $this->mpdf->shrink_this_table_to_fit);
	}

	public function testPageBreaks()
	{
		$attr = [
			'STYLE' => 'page-break-inside: avoid; page-break-after: always;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertTrue($this->mpdf->table_keep_together);
		$this->assertEquals('ALWAYS', $table['page_break_after']);
	}

	public function testBorderCollapse()
	{
		$attr = ['STYLE' => 'border-collapse: separate; border-spacing: 5px 10px;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertTrue($table['borders_separate']);
		// Note: border_spacing values are converted to mm, so we check they are set
		$this->assertNotFalse($table['border_spacing_H']);
		$this->assertNotFalse($table['border_spacing_V']);
	}

	public function testEmptyCells()
	{
		$attr = ['STYLE' => 'empty-cells: hide;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertEquals('hide', $table['empty_cells']);
	}

	public function testOverflow()
	{
		$attr = ['STYLE' => 'overflow: hidden;'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		$table = $this->mpdf->table[$this->mpdf->tableLevel][1];

		$this->assertEquals('hidden', $table['overflow']);
	}

	public function testCloseBasicTable()
	{
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open([], $ahtml, $ihtml);
		$this->assertEquals(1, $this->mpdf->tableLevel);
		
		$this->tag->close($ahtml, $ihtml);
		
		$this->assertEquals(0, $this->mpdf->tableLevel);
		$this->assertEmpty($this->mpdf->tbctr); // Reset
	}

	public function testCloseNestedTable()
	{
		$ahtml = [];
		$ihtml = 0;

		// Open parent
		$this->tag->open([], $ahtml, $ihtml);
		// Setup parent cell
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = [
			's' => 0,
			'direction' => 'ltr',
			'a' => 'L',
			'cellLineHeight' => 1.2,
			'cellLineStackingStrategy' => 'inline-line-height',
			'cellLineStackingShift' => 'consider-shifts',
		];
		
		// Open nested
		$this->tag->open([], $ahtml, $ihtml);
		$this->assertEquals(2, $this->mpdf->tableLevel);
		
		// Close nested
		$this->tag->close($ahtml, $ihtml);
		
		$this->assertEquals(1, $this->mpdf->tableLevel);
		$this->assertTrue($this->mpdf->tdbegin); // Should be back in TD context
		$this->assertTrue($this->mpdf->nestedtablejustfinished);
	}
}
