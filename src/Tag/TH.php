<?php

namespace Mpdf\Tag;


class TH extends TD
{

	public function close(&$ahtml, &$ihtml)
	{
		$this->mpdf->SetStyle('B', false);
		parent::close($ahtml, $ihtml);
	}
}
