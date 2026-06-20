<?php

namespace Mpdf;

use Mockery;
use Mpdf\Output\Destination;

class Issue242Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	public function testFloatDivPerformance()
	{
		// Increasing iterations increases "workaround" timing roughly linearly
		// but increases "noworkaround" timing by some exponent, e.g.
		// # iterations | $diff_noworkaround | $diff_workaround  | $ratio
		// 1            | 0.057888984680176  | 0.055068016052246 | 5.12
		// 5            | 0.061691045761108  | 0.06385612487793  | 3.39
		// 10           | 0.079735994338989  | 0.076904058456421 | 3.68
		// 100          | 0.32072496414185   | 0.27143216133118  | 18.16
		// 1000         | 7.2338919639587    | 2.2578461170197   | 220.39
		// 2000         | 27.950292110443    | 4.68439412117     | 496.67
		// 5000         | 220.99812102318    | 11.925464868546   | 1753.16
		// Higher iteration counts exceed execution time
		$iterations = 100;

		// Template HTML is a self-contained div whose layout is also self-contained, but which contains floated divs
		$template = <<< EOF
<div style="clear:both">
	<div style="float:left;width:50%"><div><div><div>a</div></div></div></div>
	<div style="float:right;width:50%"><div><div><div>b</div></div></div></div>
</div>
EOF;

		// Run once to preload files to reduce variance between runs
		$mpdf = new Mpdf();
		$start_noworkaround = microtime(true);

		$mpdf->WriteHTML($template);

		$mpdf->Output('prerun.pdf', Destination::STRING_RETURN);

		// Check with default behavior
		$mpdf = new Mpdf();
		$start_noworkaround = microtime(true);

		for ($i = 0; $i < $iterations; ++$i) {
			$mpdf->WriteHTML($template);
		}

		$noworkaround = $mpdf->Output('test.pdf', Destination::STRING_RETURN);

		$end_noworkaround = microtime(true);
		$diff_noworkaround = $end_noworkaround - $start_noworkaround;

		// Check with workaround behavior
		$mpdf = new Mpdf();
		$start_workaround = microtime(true);

		$floatDivs = [];

		for ($i = 0; $i < $iterations; ++$i) {
			$mpdf->WriteHTML($template);
			$floatDivs = array_merge($floatDivs, $mpdf->floatDivs);
			$mpdf->floatDivs = [];
		}

		$mpdf->floatDivs = $floatDivs;

		$workaround = $mpdf->Output('test2.pdf', Destination::STRING_RETURN);

		$end_workaround = microtime(true);
		$diff_workaround = $end_workaround - $start_workaround;

		$diff = abs($diff_noworkaround / $diff_workaround);

		$ratio = round(abs($diff - 1) * 100, 2);

		echo "$iterations | $diff_noworkaround | $diff_workaround | $ratio";

		// If the timestamp varies due to execution time, metadata won't match, so strip it
		$dateRegex = '\(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)';

		$noworkaround_metadatastripped = preg_replace('/\d+ 0 obj\n<<\n\/Producer \((.*?)\)\n\/CreationDate ' . $dateRegex . '\n\/ModDate ' . $dateRegex . '/', '', $noworkaround);
		$noworkaround_metadatastripped = preg_replace('/\/ID \[<[0-9a-f]+> <[0-9a-f]+>\]/', '', $noworkaround_metadatastripped);

		$workaround_metadatastripped = preg_replace('/\d+ 0 obj\n<<\n\/Producer \((.*?)\)\n\/CreationDate ' . $dateRegex . '\n\/ModDate ' . $dateRegex . '/', '', $workaround);
		$workaround_metadatastripped = preg_replace('/\/ID \[<[0-9a-f]+> <[0-9a-f]+>\]/', '', $workaround_metadatastripped);
		
		// Assert that the resultant PDF is the same
		$this->assertEquals($noworkaround_metadatastripped, $workaround_metadatastripped);

		// Assert that the difference in exeuction time is less than 5%
		$this->assertLessThanOrEqual(5, $ratio, "Expected workaround ($diff_workaround) to take within 5% of no workaround ($diff_noworkaround), actually $ratio%");
	}
}
