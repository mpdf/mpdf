<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Issue779Test extends \Mpdf\BaseMpdfTest
{
    public function testOffsetsInHtmlTable()
    {
        $output = 'issue779.pdf';
        $content = file_get_contents(__DIR__ . '/../data/html/issue779.html');
        $configs = ['c', 'A4', '', '', 10, 10, 10, 10, 9, 9];
        $mpdf = new Mpdf($configs);

        $mpdf->SetTitle('issue779');
        $mpdf->WriteHTML($content);
        $mpdf->Output($output, Destination::FILE);

        $this->assertFileExists($output);
    }

    public function tearDown()
    {
        @unlink('issue779.pdf');
        parent::tearDown();
    }
}