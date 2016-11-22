<?php

namespace Mpdf\Pdf\Protection;

class UniqidGeneratorTest extends \PHPUnit_Framework_TestCase
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
