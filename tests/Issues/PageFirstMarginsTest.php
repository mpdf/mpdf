<?php

namespace Issues;

class PageFirstMarginsTest extends \Mpdf\BaseMpdfTest
{

	public function testFirstPageMarginHeaderOverride()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-header: 15mm;
			}
			@page :first {
				margin-header: 5mm;
				header: html_MyHeader;
			}
		</style>

		<htmlpageheader name="MyHeader">
			Header content
		</htmlpageheader>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(5.0, $this->mpdf->margin_header);
	}

	public function testFirstPageMarginFooterOverride()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-footer: 12mm;
			}
			@page :first {
				margin-footer: 3mm;
				footer: html_MyFooter;
			}
		</style>

		<htmlpagefooter name="MyFooter">
			Footer content
		</htmlpagefooter>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(3.0, $this->mpdf->margin_footer);
	}

	public function testFirstPageMarginTopOverride()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-top: 20mm;
			}
			@page :first {
				margin-top: 10mm;
			}
		</style>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(10.0, $this->mpdf->tMargin);
	}

	public function testFirstPageMarginBottomOverride()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-bottom: 20mm;
			}
			@page :first {
				margin-bottom: 8mm;
			}
		</style>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(8.0, $this->mpdf->bMargin);
	}

	public function testGenericPageMarginsPreservedWithoutFirstOverride()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-header: 15mm;
				margin-footer: 12mm;
			}
		</style>

		Content without @page :first
		');

		$this->mpdf->Close();

		$this->assertSame(15.0, $this->mpdf->margin_header);
		$this->assertSame(12.0, $this->mpdf->margin_footer);
	}

	public function testSubsequentPagesRevertToGenericMargins()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin-header: 15mm;
			}
			@page :first {
				margin-header: 5mm;
				header: html_MyHeader;
			}
		</style>

		<htmlpageheader name="MyHeader">
			Header content
		</htmlpageheader>

		First page content
		<pagebreak />
		Second page content
		');

		$this->mpdf->Close();

		// After page break, orig_hMargin (generic @page value) should be preserved
		$this->assertSame(15.0, $this->mpdf->orig_hMargin);
	}

}
