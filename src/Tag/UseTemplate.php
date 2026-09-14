<?php

namespace Mpdf\Tag;

class UseTemplate extends Tag
{
	public function open($attr, &$ahtml, &$ihtml)
	{
		if (file_exists($attr['ORIG_SRC'])) {
			$page = intval($attr['PAGE']);
			$pageCount = $this->mpdf->setSourceFile($attr['ORIG_SRC']);
			if ($page == 0 || $page > $pageCount) {
				return;
			}
			$tplId = $this->mpdf->importPage($page);
			$this->mpdf->useTemplate($tplId);
		}
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
