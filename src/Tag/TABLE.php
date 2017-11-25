<?php

namespace Mpdf\Tag;


use Mpdf\Css\Border;
use Mpdf\Mpdf;

class TABLE extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$tag = $this->getTagName();
		$this->mpdf->tdbegin = false;
		$this->mpdf->lastoptionaltag = '';
		// Disable vertical justification in columns
		if ($this->mpdf->ColActive) {
			$this->mpdf->colvAlign = '';
		} // *COLUMNS*
		if ($this->mpdf->lastblocklevelchange == 1) {
			$blockstate = 1;
		} // Top margins/padding only
		elseif ($this->mpdf->lastblocklevelchange < 1) {
			$blockstate = 0;
		} // NO margins/padding
		// called from block after new div e.g. <div> ... <table> ...    Outputs block top margin/border and padding
		if (count($this->mpdf->textbuffer) == 0 && $this->mpdf->lastblocklevelchange == 1 && !$this->mpdf->tableLevel && !$this->mpdf->kwt) {
			$this->mpdf->newFlowingBlock($this->mpdf->blk[$this->mpdf->blklvl]['width'], $this->mpdf->lineheight, '', false, 1, true, $this->mpdf->blk[$this->mpdf->blklvl]['direction']);
			$this->mpdf->finishFlowingBlock(true); // true = END of flowing block
		} elseif (!$this->mpdf->tableLevel && count($this->mpdf->textbuffer)) {
			$this->mpdf->printbuffer($this->mpdf->textbuffer, $blockstate);
		}

		$this->mpdf->textbuffer = [];
		$this->mpdf->lastblocklevelchange = -1;



		if ($this->mpdf->tableLevel) { // i.e. now a nested table coming...
			// Save current level table
			$this->mpdf->cell['PARENTCELL'] = $this->mpdf->saveInlineProperties();
			$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['baseProperties'] = $this->mpdf->base_table_properties;
			$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['cells'] = $this->mpdf->cell;
			$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['currrow'] = $this->mpdf->row;
			$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['currcol'] = $this->mpdf->col;
		}
		$this->mpdf->tableLevel++;
		$this->cssManager->tbCSSlvl++;

		if ($this->mpdf->tableLevel > 1) { // inherit table properties from cell in which nested
			//$this->mpdf->base_table_properties['FONT-KERNING'] = ($this->mpdf->textvar & TextVars::FC_KERNING);	// mPDF 6
			$this->mpdf->base_table_properties['LETTER-SPACING'] = $this->mpdf->lSpacingCSS;
			$this->mpdf->base_table_properties['WORD-SPACING'] = $this->mpdf->wSpacingCSS;
			// mPDF 6
			$direction = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['direction'];
			$txta = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['a'];
			$cellLineHeight = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['cellLineHeight'];
			$cellLineStackingStrategy = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['cellLineStackingStrategy'];
			$cellLineStackingShift = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['cellLineStackingShift'];
		}

		if (isset($this->mpdf->tbctr[$this->mpdf->tableLevel])) {
			$this->mpdf->tbctr[$this->mpdf->tableLevel] ++;
		} else {
			$this->mpdf->tbctr[$this->mpdf->tableLevel] = 1;
		}

		$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['level'] = $this->mpdf->tableLevel;
		$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['levelid'] = $this->mpdf->tbctr[$this->mpdf->tableLevel];

		if ($this->mpdf->tableLevel > $this->mpdf->innermostTableLevel) {
			$this->mpdf->innermostTableLevel = $this->mpdf->tableLevel;
		}
		if ($this->mpdf->tableLevel > 1) {
			$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['nestedpos'] = [
				$this->mpdf->row,
				$this->mpdf->col,
				$this->mpdf->tbctr[($this->mpdf->tableLevel - 1)],
			];
		}
		//++++++++++++++++++++++++++++

		$this->mpdf->cell = [];
		$this->mpdf->col = -1; //int
		$this->mpdf->row = -1; //int
		$table = &$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]];

		// New table - any level
		$table['direction'] = $this->mpdf->directionality;
		$table['bgcolor'] = false;
		$table['va'] = false;
		$table['txta'] = false;
		$table['topntail'] = false;
		$table['thead-underline'] = false;
		$table['border'] = false;
		$table['border_details']['R']['w'] = 0;
		$table['border_details']['L']['w'] = 0;
		$table['border_details']['T']['w'] = 0;
		$table['border_details']['B']['w'] = 0;
		$table['border_details']['R']['style'] = '';
		$table['border_details']['L']['style'] = '';
		$table['border_details']['T']['style'] = '';
		$table['border_details']['B']['style'] = '';
		$table['max_cell_border_width']['R'] = 0;
		$table['max_cell_border_width']['L'] = 0;
		$table['max_cell_border_width']['T'] = 0;
		$table['max_cell_border_width']['B'] = 0;
		$table['padding']['L'] = false;
		$table['padding']['R'] = false;
		$table['padding']['T'] = false;
		$table['padding']['B'] = false;
		$table['margin']['L'] = false;
		$table['margin']['R'] = false;
		$table['margin']['T'] = false;
		$table['margin']['B'] = false;
		$table['a'] = false;
		$table['border_spacing_H'] = false;
		$table['border_spacing_V'] = false;
		$table['decimal_align'] = false;
		$this->mpdf->Reset();
		$this->mpdf->InlineProperties = [];
		$this->mpdf->InlineBDF = []; // mPDF 6
		$this->mpdf->InlineBDFctr = 0; // mPDF 6
		$table['nc'] = $table['nr'] = 0;
		$this->mpdf->tablethead = 0;
		$this->mpdf->tabletfoot = 0;
		$this->mpdf->tabletheadjustfinished = false;

		// mPDF 6
		if ($this->mpdf->tableLevel > 1) { // inherit table properties from cell in which nested
			$table['direction'] = $direction;
			$table['txta'] = $txta;
			$table['cellLineHeight'] = $cellLineHeight;
			$table['cellLineStackingStrategy'] = $cellLineStackingStrategy;
			$table['cellLineStackingShift'] = $cellLineStackingShift;
		}


		if ($this->mpdf->blockjustfinished && !count($this->mpdf->textbuffer) && $this->mpdf->y != $this->mpdf->tMargin && $this->mpdf->collapseBlockMargins && $this->mpdf->tableLevel == 1) {
			$lastbottommargin = $this->mpdf->lastblockbottommargin;
		} else {
			$lastbottommargin = 0;
		}
		$this->mpdf->lastblockbottommargin = 0;
		$this->mpdf->blockjustfinished = false;

		if ($this->mpdf->tableLevel == 1) {
			$table['headernrows'] = 0;
			$table['footernrows'] = 0;
			$this->mpdf->base_table_properties = [];
		}

		// ADDED CSS FUNCIONS FOR TABLE
		if ($this->cssManager->tbCSSlvl == 1) {
			$properties = $this->cssManager->MergeCSS('TOPTABLE', $tag, $attr);
		} else {
			$properties = $this->cssManager->MergeCSS('TABLE', $tag, $attr);
		}

		$w = '';
		if (isset($properties['WIDTH'])) {
			$w = $properties['WIDTH'];
		} elseif (isset($attr['WIDTH']) && $attr['WIDTH']) {
			$w = $attr['WIDTH'];
		}

		if (isset($attr['ALIGN']) && isset(self::ALIGN[strtolower($attr['ALIGN'])])) {
			$table['a'] = self::ALIGN[strtolower($attr['ALIGN'])];
		}
		if (!$table['a']) {
			if ($table['direction'] == 'rtl') {
				$table['a'] = 'R';
			} else {
				$table['a'] = 'L';
			}
		}

		if (isset($properties['DIRECTION']) && $properties['DIRECTION']) {
			$table['direction'] = strtolower($properties['DIRECTION']);
		} elseif (isset($attr['DIR']) && $attr['DIR']) {
			$table['direction'] = strtolower($attr['DIR']);
		} elseif ($this->mpdf->tableLevel == 1) {
			$table['direction'] = $this->mpdf->blk[$this->mpdf->blklvl]['direction'];
		}

		if (isset($properties['BACKGROUND-COLOR'])) {
			$table['bgcolor'][-1] = $properties['BACKGROUND-COLOR'];
		} elseif (isset($properties['BACKGROUND'])) {
			$table['bgcolor'][-1] = $properties['BACKGROUND'];
		} elseif (isset($attr['BGCOLOR'])) {
			$table['bgcolor'][-1] = $attr['BGCOLOR'];
		}

		if (isset($properties['VERTICAL-ALIGN']) && isset(self::ALIGN[strtolower($properties['VERTICAL-ALIGN'])])) {
			$table['va'] = self::ALIGN[strtolower($properties['VERTICAL-ALIGN'])];
		}
		if (isset($properties['TEXT-ALIGN']) && isset(self::ALIGN[strtolower($properties['TEXT-ALIGN'])])) {
			$table['txta'] = self::ALIGN[strtolower($properties['TEXT-ALIGN'])];
		}

		if (isset($properties['AUTOSIZE']) && $properties['AUTOSIZE'] && $this->mpdf->tableLevel == 1) {
			$this->mpdf->shrink_this_table_to_fit = $properties['AUTOSIZE'];
			if ($this->mpdf->shrink_this_table_to_fit < 1) {
				$this->mpdf->shrink_this_table_to_fit = 0;
			}
		}
		if (isset($properties['ROTATE']) && $properties['ROTATE'] && $this->mpdf->tableLevel == 1) {
			$this->mpdf->table_rotate = $properties['ROTATE'];
		}
		if (isset($properties['TOPNTAIL'])) {
			$table['topntail'] = $properties['TOPNTAIL'];
		}
		if (isset($properties['THEAD-UNDERLINE'])) {
			$table['thead-underline'] = $properties['THEAD-UNDERLINE'];
		}

		if (isset($properties['BORDER'])) {
			$bord = $this->mpdf->border_details($properties['BORDER']);
			if ($bord['s']) {
				$table['border'] = Border::ALL;
				$table['border_details']['R'] = $bord;
				$table['border_details']['L'] = $bord;
				$table['border_details']['T'] = $bord;
				$table['border_details']['B'] = $bord;
			}
		}
		if (isset($properties['BORDER-RIGHT'])) {
			if ($table['direction'] == 'rtl') {  // *OTL*
				$table['border_details']['R'] = $this->mpdf->border_details($properties['BORDER-LEFT']); // *OTL*
			} // *OTL*
			else { // *OTL*
				$table['border_details']['R'] = $this->mpdf->border_details($properties['BORDER-RIGHT']);
			} // *OTL*
			$this->mpdf->setBorder($table['border'], Border::RIGHT, $table['border_details']['R']['s']);
		}
		if (isset($properties['BORDER-LEFT'])) {
			if ($table['direction'] == 'rtl') {  // *OTL*
				$table['border_details']['L'] = $this->mpdf->border_details($properties['BORDER-RIGHT']); // *OTL*
			} // *OTL*
			else { // *OTL*
				$table['border_details']['L'] = $this->mpdf->border_details($properties['BORDER-LEFT']);
			} // *OTL*
			$this->mpdf->setBorder($table['border'], Border::LEFT, $table['border_details']['L']['s']);
		}
		if (isset($properties['BORDER-BOTTOM'])) {
			$table['border_details']['B'] = $this->mpdf->border_details($properties['BORDER-BOTTOM']);
			$this->mpdf->setBorder($table['border'], Border::BOTTOM, $table['border_details']['B']['s']);
		}
		if (isset($properties['BORDER-TOP'])) {
			$table['border_details']['T'] = $this->mpdf->border_details($properties['BORDER-TOP']);
			$this->mpdf->setBorder($table['border'], Border::TOP, $table['border_details']['T']['s']);
		}
		if ($table['border']) {
			$this->mpdf->table_border_css_set = 1;
		} else {
			$this->mpdf->table_border_css_set = 0;
		}

		// mPDF 6
		if (isset($properties['LANG']) && $properties['LANG']) {
			if ($this->mpdf->autoLangToFont && !$this->mpdf->usingCoreFont) {
				if ($properties['LANG'] != $this->mpdf->default_lang && $properties['LANG'] != 'UTF-8') {
					list ($coreSuitable, $mpdf_pdf_unifont) = $this->languageToFont->getLanguageOptions($properties['LANG'], $this->mpdf->useAdobeCJK);
					if ($mpdf_pdf_unifont) {
						$properties['FONT-FAMILY'] = $mpdf_pdf_unifont;
					}
				}
			}
			$this->mpdf->currentLang = $properties['LANG'];
		}


		if (isset($properties['FONT-FAMILY'])) {
			$this->mpdf->default_font = $properties['FONT-FAMILY'];
			$this->mpdf->SetFont($this->mpdf->default_font, '', 0, false);
		}
		$this->mpdf->base_table_properties['FONT-FAMILY'] = $this->mpdf->FontFamily;

		if (isset($properties['FONT-SIZE'])) {
			if ($this->mpdf->tableLevel > 1) {
				$mmsize = $this->sizeConverter->convert($properties['FONT-SIZE'], $this->mpdf->base_table_properties['FONT-SIZE']);
			} else {
				$mmsize = $this->sizeConverter->convert($properties['FONT-SIZE'], $this->mpdf->default_font_size / Mpdf::SCALE);
			}
			if ($mmsize) {
				$this->mpdf->default_font_size = $mmsize * (Mpdf::SCALE);
				$this->mpdf->SetFontSize($this->mpdf->default_font_size, false);
			}
		}
		$this->mpdf->base_table_properties['FONT-SIZE'] = $this->mpdf->FontSize . 'mm';

		if (isset($properties['FONT-WEIGHT'])) {
			if (strtoupper($properties['FONT-WEIGHT']) == 'BOLD') {
				$this->mpdf->base_table_properties['FONT-WEIGHT'] = 'BOLD';
			}
		}
		if (isset($properties['FONT-STYLE'])) {
			if (strtoupper($properties['FONT-STYLE']) == 'ITALIC') {
				$this->mpdf->base_table_properties['FONT-STYLE'] = 'ITALIC';
			}
		}
		if (isset($properties['COLOR'])) {
			$this->mpdf->base_table_properties['COLOR'] = $properties['COLOR'];
		}
		if (isset($properties['FONT-KERNING'])) {
			$this->mpdf->base_table_properties['FONT-KERNING'] = $properties['FONT-KERNING'];
		}
		if (isset($properties['LETTER-SPACING'])) {
			$this->mpdf->base_table_properties['LETTER-SPACING'] = $properties['LETTER-SPACING'];
		}
		if (isset($properties['WORD-SPACING'])) {
			$this->mpdf->base_table_properties['WORD-SPACING'] = $properties['WORD-SPACING'];
		}
		// mPDF 6
		if (isset($properties['HYPHENS'])) {
			$this->mpdf->base_table_properties['HYPHENS'] = $properties['HYPHENS'];
		}
		if (isset($properties['LINE-HEIGHT']) && $properties['LINE-HEIGHT']) {
			$table['cellLineHeight'] = $this->mpdf->fixLineheight($properties['LINE-HEIGHT']);
		} elseif ($this->mpdf->tableLevel == 1) {
			$table['cellLineHeight'] = $this->mpdf->blk[$this->mpdf->blklvl]['line_height'];
		}

		if (isset($properties['LINE-STACKING-STRATEGY']) && $properties['LINE-STACKING-STRATEGY']) {
			$table['cellLineStackingStrategy'] = strtolower($properties['LINE-STACKING-STRATEGY']);
		} elseif ($this->mpdf->tableLevel == 1 && isset($this->mpdf->blk[$this->mpdf->blklvl]['line_stacking_strategy'])) {
			$table['cellLineStackingStrategy'] = $this->mpdf->blk[$this->mpdf->blklvl]['line_stacking_strategy'];
		} else {
			$table['cellLineStackingStrategy'] = 'inline-line-height';
		}

		if (isset($properties['LINE-STACKING-SHIFT']) && $properties['LINE-STACKING-SHIFT']) {
			$table['cellLineStackingShift'] = strtolower($properties['LINE-STACKING-SHIFT']);
		} elseif ($this->mpdf->tableLevel == 1 && isset($this->mpdf->blk[$this->mpdf->blklvl]['line_stacking_shift'])) {
			$table['cellLineStackingShift'] = $this->mpdf->blk[$this->mpdf->blklvl]['line_stacking_shift'];
		} else {
			$table['cellLineStackingShift'] = 'consider-shifts';
		}

		if (isset($properties['PADDING-LEFT'])) {
			$table['padding']['L'] = $this->sizeConverter->convert($properties['PADDING-LEFT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['PADDING-RIGHT'])) {
			$table['padding']['R'] = $this->sizeConverter->convert($properties['PADDING-RIGHT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['PADDING-TOP'])) {
			$table['padding']['T'] = $this->sizeConverter->convert($properties['PADDING-TOP'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['PADDING-BOTTOM'])) {
			$table['padding']['B'] = $this->sizeConverter->convert($properties['PADDING-BOTTOM'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}

		if (isset($properties['MARGIN-TOP'])) {
			if ($lastbottommargin) {
				$tmp = $this->sizeConverter->convert($properties['MARGIN-TOP'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
				if ($tmp > $lastbottommargin) {
					$properties['MARGIN-TOP'] = (int) $properties['MARGIN-TOP'] - $lastbottommargin;
				} else {
					$properties['MARGIN-TOP'] = 0;
				}
			}
			$table['margin']['T'] = $this->sizeConverter->convert($properties['MARGIN-TOP'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}

		if (isset($properties['MARGIN-BOTTOM'])) {
			$table['margin']['B'] = $this->sizeConverter->convert($properties['MARGIN-BOTTOM'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['MARGIN-LEFT'])) {
			$table['margin']['L'] = $this->sizeConverter->convert($properties['MARGIN-LEFT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}

		if (isset($properties['MARGIN-RIGHT'])) {
			$table['margin']['R'] = $this->sizeConverter->convert($properties['MARGIN-RIGHT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['MARGIN-LEFT']) && isset($properties['MARGIN-RIGHT']) && strtolower($properties['MARGIN-LEFT']) == 'auto' && strtolower($properties['MARGIN-RIGHT']) == 'auto') {
			$table['a'] = 'C';
		} elseif (isset($properties['MARGIN-LEFT']) && strtolower($properties['MARGIN-LEFT']) == 'auto') {
			$table['a'] = 'R';
		} elseif (isset($properties['MARGIN-RIGHT']) && strtolower($properties['MARGIN-RIGHT']) == 'auto') {
			$table['a'] = 'L';
		}

		if (isset($properties['BORDER-COLLAPSE']) && strtoupper($properties['BORDER-COLLAPSE']) == 'SEPARATE') {
			$table['borders_separate'] = true;
		} else {
			$table['borders_separate'] = false;
		}

		// mPDF 5.7.3

		if (isset($properties['BORDER-SPACING-H'])) {
			$table['border_spacing_H'] = $this->sizeConverter->convert($properties['BORDER-SPACING-H'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		if (isset($properties['BORDER-SPACING-V'])) {
			$table['border_spacing_V'] = $this->sizeConverter->convert($properties['BORDER-SPACING-V'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}
		// mPDF 5.7.3
		if (!$table['borders_separate']) {
			$table['border_spacing_H'] = $table['border_spacing_V'] = 0;
		}

		if (isset($properties['EMPTY-CELLS'])) {
			$table['empty_cells'] = strtolower($properties['EMPTY-CELLS']);  // 'hide'  or 'show'
		} else {
			$table['empty_cells'] = '';
		}

		if (isset($properties['PAGE-BREAK-INSIDE']) && strtoupper($properties['PAGE-BREAK-INSIDE']) == 'AVOID' && $this->mpdf->tableLevel == 1 && !$this->mpdf->writingHTMLfooter) {
			$this->mpdf->table_keep_together = true;
		} elseif ($this->mpdf->tableLevel == 1) {
			$this->mpdf->table_keep_together = false;
		}
		if (isset($properties['PAGE-BREAK-AFTER']) && $this->mpdf->tableLevel == 1) {
			$table['page_break_after'] = strtoupper($properties['PAGE-BREAK-AFTER']);
		}

		/* -- BACKGROUNDS -- */
		if (isset($properties['BACKGROUND-GRADIENT']) && !$this->mpdf->kwt && !$this->mpdf->ColActive) {
			$table['gradient'] = $properties['BACKGROUND-GRADIENT'];
		}

		if (isset($properties['BACKGROUND-IMAGE']) && $properties['BACKGROUND-IMAGE'] && !$this->mpdf->kwt && !$this->mpdf->ColActive) {
			$ret = $this->mpdf->SetBackground($properties, $this->mpdf->blk[$this->mpdf->blklvl]['inner_width']);
			if ($ret) {
				$table['background-image'] = $ret;
			}
		}
		/* -- END BACKGROUNDS -- */

		if (isset($properties['OVERFLOW'])) {
			$table['overflow'] = strtolower($properties['OVERFLOW']);  // 'hidden' 'wrap' or 'visible' or 'auto'
			if (($this->mpdf->ColActive || $this->mpdf->tableLevel > 1) && $table['overflow'] == 'visible') {
				unset($table['overflow']);
			}
		}

		$properties = [];


		if (isset($attr['CELLPADDING'])) {
			$table['cell_padding'] = $attr['CELLPADDING'];
		} else {
			$table['cell_padding'] = false;
		}

		if (isset($attr['BORDER']) && $attr['BORDER'] == '1') {
			$this->mpdf->table_border_attr_set = 1;
			$bord = $this->mpdf->border_details('#000000 1px solid');
			if ($bord['s']) {
				$table['border'] = Border::ALL;
				$table['border_details']['R'] = $bord;
				$table['border_details']['L'] = $bord;
				$table['border_details']['T'] = $bord;
				$table['border_details']['B'] = $bord;
			}
		} else {
			$this->mpdf->table_border_attr_set = 0;
		}

		if ($w) {
			$maxwidth = $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'];
			if ($table['borders_separate']) {
				$tblblw = $table['margin']['L'] + $table['margin']['R'] + $table['border_details']['L']['w'] / 2 + $table['border_details']['R']['w'] / 2;
			} else {
				$tblblw = $table['margin']['L'] + $table['margin']['R'] + $table['max_cell_border_width']['L'] / 2 + $table['max_cell_border_width']['R'] / 2;
			}
			if (strpos($w, '%') && $this->mpdf->tableLevel == 1 && !$this->mpdf->ignore_table_percents) {
				// % needs to be of inner box without table margins etc.
				$maxwidth -= $tblblw;
				$wmm = $this->sizeConverter->convert($w, $maxwidth, $this->mpdf->FontSize, false);
				$table['w'] = $wmm + $tblblw;
			}
			if (strpos($w, '%') && $this->mpdf->tableLevel > 1 && !$this->mpdf->ignore_table_percents && $this->mpdf->keep_table_proportions) {
				$table['wpercent'] = (int) $w;  // makes 80% -> 80
			}
			if (!strpos($w, '%') && !$this->mpdf->ignore_table_widths) {
				$wmm = $this->sizeConverter->convert($w, $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
				$table['w'] = $wmm + $tblblw;
			}
			if (!$this->mpdf->keep_table_proportions) {
				if (isset($table['w']) && $table['w'] > $this->mpdf->blk[$this->mpdf->blklvl]['inner_width']) {
					$table['w'] = $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'];
				}
			}
		}

		if (isset($attr['AUTOSIZE']) && $this->mpdf->tableLevel == 1) {
			$this->mpdf->shrink_this_table_to_fit = $attr['AUTOSIZE'];
			if ($this->mpdf->shrink_this_table_to_fit < 1) {
				$this->mpdf->shrink_this_table_to_fit = 1;
			}
		}
		if (isset($attr['ROTATE']) && $this->mpdf->tableLevel == 1) {
			$this->mpdf->table_rotate = $attr['ROTATE'];
		}

		//++++++++++++++++++++++++++++
		if ($this->mpdf->table_rotate) {
			$this->mpdf->tbrot_Links = [];
			$this->mpdf->tbrot_Annots = [];
			$this->mpdf->tbrotForms = [];
			$this->mpdf->tbrot_BMoutlines = [];
			$this->mpdf->tbrot_toc = [];
		}

		if ($this->mpdf->kwt) {
			if ($this->mpdf->table_rotate) {
				$this->mpdf->table_keep_together = true;
			}
			$this->mpdf->kwt = false;
			$this->mpdf->kwt_saved = true;
		}

		//++++++++++++++++++++++++++++
		$this->mpdf->plainCell_properties = [];
		unset($table);


	}

	public function close($tag, &$ahtml, &$ihtml)
	{

	}
}