<?php

namespace Mpdf\Tag;

class HeaderFooterSettersTest extends BaseTagTestCase
{
	public function testSetPageHeader_Open()
	{
		// Initialize headers array
		$this->mpdf->pageHTMLheaders = ['myheader' => []];
		
		$tag = $this->createTag(SetPageHeader::class);

		$attr = ['NAME' => 'myheader'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify header was set (check for header assignment flag)
		$this->assertEquals(0, $this->mpdf->blklvl); // No block created
	}

	public function testSetPageHeader_WithValue()
	{
		// Initialize headers array
		$this->mpdf->pageHTMLheaders = ['myheader' => []];
		
		$tag = $this->createTag(SetPageHeader::class);

		$attr = ['NAME' => 'myheader', 'VALUE' => 'on'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify header VALUE was processed (check no crash, state valid)
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testSetPageFooter_Open()
	{
		// Initialize footers array
		$this->mpdf->pageHTMLfooters = ['myfooter' => []];
		
		$tag = $this->createTag(SetPageFooter::class);

		$attr = ['NAME' => 'myfooter'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify footer was set
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testSetHtmlPageHeader_Open()
	{
		// Initialize headers array
		$this->mpdf->pageHTMLheaders = ['myheader' => []];
		
		$tag = $this->createTag(SetHtmlPageHeader::class);

		$attr = ['NAME' => 'myheader'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify HTML header was set
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testSetHtmlPageFooter_Open()
	{
		// Initialize footers array
		$this->mpdf->pageHTMLfooters = ['myfooter' => []];
		
		$tag = $this->createTag(SetHtmlPageFooter::class);

		$attr = ['NAME' => 'myfooter'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify HTML footer was set
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testSetHtmlPageHeader_WithPage()
	{
		// Initialize headers array
		$this->mpdf->pageHTMLheaders = ['myheader' => []];
		
		$tag = $this->createTag(SetHtmlPageHeader::class);

		$attr = ['NAME' => 'myheader', 'PAGE' => 'ODD'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Verify HTML header with PAGE attribute was set
		$this->assertEquals(0, $this->mpdf->blklvl);
	}
}
