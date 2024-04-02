<?php

namespace Issues;

class Issue2004Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPdfTableBreakAvoid()
	{
		// test case: spill items that take about a bit more than half a page, no page-break-avoid would fit them on two pages, with page-break it will be three
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		$itemsPerTwothirdsPage = 28;
		for ($i = 0; $i < 3*$itemsPerTwothirdsPage; $i++) {
			if ($i % $itemsPerTwothirdsPage == 0) {
				$html .= '<tr style=""><td>groupheader</td></tr>';
			} else {
				$html .= '<tr style="page-break-before: avoid; background: lime;"><td>content</td></tr>';
			}
		}
		$mpdf->WriteHTML('<html><body><h1>Test</h1>
		<table>'.$html.'</table>
		</html>');
		$this->assertEquals($mpdf->page, 3);
	}

}
