<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;

class BrTest extends BaseTagTestCase
{
	/**
	 * @var Br
	 */
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Br::class);
	}

	public function testOpen_BasicBr()
	{
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->ignorefollowingspaces);
		$this->assertTrue($this->mpdf->linebreakjustfinished);
		$this->assertFalse($this->mpdf->blockjustfinished);
	}

	public function testOpen_ClearBoth()
	{
		// Set up float divs on both left and right
		$this->mpdf->page = 1;
		$this->mpdf->y = 100;
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['blockContext'] = 1;
		
		// Add left float that ends at y=150
		$this->mpdf->floatDivs[] = [
			'side' => 'L',
			'startpos' => 1100, // page 1, y=100
			'endpos' => 1150,   // page 1, y=150
			'w' => 50,
			'blklvl' => 1,
			'blockContext' => 1
		];
		
		// Add right float that ends at y=170
		$this->mpdf->floatDivs[] = [
			'side' => 'R',
			'startpos' => 1100,
			'endpos' => 1170,   // page 1, y=170
			'w' => 50,
			'blklvl' => 1,
			'blockContext' => 1
		];
		
		$attr = ['STYLE' => 'clear: both'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// ClearFloats with BOTH should move y to max of both floats (170)
		$this->assertEquals(170, $this->mpdf->y);
		$this->assertTrue($this->mpdf->ignorefollowingspaces);
	}

	public function testOpen_ClearLeft()
	{
		// Set up left float only
		$this->mpdf->page = 1;
		$this->mpdf->y = 100;
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['blockContext'] = 1;
		
		// Add left float that ends at y=150
		$this->mpdf->floatDivs[] = [
			'side' => 'L',
			'startpos' => 1100,
			'endpos' => 1150,
			'w' => 50,
			'blklvl' => 1,
			'blockContext' => 1
		];
		
		$attr = ['STYLE' => 'clear: left'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// ClearFloats with LEFT should move y to left float end (150)
		$this->assertEquals(150, $this->mpdf->y);
		$this->assertTrue($this->mpdf->ignorefollowingspaces);
	}

	public function testOpen_ClearRight()
	{
		// Set up right float only
		$this->mpdf->page = 1;
		$this->mpdf->y = 100;
		$this->mpdf->blklvl = 0;
		$this->mpdf->blk[0]['blockContext'] = 1;
		
		// Add right float that ends at y=160
		$this->mpdf->floatDivs[] = [
			'side' => 'R',
			'startpos' => 1100,
			'endpos' => 1160,
			'w' => 50,
			'blklvl' => 1,
			'blockContext' => 1
		];
		
		$attr = ['STYLE' => 'clear: right'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// ClearFloats with RIGHT should move y to right float end (160)
		$this->assertEquals(160, $this->mpdf->y);
		$this->assertTrue($this->mpdf->ignorefollowingspaces);
	}

	public function testOpen_WithBlockBidiCode()
	{
		// Set up block-level bidi code
		$this->mpdf->blklvl = 1;
		$this->mpdf->blk[1]['bidicode'] = 'L';
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertTrue($this->mpdf->linebreakjustfinished);
	}

	public function testOpen_WithInlineBidiCode()
	{
		// Set up inline bidi codes
		$this->mpdf->InlineBDF = [
			0 => [
				['L', 0],
				['R', 1]
			]
		];
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertTrue($this->mpdf->linebreakjustfinished);
	}

	public function testOpen_InTableContext()
	{
		// Set up table context
		$this->mpdf->tableLevel = 1;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 10];
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify cell['maxs'] was set
		$this->assertEquals(10, $this->mpdf->cell[0][0]['maxs']);
		// Verify cell['s'] was reset
		$this->assertEquals(0, $this->mpdf->cell[0][0]['s']);
	}

	public function testOpen_InTableContext_WithBlockJustFinished()
	{
		// Set up table context with blockjustfinished
		$this->mpdf->tableLevel = 1;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 5];
		$this->mpdf->blockjustfinished = true;
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		$this->assertEquals(5, $this->mpdf->cell[0][0]['maxs']);
		$this->assertFalse($this->mpdf->blockjustfinished);
	}

	public function testOpen_InTableContext_UpdateMaxs()
	{
		// Set up table context where new s is greater than existing maxs
		$this->mpdf->tableLevel = 1;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 20, 'maxs' => 10];
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify maxs was updated
		$this->assertEquals(20, $this->mpdf->cell[0][0]['maxs']);
	}

	public function testOpen_OutsideTableContext_WithTextBuffer()
	{
		// Set up textbuffer with trailing space
		$this->mpdf->textbuffer = [
			['Text with trailing space ']
		];
		
		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);
		
		// Verify trailing space was removed
		$this->assertEquals('Text with trailing space', $this->mpdf->textbuffer[0][0]);
	}
}
