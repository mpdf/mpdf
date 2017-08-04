<?php

namespace Issues;


class Issue457Test extends \Mpdf\BaseMpdfTest
{
    public function testMergePdfFilesWithPHP71()
    {
        $this->mpdf->SetImportUse();

        $pdfFilePath = __DIR__ . '/../data/pdfs/2-Page-PDF_1_3.pdf';
        $pageCount = $this->mpdf->SetSourceFile($pdfFilePath);

        for($i=1; $i <= $pageCount; $i++){
            $this->mpdf->AddPage();
            $template = $this->mpdf->importPage($i, $pdfFilePath);
            $this->mpdf->useTemplate($template);
        }

        $output = $this->mpdf->Output('', 'S');
        $this->assertStringStartsWith('%PDF-', $output);
    }
}