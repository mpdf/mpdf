<?php

namespace Mpdf\Language;

use Mpdf\Language\Fixtures\TestLanguageToFontA;
use Mpdf\Language\Fixtures\TestLanguageToFontB;
use Mpdf\MpdfException;

class LanguageToFontRegistryTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testAdd()
	{
		$registry = new LanguageToFontRegistry();
		$class = new TestLanguageToFontA();

		$registry->add($class);
		$this->assertArrayHasKey(TestLanguageToFontA::class, $registry->getAll());
	}

	public function testConstructor()
	{
		$registry = new LanguageToFontRegistry([
			new TestLanguageToFontA(),
			new TestLanguageToFontB(),
		]);

		$this->assertArrayHasKey(TestLanguageToFontA::class, $registry->getAll());
		$this->assertArrayHasKey(TestLanguageToFontB::class, $registry->getAll());
	}

	public function testConstructorException()
	{
		$this->expectException(MpdfException::class);
		new LanguageToFontRegistry(new \stdClass());
	}

	public function testRemove()
	{
		$registry = new LanguageToFontRegistry(new TestLanguageToFontA());
		$this->assertCount(1, $registry->getAll());

		$registry->remove(TestLanguageToFontA::class);
		$this->assertEmpty($registry->getAll());
	}

	public function testRemoveException()
	{
		$this->expectException(MpdfException::class);

		$registry = new LanguageToFontRegistry();
		$registry->remove('foobar');
	}

	public function testGetLanguageOptions()
	{
		// Test A only
		$registry = new LanguageToFontRegistry(new TestLanguageToFontA());
		$this->assertEquals([false, 'font_a'], $registry->getLanguageOptions('core_a', false));
		$this->assertEquals([false, ''], $registry->getLanguageOptions('core_b', false));

		// Test B only
		$registry = new LanguageToFontRegistry(new TestLanguageToFontB());
		$this->assertEquals([false, 'font_b'], $registry->getLanguageOptions('core_b', false));
		$this->assertEquals([false, 'font_b_override'], $registry->getLanguageOptions('core_a', false)); // B overrides core_a too

		// Test both together
		$registry = new LanguageToFontRegistry();
		$registry->add(new TestLanguageToFontA());
		$registry->add(new TestLanguageToFontB());

		$this->assertEquals([false, 'font_b'], $registry->getLanguageOptions('core_b', false));
		$this->assertEquals([false, 'font_b_override'], $registry->getLanguageOptions('core_a', false));

		// Test both together, but reverse the order
		$registry = new LanguageToFontRegistry();
		$registry->add(new TestLanguageToFontB());
		$registry->add(new TestLanguageToFontA());

		$this->assertEquals([false, 'font_a'], $registry->getLanguageOptions('core_a', false));
		$this->assertEquals([false, 'font_b'], $registry->getLanguageOptions('core_b', false));
	}
}
