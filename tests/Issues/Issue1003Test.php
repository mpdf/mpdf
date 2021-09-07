<?php

namespace Issues;

class Issue1003Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testNoNoticeWithAutoPaddingRight()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
	    <style>
            .list { width: 501px; margin: 42px auto 0; padding-left: 41px; }
        </style>

        <ul class="list">
            <li>some text</li>
        </ul>
		');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
