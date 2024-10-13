<?php

namespace Issues;

class Issue2051Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPdfWithDoublePercent()
	{
		// test case: spill items that take about a bit more than half a page, no page-break-avoid would fit them on two pages, with page-break it will be three
		$mpdf = new \Mpdf\Mpdf();
		$html = '';
		for ($i = 0; $i < 10; $i++) {
			$html .= 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
		}
		$mpdf->WriteHTML('<html><body><h1>Test</h1>
		<div style="width: 100%%;">'.$html.'</div>
		</html>');

		// without the bugfix, it would produce 10 pages, with the bugfix: 2
		$this->assertEquals($mpdf->page, 2);
	}

}
