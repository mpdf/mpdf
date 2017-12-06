<?php

namespace Mpdf\Tag;

// TODO: Extend THEAD instead?

class TFoot extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$this->mpdf->lastoptionaltag = 'TFOOT'; // Save current HTML specified optional endtag
		$this->cssManager->tbCSSlvl++;
		$this->mpdf->tabletfoot = 1;
		$this->mpdf->tablethead = 0;
		$properties = $this->cssManager->MergeCSS('TABLE', 'TFOOT', $attr);
		if (isset($properties['FONT-WEIGHT'])) {
			if (strtoupper($properties['FONT-WEIGHT']) == 'BOLD') {
				$this->mpdf->tfoot_font_weight = 'B';
			} else {
				$this->mpdf->tfoot_font_weight = '';
			}
		}

		if (isset($properties['FONT-STYLE'])) {
			if (strtoupper($properties['FONT-STYLE']) == 'ITALIC') {
				$this->mpdf->tfoot_font_style = 'I';
			} else {
				$this->mpdf->tfoot_font_style = '';
			}
		}
		if (isset($properties['FONT-VARIANT'])) {
			if (strtoupper($properties['FONT-VARIANT']) == 'SMALL-CAPS') {
				$this->mpdf->tfoot_font_smCaps = 'S';
			} else {
				$this->mpdf->tfoot_font_smCaps = '';
			}
		}

		if (isset($properties['VERTICAL-ALIGN'])) {
			$this->mpdf->tfoot_valign_default = $properties['VERTICAL-ALIGN'];
		}
		if (isset($properties['TEXT-ALIGN'])) {
			$this->mpdf->tfoot_textalign_default = $properties['TEXT-ALIGN'];
		}
		$properties = [];
	}

	public function close(&$ahtml, &$ihtml)
	{
		$this->mpdf->lastoptionaltag = '';
		unset($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl]);
		$this->cssManager->tbCSSlvl--;
		$this->mpdf->tabletfoot = 0;
		$this->mpdf->ResetStyles();
		$this->mpdf->tfoot_font_weight = '';
		$this->mpdf->tfoot_font_style = '';
		$this->mpdf->tfoot_font_smCaps = '';

		$this->mpdf->tfoot_valign_default = '';
		$this->mpdf->tfoot_textalign_default = '';
	}
}
