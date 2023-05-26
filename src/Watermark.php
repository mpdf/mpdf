<?php

namespace Mpdf;

use Mpdf\Css\TextVars;

class Watermark
{
    // For Text Watermark
    var $texte;
    var $angle;
    var $fontsize;
    var $color;

    // For Image Watermark
    var $src;
    var $alpha;

    public function text($texte, $angle = 45, $fontsize = 96, $alpha = 0.2, $color = 0)
    {
        $this->src = null;

        $this->texte = $texte;
        $this->angle = $angle;
        $this->fontsize = $fontsize;
        $this->alpha = $alpha;
        $this->color = $color;
    }

    public function image($src, $alpha = 0.2)
    {
        $this->texte = null;

        $this->src = $src;
        $this->alpha = $alpha;
    }

    public function render(Mpdf $pdf)
    {
        if ($pdf->PDFA || $pdf->PDFX) {
			throw new \Mpdf\MpdfException('PDFA and PDFX do not permit transparency, so mPDF does not allow Watermarks!');
		}

        if ($this->texte) {
            $this->render($pdf);
        }

        if ($this->src) {
            $this->render($pdf);
        }
    }

    protected function renderText(Mpdf $pdf)
    {
		if (!$pdf->watermark_font) {
			$pdf->watermark_font = $pdf->default_font;
		}

		$pdf->SetFont($pdf->watermark_font, "B", $this->fontsize, false); // Don't output
		$texte = $pdf->purify_utf8_text($this->texte);

		if ($pdf->text_input_as_HTML) {
			$texte = $pdf->all_entities_to_utf8($texte);
		}

		if ($pdf->usingCoreFont) {
			$texte = mb_convert_encoding($texte, $pdf->mb_enc, 'UTF-8');
		}

		// DIRECTIONALITY
		if (preg_match("/([" . $pdf->pregRTLchars . "])/u", $texte)) {
			$pdf->biDirectional = true;
		} // *OTL*

		$textvar = 0;
		$save_OTLtags = $pdf->OTLtags;
		$pdf->OTLtags = [];
		if ($pdf->useKerning) {
			if ($pdf->CurrentFont['haskernGPOS']) {
				$pdf->OTLtags['Plus'] .= ' kern';
			} else {
				$textvar = ($textvar | TextVars::FC_KERNING);
			}
		}

		/* -- OTL -- */
		// Use OTL OpenType Table Layout - GSUB & GPOS
		if (isset($pdf->CurrentFont['useOTL']) && $pdf->CurrentFont['useOTL']) {
			$texte = $pdf->otl->applyOTL($texte, $pdf->CurrentFont['useOTL']);
			$OTLdata = $pdf->otl->OTLdata;
		}
		/* -- END OTL -- */
		$pdf->OTLtags = $save_OTLtags;

		$pdf->magic_reverse_dir($texte, $pdf->directionality, $OTLdata);

		$pdf->SetAlpha($this->alpha);

		$pdf->SetTColor($pdf->colorConverter->convert($this->color, $pdf->PDFAXwarnings));

		$szfont = $this->fontsize;
		$loop = 0;
		$maxlen = (min($pdf->w, $pdf->h) ); // sets max length of text as 7/8 width/height of page

		while ($loop == 0) {
			$pdf->SetFont($pdf->watermark_font, "B", $szfont, false); // Don't output
			$offset = ((sin(deg2rad($this->angle))) * ($szfont / Mpdf::SCALE));

			$strlen = $pdf->GetStringWidth($texte, true, $OTLdata, $textvar);
			if ($strlen > $maxlen - $offset) {
				$szfont --;
			} else {
				$loop ++;
			}
		}

		$pdf->SetFont($pdf->watermark_font, "B", $szfont - 0.1, true, true); // Output The -0.1 is because SetFont above is not written to PDF

		// Repeating it will not output anything as mPDF thinks it is set
		$adj = ((cos(deg2rad($this->angle))) * ($strlen / 2));
		$opp = ((sin(deg2rad($this->angle))) * ($strlen / 2));

		$wx = ($pdf->w / 2) - $adj + $offset / 3;
		$wy = ($pdf->h / 2) + $opp;

		$pdf->Rotate($this->angle, $wx, $wy);
		$pdf->Text($wx, $wy, $texte, $OTLdata, $textvar);
		$pdf->Rotate(0);
		$pdf->SetTColor($pdf->colorConverter->convert(0, $pdf->PDFAXwarnings));

		$pdf->SetAlpha(1);
    }

    protected function renderImage(Mpdf $pdf)
	{
		if ($pdf->watermarkImgBehind) {
			$pdf->watermarkImgAlpha = $pdf->SetAlpha($this->alpha, 'Normal', true);
		} else {
			$pdf->SetAlpha($this->alpha, $pdf->watermarkImgAlphaBlend);
		}

		$pdf->Image($this->src, 0, 0, 0, 0, '', '', true, true, true);

		if (!$pdf->watermarkImgBehind) {
			$pdf->SetAlpha(1);
		}
	}
}