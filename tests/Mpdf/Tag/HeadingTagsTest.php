<?php

namespace Mpdf\Tag;

class HeadingTagsTest extends BaseTagTestCase
{
	/**
	 * @dataProvider headingTagsProvider
	 */
	public function testOpenAndClose_HeadingTags($tagName, $className)
	{
		$tag = $this->createTag($className);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$initialBlkLvl = $this->mpdf->blklvl;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals($initialBlkLvl + 1, $this->mpdf->blklvl);
		
		// Verify tag name is stored
		$this->assertEquals($tagName, $this->mpdf->blk[$this->mpdf->blklvl]['tag']);
	}

	public function headingTagsProvider()
	{
		return [
			['H1', H1::class],
			['H2', H2::class],
			['H3', H3::class],
			['H4', H4::class],
			['H5', H5::class],
			['H6', H6::class],
		];
	}

	public function testHeading_WithCssProperties()
	{
		$tag = $this->createTag(H1::class);

		$attr = ['STYLE' => 'color: blue; text-align: center; margin-top: 20px;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		$blk = $this->mpdf->blk[$this->mpdf->blklvl];
		
		// Verify CSS properties are processed
		$this->assertNotEmpty($blk);
		
		// Verify text-align is set
		$this->assertEquals('C', $blk['align']);
		
		// Verify margin-top is set
		$this->assertGreaterThan(0, $blk['margin_top']);
	}

	public function testHeading_WithId()
	{
		$tag = $this->createTag(H2::class);

		$attr = ['ID' => 'section-heading'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased (ID doesn't prevent tag processing)
		$this->assertEquals(1, $this->mpdf->blklvl);
		
		// Verify tag name is stored
		$this->assertEquals('H2', $this->mpdf->blk[$this->mpdf->blklvl]['tag']);
	}

	public function testHeading_WithPageBreak()
	{
		$tag = $this->createTag(H1::class);

		$attr = ['STYLE' => 'page-break-before: always;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased despite page-break
		$this->assertEquals(1, $this->mpdf->blklvl);
		
		// Verify tag name is stored
		$this->assertEquals('H1', $this->mpdf->blk[$this->mpdf->blklvl]['tag']);
	}
}
