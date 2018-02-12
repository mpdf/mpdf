<?php

namespace Issues;

class Issue643Test extends \Mpdf\BaseMpdfTest
{

	public function testTocPageBreak()
	{

		$html = '
				<style>
					@page {
					footer: html_myFooter;
					}
				</style>

				<htmlpagefooter name="myFooter">
				Page {PAGENO} / {nbpg}
				</htmlpagefooter>

				<tocpagebreak links="on" />

				<h1>Heading 1</h1>

				<h2>Heading 2</h2>

				<h2>Heading 2</h2>

				<h2>Heading 2</h2>

				<pagebreak />

				<h1>Heading 1</h1>';

		$this->mpdf->h2toc = array('H1' => 0, 'H2' => 1);
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Close();

		$this->assertSame(1, $this->mpdf->docPageNum(1));
		$this->assertSame(2, $this->mpdf->docPageNum(2));
		$this->assertSame(3, $this->mpdf->docPageNum(3));
	}

	public function testTocPageBreakReset()
	{

		$html = '
				<style>
					@page {
					footer: html_myFooter;
					}
				</style>

				<htmlpagefooter name="myFooter">
				Page {PAGENO} / {nbpg}
				</htmlpagefooter>

				<tocpagebreak links="on" resetpagenum="1" />

				<h1>Heading 1</h1>

				<h2>Heading 2</h2>

				<h2>Heading 2</h2>

				<h2>Heading 2</h2>

				<pagebreak />

				<h1>Heading 1</h1>';

		$this->mpdf->h2toc = array('H1' => 0, 'H2' => 1);
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Close();

		$this->assertSame(1, $this->mpdf->docPageNum(1));
		$this->assertSame(1, $this->mpdf->docPageNum(2));
		$this->assertSame(2, $this->mpdf->docPageNum(3));
	}

}
