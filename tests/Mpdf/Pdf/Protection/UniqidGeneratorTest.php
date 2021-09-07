<?php

namespace Mpdf\Pdf\Protection;

class UniqidGeneratorTest extends \PHPUnit\Framework\TestCase
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
