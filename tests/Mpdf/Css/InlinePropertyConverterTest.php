<?php

namespace Mpdf\Css;

use Mockery;
use Mpdf\Color\ColorConverter;
use PHPUnit\Framework\TestCase;

class InlinePropertyConverterTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var \Mpdf\Color\ColorConverter|\Mockery\MockInterface
	 */
	private $colorConverter;

	/**
	 * @var \Mpdf\Css\InlinePropertyConverter
	 */
	private $converter;

	protected function set_up()
	{
		parent::set_up();
		$this->colorConverter = Mockery::mock(ColorConverter::class);
		$this->converter = new InlinePropertyConverter($this->colorConverter);
	}

	protected function tear_down()
	{
		Mockery::close();
		parent::tear_down();
	}

	public function testConvertBasicProperties()
	{
		$bilp = ['B' => true, 'I' => true];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('bold', $result['FONT-WEIGHT']);
		$this->assertEquals('italic', $result['FONT-STYLE']);
	}

	public function testConvertFontSize()
	{
		$bilp = ['sizePt' => 14];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('14pt', $result['FONT-SIZE']);
	}

	public function testConvertColor()
	{
		$colorArray = [1, 255, 0, 0]; // Mock color array
		$this->colorConverter->shouldReceive('colAtoString')
			->with($colorArray)
			->andReturn('rgb(255, 0, 0)');

		$bilp = ['colorarray' => $colorArray];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('rgb(255, 0, 0)', $result['COLOR']);
	}

	public function testConvertTextDecoration()
	{
		$bilp = ['textvar' => TextVars::FD_UNDERLINE];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('underline', $result['TEXT-DECORATION']);
	}

	public function testConvertVerticalAlign()
	{
		$bilp = ['textvar' => TextVars::FA_SUPERSCRIPT];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('super', $result['VERTICAL-ALIGN']);
	}

	public function testConvertLetterSpacing()
	{
		$bilp = ['lSpacingCSS' => '2px'];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('2px', $result['LETTER-SPACING']);
	}

	public function testConvertWordSpacing()
	{
		$bilp = ['wSpacingCSS' => '5px'];
		$result = $this->converter->convert($bilp);

		$this->assertEquals('5px', $result['WORD-SPACING']);
	}

	public function testConvertHyphens()
	{
		// Auto
		$result = $this->converter->convert(['textparam' => ['hyphens' => 1]]);
		$this->assertEquals('auto', $result['HYPHENS']);

		// None
		$result = $this->converter->convert(['textparam' => ['hyphens' => 2]]);
		$this->assertEquals('none', $result['HYPHENS']);

		// Manual
		$result = $this->converter->convert(['textparam' => ['hyphens' => 0]]);
		$this->assertEquals('manual', $result['HYPHENS']);
	}

	public function testConvertTextOutline()
	{
		// None
		$result = $this->converter->convert(['textparam' => ['outline-s' => false]]);
		$this->assertEquals('none', $result['TEXT-OUTLINE']);

		// Color
		$colorArray = [1, 0, 0, 255];
		$this->colorConverter->shouldReceive('colAtoString')
			->with($colorArray)
			->andReturn('rgb(0, 0, 255)');
		
		$result = $this->converter->convert(['textparam' => ['outline-COLOR' => $colorArray]]);
		$this->assertEquals('rgb(0, 0, 255)', $result['TEXT-OUTLINE-COLOR']);

		// Width
		$result = $this->converter->convert(['textparam' => ['outline-WIDTH' => 0.5]]);
		$this->assertEquals('0.5mm', $result['TEXT-OUTLINE-WIDTH']);
	}

	public function testConvertTextDecorationVariants()
	{
		// Line-through
		$result = $this->converter->convert(['textvar' => TextVars::FD_LINETHROUGH]);
		$this->assertEquals('line-through', $result['TEXT-DECORATION']);

		// Underline + Line-through
		$result = $this->converter->convert(['textvar' => TextVars::FD_UNDERLINE | TextVars::FD_LINETHROUGH]);
		$this->assertEquals('underline line-through', $result['TEXT-DECORATION']);

		// None
		$result = $this->converter->convert(['textvar' => 1024]);
		$this->assertEquals('none', $result['TEXT-DECORATION']);
	}

	public function testConvertVerticalAlignVariants()
	{
		// Subscript
		$result = $this->converter->convert(['textvar' => TextVars::FA_SUBSCRIPT]);
		$this->assertEquals('sub', $result['VERTICAL-ALIGN']);

		// Baseline
		$result = $this->converter->convert(['textvar' => 1024]);
		$this->assertEquals('baseline', $result['VERTICAL-ALIGN']);
	}

	public function testConvertTextTransform()
	{
		// Capitalize
		$result = $this->converter->convert(['textvar' => TextVars::FT_CAPITALIZE]);
		$this->assertEquals('capitalize', $result['TEXT-TRANSFORM']);

		// Uppercase
		$result = $this->converter->convert(['textvar' => TextVars::FT_UPPERCASE]);
		$this->assertEquals('uppercase', $result['TEXT-TRANSFORM']);

		// Lowercase
		$result = $this->converter->convert(['textvar' => TextVars::FT_LOWERCASE]);
		$this->assertEquals('lowercase', $result['TEXT-TRANSFORM']);

		// None
		$result = $this->converter->convert(['textvar' => 1024]);
		$this->assertEquals('none', $result['TEXT-TRANSFORM']);
	}

	public function testConvertFontKerning()
	{
		// Normal
		$result = $this->converter->convert(['textvar' => TextVars::FC_KERNING]);
		$this->assertEquals('normal', $result['FONT-KERNING']);

		// None
		$result = $this->converter->convert(['textvar' => 1024]);
		$this->assertEquals('none', $result['FONT-KERNING']);
	}

	public function testConvertFontVariant()
	{
		// Super
		$result = $this->converter->convert(['textvar' => TextVars::FA_SUPERSCRIPT]);
		$this->assertEquals('super', $result['FONT-VARIANT-POSITION']);

		// Sub
		$result = $this->converter->convert(['textvar' => TextVars::FA_SUBSCRIPT]);
		$this->assertEquals('sub', $result['FONT-VARIANT-POSITION']);

		// Normal
		$result = $this->converter->convert(['textvar' => 1024]);
		$this->assertEquals('normal', $result['FONT-VARIANT-POSITION']);

		// Small Caps
		$result = $this->converter->convert(['textvar' => TextVars::FC_SMALLCAPS]);
		$this->assertEquals('small-caps', $result['FONT-VARIANT-CAPS']);
	}

	public function testConvertFontLanguageOverride()
	{
		// Value
		$result = $this->converter->convert(['fontLanguageOverride' => 'TRK']);
		$this->assertEquals('TRK', $result['FONT-LANGUAGE-OVERRIDE']);

		// Empty/Normal
		$result = $this->converter->convert(['fontLanguageOverride' => '']);
		$this->assertEquals('normal', $result['FONT-LANGUAGE-OVERRIDE']);
	}

	public function testConvertOTLTags()
	{
		// Minus
		$result = $this->converter->convert(['OTLtags' => ['Minus' => 'liga kern']]);
		$this->assertEquals("'liga' 0, 'kern' 0", $result['FONT-FEATURE-SETTINGS']);

		// Plus
		$result = $this->converter->convert(['OTLtags' => ['Plus' => 'smcp swsh']]);
		$this->assertEquals("'smcp' 1, 'swsh' 1", $result['FONT-FEATURE-SETTINGS']);

		// FFMinus
		$result = $this->converter->convert(['OTLtags' => ['FFMinus' => 'dlig']]);
		$this->assertEquals("'dlig' 0", $result['FONT-FEATURE-SETTINGS']);

		// FFPlus
		$result = $this->converter->convert(['OTLtags' => ['FFPlus' => 'salt']]);
		$this->assertEquals("'salt' 1", $result['FONT-FEATURE-SETTINGS']);

		// FFPlus Numeric
		$result = $this->converter->convert(['OTLtags' => ['FFPlus' => 'salt2']]);
		$this->assertEquals("'salt' 2", $result['FONT-FEATURE-SETTINGS']);

		// Combined
		$result = $this->converter->convert([
			'OTLtags' => [
				'Minus'   => 'liga',
				'Plus'    => 'calt',
				'FFMinus' => 'dlig',
				'FFPlus'  => 'salt3',
			],
		]);
		
		$settings = $result['FONT-FEATURE-SETTINGS'];
		$this->assertStringContainsString("'liga' 0", $settings);
		$this->assertStringContainsString("'calt' 1", $settings);
		$this->assertStringContainsString("'dlig' 0", $settings);
		$this->assertStringContainsString("'salt' 3", $settings);
	}
}
