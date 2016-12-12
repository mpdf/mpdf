<?php

namespace Mpdf\Pdf;

use Mockery;

class ProtectionTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Mpdf\Pdf\Protection
	 */
	private $protection;

	protected function setUp()
	{
		/** @var \Mpdf\Pdf\Protection\UniqidGenerator $generator */
		$generator = Mockery::mock('Mpdf\Pdf\Protection\UniqidGenerator');
		$generator->shouldReceive('generate')->once()->andReturn('123456');

		$this->protection = new Protection($generator);
	}

	public function testProtection()
	{
		$result = $this->protection->setProtection(['print'], '123456', '123456');

		$this->assertTrue($result);
		$this->assertFalse($this->protection->getUseRC128Encryption());
		$this->assertSame('123456', $this->protection->getUniqid());
		$this->assertSame('FsmERtZ9Sqvfk4K4O/SDBiN6fIyZBKi/wi1MiUjoKo0=', base64_encode($this->protection->getUValue()));
		$this->assertSame('sdtWqIPKtaIt1fw5Bhig+OFsq4rxTmfMul+Qg3qsiYs=', base64_encode($this->protection->getOValue()));
		$this->assertSame('NDI5NDk2MzM5Ng==', base64_encode($this->protection->getPValue()));
	}

	public function testLongKey()
	{
		$this->protection->setProtection(['print'], '123456', '123456', 128);
		$this->assertTrue($this->protection->getUseRC128Encryption());
		$this->assertSame('2w6w5vlMhs8uHNei0Z1ISAAAAAAAAAAAAAAAAAAAAAA=', base64_encode($this->protection->getUValue()));
	}

	public function testSingleStringPermission()
	{
		$this->protection->setProtection('print', '123456', '123456');
		$this->assertSame('FsmERtZ9Sqvfk4K4O/SDBiN6fIyZBKi/wi1MiUjoKo0=', base64_encode($this->protection->getUValue()));
	}

	public function testNullPermission()
	{
		$result = $result = $this->protection->setProtection(null);
		$this->assertFalse($result);
	}

	public function testRc4()
	{
		$rc4 = $this->protection->rc4('key', 'text');
		$this->assertSame('fwlMmQ==', base64_encode($rc4));
	}

	public function testObjectKey()
	{
		$key = $this->protection->objectKey(6);
		$this->assertSame('LNy8yskrNTlpuw==', base64_encode($key));
	}

	public function testInvalidPermissions()
	{
		$this->expectException(\Mpdf\MpdfException::class);
		$this->expectExceptionMessage('Invalid permission type "fly-a-broomstick"');

		$this->protection->setProtection(['fly-a-broomstick']);
	}

	public function testInvalidLength()
	{
		$this->expectException(\Mpdf\MpdfException::class);
		$this->expectExceptionMessage('PDF protection only allows lenghts of 40 or 128');

		$this->protection->setProtection(['print'], '', null, 42);
	}

}
