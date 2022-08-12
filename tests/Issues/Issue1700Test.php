<?php

namespace Issues;

use Mpdf\TestLogger;

class Issue1700Test extends \Mpdf\BaseMpdfTest
{

	public function testImageLoadingProblemForAbsolutePathsWithSpaceInTheFilename()
	{
		/* Mimic a browser-based session basepath */
		$_SERVER['HTTP_HOST'] = 'localhost';

		$logger = new TestLogger();
		$mpdf   = new \Mpdf\Mpdf([ 'mode' => 'c' ]);
		$mpdf->setLogger($logger);

		$file = __DIR__ . '/../data/img/bay eux.jpg';
		$mpdf->WriteHTML('<img src="' . $file . '" />');

		$this->assertCount(1, $logger->records);
		$this->assertSame('Fetching content of file "' . $file . '" with local basepath', $logger->records[0]['message']);
	}
}
