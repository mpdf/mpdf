<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue421Test extends \Mpdf\BaseMpdfTest
{

	public function testCleanupMethod()
	{
		$this->assertSame('🐙', mb_substr('🐙', 0, 1));

		$mpdf = new \Mpdf\Mpdf(['mode' => 'c']);
		$mpdf->WriteHTML('');

		$mpdf->resetEncoding();

		$this->assertSame('🐙', mb_substr('🐙', 0, 1));
	}

}
