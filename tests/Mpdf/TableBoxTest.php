<?php

namespace Mpdf;

class TableBoxTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testDefaultsMirrorLegacyIssetSemantics()
	{
		$table = new TableBox(2, 3, 'rtl');

		$this->assertTrue($table->isNested());
		$this->assertTrue($table->isRtl());
		$this->assertFalse(isset($table['w']));
		$this->assertFalse(isset($table['overflow']));
		$this->assertFalse(isset($table['l']));
		$this->assertTrue(isset($table['is_thead']));
		$this->assertSame(0, $table->headRowCount());
		$this->assertFalse($table['bgcolor']);
		$this->assertFalse($table['decimal_align']);
		$this->assertSame(0, $table['border_details']['L']['w']);
	}

	public function testArrayBridge()
	{
		$table = new TableBox(1, 1);

		$table['wc'][2]['miw'] = 5;
		$table['l'][0] = 1.5;
		$table['l'][0] += 1;
		$table['thead-underline'] = '1px solid #000';
		$table['overflow'] = 'visible';
		unset($table['overflow']);

		$this->assertSame(5, $table->wc[2]['miw']);
		$this->assertSame(2.5, $table->l[0]);
		$this->assertSame('1px solid #000', $table->theadUnderline);
		$this->assertFalse(isset($table['overflow']));
		$this->assertNull($table->overflow);
	}

	public function testUnknownKeyThrows()
	{
		$this->expectException(MpdfException::class);
		$table = new TableBox(1, 1);
		$table['typo'] = 1;
	}

	public function testHelpers()
	{
		$table = new TableBox(2, 4);
		$table->nestedpos = [3, 1, 7];
		$table->margin = ['L' => 1, 'R' => 2, 'T' => 0, 'B' => 0];
		$table->border_details['L']['w'] = 4;
		$table->max_cell_border_width['L'] = 2;

		$this->assertSame(7, $table->parentId());
		$this->assertSame(2.0, (float) $table->outerWidth('L'));
		$table->borders_separate = true;
		$this->assertSame(3.0, (float) $table->outerWidth('L'));

		$table->ensureColumns(3);
		$table->ensureColumns(2);
		$this->assertSame(3, $table->nc);

		$table->setBackgroundColor(-1, 'red');
		$table->setBackgroundColor(2, 'blue');
		$this->assertSame([-1 => 'red', 2 => 'blue'], $table->bgcolor);

		$table->trackDecimalAlign(1, 3, 2);
		$table->trackDecimalAlign(1, 1, 4);
		$this->assertSame(['maxs0' => 3, 'maxs1' => 4], $table->decimal_align[1]);
	}
}
