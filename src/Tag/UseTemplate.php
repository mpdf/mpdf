<?php

namespace Mpdf\Tag;

class UseTemplate extends Tag
{
    public function open($attr, &$ahtml, &$ihtml)
    {
        if(file_exists($attr['ORIG_SRC'])) {
            $this->mpdf->setSourceFile($attr['ORIG_SRC']);
            $tplId = $this->mpdf->importPage($attr['SITE']);
            $this->mpdf->useTemplate($tplId);
        }
    }

    public function close(&$ahtml, &$ihtml) {
        //var_dump("open", $ahtml);
    }
}
