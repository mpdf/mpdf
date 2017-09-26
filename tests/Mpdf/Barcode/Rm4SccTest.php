<?php

namespace Mpdf\Barcode;

/**
 * @group unit
 */
class Rm4SccTest extends \PHPUnit_Framework_TestCase
{

	public function testInit()
	{
		$xdim = 0.508; // Nominal value for X-dim (bar width) in mm (spec.)
		$bpi = 22; // Bars per inch

		$barcode = new Rm4Scc('SN34RD1A', $xdim, ((25.4 / $bpi) - $xdim) / $xdim, ['D' => 2, 'A' => 2, 'F' => 3, 'T' => 1]);

		$array = $barcode->getData();

		$this->assertInternalType('array', $array);
		$this->assertArrayHasKey('maxh', $array);
		$this->assertTrue($array['maxh'] > 0);
	}

}
