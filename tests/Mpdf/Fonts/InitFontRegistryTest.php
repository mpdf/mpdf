<?php

namespace Mpdf\Fonts;

use Mpdf\Fonts\Fixtures\TestFontRegistrationA;
use Mpdf\Fonts\Fixtures\TestFontRegistrationB;
use Mpdf\Language\LanguageToFontRegistry;
use Mpdf\Mpdf;

class InitFontRegistryTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @param FontRegistrationInterface[] $packages
	 * @param array $config
	 *
	 * @return Mpdf
	 */
	private function makeMpdf(array $packages, array $config = [])
	{
		return new Mpdf($config + [
			'mode' => 'c', /* the fixtures name no real files; only the merged config is under test */
			'fontDir' => [],
			'fontdata' => [],
			'fontRegistry' => new FontRegistry($packages),
			'languageToFont' => new LanguageToFontRegistry(),
		]);
	}

	public function testFontDirComesFromThePackages()
	{
		$mpdf = $this->makeMpdf([new TestFontRegistrationA(), new TestFontRegistrationB()]);

		$fontDir = new \ReflectionProperty(Mpdf::class, 'fontDir');
		$fontDir->setAccessible(true);

		// add() prepends, so the last package added is read first
		$this->assertSame(['/tmp/b', '/tmp/a'], $fontDir->getValue($mpdf));
	}

	public function testFontdataMergesEveryPackage()
	{
		$mpdf = $this->makeMpdf([new TestFontRegistrationA(), new TestFontRegistrationB()]);

		$this->assertArrayHasKey('fontA', $mpdf->fontdata);
		$this->assertArrayHasKey('fontB', $mpdf->fontdata);
	}

	public function testConfigFontdataBeatsThePackages()
	{
		$mpdf = $this->makeMpdf(
			[new TestFontRegistrationA()],
			['fontdata' => ['fontA' => ['R' => 'override.ttf']]]
		);

		$this->assertSame('override.ttf', $mpdf->fontdata['fontA']['R']);
	}

	public function testLineBreakDictionariesMergeFromThePackages()
	{
		$mpdf = $this->makeMpdf([new TestFontRegistrationA(), new TestFontRegistrationB()]);

		// B is read first, so it wins the shaper letter both packages declare
		$this->assertSame(
			['T' => '/tmp/b/linebrdictT.dat', 'K' => '/tmp/b/linebrdictK.dat'],
			$mpdf->lineBreakDictionaries
		);
	}

	public function testLineBreakDictionariesAreEmptyWithoutAPackage()
	{
		$mpdf = $this->makeMpdf([]);

		$this->assertSame([], $mpdf->lineBreakDictionaries);
	}

	public function testDefaultFontSortsFirstInFontdata()
	{
		// fontB is added last, so without the re-key it would head the map
		$mpdf = $this->makeMpdf(
			[new TestFontRegistrationA(), new TestFontRegistrationB()],
			['default_font' => 'fontA']
		);

		// not key(): on PHP 5.6 the array's internal pointer survives mPDF's own iteration
		$fontKeys = array_keys($mpdf->fontdata);

		$this->assertSame('fontA', $fontKeys[0]);
		$this->assertSame('fontA', $mpdf->available_unifonts[0]);
	}
}
