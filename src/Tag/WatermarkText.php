<?php

namespace Mpdf\Tag;

class WatermarkText extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		if (isset($attr['CONTENT']) && $attr['CONTENT']) {
			$txt = htmlspecialchars_decode($attr['CONTENT'], ENT_QUOTES);
		} else {
			$txt = '';
		}
		if (isset($attr['ALPHA']) && $attr['ALPHA'] > 0) {
			$alpha = $attr['ALPHA'];
		} else {
			$alpha = -1;
		}
		$this->mpdf->SetWatermarkText($txt, $alpha);
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
