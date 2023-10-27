<?php

namespace Mpdf\Tag;

use Mockery;
use Mpdf\Mpdf;

class UseTemplateTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf();
	}

	protected function tear_down()
	{
		parent::tear_down();

		Mockery::close();
	}

	public function testPdfOutput()
	{
		$this->mpdf->WriteHTML('<html><body>
        <usetemplate src="./tests/data/pdfs/Letterhead.pdf" site="1"></usetemplate>
        <pagebreak/>
        <usetemplate src="./tests/data/pdfs/Letterhead2.pdf" site="1"></usetemplate>
		</body></html>');

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$dateRegex = '\(D:\d{14}[+|-|Z]\d{2}\'\d{2}\'\)';
		$this->assertMatchesRegularExpression('/\d+ 0 obj\n<<\n\/Producer \((.*?)\)\n\/CreationDate ' . $dateRegex . '\n\/ModDate ' . $dateRegex . '/', $output);
		$this->assertTrue(strpos($output, 'tektown') !== false);
		$this->assertTrue(strpos($output, 'camtown') !== false);
	}


}
