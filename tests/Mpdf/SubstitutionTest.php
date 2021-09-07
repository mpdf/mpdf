<?php

namespace Mpdf;

use Mpdf\Pdf\Protection;
use Mpdf\Pdf\Protection\UniqidGenerator;
use Mpdf\Writer\BaseWriter;

class SubstitutionTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
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

	public function testSubstitutionOutput()
	{
		$date_format = 'j-m-Y';
		$text = 'Page {PAGENO} / {nbpg}';

		$pages = 4;
		$contents = array_fill(0, $pages, $text);

		$this->mpdf->SetCompression(false);
		$this->mpdf->SetHTMLHeader('Header: '.$text.' [{DATE '.$date_format.'}]');
		$this->mpdf->SetHTMLFooter('Footer: '.$text.' [{DATE '.$date_format.'}]');
		$this->mpdf->WriteHTML('<html><body>
'.implode('<pagebreak>', $contents).'
</body></html>');
		$this->mpdf->Close();

		$date = date($date_format);
		for ($i = 1; $i <= $pages; $i++) {
			// Removal of newline elements
			$page = str_replace("\n", "", $this->mpdf->pages[$i]);

			// Conversion in UTF-16BE
			$page_string = str_replace(['{PAGENO}', '{nbpg}'], [$i, $pages, $date], $text);
			$page_string = mb_convert_encoding($page_string, 'UTF-16BE', 'UTF-8');

			$date_string = '['.$date.']';
			$date_string = mb_convert_encoding($date_string, 'UTF-16BE', 'UTF-8');

			// Counting
			$number_page_string = substr_count($page, $page_string);
			$number_date_string = substr_count($page, $date_string);

			// Test
			$this->assertEquals(3, $number_page_string);
			$this->assertEquals(2, $number_date_string);
		}
	}
}
