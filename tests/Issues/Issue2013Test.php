<?php

namespace Issues;

class Issue2013Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPdfTableBreakAvoidTooMuch()
	{
		// test case: spill items that take about a bit more than half a page, no page-break-avoid would fit them on two pages, with page-break it will be three
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		$itemsPerTwothirdsPage = 28;
		for ($i = 0; $i < 5*$itemsPerTwothirdsPage; $i++) {
			$html .= '<tr style="page-break-before: avoid; background: lime;"><td>content</td></tr>';
		}
		$mpdf->WriteHTML('<html><body><h1>Test</h1>
		<table>'.$html.'</table>
		</html>');

		// without the bugfix, it would produce 98 pages, with the bugfix: 8
		$this->assertEquals($mpdf->page, 8);
	}

}
