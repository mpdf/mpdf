<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;

class PageFooterTest extends BaseTagTestCase
{
	/**
	 * @var PageFooter
	 */
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(PageFooter::class);
	}

	public function testOpen_BasicFooter()
	{
		$attr = ['NAME' => '', 'CONTENT-LEFT' => 'Left', 'CONTENT-RIGHT' => 'Right'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertTrue($this->mpdf->ignorefollowingspaces);
		
		// Verify default footer is set
		$pageHTMLfooters = $this->mpdf->pageHTMLfooters;
		$this->assertArrayHasKey('_nonhtmldefault', $pageHTMLfooters);

		$footer = $pageHTMLfooters['_nonhtmldefault'];
		$this->assertStringContainsString('Left', $footer['html']);
		$this->assertStringContainsString('Right', $footer['html']);
	}

	public function testOpen_NamedFooter()
	{
		$attr = ['NAME' => 'myfooter', 'CONTENT-CENTER' => 'Center'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$pageHTMLfooters = $this->mpdf->pageHTMLfooters;

		$this->assertArrayHasKey('myfooter', $pageHTMLfooters);
		$footer = $pageHTMLfooters['myfooter'];
		$this->assertStringContainsString('Center', $footer['html']);
	}

	public function testOpen_FooterStyles()
	{
		$attr = [
			'NAME' => '',
			'FOOTER-STYLE' => 'font-family: serif; font-size: 10pt; font-weight: bold; font-style: italic; color: #FF0000',
			'CONTENT-LEFT' => 'Styled'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$pageHTMLfooters = $this->mpdf->pageHTMLfooters;

		$footer = $pageHTMLfooters['_nonhtmldefault'];
		// The HTML generation logic is complex, but we can check for style attributes in the HTML
		$this->assertStringContainsString('font-family: serif', $footer['html']);
		$this->assertStringContainsString('color: #ff0000', $footer['html']);
		$this->assertStringContainsString('Styled', $footer['html']);
	}

	public function testOpen_SpecificStyles()
	{
		$attr = [
			'NAME' => '',
			'FOOTER-STYLE-LEFT' => 'font-weight: bold; color: blue',
			'FOOTER-STYLE-RIGHT' => 'font-style: italic; color: red',
			'CONTENT-LEFT' => 'L',
			'CONTENT-RIGHT' => 'R'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$pageHTMLfooters = $this->mpdf->pageHTMLfooters;

		$footer = $pageHTMLfooters['_nonhtmldefault'];
		$this->assertStringContainsString('color: blue', $footer['html']);
		$this->assertStringContainsString('color: red', $footer['html']);
	}

	public function testOpen_PageHeader()
	{
		// Use PageHeader class to test header logic
		$headerTag = $this->createTag(PageHeader::class);

		$attr = [
			'NAME' => 'myheader',
			'CONTENT-CENTER' => 'Header Content',
			'HEADER-STYLE' => 'font-weight: bold',
			'HEADER-STYLE-CENTER' => 'color: green'
		];
		$ahtml = [];
		$ihtml = 0;

		$headerTag->open($attr, $ahtml, $ihtml);
		$pageHTMLheaders = $this->mpdf->pageHTMLheaders;

		$this->assertArrayHasKey('myheader', $pageHTMLheaders);
		$header = $pageHTMLheaders['myheader'];
		$this->assertStringContainsString('Header Content', $header['html']);
		$this->assertStringContainsString('color: green', $header['html']);
	}

	public function testClose()
	{
		$ahtml = [];
		$ihtml = 0;
		// Close method is empty but should be callable without error
		$this->tag->close($ahtml, $ihtml);
		$this->assertTrue(true);
	}
}
