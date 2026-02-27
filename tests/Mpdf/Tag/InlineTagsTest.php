<?php

namespace Mpdf\Tag;

class InlineTagsTest extends BaseTagTestCase
{
	/**
	 * @dataProvider inlineTagsProvider
	 */
	public function testOpenAndClose_InlineTags($tagName, $className)
	{
		$tag = $this->createTag($className);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		// OPEN
		$tag->open($attr, $ahtml, $ihtml);

		// Verify InlineProperties are saved
		// Note: VarTag uses 'VARTAG' as the tag name internally
		$expectedTagName = ($tagName === 'VAR') ? 'VARTAG' : $tagName;
		$this->assertArrayHasKey($expectedTagName, $this->mpdf->InlineProperties);
		$this->assertNotEmpty($this->mpdf->InlineProperties[$expectedTagName]);

		// CLOSE
		$tag->close($ahtml, $ihtml);

		// After close, the properties should be restored (popped from stack)
		// For most inline tags, the array should be empty after one open/close
		if (isset($this->mpdf->InlineProperties[$expectedTagName])) {
			$this->assertEmpty($this->mpdf->InlineProperties[$expectedTagName]);
		}
	}

	public function inlineTagsProvider()
	{
		return [
			['ACRONYM', Acronym::class],
			['B', B::class],
			['BIG', Big::class],
			['CITE', Cite::class],
			['CODE', Code::class],
			['DEL', Del::class],
			['EM', Em::class],
			['FONT', Font::class],
			['I', I::class],
			['INS', Ins::class],
			['KBD', Kbd::class],
			['MARK', Mark::class],
			['Q', Q::class],
			['S', S::class],
			['SAMP', Samp::class],
			['SMALL', Small::class],
			['SPAN', Span::class],
			['STRIKE', Strike::class],
			['STRONG', Strong::class],
			['SUB', Sub::class],
			['SUP', Sup::class],
			['TIME', Time::class],
			['TT', Tt::class],
			['U', U::class],
			['VAR', VarTag::class],
		];
	}

	public function testInlineTag_WithCssProperties()
	{
		$tag = $this->createTag(Span::class);

		$attr = ['STYLE' => 'color: red; font-size: 14pt; font-weight: bold;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify color is set
		$this->assertNotEmpty($this->mpdf->colorarray);
		
		// Verify font size is set
		$this->assertEquals(14, $this->mpdf->FontSizePt);
		
		// Verify font weight is set (B flag)
		$this->assertTrue($this->mpdf->B);
	}

	public function testInlineTag_WithAnnotation()
	{
		// Enable title2annots
		$this->mpdf->title2annots = true;
		
		$tag = $this->createTag(Span::class);

		$attr = ['TITLE' => 'This is a tooltip'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify annotation is stored
		$this->assertArrayHasKey('SPAN', $this->mpdf->InlineAnnots);
		$this->assertNotEmpty($this->mpdf->InlineAnnots['SPAN']);
	}

	public function testInlineTag_NestedTags()
	{
		$spanTag = $this->createTag(Span::class);
		$bTag = $this->createTag(B::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		// Open nested tags
		$spanTag->open($attr, $ahtml, $ihtml);
		$bTag->open($attr, $ahtml, $ihtml);

		// Verify both are in InlineProperties
		$this->assertArrayHasKey('SPAN', $this->mpdf->InlineProperties);
		$this->assertArrayHasKey('B', $this->mpdf->InlineProperties);

		// Close in reverse order
		$bTag->close($ahtml, $ihtml);
		$spanTag->close($ahtml, $ihtml);

		// Verify both are cleaned up
		$this->assertEmpty($this->mpdf->InlineProperties['SPAN']);
		$this->assertEmpty($this->mpdf->InlineProperties['B']);
	}

	public function testBdo_WithDirection()
	{
		$tag = $this->createTag(Bdo::class);

		$attr = ['DIR' => 'rtl'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify BDO sets bidirectional formatting
		$this->assertArrayHasKey('BDO', $this->mpdf->InlineBDF);
		$this->assertNotEmpty($this->mpdf->InlineBDF['BDO']);
		
		// Verify textbuffer has bidi control character
		$this->assertNotEmpty($this->mpdf->textbuffer);
		
		// Verify biDirectional flag is set
		$this->assertTrue($this->mpdf->biDirectional);
	}

	public function testBdi_WithDirection()
	{
		$tag = $this->createTag(Bdi::class);

		$attr = ['DIR' => 'ltr'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify BDI sets bidirectional formatting
		$this->assertArrayHasKey('BDI', $this->mpdf->InlineBDF);
		$this->assertNotEmpty($this->mpdf->InlineBDF['BDI']);
		
		// Verify textbuffer has bidi control character
		$this->assertNotEmpty($this->mpdf->textbuffer);
	}

	public function testBdi_WithoutDirection()
	{
		$tag = $this->createTag(Bdi::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify BDI sets bidirectional formatting (FSI - First Strong Isolate)
		$this->assertArrayHasKey('BDI', $this->mpdf->InlineBDF);
		$this->assertNotEmpty($this->mpdf->InlineBDF['BDI']);
	}

	public function testSpan_WithDirection()
	{
		$tag = $this->createTag(Span::class);

		$attr = ['DIR' => 'rtl'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify SPAN with DIR sets bidirectional formatting
		$this->assertArrayHasKey('SPAN', $this->mpdf->InlineBDF);
		$this->assertNotEmpty($this->mpdf->InlineBDF['SPAN']);
	}

	public function testInlineTag_WithTextDecoration()
	{
		$tag = $this->createTag(Span::class);

		$attr = ['STYLE' => 'text-decoration: underline;'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify text decoration is applied (stored in textvar)
		$this->assertNotEquals(0, $this->mpdf->textvar);
	}
}
