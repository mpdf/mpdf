<?php

namespace Mpdf;

use Mpdf\Css\TextVars;

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
		unset($this->mpdf);
		unset($this->cssManager);

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
		$result = $this->cssManager->ReadCSS($html);

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

	public function testExpand24_WithOneValue()
	{
		$result   = $this->cssManager->expand24('10px');
		$expected = ['T' => '10px', 'R' => '10px', 'B' => '10px', 'L' => '10px'];
		$this->assertEquals($expected, $result);
	}

	public function testExpand24_WithTwoValues()
	{
		$result   = $this->cssManager->expand24('10px 20px');
		$expected = ['T' => '10px', 'R' => '20px', 'B' => '10px', 'L' => '20px'];
		$this->assertEquals($expected, $result);
	}

	public function testExpand24_WithThreeValues()
	{
		$result   = $this->cssManager->expand24('10px 20px 30px');
		$expected = ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '20px'];
		$this->assertEquals($expected, $result);
	}

	public function testExpand24_WithFourValues()
	{
		$result   = $this->cssManager->expand24('10px 20px 30px 40px');
		$expected = ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '40px'];
		$this->assertEquals($expected, $result);
	}

	public function testExpand24_WithMoreThanFourValues_IgnoresExtra()
	{
		$result   = $this->cssManager->expand24('10px 20px 30px 40px 50px');
		$expected = ['T' => '10px', 'R' => '20px', 'B' => '30px', 'L' => '40px'];
		$this->assertEquals($expected, $result);
	}

	public function testFixBorderStr_WithSingleStyle()
	{
		$result = $this->cssManager->_fix_borderStr('solid');
		$this->assertEquals('medium solid #000000', $result);
	}

	public function testFixBorderStr_WithSingleColor()
	{
		$result = $this->cssManager->_fix_borderStr('#ff0000');
		// Real implementation parses color first, so order is: color style default-color
		$this->assertEquals('#ff0000 none #000000', $result);
	}

	public function testFixBorderStr_WithSingleWidth()
	{
		$result = $this->cssManager->_fix_borderStr('2px');
		$this->assertEquals('2px none #000000', $result);
	}

	public function testFixBorderStr_WithWidthAndStyle()
	{
		$result = $this->cssManager->_fix_borderStr('2px solid');
		$this->assertEquals('2px solid #000000', $result);
	}

	public function testFixBorderStr_WithStyleAndColor()
	{
		$result = $this->cssManager->_fix_borderStr('solid #ff0000');
		$this->assertEquals('medium solid #ff0000', $result);
	}

	public function testFixBorderStr_WithWidthAndColor()
	{
		$result = $this->cssManager->_fix_borderStr('2px #ff0000');
		$this->assertEquals('2px none #ff0000', $result);
	}

	public function testFixBorderStr_WithAllThreeComponents()
	{
		$result = $this->cssManager->_fix_borderStr('2px solid #ff0000');
		$this->assertEquals('2px solid #ff0000', $result);
	}

	public function testFixBorderStr_WithReorderedComponents()
	{
		$result = $this->cssManager->_fix_borderStr('#ff0000 2px solid');
		$this->assertEquals('2px solid #ff0000', $result);
	}

	public function testFixBorderStr_WithNone()
	{
		$result = $this->cssManager->_fix_borderStr('none');
		$this->assertEquals('medium none #000000', $result);
	}

	public function testBorderRadiusExpand_WithSingleValue()
	{
		$result   = $this->cssManager->border_radius_expand('10px', 'BORDER-RADIUS');
		$expected = [
			'TL-H' => '10px',
			'TR-H' => '10px',
			'BR-H' => '10px',
			'BL-H' => '10px',
			'TL-V' => '10px',
			'TR-V' => '10px',
			'BR-V' => '10px',
			'BL-V' => '10px',
		];
		$this->assertEquals($expected, $result);
	}

	public function testBorderRadiusExpand_WithTwoValues()
	{
		$result   = $this->cssManager->border_radius_expand('10px 20px', 'BORDER-RADIUS');
		$expected = [
			'TL-H' => '10px',
			'TR-H' => '20px',
			'BR-H' => '10px',
			'BL-H' => '20px',
			'TL-V' => '10px',
			'TR-V' => '20px',
			'BR-V' => '10px',
			'BL-V' => '20px',
		];
		$this->assertEquals($expected, $result);
	}

	public function testBorderRadiusExpand_WithFourValues()
	{
		$result   = $this->cssManager->border_radius_expand('10px 20px 30px 40px', 'BORDER-RADIUS');
		$expected = [
			'TL-H' => '10px',
			'TR-H' => '20px',
			'BR-H' => '30px',
			'BL-H' => '40px',
			'TL-V' => '10px',
			'TR-V' => '20px',
			'BR-V' => '30px',
			'BL-V' => '40px',
		];
		$this->assertEquals($expected, $result);
	}

	public function testBorderRadiusExpand_WithSlashSeparatedValues()
	{
		$result   = $this->cssManager->border_radius_expand('10px 20px / 30px 40px', 'BORDER-RADIUS');
		$expected = [
			'TL-H' => '10px',
			'TR-H' => '20px',
			'BR-H' => '10px',
			'BL-H' => '20px',
			'TL-V' => '30px',
			'TR-V' => '40px',
			'BR-V' => '30px',
			'BL-V' => '40px',
		];
		$this->assertEquals($expected, $result);
	}

	public function testBorderRadiusExpand_TopLeftRadius()
	{
		$result   = $this->cssManager->border_radius_expand('10px 20px', 'BORDER-TOP-LEFT-RADIUS');
		$expected = ['TL-H' => '10px', 'TL-V' => '20px'];
		$this->assertEquals($expected, $result);
	}

	public function testBorderRadiusExpand_TopRightRadius()
	{
		$result   = $this->cssManager->border_radius_expand('15px', 'BORDER-TOP-RIGHT-RADIUS');
		$expected = ['TR-H' => '15px', 'TR-V' => '15px'];
		$this->assertEquals($expected, $result);
	}

	public function testArrayMergeRecursiveUnique_WithSimpleArrays()
	{
		$array1   = ['a' => 1, 'b' => 2];
		$array2   = ['b' => 3, 'c' => 4];
		$result   = $this->cssManager->array_merge_recursive_unique($array1, $array2);
		$expected = ['a' => 1, 'b' => 3, 'c' => 4];
		$this->assertEquals($expected, $result);
	}

	public function testArrayMergeRecursiveUnique_WithNestedArrays()
	{
		$array1   = ['a' => ['x' => 1, 'y' => 2]];
		$array2   = ['a' => ['y' => 3, 'z' => 4]];
		$result   = $this->cssManager->array_merge_recursive_unique($array1, $array2);
		$expected = ['a' => ['x' => 1, 'y' => 3, 'z' => 4]];
		$this->assertEquals($expected, $result);
	}

	public function testArrayMergeRecursiveUnique_WithIntegerKeys()
	{
		$array1 = [0 => 'a', 1 => 'b'];
		$array2 = [0 => 'c', 1 => 'd'];
		$result = $this->cssManager->array_merge_recursive_unique($array1, $array2);
		$this->assertCount(4, $result);
		$this->assertContains('a', $result);
		$this->assertContains('c', $result);
	}

	public function testNthchild_WithOdd()
	{
		$this->assertTrue($this->cssManager->_nthchild(['ODD'], 0)); // row 1
		$this->assertFalse($this->cssManager->_nthchild(['ODD'], 1)); // row 2
		$this->assertTrue($this->cssManager->_nthchild(['ODD'], 2)); // row 3
		$this->assertFalse($this->cssManager->_nthchild(['ODD'], 3)); // row 4
	}

	public function testNthchild_WithEven()
	{
		$this->assertFalse($this->cssManager->_nthchild(['EVEN'], 0)); // row 1
		$this->assertTrue($this->cssManager->_nthchild(['EVEN'], 1)); // row 2
		$this->assertFalse($this->cssManager->_nthchild(['EVEN'], 2)); // row 3
		$this->assertTrue($this->cssManager->_nthchild(['EVEN'], 3)); // row 4
	}

	public function testNthchild_WithSpecificNumber()
	{
		$this->assertFalse($this->cssManager->_nthchild(['', '3'], 0)); // row 1
		$this->assertFalse($this->cssManager->_nthchild(['', '3'], 1)); // row 2
		$this->assertTrue($this->cssManager->_nthchild(['', '3'], 2)); // row 3
		$this->assertFalse($this->cssManager->_nthchild(['', '3'], 3)); // row 4
	}

	public function testNthchild_With2nPlus1()
	{
		$formula = ['', '', '2', '+1'];
		$this->assertTrue($this->cssManager->_nthchild($formula, 0)); // row 1
		$this->assertFalse($this->cssManager->_nthchild($formula, 1)); // row 2
		$this->assertTrue($this->cssManager->_nthchild($formula, 2)); // row 3
		$this->assertFalse($this->cssManager->_nthchild($formula, 3)); // row 4
	}

	public function testNthchild_With3nPlus2()
	{
		$formula = ['', '', '3', '+2'];
		$this->assertFalse($this->cssManager->_nthchild($formula, 0)); // row 1
		$this->assertTrue($this->cssManager->_nthchild($formula, 1)); // row 2
		$this->assertFalse($this->cssManager->_nthchild($formula, 2)); // row 3
		$this->assertFalse($this->cssManager->_nthchild($formula, 3)); // row 4
		$this->assertTrue($this->cssManager->_nthchild($formula, 4)); // row 5
	}

	public function testNthchild_WithNegativeFormula()
	{
		$formula = ['', '', '-', '+3'];
		$this->assertTrue($this->cssManager->_nthchild($formula, 0)); // row 1
		$this->assertTrue($this->cssManager->_nthchild($formula, 1)); // row 2
		$this->assertTrue($this->cssManager->_nthchild($formula, 2)); // row 3
		$this->assertFalse($this->cssManager->_nthchild($formula, 3)); // row 4
	}

	public function testReadInlineCSS_WithSimpleProperty()
	{
		$result = $this->cssManager->readInlineCSS('color: red;');
		$this->assertArrayHasKey('COLOR', $result);
		$this->assertEquals('red', $result['COLOR']);
	}

	public function testReadInlineCSS_WithMultipleProperties()
	{
		$result = $this->cssManager->readInlineCSS('color: red; font-size: 14px;');
		$this->assertArrayHasKey('COLOR', $result);
		$this->assertArrayHasKey('FONT-SIZE', $result);
		$this->assertEquals('red', $result['COLOR']);
		$this->assertEquals('14px', $result['FONT-SIZE']);
	}

	public function testReadInlineCSS_WithoutTrailingSemicolon()
	{
		$result = $this->cssManager->readInlineCSS('color: blue');
		$this->assertArrayHasKey('COLOR', $result);
		$this->assertEquals('blue', $result['COLOR']);
	}

	public function testReadInlineCSS_IgnoresWebkitGradient()
	{
		$css    = 'background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#000));';
		$result = $this->cssManager->readInlineCSS($css);
		$this->assertArrayNotHasKey('BACKGROUND', $result);
	}

	public function testFixCSS_WithEmptyArray()
	{
		$result = $this->cssManager->fixCSS([]);
		$this->assertEquals([], $result);
	}

	public function testFixCSS_WithNonArray()
	{
		$result = $this->cssManager->fixCSS(null);
		$this->assertEquals([], $result);
	}

	public function testFixCSS_WithMargin()
	{
		$result = $this->cssManager->fixCSS(['MARGIN' => '10px']);
		$this->assertArrayHasKey('MARGIN-TOP', $result);
		$this->assertArrayHasKey('MARGIN-RIGHT', $result);
		$this->assertArrayHasKey('MARGIN-BOTTOM', $result);
		$this->assertArrayHasKey('MARGIN-LEFT', $result);
		$this->assertEquals('10px', $result['MARGIN-TOP']);
	}

	public function testFixCSS_WithPadding()
	{
		$result = $this->cssManager->fixCSS(['PADDING' => '5px 10px']);
		$this->assertArrayHasKey('PADDING-TOP', $result);
		$this->assertArrayHasKey('PADDING-RIGHT', $result);
		$this->assertEquals('5px', $result['PADDING-TOP']);
		$this->assertEquals('10px', $result['PADDING-RIGHT']);
	}

	public function testFixCSS_WithBorderSimple()
	{
		$result = $this->cssManager->fixCSS(['BORDER' => '1']);
		$this->assertArrayHasKey('BORDER-TOP', $result);
		$this->assertEquals('1px solid #000000', $result['BORDER-TOP']);
	}

	public function testFixCSS_WithBorderStyle()
	{
		$result = $this->cssManager->fixCSS(['BORDER-STYLE' => 'solid dashed']);
		$this->assertArrayHasKey('BORDER-TOP-STYLE', $result);
		$this->assertArrayHasKey('BORDER-RIGHT-STYLE', $result);
		$this->assertEquals('solid', $result['BORDER-TOP-STYLE']);
		$this->assertEquals('dashed', $result['BORDER-RIGHT-STYLE']);
	}

	public function testFixCSS_WithFontFamily()
	{
		$this->mpdf->available_unifonts = ['dejavusans'];

		$result = $this->cssManager->fixCSS(['FONT-FAMILY' => 'DejaVu Sans, Arial']);
		$this->assertArrayHasKey('FONT-FAMILY', $result);
		$this->assertEquals('dejavusans', $result['FONT-FAMILY']);
	}

	public function testSetCSSboxshadow_WithBasicShadow()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->cssManager->setCSSboxshadow('2px 2px');
		$this->assertCount(1, $result);
		// 2px = 0.529 mm
		$this->assertEqualsWithDelta(0.529, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.529, $result[0]['y'], 0.001);
		$this->assertEquals(0, $result[0]['blur']);
		$this->assertFalse($result[0]['inset']);
	}

	public function testSetCSSboxshadow_WithBlurAndSpread()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->cssManager->setCSSboxshadow('2px 2px 4px 1px #000');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.529, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.529, $result[0]['y'], 0.001);
		$this->assertEqualsWithDelta(1.058, $result[0]['blur'], 0.001);
		$this->assertEqualsWithDelta(0.264, $result[0]['spread'], 0.001);
	}

	public function testSetCSSboxshadow_WithInset()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->cssManager->setCSSboxshadow('inset 2px 2px #000');
		$this->assertCount(1, $result);
		$this->assertTrue($result[0]['inset']);
	}

	public function testSetCSSboxshadow_WithMultipleShadows()
	{
		$this->mpdf->blk    = [0 => ['inner_width' => 100]];
		$this->mpdf->blklvl = 1;

		$result = $this->cssManager->setCSSboxshadow('2px 2px #000, 4px 4px #fff');
		$this->assertCount(2, $result);
	}

	public function testSetCSStextshadow_WithBasicShadow()
	{
		$result = $this->cssManager->setCSStextshadow('1px 1px');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.264, $result[0]['x'], 0.001);
		$this->assertEqualsWithDelta(0.264, $result[0]['y'], 0.001);
		$this->assertEquals(0, $result[0]['blur']);
	}

	public function testSetCSStextshadow_WithBlur()
	{
		$this->mpdf->blk = [];

		$result = $this->cssManager->setCSStextshadow('2px 2px 3px #000');
		$this->assertCount(1, $result);
		$this->assertEqualsWithDelta(0.793, $result[0]['blur'], 0.001);
	}

	public function testParseCSSbackground_WithColorOnly()
	{
		$result = $this->cssManager->parseCSSbackground('#ff0000');
		$this->assertEquals('#ff0000', $result['c']);
		$this->assertFalse($result['i']);
		$this->assertFalse($result['r']);
		$this->assertFalse($result['p']);
	}

	public function testParseCSSbackground_WithUrl()
	{
		$result = $this->cssManager->parseCSSbackground('url(image.jpg)');
		$this->assertEquals('image.jpg', $result['i']);
		$this->assertFalse($result['c']);
	}

	public function testParseCSSbackground_WithUrlAndColor()
	{
		$result = $this->cssManager->parseCSSbackground('#fff url(bg.png)');
		$this->assertEquals('#fff', $result['c']);
		$this->assertEquals('bg.png', $result['i']);
	}

	public function testParseCSSbackground_WithUrlAndRepeat()
	{
		$result = $this->cssManager->parseCSSbackground('url(bg.png) repeat-x');
		$this->assertEquals('bg.png', $result['i']);
		$this->assertEquals('repeat-x', $result['r']);
	}

	public function testParseCSSbackground_WithUrlAndPosition()
	{
		$result = $this->cssManager->parseCSSbackground('url(bg.png) center top');
		$this->assertEquals('bg.png', $result['i']);
		$this->assertEquals('50% 0%', $result['p']);
	}

	public function testParseCSSbackground_WithGradient()
	{
		$gradient = 'linear-gradient(to bottom, #fff, #000)';
		$result   = $this->cssManager->parseCSSbackground($gradient);
		$this->assertEquals($gradient, $result['i']);
	}

	public function testSetBorderDominance_WithAllBorders()
	{
		$prop = [
			'BORDER-LEFT'   => '1px solid #000',
			'BORDER-RIGHT'  => '1px solid #000',
			'BORDER-TOP'    => '1px solid #000',
			'BORDER-BOTTOM' => '1px solid #000',
		];

		$this->cssManager->setBorderDominance($prop, 5);
		$this->assertEquals(5, $this->cssManager->cell_border_dominance_L);
		$this->assertEquals(5, $this->cssManager->cell_border_dominance_R);
		$this->assertEquals(5, $this->cssManager->cell_border_dominance_T);
		$this->assertEquals(5, $this->cssManager->cell_border_dominance_B);
	}

	public function testSetBorderDominance_WithPartialBorders()
	{
		$this->cssManager->cell_border_dominance_L = 0;
		$this->cssManager->cell_border_dominance_T = 0;

		$prop = ['BORDER-LEFT' => '1px solid #000'];
		$this->cssManager->setBorderDominance($prop, 3);

		$this->assertEquals(3, $this->cssManager->cell_border_dominance_L);
		$this->assertEquals(0, $this->cssManager->cell_border_dominance_T);
	}

	public function testMergeBorders_WithCompleteData()
	{
		$b = ['BORDER-TOP' => '1px solid #000'];
		$a = ['BORDER-TOP-STYLE' => 'dashed'];

		$this->cssManager->_mergeBorders($b, $a);

		$this->assertEquals('1px dashed #000', $b['BORDER-TOP']);
	}

	public function testMergeBorders_WithWidthChange()
	{
		$b = ['BORDER-LEFT' => '1px solid #000'];
		$a = ['BORDER-LEFT-WIDTH' => '3px'];

		$this->cssManager->_mergeBorders($b, $a);

		$this->assertEquals('3px solid #000', $b['BORDER-LEFT']);
	}

	public function testMergeBorders_WithColorChange()
	{
		$b = ['BORDER-RIGHT' => '1px solid #000'];
		$a = ['BORDER-RIGHT-COLOR' => '#ff0000'];

		$this->cssManager->_mergeBorders($b, $a);

		$this->assertEquals('1px solid #ff0000', $b['BORDER-RIGHT']);
	}

	public function testMergeBorders_WithoutExistingBorder()
	{
		$b = [];
		$a = ['BORDER-BOTTOM-STYLE' => 'dotted'];

		$this->cssManager->_mergeBorders($b, $a);

		$this->assertEquals('0px dotted #000000', $b['BORDER-BOTTOM']);
	}

	public function testMergeCSS_WithEmptyTarget()
	{
		$p = ['color' => 'red'];
		$t = [];

		$this->cssManager->_mergeCSS($p, $t);

		$this->assertEquals(['color' => 'red'], $t);
	}

	public function testMergeCSS_WithExistingTarget()
	{
		$p = ['color' => 'red'];
		$t = ['font-size' => '12px'];

		$this->cssManager->_mergeCSS($p, $t);

		$this->assertArrayHasKey('color', $t);
		$this->assertArrayHasKey('font-size', $t);
	}

	public function testMergeCSS_WithNullSource()
	{
		$p = null;
		$t = ['color' => 'blue'];

		$this->cssManager->_mergeCSS($p, $t);

		$this->assertEquals(['color' => 'blue'], $t);
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
		$this->cssManager->CSS = ['CLASS>>highlight' => ['background-color' => 'yellow']];

		$result = $this->cssManager->MergeCSS('INLINE', 'SPAN', ['CLASS' => 'highlight']);

		$this->assertArrayHasKey('background-color', $result);
		$this->assertEquals('yellow', $result['background-color']);
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
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = [
			'DIV>>CLASS>>alert' => ['border' => '1px solid red'],
			'DIV>>ID>>main'     => ['margin' => '20px'],
		];

		$result = $this->cssManager->MergeCSS('INLINE', 'DIV', ['CLASS' => 'alert', 'ID' => 'main']);

		$this->assertArrayHasKey('border', $result);
		$this->assertArrayHasKey('margin', $result);
	}

	public function testMergeFullCSS_WithTagSelector()
	{
		$p = ['DIV' => ['color' => 'red']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', [], '', '');

		$this->assertArrayHasKey('color', $t);
		$this->assertEquals('red', $t['color']);
	}

	public function testMergeFullCSS_WithClassSelector()
	{
		$p = ['CLASS>>myclass' => ['font-size' => '14px']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', ['myclass'], '', '');

		$this->assertArrayHasKey('font-size', $t);
		$this->assertEquals('14px', $t['font-size']);
	}

	public function testMergeFullCSS_WithIDSelector()
	{
		$p = ['ID>>myid' => ['background' => 'blue']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', [], 'myid', '');

		$this->assertArrayHasKey('background', $t);
		$this->assertEquals('blue', $t['background']);
	}

	public function testMergeFullCSS_WithLangSelector()
	{
		$p = ['LANG>>fr' => ['font-family' => 'Arial']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', [], '', 'fr');

		$this->assertArrayHasKey('font-family', $t);
		$this->assertEquals('Arial', $t['font-family']);
	}

	public function testMergeFullCSS_WithTagAndClassSelector()
	{
		$p = ['DIV>>CLASS>>highlight' => ['font-weight' => 'bold']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', ['highlight'], '', '');

		$this->assertArrayHasKey('font-weight', $t);
		$this->assertEquals('bold', $t['font-weight']);
	}

	public function testMergeFullCSS_WithTagAndIDSelector()
	{
		$p = ['P>>ID>>content' => ['line-height' => '1.5']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'P', [], 'content', '');

		$this->assertArrayHasKey('line-height', $t);
		$this->assertEquals('1.5', $t['line-height']);
	}

	public function testMergeFullCSS_WithTagAndLangSelector()
	{
		$p = ['SPAN>>LANG>>fr' => ['text-decoration' => 'underline']];
		$t = [];

		$this->cssManager->_mergeFullCSS($p, $t, 'SPAN', [], '', 'fr');

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

		$this->cssManager->_mergeFullCSS($p, $t, 'TR', [], '', '');

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

		$this->cssManager->_mergeFullCSS($p, $t, 'TR', [], '', '');

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

		$this->cssManager->_mergeFullCSS($p, $t, 'TR', [], '', '');

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

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', ['myclass'], 'myid', '');

		// All matching selectors should be applied in order
		$this->assertArrayHasKey('color', $t);
		$this->assertArrayHasKey('font-size', $t);
		$this->assertArrayHasKey('font-weight', $t);
		$this->assertArrayHasKey('background', $t);
		$this->assertEquals('black', $t['color']);
		$this->assertEquals('12px', $t['font-size']);
		$this->assertEquals('bold', $t['font-weight']);
		$this->assertEquals('white', $t['background']);
	}

	public function testMergeFullCSS_WithNoMatchingSelectors()
	{
		$p = ['SPAN' => ['color' => 'red']];
		$t = ['existing' => 'value'];

		$this->cssManager->_mergeFullCSS($p, $t, 'DIV', [], '', '');

		// Should not add the SPAN style since tag doesn't match
		$this->assertArrayNotHasKey('color', $t);
		$this->assertArrayHasKey('existing', $t);
	}

	public function testNormalizePath_WhenBasepathNotLocal()
	{
		$this->mpdf->basepathIsLocal = false;

		// Use reflection to access protected method
		$reflection = new \ReflectionClass($this->cssManager);
		$method     = $reflection->getMethod('normalizePath');
		$method->setAccessible(true);

		$result = $method->invokeArgs($this->cssManager, ['/some/path/file.css']);

		$this->assertEquals('/some/path/file.css', $result);
	}

	public function testNormalizePath_WithBasepathLocal_AndDocumentRoot()
	{
		$this->mpdf->basepathIsLocal = true;

		// Save original values
		$originalDocRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null;

		// Set up test environment
		$_SERVER['DOCUMENT_ROOT'] = '/var/www/html';

		// Use reflection to access protected method
		$reflection = new \ReflectionClass($this->cssManager);
		$method     = $reflection->getMethod('normalizePath');
		$method->setAccessible(true);

		$result = $method->invokeArgs($this->cssManager, ['http://example.com/path/to/file.css']);

		// Should return document root + path
		$this->assertStringContainsString('/path/to/file.css', $result);

		// Restore original value
		if ($originalDocRoot === null) {
			unset($_SERVER['DOCUMENT_ROOT']);
		} else {
			$_SERVER['DOCUMENT_ROOT'] = $originalDocRoot;
		}
	}

	public function testNormalizePath_WithBasepathLocal_NoScheme()
	{
		$this->mpdf->basepathIsLocal = true;

		// Use reflection to access protected method
		$reflection = new \ReflectionClass($this->cssManager);
		$method     = $reflection->getMethod('normalizePath');
		$method->setAccessible(true);

		$result = $method->invokeArgs($this->cssManager, ['relative/path/file.css']);

		// Should return original path when no scheme
		$this->assertEquals('relative/path/file.css', $result);
	}

	public function testInlinePropsToCSS_WithFontFamily()
	{
		$bilp = ['family' => 'Arial'];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FAMILY', $p);
		$this->assertEquals('Arial', $p['FONT-FAMILY']);
	}

	public function testInlinePropsToCSS_WithBoldItalic()
	{
		$bilp = ['B' => true, 'I' => true];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-WEIGHT', $p);
		$this->assertArrayHasKey('FONT-STYLE', $p);
		$this->assertEquals('bold', $p['FONT-WEIGHT']);
		$this->assertEquals('italic', $p['FONT-STYLE']);
	}

	public function testInlinePropsToCSS_WithFontSize()
	{
		$bilp = ['sizePt' => 14];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-SIZE', $p);
		$this->assertEquals('14pt', $p['FONT-SIZE']);
	}

	public function testInlinePropsToCSS_WithColor()
	{
		// Use reflection to access private colorConverter
		$mpdfReflection     = new \ReflectionClass($this->mpdf);
		$colorConverterProp = $mpdfReflection->getProperty('colorConverter');
		$colorConverterProp->setAccessible(true);
		$colorConverter = $colorConverterProp->getValue($this->mpdf);

		// Convert a color to get the proper binary format
		$colorBinary = $colorConverter->convert('#ff0000', $this->mpdf->PDFAXwarnings);

		$bilp = ['colorarray' => $colorBinary];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('COLOR', $p);
		// The result should be an RGB string
		$this->assertStringContainsString('rgb(255', $p['COLOR']);
	}

	public function testInlinePropsToCSS_WithTextDecoration()
	{
		$bilp = ['textvar' => TextVars::FD_UNDERLINE];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-DECORATION', $p);
		$this->assertEquals('underline', $p['TEXT-DECORATION']);
	}

	public function testInlinePropsToCSS_WithVerticalAlign()
	{
		$bilp = ['textvar' => TextVars::FA_SUPERSCRIPT];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('VERTICAL-ALIGN', $p);
		$this->assertEquals('super', $p['VERTICAL-ALIGN']);
	}

	public function testInlinePropsToCSS_WithLetterSpacing()
	{
		$bilp = ['lSpacingCSS' => '2px'];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('LETTER-SPACING', $p);
		$this->assertEquals('2px', $p['LETTER-SPACING']);
	}

	public function testInlinePropsToCSS_WithWordSpacing()
	{
		$bilp = ['wSpacingCSS' => '5px'];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('WORD-SPACING', $p);
		$this->assertEquals('5px', $p['WORD-SPACING']);
	}

	public function testInlinePropsToCSS_WithHyphensNone()
	{
		$bilp = ['textparam' => ['hyphens' => 2]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('HYPHENS', $p);
		$this->assertEquals('none', $p['HYPHENS']);
	}

	public function testInlinePropsToCSS_WithHyphensAuto()
	{
		$bilp = ['textparam' => ['hyphens' => 1]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('HYPHENS', $p);
		$this->assertEquals('auto', $p['HYPHENS']);
	}

	public function testInlinePropsToCSS_WithHyphensManual()
	{
		$bilp = ['textparam' => ['hyphens' => 0]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('HYPHENS', $p);
		$this->assertEquals('manual', $p['HYPHENS']);
	}

	public function testInlinePropsToCSS_WithTextOutlineNone()
	{
		$bilp = ['textparam' => ['outline-s' => false]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-OUTLINE', $p);
		$this->assertEquals('none', $p['TEXT-OUTLINE']);
	}

	public function testInlinePropsToCSS_WithTextOutlineColor()
	{
		// Use reflection to access private colorConverter
		$mpdfReflection     = new \ReflectionClass($this->mpdf);
		$colorConverterProp = $mpdfReflection->getProperty('colorConverter');
		$colorConverterProp->setAccessible(true);
		$colorConverter = $colorConverterProp->getValue($this->mpdf);

		// Convert color to binary format expected by real ColorConverter
		$colorBinary = $colorConverter->convert('#0000ff', $this->mpdf->PDFAXwarnings);

		$bilp = ['textparam' => ['outline-COLOR' => $colorBinary]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-OUTLINE-COLOR', $p);
		$this->assertStringContainsString('rgb(0, 0, 255)', $p['TEXT-OUTLINE-COLOR']);
	}

	public function testInlinePropsToCSS_WithTextOutlineWidth()
	{
		$bilp = ['textparam' => ['outline-WIDTH' => 0.5]];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-OUTLINE-WIDTH', $p);
		$this->assertEquals('0.5mm', $p['TEXT-OUTLINE-WIDTH']);
	}

	public function testInlinePropsToCSS_WithTextDecorationLineThrough()
	{
		$bilp = ['textvar' => TextVars::FD_LINETHROUGH];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-DECORATION', $p);
		$this->assertEquals('line-through', $p['TEXT-DECORATION']);
	}

	public function testInlinePropsToCSS_WithTextDecorationUnderlineLineThrough()
	{
		$bilp = ['textvar' => TextVars::FD_UNDERLINE | TextVars::FD_LINETHROUGH];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-DECORATION', $p);
		$this->assertEquals('underline line-through', $p['TEXT-DECORATION']);
	}

	public function testInlinePropsToCSS_WithTextDecorationNone()
	{
		$bilp = ['textvar' => 1024]; // Value that doesn't match FD_UNDERLINE or FD_LINETHROUGH
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-DECORATION', $p);
		$this->assertEquals('none', $p['TEXT-DECORATION']);
	}

	public function testInlinePropsToCSS_WithVerticalAlignSub()
	{
		$bilp = ['textvar' => TextVars::FA_SUBSCRIPT];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('VERTICAL-ALIGN', $p);
		$this->assertEquals('sub', $p['VERTICAL-ALIGN']);
	}

	public function testInlinePropsToCSS_WithVerticalAlignBaseline()
	{
		$bilp = ['textvar' => 1024]; // Value that doesn't match FA_SUPERSCRIPT or FA_SUBSCRIPT
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('VERTICAL-ALIGN', $p);
		$this->assertEquals('baseline', $p['VERTICAL-ALIGN']);
	}

	public function testInlinePropsToCSS_WithTextTransformCapitalize()
	{
		$bilp = ['textvar' => TextVars::FT_CAPITALIZE];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-TRANSFORM', $p);
		$this->assertEquals('capitalize', $p['TEXT-TRANSFORM']);
	}

	public function testInlinePropsToCSS_WithTextTransformUppercase()
	{
		$bilp = ['textvar' => TextVars::FT_UPPERCASE];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-TRANSFORM', $p);
		$this->assertEquals('uppercase', $p['TEXT-TRANSFORM']);
	}

	public function testInlinePropsToCSS_WithTextTransformLowercase()
	{
		$bilp = ['textvar' => TextVars::FT_LOWERCASE];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-TRANSFORM', $p);
		$this->assertEquals('lowercase', $p['TEXT-TRANSFORM']);
	}

	public function testInlinePropsToCSS_WithTextTransformNone()
	{
		$bilp = ['textvar' => 1024]; // Value that doesn't match any text-transform flags
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('TEXT-TRANSFORM', $p);
		$this->assertEquals('none', $p['TEXT-TRANSFORM']);
	}

	public function testInlinePropsToCSS_WithFontKerningNormal()
	{
		$bilp = ['textvar' => TextVars::FC_KERNING];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-KERNING', $p);
		$this->assertEquals('normal', $p['FONT-KERNING']);
	}

	public function testInlinePropsToCSS_WithFontKerningNone()
	{
		$bilp = ['textvar' => 1024]; // Value that doesn't match FC_KERNING
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-KERNING', $p);
		$this->assertEquals('none', $p['FONT-KERNING']);
	}

	public function testInlinePropsToCSS_WithFontVariantPositionSuper()
	{
		$bilp = ['textvar' => TextVars::FA_SUPERSCRIPT];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-VARIANT-POSITION', $p);
		$this->assertEquals('super', $p['FONT-VARIANT-POSITION']);
	}

	public function testInlinePropsToCSS_WithFontVariantPositionSub()
	{
		$bilp = ['textvar' => TextVars::FA_SUBSCRIPT];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-VARIANT-POSITION', $p);
		$this->assertEquals('sub', $p['FONT-VARIANT-POSITION']);
	}

	public function testInlinePropsToCSS_WithFontVariantPositionNormal()
	{
		$bilp = ['textvar' => 1024]; // Value that doesn't match FA_SUPERSCRIPT or FA_SUBSCRIPT
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-VARIANT-POSITION', $p);
		$this->assertEquals('normal', $p['FONT-VARIANT-POSITION']);
	}

	public function testInlinePropsToCSS_WithFontVariantCapsSmallCaps()
	{
		$bilp = ['textvar' => TextVars::FC_SMALLCAPS];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-VARIANT-CAPS', $p);
		$this->assertEquals('small-caps', $p['FONT-VARIANT-CAPS']);
	}

	public function testInlinePropsToCSS_WithFontLanguageOverride()
	{
		$bilp = ['fontLanguageOverride' => 'TRK'];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-LANGUAGE-OVERRIDE', $p);
		$this->assertEquals('TRK', $p['FONT-LANGUAGE-OVERRIDE']);
	}

	public function testInlinePropsToCSS_WithFontLanguageOverrideNormal()
	{
		$bilp = ['fontLanguageOverride' => ''];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-LANGUAGE-OVERRIDE', $p);
		$this->assertEquals('normal', $p['FONT-LANGUAGE-OVERRIDE']);
	}

	public function testInlinePropsToCSS_WithOTLtagsMinus()
	{
		$bilp = ['OTLtags' => ['Minus' => 'liga kern']];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'liga' 0, 'kern' 0", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testInlinePropsToCSS_WithOTLtagsPlus()
	{
		$bilp = ['OTLtags' => ['Plus' => 'smcp swsh']];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'smcp' 1, 'swsh' 1", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testInlinePropsToCSS_WithOTLtagsFFMinus()
	{
		$bilp = ['OTLtags' => ['FFMinus' => 'dlig']];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'dlig' 0", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testInlinePropsToCSS_WithOTLtagsFFPlus()
	{
		$bilp = ['OTLtags' => ['FFPlus' => 'salt']];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'salt' 1", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testInlinePropsToCSS_WithOTLtagsFFPlusNumeric()
	{
		$bilp = ['OTLtags' => ['FFPlus' => 'salt4']];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'salt' 4", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testInlinePropsToCSS_WithOTLtagsCombined()
	{
		$bilp = [
			'OTLtags' => [
				'Minus'   => 'liga',
				'FFMinus' => 'dlig',
				'Plus'    => 'smcp',
				'FFPlus'  => 'salt4',
			],
		];
		$p    = [];

		$this->cssManager->inlinePropsToCSS($bilp, $p);

		$this->assertArrayHasKey('FONT-FEATURE-SETTINGS', $p);
		$this->assertEquals("'liga' 0, 'dlig' 0, 'smcp' 1, 'salt' 4", $p['FONT-FEATURE-SETTINGS']);
	}

	public function testFixCSS_ComplexBorderRadius()
	{
		$result = $this->cssManager->fixCSS(['BORDER-RADIUS' => '10px 20px / 30px']);

		$this->assertArrayHasKey('BORDER-TOP-LEFT-RADIUS-H', $result);
		$this->assertArrayHasKey('BORDER-TOP-LEFT-RADIUS-V', $result);
		$this->assertEquals('10px', $result['BORDER-TOP-LEFT-RADIUS-H']);
		$this->assertEquals('30px', $result['BORDER-TOP-LEFT-RADIUS-V']);
	}

	public function testFixCSS_ListStyle()
	{
		$result = $this->cssManager->fixCSS(['LIST-STYLE' => 'disc inside']);

		$this->assertArrayHasKey('LIST-STYLE-TYPE', $result);
		$this->assertArrayHasKey('LIST-STYLE-POSITION', $result);
		$this->assertEquals('disc', $result['LIST-STYLE-TYPE']);
		$this->assertEquals('inside', $result['LIST-STYLE-POSITION']);
	}

	public function testFixCSS_TextAlign()
	{
		$result = $this->cssManager->fixCSS(['TEXT-ALIGN' => 'center']);

		$this->assertArrayHasKey('TEXT-ALIGN', $result);
		$this->assertEquals('center', $result['TEXT-ALIGN']);
	}

	public function testReadInlineCSS_WithUrlsContainingSpecialChars()
	{
		$css    = 'background: url("http://example.com/image.jpg?param=value")';
		$result = $this->cssManager->readInlineCSS($css);

		// Should handle URLs with special characters
		$this->assertIsArray($result);
		$this->assertArrayHasKey('BACKGROUND-IMAGE', $result);
		$this->assertEquals('http://example.com/image.jpg?param=value', $result['BACKGROUND-IMAGE']);
	}

	public function testPreviewBlockCSS_WithDefaultCSS()
	{
		$this->mpdf->blk        = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl     = 0;
		$this->mpdf->defaultCSS = [
			'P' => ['MARGIN-TOP' => '1em', 'MARGIN-BOTTOM' => '1em'],
		];

		$result = $this->cssManager->PreviewBlockCSS('P', []);

		$this->assertArrayHasKey('MARGIN-TOP', $result);
		$this->assertArrayHasKey('MARGIN-BOTTOM', $result);
		$this->assertEquals('1em', $result['MARGIN-TOP']);
	}

	public function testPreviewBlockCSS_WithTagStyle()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['H1' => ['font-size' => '2em', 'font-weight' => 'bold']];

		$result = $this->cssManager->PreviewBlockCSS('H1', []);

		$this->assertArrayHasKey('font-size', $result);
		$this->assertArrayHasKey('font-weight', $result);
		$this->assertEquals('2em', $result['font-size']);
		$this->assertEquals('bold', $result['font-weight']);
	}

	public function testPreviewBlockCSS_WithClassAttribute()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['CLASS>>highlight' => ['background-color' => 'yellow']];

		$result = $this->cssManager->PreviewBlockCSS('DIV', ['CLASS' => 'highlight']);

		$this->assertArrayHasKey('background-color', $result);
		$this->assertEquals('yellow', $result['background-color']);
	}

	public function testPreviewBlockCSS_WithIDAttribute()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['ID>>header' => ['padding' => '20px']];

		$result = $this->cssManager->PreviewBlockCSS('DIV', ['ID' => 'header']);

		$this->assertArrayHasKey('padding', $result);
		$this->assertEquals('20px', $result['padding']);
	}

	public function testPreviewBlockCSS_WithTagAndClass()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['P>>CLASS>>intro' => ['font-style' => 'italic']];

		$result = $this->cssManager->PreviewBlockCSS('P', ['CLASS' => 'intro']);

		$this->assertArrayHasKey('font-style', $result);
		$this->assertEquals('italic', $result['font-style']);
	}

	public function testPreviewBlockCSS_WithTagAndID()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = ['DIV>>ID>>main' => ['width' => '960px']];

		$result = $this->cssManager->PreviewBlockCSS('DIV', ['ID' => 'main']);

		$this->assertArrayHasKey('width', $result);
		$this->assertEquals('960px', $result['width']);
	}

	public function testPreviewBlockCSS_WithCascadedStyles()
	{
		$this->mpdf->blk    = [
			0 => [
				'cascadeCSS' => [
					'P'           => ['depth' => 2, 'color' => 'blue'],
					'CLASS>>note' => ['depth' => 2, 'border' => '1px solid'],
					'ID>>content' => ['depth' => 2, 'margin' => '10px'],
				],
			],
		];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->PreviewBlockCSS('P', ['CLASS' => 'note', 'ID' => 'content']);

		$this->assertArrayHasKey('color', $result);
		$this->assertArrayHasKey('border', $result);
		$this->assertArrayHasKey('margin', $result);
		$this->assertEquals('blue', $result['color']);
	}

	public function testPreviewBlockCSS_WithInlineStyle()
	{
		$this->mpdf->blk    = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl = 0;

		$result = $this->cssManager->PreviewBlockCSS('DIV', ['STYLE' => 'color: red; padding: 5px;']);

		$this->assertArrayHasKey('COLOR', $result);
		$this->assertArrayHasKey('PADDING-TOP', $result);
		$this->assertEquals('red', $result['COLOR']);
	}

	public function testPreviewBlockCSS_WithMultipleClasses()
	{
		$this->mpdf->blk       = [0 => ['cascadeCSS' => []]];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = [
			'CLASS>>box'    => ['border' => '1px solid'],
			'CLASS>>shadow' => ['box-shadow' => '0 2px 4px'],
		];

		$result = $this->cssManager->PreviewBlockCSS('DIV', ['CLASS' => 'box shadow']);

		$this->assertArrayHasKey('border', $result);
		$this->assertArrayHasKey('box-shadow', $result);
	}

	public function testPreviewBlockCSS_WithCombinedSelectors()
	{
		$this->mpdf->blk       = [
			0 => [
				'cascadeCSS' => [
					'P>>CLASS>>alert' => ['depth' => 2, 'font-weight' => 'bold'],
				],
			],
		];
		$this->mpdf->blklvl    = 0;
		$this->cssManager->CSS = [
			'P'               => ['margin' => '1em'],
			'CLASS>>alert'    => ['color' => 'red'],
			'ID>>warning'     => ['border-left' => '4px solid'],
			'P>>CLASS>>alert' => ['padding' => '10px'],
			'P>>ID>>warning'  => ['background' => '#fee'],
		];

		$result = $this->cssManager->PreviewBlockCSS('P', ['CLASS' => 'alert', 'ID' => 'warning']);

		// Should have all applicable styles
		$this->assertArrayHasKey('margin', $result);
		$this->assertArrayHasKey('color', $result);
		$this->assertArrayHasKey('border-left', $result);
		$this->assertArrayHasKey('padding', $result);
		$this->assertArrayHasKey('background', $result);
		$this->assertArrayHasKey('font-weight', $result); // From cascaded styles with depth
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

		$result = $this->cssManager->PreviewBlockCSS('P', [
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

		$result = $this->cssManager->PreviewBlockCSS('SPAN', []);

		$this->assertArrayHasKey('display', $result);
		$this->assertEquals('inline', $result['display']);
	}
}
