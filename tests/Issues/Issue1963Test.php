<?php


namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use mysql_xdevapi\Warning;

class Issue1963Test extends BaseMpdfTest
{
    protected function set_up()
    {
        $this->mpdf = new Mpdf([
            'mode' => '-aCJK',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font' => 'dejavusans',
        ]);
    }

    public function testNoWarning()
    {
        $this->mpdf->WriteHTML('<p>न्</p>');
        $this->mpdf->Output('', Destination::STRING_RETURN);
    }
}
