<?php

namespace Issues;

class Issue732Test extends \Mpdf\BaseMpdfTest
{

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf->setAutoTopMargin = 'stretch';
		$this->mpdf->setAutoBottomMargin = 'stretch';
	}

	public function testAutoHeaderFooterOnPagesThatHaveThem()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin: 0;
			}

			@page :first {
				header: html_Header;
				footer: html_Footer;
			}
		</style>

		<htmlpageheader name="Header">
			Header
		</htmlpageheader>

		<htmlpagefooter name="Footer">
			Footer
		</htmlpagefooter>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(9.0, $this->mpdf->tMargin);
		$this->assertSame(9.0, $this->mpdf->bMargin);
	}

	public function testAutoHeaderFooterOnPagesThatDontThem()
	{
		$this->mpdf->WriteHTML('
		<style>
			@page {
				margin: 0;
			}
		</style>

		First page content
		');

		$this->mpdf->Close();

		$this->assertSame(0.0, $this->mpdf->tMargin);
		$this->assertSame(0.0, $this->mpdf->bMargin);
	}

}
