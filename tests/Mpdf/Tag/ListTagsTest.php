<?php

namespace Mpdf\Tag;

class ListTagsTest extends BaseTagTestCase
{
	public function testOl_Open()
	{
		$tag = $this->createTag(Ol::class);

		$attr = ['START' => '5'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
		
		// Verify list level increased
		$this->assertEquals(1, $this->mpdf->listlvl);
		
		// Verify START attribute sets counter
		$this->assertEquals(4, $this->mpdf->listcounter[1]); // START - 1
	}

	public function testOl_WithType()
	{
		$tag = $this->createTag(Ol::class);

		$attr = ['TYPE' => 'A'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify list type is set
		$this->assertEquals('upper-latin', $this->mpdf->blk[$this->mpdf->blklvl]['list_style_type']);
	}

	public function testUl_Open()
	{
		$tag = $this->createTag(Ul::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify list level increased
		$this->assertEquals(1, $this->mpdf->listlvl);
		
		// Verify counter initialized
		$this->assertEquals(0, $this->mpdf->listcounter[1]);
	}

	public function testUl_WithCssListStyle()
	{
		$tag = $this->createTag(Ul::class);

		$attr = ['STYLE' => 'list-style-type: square;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify list style type is set
		$this->assertEquals('square', $this->mpdf->blk[$this->mpdf->blklvl]['list_style_type']);
	}

	public function testLi_Open()
	{
		// Setup list context
		$this->mpdf->listlvl = 1;
		$this->mpdf->listcounter[1] = 0;
		$this->mpdf->listtype[1] = 'decimal';
		$this->mpdf->blk[1]['list_style_type'] = 'decimal';
		$this->mpdf->blk[1]['list_style_image'] = 'none';
		$this->mpdf->blk[1]['list_style_position'] = 'outside';
		
		$tag = $this->createTag(Li::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify counter incremented
		$this->assertEquals(1, $this->mpdf->listcounter[1]);
	}

	public function testDl_Open()
	{
		$tag = $this->createTag(Dl::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
	}

	public function testDt_Open()
	{
		$tag = $this->createTag(Dt::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
	}

	public function testDd_Open()
	{
		$tag = $this->createTag(Dd::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
	}

	public function testNestedLists()
	{
		$olTag = $this->createTag(Ol::class);
		$ulTag = $this->createTag(Ul::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		// Open outer list
		$olTag->open($attr, $ahtml, $ihtml);
		$this->assertEquals(1, $this->mpdf->listlvl);

		// Open nested list
		$ulTag->open($attr, $ahtml, $ihtml);
		$this->assertEquals(2, $this->mpdf->listlvl);
	}
}
