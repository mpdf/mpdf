<?php

namespace Mpdf\Css;

use Mpdf\Mpdf;

class SelectorParserTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	private $mpdf;
	private $parser;

	public function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();
		$this->parser = new SelectorParser($this->mpdf);
	}

	public function tear_down()
	{
		unset($this->parser, $this->mpdf);
		parent::tear_down();
	}

	public function testParsePageSelector()
	{
		$tags = ['@PAGE'];
		$expected = '@PAGE';
		$this->assertEquals($expected, $this->parser->parsePageSelector($tags));
		$this->assertFalse((bool) $this->mpdf->mirrorMargins);

		$tags = ['@PAGE', ':LEFT'];
		$expected = '@PAGE>>PSEUDO>>LEFT';
		$this->assertEquals($expected, $this->parser->parsePageSelector($tags));
		$this->assertTrue($this->mpdf->mirrorMargins);

		$tags = ['@PAGE', 'Named'];
		$expected = '@PAGE>>NAMED>>Named';
		$this->assertEquals($expected, $this->parser->parsePageSelector($tags));
		$this->assertTrue((bool) $this->mpdf->mirrorMargins); // once on, it doesnt turn off
	}

	public function testParseSimpleSelector()
	{
		$this->assertEquals('CLASS>>foo', $this->parser->parseSimpleSelector(['.foo']));
		$this->assertEquals('ID>>bar', $this->parser->parseSimpleSelector(['#bar']));
		$this->assertEquals('DIV', $this->parser->parseSimpleSelector(['DIV']));
		$this->assertEquals('DIV>>CLASS>>foo', $this->parser->parseSimpleSelector(['DIV.foo']));
		$this->assertEquals('DIV>>ID>>bar', $this->parser->parseSimpleSelector(['DIV#bar']));
		$this->assertNull($this->parser->parseSimpleSelector(['']));
	}

	public function testParseCascadedSelector()
	{
		$tags = ['DIV', 'P'];
		$expected = ['DIV', 'P'];
		$this->assertEquals($expected, $this->parser->parseCascadedSelector($tags));

		$tags = ['DIV.foo', '#bar'];
		$expected = ['DIV>>CLASS>>foo', 'ID>>bar'];
		$this->assertEquals($expected, $this->parser->parseCascadedSelector($tags));
	}

	public function testNthchild_WithOdd()
	{
		$this->assertTrue($this->parser->matchesNthChild(['ODD'], 0)); // row 1
		$this->assertFalse($this->parser->matchesNthChild(['ODD'], 1)); // row 2
		$this->assertTrue($this->parser->matchesNthChild(['ODD'], 2)); // row 3
		$this->assertFalse($this->parser->matchesNthChild(['ODD'], 3)); // row 4
	}

	public function testNthchild_WithEven()
	{
		$this->assertFalse($this->parser->matchesNthChild(['EVEN'], 0)); // row 1
		$this->assertTrue($this->parser->matchesNthChild(['EVEN'], 1)); // row 2
		$this->assertFalse($this->parser->matchesNthChild(['EVEN'], 2)); // row 3
		$this->assertTrue($this->parser->matchesNthChild(['EVEN'], 3)); // row 4
	}

	public function testNthchild_WithSpecificNumber()
	{
		$this->assertFalse($this->parser->matchesNthChild(['', '3'], 0)); // row 1
		$this->assertFalse($this->parser->matchesNthChild(['', '3'], 1)); // row 2
		$this->assertTrue($this->parser->matchesNthChild(['', '3'], 2)); // row 3
		$this->assertFalse($this->parser->matchesNthChild(['', '3'], 3)); // row 4
	}

	public function testNthchild_With2nPlus1()
	{
		$formula = ['', '', '2', '+1'];
		$this->assertTrue($this->parser->matchesNthChild($formula, 0)); // row 1
		$this->assertFalse($this->parser->matchesNthChild($formula, 1)); // row 2
		$this->assertTrue($this->parser->matchesNthChild($formula, 2)); // row 3
		$this->assertFalse($this->parser->matchesNthChild($formula, 3)); // row 4
	}

	public function testNthchild_With3nPlus2()
	{
		$formula = ['', '', '3', '+2'];
		$this->assertFalse($this->parser->matchesNthChild($formula, 0)); // row 1
		$this->assertTrue($this->parser->matchesNthChild($formula, 1)); // row 2
		$this->assertFalse($this->parser->matchesNthChild($formula, 2)); // row 3
		$this->assertFalse($this->parser->matchesNthChild($formula, 3)); // row 4
		$this->assertTrue($this->parser->matchesNthChild($formula, 4)); // row 5
	}

	public function testNthchild_WithNegativeFormula()
	{
		$formula = ['', '', '-', '+3'];
		$this->assertTrue($this->parser->matchesNthChild($formula, 0)); // row 1
		$this->assertTrue($this->parser->matchesNthChild($formula, 1)); // row 2
		$this->assertTrue($this->parser->matchesNthChild($formula, 2)); // row 3
		$this->assertFalse($this->parser->matchesNthChild($formula, 3)); // row 4
	}
}
