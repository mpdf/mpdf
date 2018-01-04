<?php

namespace Mpdf\Tag;

class TocPageBreak extends FormFeed
{
	public function open($attr, &$ahtml, &$ihtml)
	{
		list($isbreak, $toc_id) = $this->tableOfContents->openTagTOCPAGEBREAK($attr);
		$this->toc_id = $toc_id;
		if ($isbreak) {
			return;
		}
		if (!isset($attr['RESETPAGENUM']) || $attr['RESETPAGENUM'] < 1) {
			$attr['RESETPAGENUM'] = 1;
		} // mPDF 6
		parent::open($attr, $ahtml, $ihtml);
	}
}
