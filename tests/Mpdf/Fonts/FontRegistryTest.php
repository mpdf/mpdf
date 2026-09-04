<?php

namespace Mpdf\Fonts;

use Mpdf\Fonts\Fixtures\TestFontRegistrationA;
use Mpdf\Fonts\Fixtures\TestFontRegistrationB;
use Mpdf\Fonts\Fixtures\TestFontRegistrationWithId;
use Mpdf\MpdfException;

class FontRegistryTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testAdd()
	{
		$registry = new FontRegistry([]);
		$class = new TestFontRegistrationA();

		$registry->add($class);
		$this->assertArrayHasKey(TestFontRegistrationA::class, $registry->getAll());
	}

	public function testAddRepeated()
	{
		$registry = new FontRegistry([]);
		$class = new TestFontRegistrationA();

		$registry->add($class);
		$this->assertCount(1, $registry->getAll());
		
		$registry->add($class);
		$this->assertCount(1, $registry->getAll());
	}

	public function testRemove()
	{
		$registry = new FontRegistry(new TestFontRegistrationA());
		$this->assertCount(1, $registry->getAll());

		$registry->remove(TestFontRegistrationA::class);
		$this->assertEmpty($registry->getAll());
	}

	public function testRemoveException()
	{
		$this->expectException(MpdfException::class);

		$registry = new FontRegistry([]);
		$registry->remove('foobar');
	}

	public function testAutoloadValues()
	{
		$registry = new FontRegistry([]);
		$this->assertTrue($registry->getAutoloadConfigSetting());

		$registry->setAutoloadConfigSetting(false);
		$this->assertFalse($registry->getAutoloadConfigSetting());
	}

	public function testAddWithConstructor()
	{
		$registry = new FontRegistry([
			new TestFontRegistrationA(),
			new TestFontRegistrationB(),
		]);

		$this->assertArrayHasKey(TestFontRegistrationA::class, $registry->getAll());
		$this->assertArrayHasKey(TestFontRegistrationB::class, $registry->getAll());
	}

	public function testAutoloadFonts()
	{
		$lockFile = tempnam(sys_get_temp_dir(), 'composer.lock');
		$json = json_encode([
			'packages' => [
				[
					'extra' => [
						'mpdf' => [
							'fonts' => TestFontRegistrationA::class,
							'fontOrder' => 20,
						]
					]
				],
				[
					'extra' => [
						'mpdf' => [
							'fonts' => TestFontRegistrationB::class,
						]
					]
				]
			]
		]);

		file_put_contents($lockFile, $json);

		$registry = new FontRegistry(null, $lockFile);
		$fonts = $registry->getAll();
		$fontKeys = array_keys($fonts);

		$this->assertCount(2, $fonts);
		$this->assertSame(TestFontRegistrationA::class, $fontKeys[0]);
		$this->assertSame(TestFontRegistrationB::class, $fontKeys[1]);
		
		unlink($lockFile);
	}

	public function testAutoloadFontsWithoutLockFile()
	{
		$registry = new FontRegistry(null, sys_get_temp_dir() . '/no-such-composer.lock');

		$this->assertEmpty($registry->getAll());
	}

	public function testIdDefaultsToTheClassName()
	{
		$this->assertSame(TestFontRegistrationA::class, (new TestFontRegistrationA())->getId());
	}

	public function testOneClassCanBackSeveralPackages()
	{
		$registry = new FontRegistry([
			new TestFontRegistrationWithId('bundled', 'fontA'),
			new TestFontRegistrationWithId('installed', 'fontB'),
		]);

		$this->assertCount(2, $registry->getAll());
		$this->assertSame(['installed', 'bundled'], array_keys($registry->getAll()));
	}

	public function testAddReplacesAPackageWithTheSameId()
	{
		$registry = new FontRegistry([
			new TestFontRegistrationWithId('installed', 'fontA'),
			new TestFontRegistrationWithId('installed', 'fontB'),
		]);

		$this->assertCount(1, $registry->getAll());
		$this->assertArrayHasKey('fontB', $registry->getAll()['installed']->getFonts());
	}

	public function testRemoveById()
	{
		$registry = new FontRegistry(new TestFontRegistrationWithId('bundled', 'fontA'));

		$registry->remove('bundled');
		$this->assertEmpty($registry->getAll());
	}
}
