<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Class Issue1320Test
 * @author Antonio Norman - softcodex.ch
 */
class Issue1320Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * Issue 1320: First header of named page is added twice
	 */
	public function testNamedHeaderIsPrintedOnlyOnce()
	{
		// To test the fix, we want to observe the calls to the writer
		// as the writer class is marked as final we can't use Mockery's spy
		// so instead we have to use a proxy class.
		// Note this will fail if the class is ever type hinted

		// Use reflection to set the writer class
		$reflection = new \ReflectionClass(Mpdf::class);

		$mpdf     = $reflection->newInstance();
		$property = $reflection->getProperty('writer');
		$property->setAccessible(true);

		// Get the instance of the writer class from Mpdf to use in Mockery proxy
		$writer = \Mockery::mock($property->getValue($mpdf));

		$count = 0;
		$writer->shouldReceive('write')
			   ->with(\Mockery::on(function ($argument) use (&$count) {
				if (strpos($argument, '___PAGE___START') === 0) {
					$count++;
				}

				return true;
			   }));

		$property->setValue($mpdf, $writer);

		/** @var Mpdf $mpdf */
		$mpdf->WriteHTML($this->getHtmlWithNamedPage());

		// Before the fix it was called 4 times
		$this->assertEquals(3, $count);

		\Mockery::close();

		// Test the PDF still generates "correctly"
		$mpdf = new Mpdf();
		$mpdf->WriteHTML($this->getHtmlWithNamedPage());
		$output = $mpdf->output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}


	private function getHtmlWithNamedPage()
	{
		return <<<HTML
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>title</title>
	<style>
        @page {
            size: 210mm 297mm;
            margin-top: 10mm;
            margin-right: 10mm;
            margin-bottom: 10mm;
            margin-left: 10mm;
        }

        @page P_mpdf_page_27 {
            margin-top: 81mm;
            odd-header-name: H_mpdf_section_22;
            even-header-name: H_mpdf_section_22;
        }

        #mpdf_page_27 {
            page: P_mpdf_page_27;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        #Line1 {
            font-size: 10px;
            font-weight: normal;
        }

        #Line2 {
            font-size: 12px;
            font-weight: normal;
        }

        #Line3 {
            font-size: 14px;
            font-weight: normal;
        }

        #Line4 {
            font-size: 16px;
            font-weight: normal;
        }


	</style>
</head>
<body>
<section id="mpdf_page_27">
	<htmlpageheader name="H_mpdf_section_22">
		<div id="Line1">Line 1</div>
		<div id="Line2"> Line 2</div>
		<div id="Line3"> Line 3</div>
		<div id="Line4"> Line 4</div>
	</htmlpageheader>
	<div style="height:100mm; background-color: aqua;">foo</div>
	<div style="height:100mm; background-color: hotpink;">bar</div>

	<div style="height:100mm; background-color: greenyellow;">foo</div>
	<div style="height:100mm; background-color: skyblue;">bar</div>
</body>
</html>
HTML;
	}
}
