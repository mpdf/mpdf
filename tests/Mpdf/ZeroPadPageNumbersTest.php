<?php

namespace Mpdf;

class ZeroPadPageNumbersTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
    public function testPageNumbersWithoutZeroPadding()
    {
        $mpdf = new Mpdf();
        $text = 'Page {PAGENO} / {nbpg}';

        $pages = 3;
        $contents = array_fill(0, $pages, $text);

        $mpdf->SetCompression(false);
        $mpdf->SetHTMLHeader('Header: ' . $text);
        $mpdf->SetHTMLFooter('Footer: ' . $text);
        $mpdf->WriteHTML('<html><body>' . implode('<pagebreak>', $contents) . '</body></html>');
        $mpdf->Close();

        for ($i = 1; $i <= $pages; $i++) {
            $page = str_replace("\n", "", $mpdf->pages[$i]);

            $expected_page = (string) $i;
            $expected_total = (string) $pages;

            $page_string = 'Page ' . $expected_page . ' / ' . $expected_total;
            $page_string = mb_convert_encoding($page_string, 'UTF-16BE', 'UTF-8');

            $number_page_string = substr_count($page, $page_string);

            $this->assertGreaterThanOrEqual(
                2,
                $number_page_string,
                "Page $i did not contain unpadded numbers"
            );
        }
    }

    public function testPageNumbersWithZeroPadding()
    {
        $mpdf = new Mpdf([
            'zero_pad_page_numbers' => 2,
        ]);

        $text = 'Page {PAGENO} / {nbpg}';

        $pages = 3;
        $contents = array_fill(0, $pages, $text);

        $mpdf->SetCompression(false);
        $mpdf->SetHTMLHeader('Header: ' . $text);
        $mpdf->SetHTMLFooter('Footer: ' . $text);
        $mpdf->WriteHTML('<html><body>' . implode('<pagebreak>', $contents).'</body></html>');
        $mpdf->Close();

        for ($i = 1; $i <= $pages; $i++) {
            $page = str_replace("\n", "", $mpdf->pages[$i]);

            $expected_page = str_pad($i, 2, '0', STR_PAD_LEFT);
            $expected_total = str_pad($pages, 2, '0', STR_PAD_LEFT);

            $page_string = 'Page ' . $expected_page . ' / ' . $expected_total;
            $page_string = mb_convert_encoding($page_string, 'UTF-16BE', 'UTF-8');

            $number_page_string = substr_count($page, $page_string);

            $this->assertGreaterThanOrEqual(
                2,
                $number_page_string,
                "Page $i did not contain zero-padded numbers"
            );
        }
    }
}
