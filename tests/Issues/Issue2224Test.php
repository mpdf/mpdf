<?php

namespace Issues;

use Mpdf\Css\Border;

class Issue2224Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	const HTML = '<table style="border-collapse: collapse;">'
		. '<tr style="border-left: 1px solid #000;">'
		. '<td style="border-top: 2px solid #f00; border-bottom: 2px solid #f00; border-right: 2px solid #f00;">A</td>'
		. '</tr></table>';

	public function testPartialRowBorderDoesNotRaiseWarningsOrDeprecations()
	{
		$raised = [];
		set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$raised) {
			$raised[] = '#' . $errno . ': ' . $errstr . ' in ' . $errfile . ':' . $errline;
			return true;
		});

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML(self::HTML);

		restore_error_handler();

		$this->assertSame([], $raised);
	}

	public function testCellBordersAreNotErasedByPartialRowBorder()
	{
		$mpdf = new Issue2224MpdfSpy();
		$mpdf->WriteHTML(self::HTML);

		$cell = null;
		foreach ($mpdf->capturedTables as $table) {
			if (isset($table['cells'][0][0]['border'])) {
				$cell = $table['cells'][0][0];
				break;
			}
		}

		$this->assertNotNull($cell, 'The table was not written');
		$this->assertSame(Border::LEFT, $cell['border'] & Border::LEFT, 'Border set on the TR is missing');
		$this->assertSame(Border::RIGHT, $cell['border'] & Border::RIGHT, 'Right border of the TD was erased');
		$this->assertSame(Border::BOTTOM, $cell['border'] & Border::BOTTOM, 'Bottom border of the TD was erased');
		$this->assertSame(Border::TOP, $cell['border'] & Border::TOP, 'Top border of the TD was erased');
	}

}
