<?php

namespace Mpdf;

class TocNumbering extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function setUp()
	{
		$this->mpdf = new Mpdf();
	}

	public function testTocPageNumbering()
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->h2toc = array('H1' => 0, 'H2' => 1);

		$this->mpdf->WriteHTML('
			<style>
				@page {
				footer: html_myFooter;
				}
			</style>
			
			<htmlpagefooter name="myFooter">
				Page {PAGENO} / {nbpg}
			</htmlpagefooter>
			
			My intro page
			
			<pagebreak />
			
			<tocpagebreak links="on" toc-resetpagenum="0" />
			
			<h1>Heading 1</h1>
			
			<h2>Heading 2</h2>
			
			<h2>Heading 2</h2>
			
			<h2>Heading 2</h2>
			
			<pagebreak />
			
			<h1>Heading 1</h1>
			');

		$this->mpdf->Close();

		$this->assertNotFalse(strpos($this->mpdf->pages[2], $this->getPattern(3)));
	}

	/**
	 * Test the page numbering is correct when using multiple TOC fields
	 */
	public function testTocMultiPageNumbering()
	{
		$this->mpdf->setCompression(false);
		$markup = str_repeat('
		<h1><tocentry content="Heading 1" name="first" />Heading 1</h1>
		<h2><tocentry content="Heading 2" name="first" level="1" />Heading 2</h2>
		
		<h3><tocentry content="Heading 3" name="first" level="2" />Heading 3</h3>
		
		<h4><tocentry content="Heading 4" name="first" level="3" />Heading 4</h4>
		
		<pagebreak />
		
		<h1><tocentry content="Alternate 1" name="second" />Alternate 1</h1>
		<h2><tocentry content="Alternate 2" name="second" level="1" />Alternate 2</h2>
		
		<h3><tocentry content="Alternate 3" name="second" level="2" />Alternate 3</h3>
		
		<h4><tocentry content="Alternate 4" name="second" level="3" />Alternate 4</h4>
		
		<pagebreak />
		
		<h1><tocentry content="Final 1" name="third" />Final 1</h1>
		<h2><tocentry content="Final 2" name="third" level="1" />Final 2</h2>
		
		<h3><tocentry content="Final 3" name="third" level="2" />Final 3</h3>
		
		<h4><tocentry content="Final 4" name="third" level="3" />Final 4</h4>', 5);

		$this->mpdf->WriteHTML('
		<style>
		@page {
			footer: html_myFooter;
		}
		</style>
		
		<htmlpagefooter name="myFooter">
			Page {PAGENO} / {nbpg}
		</htmlpagefooter>
		
		<tocpagebreak links="on" name="first" />
		
		This is a page after the TOC
		
		<pagebreak />
		
		This is another page 
		
		<pagebreak />
		
		<tocpagebreak links="on" name="second" />
		
		<h1>Test</h1>
		Another empty page
		
		<tocpagebreak links="on" name="third" />' . $markup);

		$this->mpdf->Close();

		$this->assertNotFalse(strpos($this->mpdf->pages[1], $this->getPattern(7)));
		$this->assertNotFalse(strpos($this->mpdf->pages[4], $this->getPattern(8)));
		$this->assertNotFalse(strpos($this->mpdf->pages[6], $this->getPattern(9)));
	}

	public function testTocAlternateSymbols()
	{
		$this->mpdf->setCompression(false);
		$markup = str_repeat('
		<h1><tocentry content="Heading 1" name="first" />Heading 1</h1>
		<h2><tocentry content="Heading 2" name="first" level="1" />Heading 2</h2>
		
		<h3><tocentry content="Heading 3" name="first" level="2" />Heading 3</h3>
		
		<h4><tocentry content="Heading 4" name="first" level="3" />Heading 4</h4>
		
		<pagebreak />
		
		<h1><tocentry content="Alternate 1" name="second" />Alternate 1</h1>
		<h2><tocentry content="Alternate 2" name="second" level="1" />Alternate 2</h2>
		
		<h3><tocentry content="Alternate 3" name="second" level="2" />Alternate 3</h3>
		
		<h4><tocentry content="Alternate 4" name="second" level="3" />Alternate 4</h4>
		
		<pagebreak />
		
		<h1><tocentry content="Final 1" name="third" />Final 1</h1>
		<h2><tocentry content="Final 2" name="third" level="1" />Final 2</h2>
		
		<h3><tocentry content="Final 3" name="third" level="2" />Final 3</h3>
		
		<h4><tocentry content="Final 4" name="third" level="3" />Final 4</h4>', 5);

		$this->mpdf->WriteHTML('
		<style>
		@page {
			footer: html_myFooter;
		}
		</style>
		
		<htmlpagefooter name="myFooter">
			Page {PAGENO} / {nbpg}
		</htmlpagefooter>
		
		<tocpagebreak links="on" name="first" pagenumstyle="A" />
		
		This is a page after the TOC
		
		<pagebreak />
		
		This is another page 
		
		<pagebreak />
		
		<tocpagebreak links="on" name="second" pagenumstyle="i" />
		
		<h1>Test</h1>
		Another empty page
		
		<tocpagebreak links="on" name="third" pagenumstyle="I" />' . $markup);

		$this->mpdf->Close();

		$this->assertNotFalse(
			strpos(
				$this->mpdf->pages[1],
				$this->getPattern('VII', 'q 0.000 0.000 0.000 rg  0 Tr BT 540.165 784.480 Td  (%s) Tj ET Q')
			)
		);

		$this->assertNotFalse(
			strpos(
				$this->mpdf->pages[4],
				$this->getPattern('VIII', 'q 0.000 0.000 0.000 rg  0 Tr BT 537.250 784.480 Td  (%s) Tj ET Q')
			)
		);

		$this->assertNotFalse(
			strpos(
				$this->mpdf->pages[6],
				$this->getPattern('IX', 'q 0.000 0.000 0.000 rg  0 Tr BT 543.069 784.480 Td  (%s) Tj ET Q')
			)
		);
	}
	
	public function testTocNumberSuppression()
	{
		$this->mpdf->setCompression(false);

		$this->mpdf->AddPageByArray([
			'suppress' => 'on'
		]);
		$this->mpdf->WriteHTML('<p>TitlePage</p>');

		$this->mpdf->TOCpagebreakByArray([
			'links' => true,
			'resetpagenum' => 3,
			'name' => 'main',
			'suppress' => 'off'
		]);
		$this->mpdf->TOC_Entry('1', 1, 'main');
		$this->mpdf->WriteHTML("<h1>chapter 1</h1>");

		$this->mpdf->AddPage();
		$this->mpdf->TOC_Entry('1.1', 2, 'main');
		$this->mpdf->WriteHTML("<h1>chapter 1.1</h1>");

		$this->mpdf->AddPage();
		$this->mpdf->TOC_Entry('2', 1, 'main');
		$this->mpdf->WriteHTML("<h1>chapter 2</h1>");

		$this->mpdf->AddPage();
		$this->mpdf->TOC_Entry('3', 1, 'main');
		$this->mpdf->WriteHTML("<h1>chapter 3</h1>");

		$this->mpdf->Close();

		$this->assertNotFalse(
			strpos(
				$this->mpdf->pages[2],
				$this->getPattern('6', 'q 0.000 0.000 0.000 rg  0 Tr BT 546.468 741.642 Td  (%s) Tj ET Q')
			)
		);
	}

	public function testTocNumberWithCustomNumberStylingOnTocPage()
	{
		$this->mpdf->setCompression(false);

		$this->mpdf->writeHTML('
		<style>
			@page {
				footer: html_myFooter;
			}
		</style>
		
		<htmlpagefooter name="myFooter">
			Page {PAGENO} / {nbpg}
		</htmlpagefooter>
		
		Content
		
		<pagebreak />
		
		<tocpagebreak links="on" toc-pagenumstyle="i" />
		
		<pagebreak pagenumstyle="1" />
	
		<h2>Entry 1 <tocentry content="Entry 1"></h2>
	
		<pagebreak />
	
		<h2>Entry 2 <tocentry content="Entry 2"></h2>');

		$this->mpdf->Close();

		$this->assertNotFalse(
			strpos(
				$this->mpdf->pages[2],
				$this->getPattern('5', 'q 0.000 0.000 0.000 rg  0 Tr BT 546.468 767.980 Td  (%s) Tj ET Q')
			)
		);
	}

	protected function getPattern(
		$pageNumber,
		$pattern = 'q 0.000 0.000 0.000 rg  0 Tr BT 546.468 784.480 Td  (%s) Tj ET Q'
	) {
		$pageNumber = $this->mpdf->_escape(
			$this->mpdf->UTF8ToUTF16BE($pageNumber, false)
		);

		return sprintf(
			$pattern,
			$pageNumber
		);
	}

}
