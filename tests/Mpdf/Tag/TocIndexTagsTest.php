<?php

namespace Mpdf\Tag;

class TocIndexTagsTest extends BaseTagTestCase
{
	public function testToc_Open()
	{
		$tag = $this->createTag(Toc::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// TOC is a marker tag, doesn't create blocks
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify TOC marker was set in tableOfContents
		// Use reflection to access private tableOfContents property from Mpdf
		$mpdfReflection = new \ReflectionClass($this->mpdf);
		$tocProp = $mpdfReflection->getProperty('tableOfContents');
		$tocProp->setAccessible(true);
		$tableOfContents = $tocProp->getValue($this->mpdf);
		
		// Now access TOC properties
		$tocReflection = new \ReflectionClass($tableOfContents);
		$tocMarkProp = $tocReflection->getProperty('TOCmark');
		$tocMarkProp->setAccessible(true);
		$this->assertEquals(1, $tocMarkProp->getValue($tableOfContents), 'TOC marker should be set to current page');
		
		$tocUsePagingProp = $tocReflection->getProperty('TOCusePaging');
		$tocUsePagingProp->setAccessible(true);
		$this->assertTrue($tocUsePagingProp->getValue($tableOfContents), 'TOC should use paging by default');
		
		$tocUseLinkingProp = $tocReflection->getProperty('TOCuseLinking');
		$tocUseLinkingProp->setAccessible(true);
		$this->assertFalse($tocUseLinkingProp->getValue($tableOfContents), 'TOC should not use linking by default');
	}

	public function testToc_WithAttributes()
	{
		$tag = $this->createTag(Toc::class);

		$attr = ['PAGING' => 'OFF', 'LINKS' => 'ON'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// TOC with attributes is still a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify attributes are applied to TOC settings using reflection
		$mpdfReflection = new \ReflectionClass($this->mpdf);
		$tocProp = $mpdfReflection->getProperty('tableOfContents');
		$tocProp->setAccessible(true);
		$tableOfContents = $tocProp->getValue($this->mpdf);
		
		$tocReflection = new \ReflectionClass($tableOfContents);
		$tocUsePagingProp = $tocReflection->getProperty('TOCusePaging');
		$tocUsePagingProp->setAccessible(true);
		$this->assertFalse($tocUsePagingProp->getValue($tableOfContents), 'PAGING should be disabled when set to OFF');
		
		$tocUseLinkingProp = $tocReflection->getProperty('TOCuseLinking');
		$tocUseLinkingProp->setAccessible(true);
		$this->assertTrue($tocUseLinkingProp->getValue($tableOfContents), 'LINKS should be enabled when set to ON');
	}

	public function testTocEntry_Open()
	{
		$tag = $this->createTag(TocEntry::class);

		$attr = ['CONTENT' => 'Test Entry'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// TOC entry is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify textbuffer contains the TOC entry object
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$this->assertCount(1, $this->mpdf->textbuffer);
		
		// Extract and verify the serialized object
		$bufferContent = $this->mpdf->textbuffer[0][0];
		$this->assertStringContainsString('type=toc', $bufferContent);
		
		// Deserialize and verify object attributes
		preg_match('/objattr=(.+)' . preg_quote(\Mpdf\Mpdf::OBJECT_IDENTIFIER, '/') . '/', $bufferContent, $matches);
		$this->assertNotEmpty($matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('Test Entry', $objattr['CONTENT']);
		$this->assertEquals('toc', $objattr['type']);
		$this->assertEquals(0, $objattr['toclevel']);
		$this->assertEquals('T', $objattr['vertical-align']);
	}

	public function testTocEntry_WithLevel()
	{
		$tag = $this->createTag(TocEntry::class);

		$attr = ['CONTENT' => 'Test Entry', 'LEVEL' => '2'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// TOC entry with level is still a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify textbuffer contains the TOC entry object with level
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferContent = $this->mpdf->textbuffer[0][0];
		
		// Deserialize and verify level attribute
		preg_match('/objattr=(.+)' . preg_quote(\Mpdf\Mpdf::OBJECT_IDENTIFIER, '/') . '/', $bufferContent, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('Test Entry', $objattr['CONTENT']);
		$this->assertEquals('2', $objattr['toclevel']);
	}

	public function testIndexEntry_Open()
	{
		$tag = $this->createTag(IndexEntry::class);

		$attr = ['CONTENT' => 'Test Index'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Index entry is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify textbuffer contains the index entry object
		$this->assertNotEmpty($this->mpdf->textbuffer);
		$bufferContent = $this->mpdf->textbuffer[0][0];
		$this->assertStringContainsString('type=indexentry', $bufferContent);
		
		// Deserialize and verify object attributes
		preg_match('/objattr=(.+)' . preg_quote(\Mpdf\Mpdf::OBJECT_IDENTIFIER, '/') . '/', $bufferContent, $matches);
		$objattr = unserialize($matches[1]);
		
		$this->assertEquals('Test Index', $objattr['CONTENT']);
		$this->assertEquals('indexentry', $objattr['type']);
		$this->assertEquals('T', $objattr['vertical-align']);
	}

	public function testIndexInsert_Open()
	{
		// Add some index entries first
		$this->mpdf->Reference[] = ['t' => 'Apple', 'p' => [1]];
		$this->mpdf->Reference[] = ['t' => 'Banana', 'p' => [2]];
		
		$tag = $this->createTag(IndexInsert::class);

		$attr = [];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Index insert is a marker tag
		$this->assertEquals(0, $this->mpdf->blklvl);
		
		// Verify IndexInsert processed the Reference array
		// InsertIndex() sorts and formats entries, adding 'uf' (unformatted) field
		$this->assertArrayHasKey('uf', $this->mpdf->Reference[0], 'Reference entries should have unformatted field');
		$this->assertArrayHasKey('uf', $this->mpdf->Reference[1], 'Reference entries should have unformatted field');
		
		// Verify entries are sorted alphabetically
		$this->assertEquals('Apple', $this->mpdf->Reference[0]['uf']);
		$this->assertEquals('Banana', $this->mpdf->Reference[1]['uf']);
	}
}
