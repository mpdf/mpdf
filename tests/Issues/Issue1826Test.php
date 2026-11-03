<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1826Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @dataProvider zeroFontSizeHtml
	 */
	public function testZeroFontSizeDoesNotDivideByZero($config, $html)
	{
		$mpdf = new Mpdf($config);
		$mpdf->WriteHTML($html);

		$this->assertStringStartsWith('%PDF-', $mpdf->Output('', 'S'));
	}

	public function zeroFontSizeHtml()
	{
		$text = str_repeat('Lorem ipsum dolor sit amet. ', 20);

		return [
			'justified text' => [[], '<p style="text-align: justify"><span style="font-size: 0">Hidden text</span> ' . $text . '</p>'],
			'justified text with core font' => [['mode' => 'c'], '<p style="text-align: justify"><span style="font-size: 0">Hidden text</span> ' . $text . '</p>'],
			'small caps' => [[], '<p style="text-align: justify"><span style="font-size: 0; font-variant: small-caps">Hidden text</span> ' . $text . '</p>'],
		];
	}

}
