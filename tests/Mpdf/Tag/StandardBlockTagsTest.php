<?php

namespace Mpdf\Tag;

class StandardBlockTagsTest extends BaseTagTestCase
{
	/**
	 * @dataProvider blockTagsProvider
	 */
	public function testOpenAndClose_BlockTags($tagName, $className)
	{
		$tag = $this->createTag($className);

		$attr = ['ALIGN' => 'center'];
		$ahtml = [];
		$ihtml = 0;

		// Initial block level
		$initialBlkLvl = $this->mpdf->blklvl;

		// OPEN
		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level increased
		$this->assertEquals($initialBlkLvl + 1, $this->mpdf->blklvl, "Block level should increment");
		
		// Verify tag name is stored
		$this->assertEquals($tagName, $this->mpdf->blk[$this->mpdf->blklvl]['tag']);
		
		// Verify align attribute is processed
		$this->assertEquals('C', $this->mpdf->blk[$this->mpdf->blklvl]['block-align']);
		
		// Verify InlineProperties are saved for the new block
		$this->assertArrayHasKey('InlineProperties', $this->mpdf->blk[$this->mpdf->blklvl]);
	}

	public function blockTagsProvider()
	{
		return [
			['ADDRESS', Address::class],
			['ARTICLE', Article::class],
			['ASIDE', Aside::class],
			['BLOCKQUOTE', BlockQuote::class],
			['CENTER', Center::class],
			['DETAILS', Details::class],
			['DIV', Div::class],
			['FIELDSET', FieldSet::class],
			['FIGCAPTION', FigCaption::class],
			['FIGURE', Figure::class],
			['FOOTER', Footer::class],
			['HEADER', Header::class],
			['HGROUP', HGroup::class],
			['MAIN', Main::class],
			['NAV', Nav::class],
			['SECTION', Section::class],
			['SUMMARY', Summary::class],
		];
	}

	public function testCenterTag_InTable()
	{
		$tag = $this->createTag(Center::class);

		$this->mpdf->tableLevel = 1;
		$this->mpdf->tdbegin = true;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col] = ['s' => 0];

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify Center tag sets cell alignment to center
		$this->assertEquals('C', $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['a']);
	}

	public function testBlockTag_WithDisplayNone()
	{
		$tag = $this->createTag(Div::class);

		$attr = ['STYLE' => 'display: none;'];
		$ahtml = [];
		$ihtml = 0;

		$initialBlkLvl = $this->mpdf->blklvl;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify block level still increased
		$this->assertEquals($initialBlkLvl + 1, $this->mpdf->blklvl);
		
		// Verify hide flag is set
		$this->assertTrue($this->mpdf->blk[$this->mpdf->blklvl]['hide']);
	}

	public function testBlockTag_WithCssProperties()
	{
		$tag = $this->createTag(Div::class);

		$attr = ['STYLE' => 'margin: 10px; padding: 5px; text-align: right;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		$blk = $this->mpdf->blk[$this->mpdf->blklvl];
		
		// Verify margins are set (converted from px to mm: 10px ~= 2.65mm)
		// 10px * (25.4mm / 96dpi) = 2.645833333...
		$expectedMargin = 10 * (25.4 / 96);
		$this->assertEqualsWithDelta($expectedMargin, $blk['margin_top'], 0.001);
		$this->assertEqualsWithDelta($expectedMargin, $blk['margin_bottom'], 0.001);
		$this->assertEqualsWithDelta($expectedMargin, $blk['margin_left'], 0.001);
		$this->assertEqualsWithDelta($expectedMargin, $blk['margin_right'], 0.001);
		
		// Verify padding is set (converted from px to mm: 5px ~= 1.32mm)
		// 5px * (25.4mm / 96dpi) = 1.322916667...
		$expectedPadding = 5 * (25.4 / 96);
		$this->assertEqualsWithDelta($expectedPadding, $blk['padding_top'], 0.001);
		$this->assertEqualsWithDelta($expectedPadding, $blk['padding_bottom'], 0.001);
		$this->assertEqualsWithDelta($expectedPadding, $blk['padding_left'], 0.001);
		$this->assertEqualsWithDelta($expectedPadding, $blk['padding_right'], 0.001);
		
		// Verify text-align is set
		$this->assertEquals('R', $blk['align']);
	}

	public function testBlockTag_WithBorderProperties()
	{
		$tag = $this->createTag(Div::class);

		$attr = ['STYLE' => 'border: 2px solid red;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		$blk = $this->mpdf->blk[$this->mpdf->blklvl];
		
		// Verify border width is set (2px ~= 0.53mm)
		// 2px * (25.4mm / 96dpi) = 0.529166667...
		$expectedBorderWidth = 2 * (25.4 / 96);
		$this->assertEqualsWithDelta($expectedBorderWidth, $blk['border_top']['w'], 0.001);
		$this->assertEqualsWithDelta($expectedBorderWidth, $blk['border_bottom']['w'], 0.001);
		$this->assertEqualsWithDelta($expectedBorderWidth, $blk['border_left']['w'], 0.001);
		$this->assertEqualsWithDelta($expectedBorderWidth, $blk['border_right']['w'], 0.001);
		
		// Verify border style is solid
		$this->assertEquals('solid', $blk['border_top']['style']);
		$this->assertEquals('solid', $blk['border_bottom']['style']);
		$this->assertEquals('solid', $blk['border_left']['style']);
		$this->assertEquals('solid', $blk['border_right']['style']);
		
		// Verify border color is set (mPDF uses internal color representation)
		$this->assertNotEmpty($blk['border_top']['c']);
		$this->assertNotEmpty($blk['border_bottom']['c']);
		$this->assertNotEmpty($blk['border_left']['c']);
		$this->assertNotEmpty($blk['border_right']['c']);
	}

	public function testBlockTag_WithWidthAndHeight()
	{
		$tag = $this->createTag(Div::class);

		$attr = ['STYLE' => 'width: 100px; height: 50px;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		$blk = $this->mpdf->blk[$this->mpdf->blklvl];
		
		// Verify width is set (100px ~= 26.46mm)
		// 100px * (25.4mm / 96dpi) = 26.458333333...
		$expectedWidth = 100 * (25.4 / 96);
		$this->assertArrayHasKey('css_set_width', $blk);
		$this->assertEqualsWithDelta($expectedWidth, $blk['css_set_width'], 0.001);
		
		// Verify height is set (50px ~= 13.23mm)
		// 50px * (25.4mm / 96dpi) = 13.229166667...
		$expectedHeight = 50 * (25.4 / 96);
		$this->assertEqualsWithDelta($expectedHeight, $blk['css_set_height'], 0.001);
	}

	public function testBlockTag_WithBackgroundColor()
	{
		$tag = $this->createTag(Div::class);

		$attr = ['STYLE' => 'background-color: blue;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		$blk = $this->mpdf->blk[$this->mpdf->blklvl];
		
		// Verify background color is set (mPDF stores boolean flag)
		$this->assertArrayHasKey('bgcolor', $blk);
		$this->assertNotFalse($blk['bgcolor'], 'Background color should be set');
	}
}
