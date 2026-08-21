<?php

namespace Issues;

class Issue2214Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @dataProvider columnContentProvider
	 */
	public function testEndingColumnsDoesNotAccessUndefinedColumnDetails($repeat)
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

			$item =
				'<h4>Voice</h4>'
				. '<p>'
				. 'Description Description Description Description '
				. 'Description Description Description Description'
				. '</p>'
				. '<br />';

			$html =
				'<columns column-count="2" column-gap="15" />'
				. str_repeat($item, $repeat)
				. '<columns column-count="1" />';

			$mpdf->WriteHTML($html);

			$output = $mpdf->OutputBinaryData();
		} finally {
			restore_error_handler();
		}

		$this->assertFalse($undefinedKey);
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function columnContentProvider()
	{
		return [
			'breakpoints without column details' => [7],
			'previous column without bottom margin' => [14],
		];
	}

}
