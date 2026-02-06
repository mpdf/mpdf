<?php

namespace Mpdf\Tag;

class ListInTableTest extends BaseTagTestCase
{
	/**
	 * Helper to set up a table context so BlockTag takes the in-table code path.
	 */
	private function setUpTableContext()
	{
		$this->mpdf->tableLevel = 1;
		$this->mpdf->tdbegin = true;
		$this->mpdf->row = 0;
		$this->mpdf->col = 0;
		$this->mpdf->cell[0][0] = ['s' => 0];
	}

	/**
	 * Helper to extract the text content written to the cell textbuffer.
	 */
	private function getCellTextBufferContent()
	{
		$texts = [];
		if (!empty($this->mpdf->cell[0][0]['textbuffer'])) {
			foreach ($this->mpdf->cell[0][0]['textbuffer'] as $entry) {
				$texts[] = $entry[0];
			}
		}
		return $texts;
	}

	/**
	 * Helper to open an OL tag in table context.
	 */
	private function openOlInTable($attr = [])
	{
		$tag = $this->createTag(Ol::class);
		$ahtml = [];
		$ihtml = 0;
		$tag->open($attr, $ahtml, $ihtml);
		return $tag;
	}

	/**
	 * Helper to open a UL tag in table context.
	 */
	private function openUlInTable($attr = [])
	{
		$tag = $this->createTag(Ul::class);
		$ahtml = [];
		$ihtml = 0;
		$tag->open($attr, $ahtml, $ihtml);
		return $tag;
	}

	/**
	 * Helper to open a LI tag in table context.
	 */
	private function openLiInTable($attr = [])
	{
		$tag = $this->createTag(Li::class);
		$ahtml = [];
		$ihtml = 0;
		$tag->open($attr, $ahtml, $ihtml);
		return $tag;
	}

	// ============================================================
	// OL in table - default behavior
	// ============================================================

	public function testOlInTable_DefaultDecimalType()
	{
		$this->setUpTableContext();
		$this->openOlInTable();

		$this->assertEquals(1, $this->mpdf->listlvl);
		$this->assertEquals(0, $this->mpdf->listcounter[1]);
		$this->assertEquals('decimal', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_StartAttribute()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['START' => '5']);

		$this->assertEquals(4, $this->mpdf->listcounter[1]); // START - 1
	}

	// ============================================================
	// OL in table - HTML type attribute
	// ============================================================

	public function testOlInTable_TypeA_UpperLatin()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'A']);

		$this->assertEquals('upper-latin', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_TypeLowerA_LowerLatin()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'a']);

		$this->assertEquals('lower-latin', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_TypeI_UpperRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'I']);

		$this->assertEquals('upper-roman', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_TypeLowerI_LowerRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'i']);

		$this->assertEquals('lower-roman', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_Type1_Decimal()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => '1']);

		$this->assertEquals('decimal', $this->mpdf->listtype[1]);
	}

	// ============================================================
	// OL in table - CSS list-style-type overrides HTML type attribute
	// ============================================================

	public function testOlInTable_CssOverridesHtmlType()
	{
		$this->setUpTableContext();
		// HTML type="A" would set upper-latin, but CSS should override
		$this->openOlInTable(['TYPE' => 'A', 'STYLE' => 'list-style-type: lower-roman;']);

		$this->assertEquals('lower-roman', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeDecimal()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: decimal;']);

		$this->assertEquals('decimal', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeUpperAlpha()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: upper-alpha;']);

		$this->assertEquals('upper-alpha', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeLowerAlpha()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: lower-alpha;']);

		$this->assertEquals('lower-alpha', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeUpperRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: upper-roman;']);

		$this->assertEquals('upper-roman', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeLowerRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: lower-roman;']);

		$this->assertEquals('lower-roman', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeNone()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: none;']);

		$this->assertEquals('none', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeDisc()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: disc;']);

		$this->assertEquals('disc', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeCircle()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: circle;']);

		$this->assertEquals('circle', $this->mpdf->listtype[1]);
	}

	public function testOlInTable_CssListStyleTypeSquare()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: square;']);

		$this->assertEquals('square', $this->mpdf->listtype[1]);
	}

	// ============================================================
	// UL in table - default behavior cycles disc/circle/square
	// ============================================================

	public function testUlInTable_Level1_DefaultDisc()
	{
		$this->setUpTableContext();
		$this->openUlInTable();

		$this->assertEquals(1, $this->mpdf->listlvl);
		$this->assertEquals('disc', $this->mpdf->listtype[1]); // lvl 1 % 3 == 1 => disc
	}

	public function testUlInTable_Level2_DefaultCircle()
	{
		$this->setUpTableContext();
		// Open first UL to get to level 1
		$this->openUlInTable();
		// Open second UL to get to level 2
		$this->openUlInTable();

		$this->assertEquals(2, $this->mpdf->listlvl);
		$this->assertEquals('circle', $this->mpdf->listtype[2]); // lvl 2 % 3 == 2 => circle
	}

	public function testUlInTable_Level3_DefaultSquare()
	{
		$this->setUpTableContext();
		$this->openUlInTable();
		$this->openUlInTable();
		$this->openUlInTable();

		$this->assertEquals(3, $this->mpdf->listlvl);
		$this->assertEquals('square', $this->mpdf->listtype[3]); // lvl 3 % 3 == 0 => square
	}

	// ============================================================
	// UL in table - CSS list-style-type
	// ============================================================

	public function testUlInTable_CssListStyleTypeSquare()
	{
		$this->setUpTableContext();
		$this->openUlInTable(['STYLE' => 'list-style-type: square;']);

		$this->assertEquals('square', $this->mpdf->listtype[1]);
	}

	public function testUlInTable_CssListStyleTypeNone()
	{
		$this->setUpTableContext();
		$this->openUlInTable(['STYLE' => 'list-style-type: none;']);

		$this->assertEquals('none', $this->mpdf->listtype[1]);
	}

	public function testUlInTable_CssListStyleTypeDecimal()
	{
		$this->setUpTableContext();
		$this->openUlInTable(['STYLE' => 'list-style-type: decimal;']);

		$this->assertEquals('decimal', $this->mpdf->listtype[1]);
	}

	// ============================================================
	// LI in table - decimal markers
	// ============================================================

	public function testLiInTable_DecimalMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable();
		$this->openLiInTable();

		$this->assertEquals(1, $this->mpdf->listcounter[1]);
		$texts = $this->getCellTextBufferContent();
		// Should contain "1." (decimal counter + suffix)
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('1', $texts[0]);
	}

	public function testLiInTable_DecimalMarker_MultipleItems()
	{
		$this->setUpTableContext();
		$this->openOlInTable();

		$this->openLiInTable();
		$texts1 = $this->getCellTextBufferContent();
		$this->assertStringContainsString('1', $texts1[0]);

		$this->openLiInTable();
		$texts2 = $this->getCellTextBufferContent();
		$this->assertStringContainsString('2', end($texts2));
	}

	public function testLiInTable_DecimalMarker_WithStartAttribute()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['START' => '10']);
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertStringContainsString('10', $texts[0]);
	}

	// ============================================================
	// LI in table - upper-latin markers (type="A")
	// ============================================================

	public function testLiInTable_UpperLatinMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'A']);
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('A', $texts[0]);
	}

	// ============================================================
	// LI in table - lower-latin markers (type="a")
	// ============================================================

	public function testLiInTable_LowerLatinMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'a']);
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('a', $texts[0]);
	}

	// ============================================================
	// LI in table - upper-roman markers (type="I")
	// ============================================================

	public function testLiInTable_UpperRomanMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'I']);
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('I', $texts[0]);
	}

	// ============================================================
	// LI in table - lower-roman markers (type="i")
	// ============================================================

	public function testLiInTable_LowerRomanMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'i']);
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// lower-roman should output lowercase
		$this->assertStringContainsString('i', $texts[0]);
	}

	// ============================================================
	// LI in table - disc/circle/square markers
	// ============================================================

	public function testLiInTable_DiscMarker()
	{
		$this->setUpTableContext();
		$this->openUlInTable(); // Level 1 = disc
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// disc marker is bullet U+2022 or fallback '-'
		$marker = $texts[0];
		$this->assertTrue(
			strpos($marker, "\xe2\x80\xa2") !== false || strpos($marker, '-') !== false,
			'Disc marker should be bullet or dash'
		);
	}

	public function testLiInTable_CircleMarker()
	{
		$this->setUpTableContext();
		$this->openUlInTable();
		$this->openUlInTable(); // Level 2 = circle
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$marker = end($texts);
		$this->assertTrue(
			strpos($marker, "\xe2\x9a\xac") !== false || strpos($marker, '-') !== false,
			'Circle marker should be U+26AC or dash'
		);
	}

	public function testLiInTable_SquareMarker()
	{
		$this->setUpTableContext();
		$this->openUlInTable();
		$this->openUlInTable();
		$this->openUlInTable(); // Level 3 = square
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$marker = end($texts);
		$this->assertTrue(
			strpos($marker, "\xe2\x96\xaa") !== false || strpos($marker, '-') !== false,
			'Square marker should be U+25AA or dash'
		);
	}

	// ============================================================
	// LI in table - none marker
	// ============================================================

	public function testLiInTable_NoneMarker()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['STYLE' => 'list-style-type: none;']);
		$this->openLiInTable();

		// With list-style-type:none, no text should be written for the marker
		$texts = $this->getCellTextBufferContent();
		$this->assertEmpty($texts);
	}

	// ============================================================
	// LI in table - CSS on LI overrides parent OL type
	// ============================================================

	public function testLiInTable_CssOnLiOverridesOlType()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'A']); // upper-latin
		$this->openLiInTable(['STYLE' => 'list-style-type: lower-roman;']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// lower-roman should output lowercase
		$this->assertStringContainsString('i', $texts[0]);
	}

	// ============================================================
	// LI in table - HTML type attribute on LI overrides parent OL
	// ============================================================

	public function testLiInTable_HtmlTypeOnLiOverridesOl()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // decimal
		$this->openLiInTable(['TYPE' => 'A']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('A', $texts[0]);
	}

	public function testLiInTable_HtmlTypeOnLi_LowerLatin()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // decimal
		$this->openLiInTable(['TYPE' => 'a']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('a', $texts[0]);
	}

	public function testLiInTable_HtmlTypeOnLi_UpperRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // decimal
		$this->openLiInTable(['TYPE' => 'I']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('I', $texts[0]);
	}

	public function testLiInTable_HtmlTypeOnLi_LowerRoman()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // decimal
		$this->openLiInTable(['TYPE' => 'i']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// lower-roman should output lowercase
		$this->assertStringContainsString('i', $texts[0]);
	}

	public function testLiInTable_HtmlTypeOnLi_Decimal()
	{
		$this->setUpTableContext();
		$this->openOlInTable(['TYPE' => 'A']); // upper-latin
		$this->openLiInTable(['TYPE' => '1']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		$this->assertStringContainsString('1', $texts[0]);
	}

	// ============================================================
	// LI in table - CSS on LI takes highest priority over HTML type on LI
	// ============================================================

	public function testLiInTable_CssOnLiOverridesHtmlTypeOnLi()
	{
		$this->setUpTableContext();
		$this->openOlInTable();
		// HTML type="A" would give upper-latin, but CSS should win
		$this->openLiInTable(['TYPE' => 'A', 'STYLE' => 'list-style-type: lower-roman;']);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// lower-roman should output lowercase
		$this->assertStringContainsString('i', $texts[0]);
	}

	// ============================================================
	// LI in table - malformed HTML (LI without parent OL/UL)
	// ============================================================

	public function testLiInTable_WithoutParentList_TriggersError()
	{
		$this->setUpTableContext();
		// No OL/UL opened - listlvl should be 0
		$this->assertEquals(0, $this->mpdf->listlvl);

		// LI without parent OL/UL auto-increments listlvl to 1,
		// but listtype[1] is not initialized, causing an undefined array key notice/warning.
		// Use set_error_handler to catch the expected notice without PHPUnit deprecation warnings.
		$errorTriggered = false;
		set_error_handler(function ($errno) use (&$errorTriggered) {
			$errorTriggered = true;
			return true; // suppress
		});

		try {
			$this->openLiInTable();
		} finally {
			restore_error_handler();
		}

		$this->assertTrue($errorTriggered, 'Expected an undefined array key error for orphan LI in table');
	}

	// ============================================================
	// Nested lists in table
	// ============================================================

	public function testNestedOlInUlInTable()
	{
		$this->setUpTableContext();
		$this->openUlInTable(); // Level 1 - disc
		$this->openOlInTable(); // Level 2 - decimal
		$this->openLiInTable();

		$this->assertEquals(2, $this->mpdf->listlvl);
		$this->assertEquals('decimal', $this->mpdf->listtype[2]);

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
	}

	public function testNestedUlInOlInTable()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // Level 1 - decimal
		$this->openUlInTable(); // Level 2 - circle

		$this->assertEquals(2, $this->mpdf->listlvl);
		$this->assertEquals('circle', $this->mpdf->listtype[2]); // lvl 2 % 3 == 2 => circle
	}

	// ============================================================
	// Nested list indentation via nbsp
	// ============================================================

	public function testLiInTable_NestedIndentation()
	{
		$this->setUpTableContext();
		$this->openOlInTable(); // Level 1
		$this->openOlInTable(); // Level 2
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// Level 2 should have nbsp indentation before the marker
		$marker = end($texts);
		// Should contain non-breaking spaces for indentation (level-1)*2 pairs
		$this->assertTrue(
			strpos($marker, "\xc2\xa0") !== false,
			'Nested list marker should have nbsp indentation'
		);
	}

	public function testLiInTable_Level1_NoIndentation()
	{
		$this->setUpTableContext();
		$this->openOlInTable();
		$this->openLiInTable();

		$texts = $this->getCellTextBufferContent();
		$this->assertNotEmpty($texts);
		// Level 1 should have no nbsp indentation (level-1 = 0 pairs)
		$marker = $texts[0];
		$this->assertFalse(
			strpos($marker, "\xc2\xa0") !== false,
			'Level 1 list marker should not have nbsp indentation'
		);
	}

	// ============================================================
	// Close tags decrement list level
	// ============================================================

	public function testOlCloseInTable_DecrementsListLevel()
	{
		$this->setUpTableContext();
		$olTag = $this->createTag(Ol::class);
		$ahtml = [];
		$ihtml = 0;
		$olTag->open([], $ahtml, $ihtml);

		$this->assertEquals(1, $this->mpdf->listlvl);

		$olTag->close($ahtml, $ihtml);

		$this->assertEquals(0, $this->mpdf->listlvl);
	}

	public function testUlCloseInTable_DecrementsListLevel()
	{
		$this->setUpTableContext();
		$ulTag = $this->createTag(Ul::class);
		$ahtml = [];
		$ihtml = 0;
		$ulTag->open([], $ahtml, $ihtml);

		$this->assertEquals(1, $this->mpdf->listlvl);

		$ulTag->close($ahtml, $ihtml);

		$this->assertEquals(0, $this->mpdf->listlvl);
	}

	public function testNestedListCloseInTable_DecrementsCorrectly()
	{
		$this->setUpTableContext();
		$olTag = $this->createTag(Ol::class);
		$ulTag = $this->createTag(Ul::class);
		$ahtml = [];
		$ihtml = 0;

		$olTag->open([], $ahtml, $ihtml);
		$this->assertEquals(1, $this->mpdf->listlvl);

		$ulTag->open([], $ahtml, $ihtml);
		$this->assertEquals(2, $this->mpdf->listlvl);

		$ulTag->close($ahtml, $ihtml);
		$this->assertEquals(1, $this->mpdf->listlvl);

		$olTag->close($ahtml, $ihtml);
		$this->assertEquals(0, $this->mpdf->listlvl);
	}

	// ============================================================
	// Full HTML rendering via WriteHTML - integration tests
	// ============================================================

	public function testFullHtml_OlInTable_DecimalDefault()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol><li>First</li><li>Second</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_UlInTable_DiscDefault()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ul><li>Item A</li><li>Item B</li></ul></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_TypeA()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol type="A"><li>Alpha</li><li>Beta</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_TypeLowerA()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol type="a"><li>Alpha</li><li>Beta</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_TypeI()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol type="I"><li>One</li><li>Two</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_TypeLowerI()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol type="i"><li>One</li><li>Two</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_Type1()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<table><tr><td><ol type="1"><li>One</li><li>Two</li></ol></td></tr></table>');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_CssListStyleUpperRoman()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol style="list-style-type: upper-roman;">
					<li>One</li><li>Two</li><li>Three</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_CssListStyleLowerAlpha()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol style="list-style-type: lower-alpha;">
					<li>One</li><li>Two</li><li>Three</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlInTable_CssListStyleNone()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol style="list-style-type: none;">
					<li>One</li><li>Two</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_UlInTable_CssListStyleSquare()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ul style="list-style-type: square;">
					<li>One</li><li>Two</li>
				</ul>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_UlInTable_CssListStyleCircle()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ul style="list-style-type: circle;">
					<li>One</li><li>Two</li>
				</ul>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_UlInTable_CssListStyleNone()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ul style="list-style-type: none;">
					<li>One</li><li>Two</li>
				</ul>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_StylesheetListStyleType()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<style>
				ol.roman { list-style-type: upper-roman; }
				ol.alpha { list-style-type: lower-alpha; }
				ul.square { list-style-type: square; }
				ul.none { list-style-type: none; }
			</style>
			<table><tr><td>
				<ol class="roman"><li>One</li><li>Two</li></ol>
				<ol class="alpha"><li>One</li><li>Two</li></ol>
				<ul class="square"><li>One</li><li>Two</li></ul>
				<ul class="none"><li>One</li><li>Two</li></ul>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_LiStyleOverridesParent()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol type="A">
					<li>Upper Latin</li>
					<li style="list-style-type: decimal;">Decimal Override</li>
					<li>Back to Upper Latin</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_LiTypeAttributeOverridesParent()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol>
					<li>Decimal</li>
					<li type="A">Upper Latin</li>
					<li type="a">Lower Latin</li>
					<li type="I">Upper Roman</li>
					<li type="i">Lower Roman</li>
					<li type="1">Back to Decimal</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_NestedListsInTable()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol>
					<li>Item 1
						<ul>
							<li>Sub A</li>
							<li>Sub B</li>
						</ul>
					</li>
					<li>Item 2
						<ol type="a">
							<li>Sub a</li>
							<li>Sub b</li>
						</ol>
					</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_OlWithStartInTable()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol start="5">
					<li>Five</li><li>Six</li><li>Seven</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_AllListStyleTypesInTable()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol style="list-style-type: decimal;"><li>Decimal</li></ol>
				<ol style="list-style-type: upper-alpha;"><li>Upper Alpha</li></ol>
				<ol style="list-style-type: lower-alpha;"><li>Lower Alpha</li></ol>
				<ol style="list-style-type: upper-latin;"><li>Upper Latin</li></ol>
				<ol style="list-style-type: lower-latin;"><li>Lower Latin</li></ol>
				<ol style="list-style-type: upper-roman;"><li>Upper Roman</li></ol>
				<ol style="list-style-type: lower-roman;"><li>Lower Roman</li></ol>
				<ul style="list-style-type: disc;"><li>Disc</li></ul>
				<ul style="list-style-type: circle;"><li>Circle</li></ul>
				<ul style="list-style-type: square;"><li>Square</li></ul>
				<ul style="list-style-type: none;"><li>None</li></ul>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_CssOnLiOverridesParentAndHtmlType()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<table><tr><td>
				<ol type="I">
					<li>Roman I</li>
					<li type="A" style="list-style-type: decimal;">CSS wins over both</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFullHtml_StylesheetOnLiOverridesParent()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
			<style>
				li.special { list-style-type: upper-roman; }
			</style>
			<table><tr><td>
				<ol>
					<li>Normal decimal</li>
					<li class="special">Should be roman</li>
					<li>Back to decimal</li>
				</ol>
			</td></tr></table>
		');
		$output = $mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
