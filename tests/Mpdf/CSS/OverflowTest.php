<?php

namespace Mpdf\CSS;

use Mockery;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class OverflowTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf([
			'mode'          => 'utf-8',
			'format'        => [96, 61],
			'fontDir' => [
				__DIR__ . '/../../data/ttf',
			],
			'fontdata' => [
				'notosans' => [
					'R' => 'NotoSans-Regular.ttf',
				]
			],
			'default_font' => 'notosans'
		]);
	}

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}
	
	public function testOverflowAutoWidth()
	{
		$this->mpdf->WriteHTML('@page {
            margin: 0mm;
            size: 90mm 55mm;
            marks: NONE;
        }', HTMLParserMode::HEADER_CSS);
		$this->mpdf->WriteHTML('
		<div style="position: fixed; border: 1; overflow: autoWidth; height: 6mm; padding: 0;
	left: 12mm; top: 40mm; width: 40mm; font-size: 11pt;">Lisa@Kaltwasser1234567</div>');

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$dateRegex = '\(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)';
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\n\/Producer \((.*?)\)\n\/CreationDate ' . $dateRegex . '\n\/ModDate ' . $dateRegex . '/', $output);
	}
}
