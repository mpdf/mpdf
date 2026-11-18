<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue2137Test extends \Mpdf\BaseMpdfTest
{

	/**
	 * @dataProvider contentProvider
	 */
	public function testEmptyPositionedBlockWithBorderIsNotAutoSized($content)
	{
		$this->mpdf->WriteHTML($this->html(true, '', $content));

		// overflow: visible skips auto-sizing, so it is what the empty block should look like
		$this->assertSame($this->render($this->html(true, ' overflow: visible;', $content)), $this->mpdf->pages[1]);

		// and the border is still drawn
		$this->assertNotSame($this->render($this->html(false, '', $content)), $this->mpdf->pages[1]);
	}

	public function contentProvider()
	{
		return [
			'empty' => [''],
			'whitespace only' => [" \n\t "],
		];
	}

	private function html($border, $style, $content)
	{
		// the border has to come from a style sheet: it is then applied to the block that gets auto-sized
		return ($border ? '<style>div { border: 1px solid red; }</style>' : '')
			. '<div style="top: 160px; left: 240px; position: absolute; width: 200px;' . $style . '">' . $content . '</div>';
	}

	private function render($html)
	{
		$mpdf = new Mpdf(['mode' => 'c']);
		$mpdf->WriteHTML($html);
		$page = $mpdf->pages[1];
		$mpdf->cleanup();

		return $page;
	}

}
