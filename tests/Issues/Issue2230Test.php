<?php

namespace Issues;

class Issue2230Test extends \Mpdf\BaseMpdfTest
{
	public function testLastFooterDoesNotChangeGlobalBottomMargin()
	{
		$this->mpdf->setAutoBottomMargin = 'stretch';

		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Regular footer</div>',
			'h' => 20,
		]);

		$bottomMarginAfterRegularFooter = $this->mpdf->bMargin;

		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Last page footer</div>',
			'h' => 60,
		], 'L');

		$this->assertSame($bottomMarginAfterRegularFooter, $this->mpdf->bMargin);
	}

	public function testLastFooterAddsPageOnlyWhenExtraHeightDoesNotFit()
	{
		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Regular footer</div>',
			'h' => 20,
		]);

		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Last page footer</div>',
			'h' => 40,
		], 'L');

		$this->mpdf->AddPage();
		$this->mpdf->y = $this->mpdf->PageBreakTrigger - 10; // extra needed is 20, only 10 left

		$this->mpdf->Close();

		$this->assertCount(2, $this->mpdf->pages);
	}

	public function testLastFooterDoesNotAddPageWhenExtraHeightFits()
	{
		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Regular footer</div>',
			'h' => 20,
		]);

		$this->mpdf->SetHTMLFooter([
			'html' => '<div>Last page footer</div>',
			'h' => 40,
		], 'L');

		$this->mpdf->AddPage();
		$this->mpdf->y = $this->mpdf->PageBreakTrigger - 25; // extra needed is 20, 25 left

		$this->mpdf->Close();

		$this->assertCount(1, $this->mpdf->pages);
	}
}
