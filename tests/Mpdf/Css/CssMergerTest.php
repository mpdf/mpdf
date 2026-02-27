<?php

namespace Mpdf\Css;

use Mpdf\CssManager;
use Mpdf\Mpdf;

class CssMergerTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/** @var CssMerger */
	private $cssMerger;

	/** @var CssManager */
	private $cssManager;

	/** @var Mpdf */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();

		// Get CssManager (private property of Mpdf, but we can reflect it)
		$reflection = new \ReflectionClass($this->mpdf);
		$property   = $reflection->getProperty('cssManager');
		$property->setAccessible(true);
		$this->cssManager = $property->getValue($this->mpdf);

		// Get CssMerger (private property of CssManager)
		$reflection = new \ReflectionClass($this->cssManager);
		$property   = $reflection->getProperty('cssMerger');
		$property->setAccessible(true);
		$this->cssMerger = $property->getValue($this->cssManager);

		$this->mpdf->AddPage();
	}

	protected function tear_down()
	{
		unset($this->mpdf, $this->cssManager, $this->cssMerger);

		parent::tear_down();
	}

	/**
	 * Call protected/private method of a class.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = [])
	{
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);
		return $method->invokeArgs($object, $parameters);
	}

	protected function setCssProperties(array $properties)
	{
		$reflection = new \ReflectionClass($this->cssMerger);
		$property = $reflection->getProperty('cssProperties');
		$property->setAccessible(true);
		$property->setValue($this->cssMerger, $properties);
	}

	protected function getCssProperties()
	{
		$reflection = new \ReflectionClass($this->cssMerger);
		$property = $reflection->getProperty('cssProperties');
		$property->setAccessible(true);
		return $property->getValue($this->cssMerger);
	}

	public function testMergeBorders_WithCompleteData()
	{
		$b = ['BORDER-TOP' => '1px solid #000'];
		$a = ['BORDER-TOP-STYLE' => 'dashed'];

		$this->setCssProperties($b);
		$this->invokeMethod($this->cssMerger, 'mergeBorderProperties', [&$a]);
		$result = $this->getCssProperties();

		$this->assertEquals('1px dashed #000', $result['BORDER-TOP']);
	}

	public function testMergeBorders_WithWidthChange()
	{
		$b = ['BORDER-LEFT' => '1px solid #000'];
		$a = ['BORDER-LEFT-WIDTH' => '3px'];

		$this->setCssProperties($b);
		$this->invokeMethod($this->cssMerger, 'mergeBorderProperties', [&$a]);
		$result = $this->getCssProperties();

		$this->assertEquals('3px solid #000', $result['BORDER-LEFT']);
	}

	public function testMergeBorders_WithColorChange()
	{
		$b = ['BORDER-RIGHT' => '1px solid #000'];
		$a = ['BORDER-RIGHT-COLOR' => '#ff0000'];

		$this->setCssProperties($b);
		$this->invokeMethod($this->cssMerger, 'mergeBorderProperties', [&$a]);
		$result = $this->getCssProperties();

		$this->assertEquals('1px solid #ff0000', $result['BORDER-RIGHT']);
	}

	public function testMergeBorders_WithoutExistingBorder()
	{
		$b = [];
		$a = ['BORDER-BOTTOM-STYLE' => 'dotted'];

		$this->setCssProperties($b);
		$this->invokeMethod($this->cssMerger, 'mergeBorderProperties', [&$a]);
		$result = $this->getCssProperties();

		$this->assertEquals('0px dotted #000000', $result['BORDER-BOTTOM']);
	}

	public function testMergeCssProperties_WithEmptyTarget()
	{
		$p = ['color' => 'red'];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeCssProperties', [$p, &$t]);

		$this->assertEquals(['color' => 'red'], $t);
	}

	public function testMergeCssProperties_WithExistingTarget()
	{
		$p = ['color' => 'red'];
		$t = ['font-size' => '12px'];

		$this->invokeMethod($this->cssMerger, 'mergeCssProperties', [$p, &$t]);

		$this->assertArrayHasKey('color', $t);
		$this->assertArrayHasKey('font-size', $t);
	}

	public function testMergeCssProperties_WithNullSource()
	{
		$p = null;
		$t = ['color' => 'blue'];

		$this->invokeMethod($this->cssMerger, 'mergeCssProperties', [$p, &$t]);

		$this->assertEquals(['color' => 'blue'], $t);
	}

	public function testMergeFullCSS_WithTagSelector()
	{
		$p = ['DIV' => ['color' => 'red']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', [], '', '']);

		$this->assertArrayHasKey('color', $t);
		$this->assertEquals('red', $t['color']);
	}

	public function testMergeFullCSS_WithClassSelector()
	{
		$p = ['CLASS>>myclass' => ['font-size' => '14px']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', ['myclass'], '', '']);

		$this->assertArrayHasKey('font-size', $t);
		$this->assertEquals('14px', $t['font-size']);
	}

	public function testMergeFullCSS_WithIDSelector()
	{
		$p = ['ID>>myid' => ['background' => 'blue']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', [], 'myid', '']);

		$this->assertArrayHasKey('background', $t);
		$this->assertEquals('blue', $t['background']);
	}

	public function testMergeFullCSS_WithLangSelector()
	{
		$p = ['LANG>>fr' => ['font-family' => 'Arial']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', [], '', 'fr']);

		$this->assertArrayHasKey('font-family', $t);
		$this->assertEquals('Arial', $t['font-family']);
	}

	public function testMergeFullCSS_WithTagAndClassSelector()
	{
		$p = ['DIV>>CLASS>>highlight' => ['font-weight' => 'bold']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', ['highlight'], '', '']);

		$this->assertArrayHasKey('font-weight', $t);
		$this->assertEquals('bold', $t['font-weight']);
	}

	public function testMergeFullCSS_WithTagAndIDSelector()
	{
		$p = ['P>>ID>>content' => ['line-height' => '1.5']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'P', [], 'content', '']);

		$this->assertArrayHasKey('line-height', $t);
		$this->assertEquals('1.5', $t['line-height']);
	}

	public function testMergeFullCSS_WithTagAndLangSelector()
	{
		$p = ['SPAN>>LANG>>fr' => ['text-decoration' => 'underline']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'SPAN', [], '', 'fr']);

		$this->assertArrayHasKey('text-decoration', $t);
		$this->assertEquals('underline', $t['text-decoration']);
	}

	public function testMergeFullCSS_WithTRTag_NthChildOdd()
	{
		// Set up for TR tag with nth-child selector using reflection for private properties
		$this->mpdf->row        = 0; // First row (will be evaluated as row 1 after +1)
		$this->mpdf->table      = [];
		$this->mpdf->tableLevel = 0;
		$this->mpdf->tbctr      = [0];

		$p = ['TR>>SELECTORNTHCHILD>>ODD' => ['background-color' => 'yellow']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'TR', [], '', '']);

		// Row 1 is odd, so style should be applied
		$this->assertArrayHasKey('background-color', $t);
		$this->assertEquals('yellow', $t['background-color']);
	}

	public function testMergeFullCSS_WithTRTag_NthChildEven()
	{

		// Set up for TR tag with nth-child selector, row 2 (even)
		$this->mpdf->row        = 1; // Second row (will be evaluated as row 2 after +1)
		$this->mpdf->table      = [];
		$this->mpdf->tableLevel = 0;
		$this->mpdf->tbctr      = [0];

		$p = ['TR>>SELECTORNTHCHILD>>EVEN' => ['background-color' => 'lightblue']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'TR', [], '', '']);

		// Row 2 is even, so style should be applied
		$this->assertArrayHasKey('background-color', $t);
		$this->assertEquals('lightblue', $t['background-color']);
	}

	public function testMergeFullCSS_WithTRTag_NthChild2nPlus1()
	{
		// Set up for TR tag with nth-child selector 2n+1
		$this->mpdf->row        = 2; // Third row (will be evaluated as row 3 after +1)
		$this->mpdf->table      = [];
		$this->mpdf->tableLevel = 0;
		$this->mpdf->tbctr      = [0];

		$p = ['TR>>SELECTORNTHCHILD>>2N+1' => ['border' => '1px solid red']];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'TR', [], '', '']);

		// Row 3 matches 2n+1 (odd rows), so style should be applied
		$this->assertArrayHasKey('border', $t);
		$this->assertEquals('1px solid red', $t['border']);
	}

	public function testMergeFullCSS_WithMultipleSelectors()
	{
		// Test combining multiple selector types
		$p = [
			'DIV'                 => ['color' => 'black'],
			'CLASS>>myclass'      => ['font-size' => '12px'],
			'DIV>>CLASS>>myclass' => ['font-weight' => 'bold'],
			'DIV>>ID>>myid'       => ['background' => 'white'],
		];
		$t = [];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', ['myclass'], 'myid', '']);

		// All matching selectors should be applied in order
		$this->assertEquals('black', $t['color']);
		$this->assertEquals('12px', $t['font-size']);
		$this->assertEquals('bold', $t['font-weight']);
		$this->assertEquals('white', $t['background']);
	}

	public function testMergeFullCSS_WithNoMatchingSelectors()
	{
		$p = ['SPAN' => ['color' => 'red']];
		$t = ['existing' => 'value'];

		$this->invokeMethod($this->cssMerger, 'mergeFullCssRules', [$p, &$t, 'DIV', [], '', '']);

		// Should not add the SPAN style since tag doesn't match
		$this->assertArrayNotHasKey('color', $t);
		$this->assertArrayHasKey('existing', $t);
	}

	public function testPreviewBlockCSS_WithDefaultCSS()
	{
		$this->mpdf->blk        = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl     = 0;
		$this->mpdf->defaultCSS = [
			'P' => ['MARGIN-TOP' => '1em', 'MARGIN-BOTTOM' => '1em'],
		];

		$result = $this->cssMerger->previewBlockCss('P', []);

		$this->assertEquals('1em', $result['MARGIN-TOP']);
		$this->assertEquals('1em', $result['MARGIN-BOTTOM']);
	}

	public function testPreviewBlockCSS_WithTagStyle()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['H1' => ['font-size' => '2em', 'font-weight' => 'bold']];

		$result = $this->cssMerger->previewBlockCss('H1', []);

		$this->assertEquals('2em', $result['font-size']);
		$this->assertEquals('bold', $result['font-weight']);
	}

	public function testPreviewBlockCSS_WithClassAttribute()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->readCss('<style>.highlight { background-color: yellow; }</style>');

		$result = $this->cssMerger->previewBlockCss('DIV', ['CLASS' => 'HIGHLIGHT']);

		$this->assertEquals('yellow', $result['BACKGROUND-COLOR']);
	}

	public function testPreviewBlockCSS_WithIDAttribute()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['ID>>header' => ['padding' => '20px']];

		$result = $this->cssMerger->previewBlockCss('DIV', ['ID' => 'header']);

		$this->assertEquals('20px', $result['padding']);
	}

	public function testPreviewBlockCSS_WithTagAndClass()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->readCss('<style>P.intro { font-style: italic; }</style>');

		$result = $this->cssMerger->previewBlockCss('P', ['CLASS' => 'INTRO']);

		$this->assertEquals('italic', $result['FONT-STYLE']);
	}

	public function testPreviewBlockCSS_WithTagAndID()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['DIV>>ID>>main' => ['width' => '960px']];

		$result = $this->cssMerger->previewBlockCss('DIV', ['ID' => 'main']);

		$this->assertEquals('960px', $result['width']);
	}

	public function testPreviewBlockCSS_WithCascadedStyles()
	{
		$this->cssManager->readCss('
			<style>
				div p { color: blue; }
				div .note { border: 1px solid; } 
				div #content { margin: 10px; }
			</style>
		');

		$cascade = $this->cssManager->cascadeCSS;
		$this->mpdf->blk    = [
			0 => [
				'cascadeCSS' => $cascade['DIV'],
			],
		];
		$this->mpdf->blklvl = 0;


		$result = $this->cssMerger->previewBlockCss('P', ['CLASS' => 'NOTE', 'ID' => 'CONTENT']);

		$this->assertEquals('blue', $result['COLOR']);
		$this->assertEquals('1px solid #000000', $result['BORDER-TOP']); // readCss expands
		$this->assertEquals('10px', $result['MARGIN-TOP']);
	}

	public function testPreviewBlockCSS_WithInlineStyle()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssMerger->previewBlockCss('DIV', ['STYLE' => 'color: red; padding: 5px;']);

		$this->assertEquals('red', $result['COLOR']);
		$this->assertEquals('5px', $result['PADDING-TOP']);
		$this->assertEquals('5px', $result['PADDING-BOTTOM']);
		$this->assertEquals('5px', $result['PADDING-LEFT']);
		$this->assertEquals('5px', $result['PADDING-RIGHT']);
	}

	public function testPreviewBlockCSS_WithMultipleClasses()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->readCss('<style>.box { border: 1px solid; } .shadow { box-shadow: 0 2px 4px; }</style>');

		$result = $this->cssMerger->previewBlockCss('DIV', ['CLASS' => 'BOX SHADOW']);

		$this->assertArrayHasKey('BORDER-TOP', $result);
		$this->assertArrayHasKey('BOX-SHADOW', $result);
	}

	public function testPreviewBlockCSS_WithCombinedSelectors()
	{
		$this->mpdf->blk       = [
			0 => [
				'cascadeCSS' => [
					'P>>CLASS>>ALERT' => ['depth' => 2, 'FONT-WEIGHT' => 'bold'],
				],
			],
		];
		$this->mpdf->blklvl    = 0;

		$this->cssManager->readCss('
			<style>
				p { margin: 1em; }
				.alert { color: red; }
				#warning { border-left: 4px solid; }
				p.alert { padding: 10px; }
				p#warning { background: #fee; }
			</style>
		');

		$result = $this->cssMerger->previewBlockCss('P', ['CLASS' => 'ALERT', 'ID' => 'WARNING']);

		// Should have all applicable styles
		$this->assertArrayHasKey('MARGIN-TOP', $result);
		$this->assertArrayHasKey('COLOR', $result);
		$this->assertArrayHasKey('BORDER-LEFT', $result); // Specific property should exist
		$this->assertArrayHasKey('PADDING-TOP', $result);
		$this->assertArrayHasKey('BACKGROUND-COLOR', $result); // background expands
		$this->assertArrayHasKey('FONT-WEIGHT', $result); // From cascaded styles with depth
	}

	public function testPreviewBlockCSS_CSSPrecedence()
	{
		$this->mpdf->blk        = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl     = 0;
		$this->mpdf->defaultCSS = ['P' => ['COLOR' => 'black']];
		$this->cssManager->CSS  = [
			'P'                => ['color' => 'blue'],
			'CLASS>>highlight' => ['color' => 'green'],
		];

		$result = $this->cssMerger->previewBlockCss('P', [
			'CLASS' => 'highlight',
			'STYLE' => 'color: red',
		]);

		// Inline style should win
		$this->assertArrayHasKey('COLOR', $result);
		$this->assertEquals('red', $result['COLOR']);
	}

	public function testPreviewBlockCSS_WithNoAttributes()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['SPAN' => ['display' => 'inline']];

		$result = $this->cssMerger->previewBlockCss('SPAN', []);

		$this->assertArrayHasKey('display', $result);
		$this->assertEquals('inline', $result['display']);
	}

	public function testSetBorderDominance_WithAllBorders()
	{
		$prop = [
			'BORDER-LEFT'   => '1px solid #000',
			'BORDER-RIGHT'  => '1px solid #000',
			'BORDER-TOP'    => '1px solid #000',
			'BORDER-BOTTOM' => '1px solid #000',
		];

		$this->cssMerger->setDominanceFromProperties($prop, 5);
		$this->assertEquals(5, $this->cssMerger->getBorderDominance('L'));
		$this->assertEquals(5, $this->cssMerger->getBorderDominance('R'));
		$this->assertEquals(5, $this->cssMerger->getBorderDominance('T'));
		$this->assertEquals(5, $this->cssMerger->getBorderDominance('B'));
	}

	public function testSetBorderDominance_WithPartialBorders()
	{
		$this->cssMerger->setBorderDominance('L', 0);
		$this->cssMerger->setBorderDominance('T', 0);

		$prop = ['BORDER-LEFT' => '1px solid #000'];
		$this->cssMerger->setDominanceFromProperties($prop, 3);

		$this->assertEquals(3, $this->cssMerger->getBorderDominance('L'));
		$this->assertEquals(0, $this->cssMerger->getBorderDominance('T'));
	}


	public function testMergeWithManyClasses()
	{
		$this->cssManager->readCss('<style>.red { color: red; }</style>');
		$classes = 'RED';
		for ($i = 0; $i < 30; $i++) {
			$classes .= ' UNUSED-CLASS-' . $i;
		}

		$result = $this->cssMerger->previewBlockCss('DIV', ['CLASS' => $classes]);

		$this->assertEquals('red', $result['COLOR']);
	}

	public function testMergeWithComplexSelectors()
	{
		$this->cssManager->readCss('<style>.a.b.c { color: green; }</style>');
		
		$result = $this->cssMerger->previewBlockCss('DIV', ['CLASS' => 'A B C D']);

		$this->assertEquals('green', $result['COLOR']);
	}
}
