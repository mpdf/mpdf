<?php

namespace Mpdf\Tag;

class PageLayoutTagsTest extends BaseTagTestCase
{
	/**
	 * @dataProvider pageLayoutTagsProvider
	 */
	public function testPageLayoutTags($tagName, $className)
	{
		$tag = $this->createTag($className);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		// These tags typically trigger page/column breaks
		// Verify tag executes and Mpdf state remains valid
		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify Mpdf object is still in valid state
		$this->assertGreaterThanOrEqual(0, $this->mpdf->blklvl);
		$this->assertGreaterThanOrEqual(1, $this->mpdf->page);
	}

	public function pageLayoutTagsProvider()
	{
		return [
			['COLUMNBREAK', ColumnBreak::class],
			['FORMFEED', FormFeed::class],
			['NEWCOLUMN', NewColumn::class],
			['NEWPAGE', NewPage::class],
			['PAGEBREAK', PageBreak::class],
			['TOCPAGEBREAK', TocPageBreak::class],
		];
	}

	public function testNewPage_WithOrientation()
	{
		$tag = $this->createTag(NewPage::class);

		$attr = ['ORIENTATION' => 'L'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify tag executed successfully
		$this->assertGreaterThanOrEqual(0, $this->mpdf->blklvl);
		$this->assertGreaterThanOrEqual(1, $this->mpdf->page);
	}

	public function testPageBreak_WithType()
	{
		$tag = $this->createTag(PageBreak::class);

		$attr = ['TYPE' => 'NEXT-ODD'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify tag executed successfully
		$this->assertGreaterThanOrEqual(0, $this->mpdf->blklvl);
		$this->assertGreaterThanOrEqual(1, $this->mpdf->page);
	}
}
