<?php

namespace Mpdf\Pdf\Protection;

class UniqidGeneratorTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testGenerate()
	{
		$generator = new UniqidGenerator();
		$this->assertNotEquals(
			$generator->generate(),
			$generator->generate()
		);
	}

}
