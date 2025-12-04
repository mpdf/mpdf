<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;

class ColumnsTest extends BaseTagTestCase
{
	/**
	 * @var Columns
	 */
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Columns::class);
	}

	public function testOpen_Columns_Basic()
	{
		$attr = ['COLUMN-COUNT' => '3', 'COLUMN-GAP' => '7'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->ignorefollowingspaces);
		$this->assertEquals(1, $this->mpdf->ColActive);
		$this->assertEquals(3, $this->mpdf->NbCol);
		$this->assertEquals(7, $this->mpdf->ColGap);
	}

	public function testOpen_Columns_Valign()
	{
		// Test Justify
		$attr = ['COLUMN-COUNT' => '2', 'VALIGN' => 'J'];
		$ahtml = [];
		$ihtml = 0;
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertEquals('J', $this->mpdf->colvAlign);

		// Test Top (SetColumns overrides non-J values to empty string)
		$attr = ['COLUMN-COUNT' => '2', 'VALIGN' => 'top'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertEquals('', $this->mpdf->colvAlign);
	}

	public function testOpen_Columns_ClosesBlockTags()
	{
		// Simulate an open block tag with necessary properties to avoid undefined index errors
		$this->mpdf->blklvl = 1;
		$this->mpdf->blk[1] = [
			'tag' => 'DIV',
			'margin_top' => 0,
			'margin_bottom' => 0,
			'margin_left' => 0,
			'margin_right' => 0,
			'padding_top' => 0,
			'padding_bottom' => 0,
			'padding_left' => 0,
			'padding_right' => 0,
			'border_top' => ['w' => 0],
			'border_bottom' => ['w' => 0],
			'border_left' => ['w' => 0],
			'border_right' => ['w' => 0],
			'float' => '',
			'blockContext' => 0,
			'outer_left_margin' => 0,
			'outer_right_margin' => 0,
			'InlineProperties' => [
				'size' => 12,
				'family' => 'serif',
				'style' => '',
			],
			'line_height' => 1.2,
			'width' => 100,
			'bgcolor' => false,
			'w' => 100,
			'keep_block_together' => false,
		];
		// Also need fonts array populated for the font key
		$this->mpdf->fonts['serif'] = ['desc' => []];
		
		$attr = ['COLUMN-COUNT' => '2'];
		$ahtml = [];
		$ihtml = 0;
		
		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testOpen_Columns_FlushesBuffer()
	{
		// Ensure blk[0] has necessary properties for printbuffer
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0] = [
			'InlineProperties' => [
				'size' => 12,
				'family' => 'serif',
				'style' => '',
			],
			'line_height' => 1.2,
			'blockContext' => 0,
			'width' => 100,
			'padding_top' => 0,
			'padding_bottom' => 0,
			'padding_left' => 0,
			'padding_right' => 0,
			'border_top' => ['w' => 0],
			'border_bottom' => ['w' => 0],
			'border_left' => ['w' => 0],
			'border_right' => ['w' => 0],
			'bgcolor' => false,
			'w' => 100,
			'outer_left_margin' => 0,
			'outer_right_margin' => 0,
			'margin_top' => 0,
			'margin_bottom' => 0,
			'margin_left' => 0,
			'margin_right' => 0,
		];
		// Also need fonts array populated for the font key
		$this->mpdf->fonts['serif'] = ['desc' => []];

		// textbuffer expects indexed array with text at index 0
		$this->mpdf->textbuffer = [['Some text']];
		
		$attr = ['COLUMN-COUNT' => '2'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEmpty($this->mpdf->textbuffer);
	}

	public function testOpen_Columns_ZeroCount()
	{
		// COLUMN-COUNT < 2 turns columns off
		$this->mpdf->ColActive = 1;
		
		$attr = ['COLUMN-COUNT' => '1'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals(0, $this->mpdf->ColActive);
		
		// Test with 0
		$this->mpdf->ColActive = 1;
		$attr = ['COLUMN-COUNT' => '0'];
		$this->tag->open($attr, $ahtml, $ihtml);
		$this->assertEquals(0, $this->mpdf->ColActive);
	}
	
	public function testClose()
	{
		$ahtml = [];
		$ihtml = 0;
		$this->tag->close($ahtml, $ihtml);
		$this->assertTrue(true); // Should do nothing and not error
	}
}
