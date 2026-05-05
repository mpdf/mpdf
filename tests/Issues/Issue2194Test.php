<?php

namespace Issues;

use Mpdf\Color\ColorConverter;
use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;
use Mpdf\Css\NormalizeProperties;
use Mpdf\SizeConverter;
use Psr\Log\NullLogger;

class Issue2194Test extends \Mpdf\BaseMpdfTest
{

	private function makeNormalizer()
	{
		$sizeConverter = new SizeConverter(96, 11, $this->mpdf, new NullLogger());
		$colorModeConverter = new ColorModeConverter();
		$colorConverter = new ColorConverter(
			$this->mpdf,
			$colorModeConverter,
			new ColorSpaceRestrictor($this->mpdf, $colorModeConverter)
		);

		return new NormalizeProperties($this->mpdf, $sizeConverter, $colorConverter);
	}

	public function testFontFamilyWithUnknownTokensStillDropsProperty()
	{
		$result = $this->makeNormalizer()->normalize([
			'FONT-FAMILY' => "doesnotexist 'AlsoDoesNotExist'",
		]);

		$this->assertArrayNotHasKey('FONT-FAMILY', $result);
	}

	/**
	 * Regression test to ensure font is correctly selected when it is only in the fontdata array
	 */
	public function testFontFamilyResolvesAgainstFontdataKeysNotValues()
	{
		$this->mpdf->fontdata['issue2194customfont'] = [
			'R' => 'DummyRegular.ttf',
		];

		$this->assertNotContains('issue2194customfont', $this->mpdf->available_unifonts);
		$this->assertNotContains('issue2194customfont', $this->mpdf->sans_fonts);
		$this->assertNotContains('issue2194customfont', $this->mpdf->serif_fonts);
		$this->assertNotContains('issue2194customfont', $this->mpdf->mono_fonts);

		$result = $this->makeNormalizer()->normalize([
			'FONT-FAMILY' => 'issue2194customfont, sans-serif',
		]);

		$this->assertArrayHasKey('FONT-FAMILY', $result);
		$this->assertSame('issue2194customfont', $result['FONT-FAMILY']);
	}

	/**
	 * @dataProvider providerMultiFontFontFamilyValues
	 */
	public function testFontFamilyMultiFontResolution($expected, $value)
	{
		$result = $this->makeNormalizer()->normalize([
			'FONT-FAMILY' => $value,
		]);

		$this->assertArrayHasKey('FONT-FAMILY', $result);
		$this->assertSame($expected, $result['FONT-FAMILY']);
	}

	public function providerMultiFontFontFamilyValues()
	{
		return [
			// Bare names — case insensitivity
			['dejavusanscondensed', 'dejavusanscondensed'],
			['dejavusanscondensed', 'DEJAVUSANSCONDENSED'],
			['dejavusanscondensed', 'DejaVuSansCondensed'],

			// Single entry wrapped in quotes
			['dejavusanscondensed', "'dejavusanscondensed'"],
			['dejavusanscondensed', '"dejavusanscondensed"'],
			['dejavusanscondensed', "'DejaVu Sans Condensed'"],
			['dejavusanscondensed', '"DejaVu Sans Condensed"'],

			// Generic keywords on their own
			['sans-serif', 'sans-serif'],
			['serif', 'serif'],
			['monospace', 'monospace'],
			['cursive', 'cursive'],
			['fantasy', 'fantasy'],

			// Comma-separated, varying registered position
			['dejavusanscondensed', 'dejavusanscondensed, foo, bar'],
			['dejavusanscondensed', 'foo, dejavusanscondensed, bar'],
			['dejavusanscondensed', 'foo, bar, dejavusanscondensed'],

			// Comma-separated, mixed single/double quotes
			['dejavusanscondensed', "'foo', \"bar\", dejavusanscondensed"],
			['dejavusanscondensed', "\"a\", 'b', \"c\", 'd', dejavusanscondensed"],
			['dejavusanscondensed', "'a', \"b\", dejavusanscondensed, 'c', \"d\""],
			['dejavusanscondensed', "\"foo bar\", 'baz qux', dejavusanscondensed"],
			['dejavusanscondensed', "\"a\", 'b', \"c\", 'd', 'e', dejavusanscondensed"],

			// Missing commas between names (the #2194 bug shape)
			['dejavusanscondensed', "dejavusanscondensed 'DejaVu Sans Condensed'"],
			['dejavusanscondensed', "'DejaVu Sans Condensed' dejavusanscondensed"],
			['dejavusanscondensed', '"DejaVu Sans Condensed" dejavusanscondensed'],
			['dejavusanscondensed', 'foo bar dejavusanscondensed'],
			['dejavusanscondensed', "'A B' 'C D' 'E F' dejavusanscondensed"],
			['dejavusanscondensed', "dejavusanscondensed 'A B' \"C D\" 'E F' \"G H\""],

			// Missing commas mixed with commas
			['dejavusanscondensed', "'foo bar' baz, dejavusanscondensed"],
			['dejavusanscondensed', "'no match' 'also none', dejavusanscondensed"],
			['dejavusanscondensed', "'A B' \"C D\", dejavusanscondensed, 'E F'"],

			// Generic keyword fallback after failing chains
			['sans-serif', 'doesnotexist, sans-serif'],
			['serif', "doesnotexist, 'AlsoMissing', serif"],
			['monospace', "'foo', \"bar\", 'baz', monospace"],
			['cursive', "'a', 'b', 'c', 'd', cursive"],
			['fantasy', "'a', 'b', 'c', 'd', 'e', fantasy"],
			['sans-serif', "doesnotexist 'AlsoMissing' \"StillMissing\", sans-serif"],
			['monospace', "'a' 'b', 'c' 'd', monospace"],

			// Invalid input shapes — empty entries, whitespace, unclosed quote
			['dejavusanscondensed', ', , dejavusanscondensed'],
			['dejavusanscondensed', 'dejavusanscondensed,,,'],
			['dejavusanscondensed', '   dejavusanscondensed   '],
			['dejavusanscondensed', "\t\n dejavusanscondensed \t"],
			['sans-serif', ',,,  ,, sans-serif'],
			['dejavusanscondensed', "'unclosed, dejavusanscondensed"],
		];
	}

	public function testFullPipelineAppliesCssFontToTableCell()
	{
		$mpdf = new \Mpdf\Mpdf([
			'default_font' => 'dejavusans',
		]);

		$css = ".mytable td, .mytable th { font-family: dejavusanscondensed 'DejaVu Sans Condensed'; font-size: 12pt; }";
		$html = '<p>Outside table.</p>'
			. '<table class="mytable"><thead><tr><th>Header</th></tr></thead>'
			. '<tbody><tr><td>Cell</td></tr></tbody></table>';

		$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

		$output = $mpdf->OutputBinaryData();

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertStringContainsString('DejaVuSansCondensed', $output);
	}

}
