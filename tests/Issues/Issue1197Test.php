<?php

namespace Issues;

class Issue1197Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testDontThrowUninitializedStringOffsetException()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->setCSS(['FONT-SIZE' => ''], 'INLINE');
	}
}
