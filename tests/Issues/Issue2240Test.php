<?php

namespace Issues;

class Issue2240Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testBrInsideLaterBidiInlineDoesNotWarnOnUndefinedKey()
	{
		$undefinedKey = false;

		set_error_handler(function ($errno, $errstr) use (&$undefinedKey) {
			if (strpos($errstr, 'Undefined array key') !== false
				|| strpos($errstr, 'Undefined offset') !== false
				|| strpos($errstr, 'Undefined index') !== false
			) {
				$undefinedKey = true;
			}

			return true;
		});

		try {
			$mpdf = new \Mpdf\Mpdf();

			$html =
				'<html><body style="direction:rtl">'
				. '<span dir="ltr">ABC</span>'
				. '<span dir="rtl">عربي<br>سطر</span>'
				. '</body></html>';

			$mpdf->WriteHTML($html);
			$pdf = $mpdf->Output('', 'S');
		} finally {
			restore_error_handler();
		}

		$this->assertFalse($undefinedKey, 'WriteHTML raised an undefined array key warning');
		$this->assertNotEmpty($pdf);
	}

}
