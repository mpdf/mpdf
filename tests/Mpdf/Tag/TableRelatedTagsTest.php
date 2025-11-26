<?php

namespace Mpdf\Tag;

class TableRelatedTagsTest extends BaseTagTestCase
{
	public function testCaption_Open()
	{
		$tag = $this->createTag(Caption::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$initialBlkLvl = $this->mpdf->blklvl;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals($initialBlkLvl + 1, $this->mpdf->blklvl);
	}
}
