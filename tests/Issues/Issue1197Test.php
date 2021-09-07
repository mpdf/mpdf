<?php

namespace Issues;

class Issue1197Test extends \PHPUnit\Framework\TestCase
{

	public function testDontThrowUninitializedStringOffsetException()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->setCSS(['FONT-SIZE' => ''], 'INLINE');
	}
}
