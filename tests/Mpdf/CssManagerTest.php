<?php

namespace Mpdf;

class CssManagerTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/** @var CssManager */
	private $cssManager;

	/** @var Mpdf */
	private $mpdf;

	private $tempDir;

	protected function set_up()
	{
		parent::set_up();

		$this->tempDir = sys_get_temp_dir() . '/mpdf_test_' . uniqid();
		mkdir($this->tempDir);

		$this->mpdf = new Mpdf(['tempDir' => $this->tempDir]);
		$this->mpdf->setBasePath($this->tempDir);

		// Use reflection to access private cssManager property
		$reflection = new \ReflectionClass($this->mpdf);
		$property   = $reflection->getProperty('cssManager');
		$property->setAccessible(true);
		$this->cssManager = $property->getValue($this->mpdf);

		// Ensure we have a page to work with
		$this->mpdf->AddPage();
	}

	protected function tear_down()
	{
		unset($this->mpdf, $this->cssManager);

		$this->removeDirectory($this->tempDir);

		parent::tear_down();
	}

	private function createCssFile($filename, $content)
	{
		file_put_contents($this->tempDir . '/' . $filename, $content);
	}

	private function removeDirectory($dir)
	{
		if (! is_dir($dir)) {
			return;
		}

		$files = array_diff(scandir($dir), ['.', '..']);
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->removeDirectory("$dir/$file") : unlink("$dir/$file");
		}

		rmdir($dir);
	}

	public function testConstructor_InitializesProperties()
	{
		$this->assertIsArray($this->cssManager->tablecascadeCSS);
		$this->assertIsArray($this->cssManager->CSS);
		$this->assertIsArray($this->cssManager->cascadeCSS);
		$this->assertEquals(0, $this->cssManager->tbCSSlvl);
	}

	public function testReadCSS_WithMediaQueryFiltering()
	{
		// Set CSSselectMedia to 'screen'
		$this->mpdf->CSSselectMedia = 'screen';

		$html = '<style media="print">body { color: black; }</style>
				<style media="screen">body { color: blue; }</style>
				<style media="all">h1 { font-size: 2em; }</style>';

		$result = $this->cssManager->ReadCSS($html);

		// Should remove print media style from HTML (and all style tags)
		$this->assertStringNotContainsString('media="print"', $result);
		$this->assertStringNotContainsString('media="screen"', $result);

		// Verify CSS was parsed into cssManager.CSS property
		$this->assertIsArray($this->cssManager->CSS);
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
		// Screen media style should be present
		$this->assertEquals('blue', $this->cssManager->CSS['BODY']['COLOR']);
		// Verify style tags removed from result
		$this->assertStringNotContainsString('<style', $result);
	}

	public function testReadCSS_WithCommentRemoval()
	{
		$html = '<style>
				/* CSS comment */
				body { color: red; }
				<!-- HTML comment -->
				</style>';

		$result = $this->cssManager->ReadCSS($html);

		// ReadCSS returns modified HTML with processed styles (style tags removed)
		$this->assertStringNotContainsString('<style', $result);

		// Verify CSS was parsed
		$this->assertIsArray($this->cssManager->CSS);
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
	}

	public function testReadCSS_WithExternalStylesheet()
	{
		$this->createCssFile('style.css', 'body { background: white; }');

		$html = '<link rel="stylesheet" href="style.css">';

		$result = $this->cssManager->ReadCSS($html);

		// Should process the external stylesheet and remove style tags
		$this->assertStringNotContainsString('<style', $result);
		// Verify the link tag is still present (ReadCSS returns modified HTML)
		$this->assertStringContainsString('link', $result);

		// Verify CSS was parsed
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
		$this->assertEquals('white', $this->cssManager->CSS['BODY']['BACKGROUND-COLOR']);
	}

	public function testReadCSS_WithReversedRelHref()
	{
		$this->createCssFile('layout.css', '.content { padding: 20px; }');

		$html = '<link href="layout.css" rel="stylesheet">';

		$result = $this->cssManager->ReadCSS($html);

		// Verify link tag is preserved in result
		$this->assertStringContainsString('link', $result);

		// Verify CSS parsed
		$this->assertArrayHasKey('CLASS>>CONTENT', $this->cssManager->CSS);
	}

	public function testReadCSS_WithImportUrl()
	{
		$this->createCssFile('reset.css', 'body { margin: 0; }');

		$html = '<style>@import url("reset.css");</style>';

		$result = $this->cssManager->ReadCSS($html);

		// Verify style tags removed (import processed)
		$this->assertStringNotContainsString('<style', $result);

		// Verify CSS parsed
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
		$this->assertEquals('0', $this->cssManager->CSS['BODY']['MARGIN-TOP']);
	}

	public function testReadCSS_WithImportWithoutUrl()
	{
		$this->createCssFile('theme.css', '.theme { color: blue; }');

		$html = '<style>@import "theme.css";</style>';

		$result = $this->cssManager->ReadCSS($html);

		// Verify style tags removed (import processed)
		$this->assertStringNotContainsString('<style', $result);

		// Verify CSS parsed
		$this->assertArrayHasKey('CLASS>>THEME', $this->cssManager->CSS);
	}

	public function testReadCSS_WithNestedImport()
	{
		$this->createCssFile('base.css', '* { box-sizing: border-box; }');
		$this->createCssFile('main.css', '@import url("base.css"); body { font-family: Arial; }');

		$html = '<link rel="stylesheet" href="main.css">';

		$result = $this->cssManager->ReadCSS($html);

		// Verify link tag is preserved in result
		$this->assertStringContainsString('link', $result);

		// Verify CSS parsed from both files
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);

		$this->assertEquals('dejavuserifcondensed', $this->cssManager->CSS['BODY']['FONT-FAMILY']);
	}

	public function testReadCSS_WithBackgroundUrlRewriting()
	{
		$this->createCssFile('theme.css', '.header { background: url(images/logo.png); }');

		$html = '<link rel="stylesheet" href="theme.css">';

		$result = $this->cssManager->ReadCSS($html);

		// Should process background URLs and preserve link
		$this->assertStringContainsString('link', $result);

		// Verify CSS parsed
		$this->assertArrayHasKey('CLASS>>HEADER', $this->cssManager->CSS);
		$this->assertStringContainsString('logo.png', $this->cssManager->CSS['CLASS>>HEADER']['BACKGROUND-IMAGE']);
	}

	public function testReadCSS_WithDataUri()
	{
		$this->createCssFile('icons.css', '.icon { background: url(data:image/png;base64,iVBOR); }');

		$html = '<link rel="stylesheet" href="icons.css">';

		$result = $this->cssManager->ReadCSS($html);

		// Verify CSS was parsed
		$this->assertStringContainsString('link', $result);
		$this->assertArrayHasKey('CLASS>>ICON', $this->cssManager->CSS);
		$this->assertArrayHasKey('BACKGROUND-IMAGE', $this->cssManager->CSS['CLASS>>ICON']);
		$this->assertNotEmpty($this->cssManager->CSS['CLASS>>ICON']['BACKGROUND-IMAGE']);
	}

	public function testReadCSS_WithInlineStyles()
	{
		$html = '<style>
				body { margin: 0; padding: 0; }
				.container { width: 100%; }
				</style>';

		$result = $this->cssManager->ReadCSS($html);

		// ReadCSS returns modified HTML with style tags removed
		$this->assertStringNotContainsString('<style', $result);

		// Verify CSS was parsed
		$this->assertIsArray($this->cssManager->CSS);
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
		$this->assertArrayHasKey('CLASS>>CONTAINER', $this->cssManager->CSS);
	}

	public function testReadCSS_WithEmptyHtml()
	{
		$html = '';

		$result = $this->cssManager->ReadCSS($html);

		$this->assertEquals('', $result);
	}

	public function testReadCSS_ComprehensiveStressTest()
	{
		// Create external stylesheets
		$this->createCssFile('base.css', '* { margin: 0; padding: 0; box-sizing: border-box; }');

		$this->createCssFile('main.css', '
			.header { background: url(images/header-bg.jpg) no-repeat; padding: 20px; }
			.nav-item:hover { color: #3498db; transition: all 0.3s; }');

		$this->createCssFile('theme.css', '.theme-dark { background: #2c3e50; color: #ecf0f1; }
			.btn-primary { background: linear-gradient(to bottom, #3498db, #2980b9); }');

		$this->createCssFile('fonts.css', '@font-face { font-family: "Open Sans"; src: url("fonts/OpenSans-Regular.ttf"); }');
		$this->createCssFile('responsive.css', '@media (max-width: 768px) { .container { width: 100%; } }');

		// Comprehensive HTML with 100+ CSS data points
		$html = '<!DOCTYPE html>
		<html>
		<head>
			<title>Comprehensive CSS Test</title>
			
			<!-- External Stylesheets (3 files) -->
			<link rel="stylesheet" href="main.css">
			<link href="theme.css" rel="stylesheet">
			
			<!-- Inline Styles with Media Queries -->
			<style media="screen">
				/* Reset Styles */
				body, html { margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; }
				
				/* Typography */
				h1 { font-size: 2.5em; font-weight: bold; color: #2c3e50; margin-bottom: 0.5em; text-align: center; }
				h2 { font-size: 2em; font-weight: 600; color: #34495e; margin: 1em 0 0.5em; }
				h3 { font-size: 1.75em; font-weight: 500; color: #7f8c8d; }
				p { margin: 0 0 1em; color: #333; }
				a { color: #3498db; text-decoration: none; transition: color 0.3s ease; }
				a:hover { color: #2980b9; text-decoration: underline; }
				a:visited { color: #8e44ad; }
				a:active { color: #c0392b; }
				
				/* Layout */
				.container { max-width: 1200px; margin: 0 auto; padding: 20px; }
				.row { display: flex; flex-wrap: wrap; margin: -10px; }
				.col { flex: 1; padding: 10px; min-width: 300px; }
				.col-2 { flex: 0 0 50%; }
				.col-3 { flex: 0 0 33.333%; }
				.col-4 { flex: 0 0 25%; }
				
				/* Header */
				header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2em 0; }
				header .logo { font-size: 2em; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
				
				/* Navigation */
				nav { background: #2c3e50; border-bottom: 3px solid #3498db; }
				nav ul { list-style: none; margin: 0; padding: 0; display: flex; }
				nav li { padding: 0; }
				nav a { display: block; padding: 15px 20px; color: #ecf0f1; }
				nav a:hover { background: #34495e; color: #3498db; }
				
				/* Buttons */
				.btn { display: inline-block; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; transition: all 0.3s; }
				.btn-primary { background: #3498db; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
				.btn-primary:hover { background: #2980b9; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
				.btn-secondary { background: #95a5a6; color: white; }
				.btn-danger { background: #e74c3c; color: white; }
				.btn-success { background: #2ecc71; color: white; }
				
				/* Cards */
				.card { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
				.card-header { font-size: 1.25em; font-weight: bold; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #ecf0f1; }
				.card-body { color: #555; }
				.card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.15); transform: translateY(-5px); transition: all 0.3s; }
				
				/* Form Elements */
				input[type="text"], input[type="email"], input[type="password"], textarea, select {
					width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em;
					transition: border-color 0.3s, box-shadow 0.3s;
				}
				input:focus, textarea:focus, select:focus {
					border-color: #3498db; outline: none; box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
				}
				label { display: block; margin-bottom: 5px; font-weight: 500; color: #2c3e50; }
				
				/* Tables */
				table { width: 100%; border-collapse: collapse; margin: 1em 0; }
				th { background: #2c3e50; color: white; padding: 12px; text-align: left; font-weight: bold; }
				td { padding: 10px; border-bottom: 1px solid #ecf0f1; }
				tr:hover { background: #f8f9fa; }
				tr:nth-child(even) { background: #f4f4f4; }
				tr:nth-child(odd) { background: white; }
				
				/* Utility Classes */
				.text-center { text-align: center; }
				.text-right { text-align: right; }
				.text-left { text-align: left; }
				.m-0 { margin: 0; }
				.m-1 { margin: 10px; }
				.m-2 { margin: 20px; }
				.p-0 { padding: 0; }
				.p-1 { padding: 10px; }
				.p-2 { padding: 20px; }
				.d-none { display: none; }
				.d-block { display: block; }
				.d-flex { display: flex; }
				.float-left { float: left; }
				.float-right { float: right; }
				.clearfix::after { content: ""; display: table; clear: both; }
				
				/* Animations */
				@keyframes fadeIn {
					from { opacity: 0; transform: translateY(20px); }
					to { opacity: 1; transform: translateY(0); }
				}
				@keyframes spin {
					from { transform: rotate(0deg); }
					to { transform: rotate(360deg); }
				}
				.fade-in { animation: fadeIn 0.5s ease-in; }
				.spinner { animation: spin 1s linear infinite; }
				
				/* Pseudo-elements  */
				.quote::before { content: """; font-size: 3em; color: #3498db; }
				.quote::after { content: """; font-size: 3em; color: #3498db; }
				
				/* Advanced Selectors */
				.list > li { margin-bottom: 10px; }
				.menu ~ .content { margin-top: 20px; }
				.parent + .sibling { border-top: 1px solid #ddd; }
				input[type="checkbox"]:checked + label { color: #2ecc71; font-weight: bold; }
				.item:first-child { border-top-left-radius: 8px; border-top-right-radius: 8px; }
				.item:last-child { border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; }
				.item:not(.active) { opacity: 0.6; }
				
				/* Responsive */
				@media (max-width: 768px) {
					.container { padding: 10px; }
					.col { flex: 0 0 100%; }
					nav ul { flex-direction: column; }
				}
			</style>
			
			<!-- Print Styles (should be filtered out if CSSselectMedia is set) -->
			<style media="print">
				body { color: black; background: white; }
				header, nav, .no-print { display: none; }
			</style>
			
			<!-- @import statements -->
			<style>
				@import url("fonts.css");
				@import "responsive.css";
			</style>
		</head>
		<body>
			<header>
				<div class="container">
					<h1 class="logo">Test Site</h1>
				</div>
			</header>
		</body>
		</html>';

		// Set media to screen to ensure screen styles are parsed
		$this->mpdf->CSSselectMedia = 'screen';
		$this->cssManager->ReadCSS($html);

		// Verify CSS was parsed into CSS property
		$this->assertIsArray($this->cssManager->CSS);
		$this->assertNotEmpty($this->cssManager->CSS);

		// Verify Reset Styles (BODY, HTML)
		$this->assertArrayHasKey('BODY', $this->cssManager->CSS);
		$this->assertArrayHasKey('MARGIN-TOP', $this->cssManager->CSS['BODY']);
		$this->assertEquals('0', $this->cssManager->CSS['BODY']['MARGIN-TOP']);
		$this->assertEquals('16px', $this->cssManager->CSS['BODY']['FONT-SIZE']);
		$this->assertEquals('arial', $this->cssManager->CSS['BODY']['FONT-FAMILY']);

		// Verify Typography (H1, P, A)
		$this->assertArrayHasKey('H1', $this->cssManager->CSS);
		$this->assertEquals('2.5em', $this->cssManager->CSS['H1']['FONT-SIZE']);
		$this->assertEquals('bold', $this->cssManager->CSS['H1']['FONT-WEIGHT']);
		$this->assertEquals('#2c3e50', $this->cssManager->CSS['H1']['COLOR']);
		
		$this->assertArrayHasKey('P', $this->cssManager->CSS);
		$this->assertEquals('#333', $this->cssManager->CSS['P']['COLOR']);

		$this->assertArrayHasKey('A', $this->cssManager->CSS);
		$this->assertEquals('#3498db', $this->cssManager->CSS['A']['COLOR']);
		$this->assertEquals('none', $this->cssManager->CSS['A']['TEXT-DECORATION']);

		// Verify Layout Classes (.container, .row, .col)
		$this->assertArrayHasKey('CLASS>>CONTAINER', $this->cssManager->CSS);
		$this->assertEquals('1200px', $this->cssManager->CSS['CLASS>>CONTAINER']['MAX-WIDTH']);
		$this->assertEquals('0', $this->cssManager->CSS['CLASS>>CONTAINER']['MARGIN-TOP']);
		$this->assertEquals('0', $this->cssManager->CSS['CLASS>>CONTAINER']['MARGIN-BOTTOM']);
		$this->assertEquals('auto', $this->cssManager->CSS['CLASS>>CONTAINER']['MARGIN-LEFT']);
		$this->assertEquals('auto', $this->cssManager->CSS['CLASS>>CONTAINER']['MARGIN-RIGHT']);

		$this->assertArrayHasKey('CLASS>>ROW', $this->cssManager->CSS);
		$this->assertEquals('flex', $this->cssManager->CSS['CLASS>>ROW']['DISPLAY']);

		$this->assertArrayHasKey('CLASS>>COL', $this->cssManager->CSS);
		$this->assertEquals('300px', $this->cssManager->CSS['CLASS>>COL']['MIN-WIDTH']);
		$this->assertEquals('10px', $this->cssManager->CSS['CLASS>>COL']['PADDING-TOP']); // padding: 10px expands

		// Verify Components (.btn-primary, .card)
		$this->assertArrayHasKey('CLASS>>BTN-PRIMARY', $this->cssManager->CSS);
		$this->assertEquals('#3498db', $this->cssManager->CSS['CLASS>>BTN-PRIMARY']['BACKGROUND-COLOR']);
		$this->assertEquals('white', $this->cssManager->CSS['CLASS>>BTN-PRIMARY']['COLOR']);

		$this->assertArrayHasKey('CLASS>>CARD', $this->cssManager->CSS);
		$this->assertEquals('white', $this->cssManager->CSS['CLASS>>CARD']['BACKGROUND-COLOR']);
		$this->assertEquals('8px', $this->cssManager->CSS['CLASS>>CARD']['BORDER-TOP-LEFT-RADIUS-H']);

		// Verify External Styles (from main.css, theme.css)
		// .header from main.css
		$this->assertArrayHasKey('CLASS>>HEADER', $this->cssManager->CSS);
		$this->assertStringContainsString('images/header-bg.jpg', $this->cssManager->CSS['CLASS>>HEADER']['BACKGROUND-IMAGE']);
		
		// .theme-dark from theme.css
		$this->assertArrayHasKey('CLASS>>THEME-DARK', $this->cssManager->CSS);
		$this->assertEquals('#2c3e50', $this->cssManager->CSS['CLASS>>THEME-DARK']['BACKGROUND-COLOR']);

		// Verify Nested/Complex Selectors
		$this->assertArrayHasKey('CLASS>>NAV-ITEM:HOVER', $this->cssManager->CSS);
		$this->assertEquals('#3498db', $this->cssManager->CSS['CLASS>>NAV-ITEM:HOVER']['COLOR']);

		// Verify Media Queries are parsed and ignored
		// Check if the base .container style is still there (max-width: 1200px).
		$this->assertEquals('1200px', $this->cssManager->CSS['CLASS>>CONTAINER']['MAX-WIDTH']);

		// Verify Utility Classes
		$this->assertArrayHasKey('CLASS>>TEXT-CENTER', $this->cssManager->CSS);
		$this->assertEquals('center', $this->cssManager->CSS['CLASS>>TEXT-CENTER']['TEXT-ALIGN']);

		$this->assertArrayHasKey('CLASS>>D-NONE', $this->cssManager->CSS);
		$this->assertEquals('none', $this->cssManager->CSS['CLASS>>D-NONE']['DISPLAY']);
	}

	public function testMergeCSS_WithInheritBlock()
	{
		// Set up mock data for BLOCK inheritance
		$this->mpdf->blk       = [
			0 => [
				'cascadeCSS'      => [],
				'margin_collapse' => true,
				'line_height'     => '1.5',
				'direction'       => 'rtl',
				'align'           => 'C',
			],
			1 => [
				'cascadeCSS' => [],
			],
		];
		$this->mpdf->blklvl    = 1;
		$this->cssManager->CSS = ['DIV' => ['font-size' => '14px']];

		$result = $this->cssManager->MergeCSS('BLOCK', 'DIV', []);

		// Should inherit block properties
		$this->assertArrayHasKey('MARGIN-COLLAPSE', $result);
		$this->assertArrayHasKey('LINE-HEIGHT', $result);
		$this->assertArrayHasKey('DIRECTION', $result);
		$this->assertArrayHasKey('TEXT-ALIGN', $result);
		$this->assertEquals('COLLAPSE', $result['MARGIN-COLLAPSE']);
		$this->assertEquals('1.5', $result['LINE-HEIGHT']);
		$this->assertEquals('rtl', $result['DIRECTION']);
		$this->assertEquals('center', $result['TEXT-ALIGN']);
	}

	public function testMergeCSS_WithInheritInline()
	{
		// Set up mock data for INLINE inheritance
		$this->mpdf->blk       = [
			0 => [
				'cascadeCSS' => [],
			],
		];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['SPAN' => ['color' => 'blue']];

		$result = $this->cssManager->MergeCSS('INLINE', 'SPAN', []);

		// Should merge CSS but not inherit block properties
		$this->assertArrayHasKey('color', $result);
		$this->assertEquals('blue', $result['color']);
	}

	public function testMergeCSS_WithInheritTOPTABLE()
	{
		// Set up mock data for TOPTABLE inheritance
		$this->mpdf->blk                   = [
			0 => ['cascadeCSS' => []],
		];
		$this->mpdf->blklvl                = 0;
		$this->cssManager->CSS             = ['TABLE' => ['border-collapse' => 'collapse']];
		$this->cssManager->tablecascadeCSS = [];
		$this->cssManager->tbCSSlvl        = 0;

		$result = $this->cssManager->MergeCSS('TOPTABLE', 'TABLE', []);

		// Should save cascade CSS and apply table styles
		$this->assertArrayHasKey('border-collapse', $result);
		$this->assertEquals('collapse', $result['border-collapse']);
	}

	public function testMergeCSS_WithInheritTABLE()
	{
		// Set up mock data for TABLE inheritance
		$this->cssManager->tablecascadeCSS = [0 => []];
		$this->cssManager->tbCSSlvl        = 1;
		$this->cssManager->CSS             = ['TR' => ['background' => 'white']];

		$result = $this->cssManager->MergeCSS('TABLE', 'TR', []);

		// Should apply table styles and cascade from previous level
		$this->assertArrayHasKey('background', $result);
		$this->assertEquals('white', $result['background']);
	}

	public function testMergeCSS_WithAttrCLASS()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->readCss('<style>.highlight { background-color: yellow; }</style>');

		$result = $this->cssManager->MergeCSS('INLINE', 'SPAN', ['CLASS' => 'HIGHLIGHT']);

		$this->assertArrayHasKey('BACKGROUND-COLOR', $result);
		$this->assertEquals('yellow', $result['BACKGROUND-COLOR']);
	}

	public function testMergeCSS_WithAttrID()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['ID>>myid' => ['font-weight' => 'bold']];

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['ID' => 'myid']);

		$this->assertArrayHasKey('font-weight', $result);
		$this->assertEquals('bold', $result['font-weight']);
	}

	public function testMergeCSS_WithAttrLANG()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = [
			'LANG>>fr' => ['quotes' => '« »'],
		];

		$result = $this->cssManager->MergeCSS('INLINE', 'P', ['LANG' => 'fr']);

		$this->assertArrayHasKey('LANG', $result);
		$this->assertEquals('fr', $result['LANG']);
		$this->assertArrayHasKey('quotes', $result);
	}

	public function testMergeCSS_WithAttrLANG_Shortcode()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = [
			'LANG>>en' => ['hyphenate' => 'auto'],
		];

		// Test with 5-character lang code that should use 2-character shortlang
		$result = $this->cssManager->MergeCSS('INLINE', 'P', ['LANG' => 'en-US']);

		$this->assertArrayHasKey('LANG', $result);
		$this->assertEquals('en-us', $result['LANG']);
		$this->assertArrayHasKey('hyphenate', $result);
	}

	public function testMergeCSS_WithAttrSTYLE()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['STYLE' => 'color: red; margin: 10px;']);

		$this->assertArrayHasKey('COLOR', $result);
		$this->assertEquals('red', $result['COLOR']);
		$this->assertArrayHasKey('MARGIN-TOP', $result);
	}

	public function testMergeCSS_WithAttrDIR()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['DIR' => 'rtl']);

		$this->assertArrayHasKey('DIRECTION', $result);
		$this->assertEquals('rtl', $result['DIRECTION']);
	}

	public function testMergeCSS_WithAttrCOLOR()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'SPAN', ['COLOR' => '#ff0000']);

		$this->assertArrayHasKey('COLOR', $result);
		$this->assertEquals('#ff0000', $result['COLOR']);
	}

	public function testMergeCSS_WithAttrWIDTH_HEIGHT()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['WIDTH' => '100px', 'HEIGHT' => '200px']);

		$this->assertArrayHasKey('WIDTH', $result);
		$this->assertArrayHasKey('HEIGHT', $result);
		$this->assertEquals('100px', $result['WIDTH']);
		$this->assertEquals('200px', $result['HEIGHT']);
	}

	public function testMergeCSS_WithFONT_Tag()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'FONT', [
			'FACE'  => 'Arial',
			'SIZE'  => '4',
			'COLOR' => 'blue',
		]);

		$this->assertArrayHasKey('FONT-FAMILY', $result);
		$this->assertArrayHasKey('FONT-SIZE', $result);
		$this->assertEquals('Arial', $result['FONT-FAMILY']);
		$this->assertEquals('MEDIUM', $result['FONT-SIZE']);
	}

	public function testMergeCSS_WithFONT_SizePlus1()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'FONT', ['SIZE' => '+1']);

		$this->assertArrayHasKey('FONT-SIZE', $result);
		$this->assertEquals('120%', $result['FONT-SIZE']);
	}

	public function testMergeCSS_WithFONT_SizeMinus1()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'FONT', ['SIZE' => '-1']);
		$this->assertArrayHasKey('FONT-SIZE', $result);
		$this->assertEquals('86%', $result['FONT-SIZE']);
	}

	public function testMergeCSS_WithAttrVALIGN()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;
		// Add table data required for TD/TH tags
		$this->mpdf->table      = [];
		$this->mpdf->tableLevel = 0;
		$this->mpdf->tbctr      = [0];

		$result = $this->cssManager->MergeCSS('INLINE', 'TD', ['VALIGN' => 'middle']);

		$this->assertArrayHasKey('VERTICAL-ALIGN', $result);
		$this->assertEquals('middle', $result['VERTICAL-ALIGN']);
	}

	public function testMergeCSS_WithAttrVSPACE_HSPACE()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'IMG', ['VSPACE' => '10', 'HSPACE' => '20']);

		// Default the default styles override the inherited styles
		$this->assertEquals('0', $result['MARGIN-TOP']);
		$this->assertEquals('0', $result['MARGIN-BOTTOM']);
		$this->assertEquals('0', $result['MARGIN-LEFT']);
		$this->assertEquals('0', $result['MARGIN-RIGHT']);

		// remove default stylesheet properties so we can test the inherited styles
		$this->mpdf->defaultCSS['IMG'] = [];
		$result                        = $this->cssManager->MergeCSS('INLINE', 'IMG', ['VSPACE' => '10', 'HSPACE' => '20']);

		$this->assertEquals('10', $result['MARGIN-TOP']);
		$this->assertEquals('10', $result['MARGIN-BOTTOM']);
		$this->assertEquals('20', $result['MARGIN-LEFT']);
		$this->assertEquals('20', $result['MARGIN-RIGHT']);
	}

	public function testMergeCSS_WithTABLE_CELLSPACING()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->MergeCSS('INLINE', 'TABLE', ['CELLSPACING' => '5']);

		$this->assertEquals('5', $result['BORDER-SPACING-H']);
		$this->assertEquals('5', $result['BORDER-SPACING-V']);
	}

	public function testMergeCSS_WithLI_Tag_InheritsListStyle()
	{
		$this->mpdf->blk    = [
			0 => [
				'cascadeCSS'          => [],
				'list_style_type'     => 'disc',
				'list_style_image'    => 'bullet.png',
				'list_style_position' => 'inside',
			],
			1 => ['cascadeCSS' => []],
		];
		$this->mpdf->blklvl = 1;

		$result = $this->cssManager->MergeCSS('BLOCK', 'LI', []);

		$this->assertArrayHasKey('LIST-STYLE-TYPE', $result);
		$this->assertArrayHasKey('LIST-STYLE-IMAGE', $result);
		$this->assertArrayHasKey('LIST-STYLE-POSITION', $result);
		$this->assertEquals('disc', $result['LIST-STYLE-TYPE']);
	}

	public function testMergeCSS_WithDefaultCSS()
	{
		$this->mpdf->blk        = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl     = 0;
		$this->mpdf->defaultCSS = [
			'P' => ['MARGIN-TOP' => '1em', 'MARGIN-BOTTOM' => '1em'],
		];

		$result = $this->cssManager->MergeCSS('INLINE', 'P', []);

		$this->assertArrayHasKey('MARGIN-TOP', $result);
		$this->assertArrayHasKey('MARGIN-BOTTOM', $result);
		$this->assertEquals('1em', $result['MARGIN-TOP']);
	}

	public function testMergeCSS_WithCombinedClassAndID()
	{
		$this->mpdf->blk = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;
		$this->cssManager->readCss('<style>div.alert { border: 1px solid red; } div#main { margin: 20px; }</style>');

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['CLASS' => 'ALERT', 'ID' => 'MAIN']);

		$this->assertArrayHasKey('BORDER-TOP', $result); // Border expanded
		$this->assertArrayHasKey('MARGIN-TOP', $result); // Margin expanded
	}
}
