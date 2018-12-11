<?php

namespace Mpdf;

class FpdiPdfParser extends \fpdi_pdf_parser
{
    /**
     * @return array
     */
    public function getPages()
    {
        return $this->_pages;
    }
}
