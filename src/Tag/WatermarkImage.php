<?php

namespace Mpdf\Tag;

class WatermarkImage extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		if (isset($attr['SRC'])) {
			$src = $attr['SRC'];
		} else {
			$src = '';
		}
		if (isset($attr['ALPHA']) && $attr['ALPHA'] > 0) {
			$alpha = $attr['ALPHA'];
		} else {
			$alpha = -1;
		}
		if (isset($attr['SIZE']) && $attr['SIZE']) {
			$size = $attr['SIZE'];
			if (strpos($size, ',')) {
				$size = explode(',', $size);
			}
		} else {
			$size = 'D';
		}
		if (isset($attr['POSITION']) && $attr['POSITION']) {  // mPDF 5.7.2
			$pos = $attr['POSITION'];
			if (strpos($pos, ',')) {
				$pos = explode(',', $pos);
			}
		} else {
			$pos = 'P';
		}
		$this->mpdf->SetWatermarkImage($src, $alpha, $size, $pos);
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
