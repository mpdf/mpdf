<?php

namespace Issues;

class Issue2158Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testMultiCellWithEmptyStringDoesNotAccessUndefinedKey()
	{
		$undefinedKey = false;
		set_error_handler(function ($errno, $errstr) use (&$undefinedKey) {
			if (strpos($errstr, 'Undefined array key') !== false || strpos($errstr, 'Undefined index') !== false) {
				$undefinedKey = true;
			}
			return true;
		});

		try {
			$mpdf = new \Mpdf\Mpdf(['default_font' => 'dejavusans']);
			$mpdf->AddPage();
			$mpdf->MultiCell(0, 5, '');
			$output = $mpdf->OutputBinaryData();
		} finally {
			restore_error_handler();
		}

		$this->assertFalse($undefinedKey);
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
