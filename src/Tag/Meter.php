<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;

class Meter extends InlineTag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$tag = $this->getTagName();
		$this->mpdf->inMeter = true;

		$max = 1;
		if (!empty($attr['MAX'])) {
			$max = $attr['MAX'];
		}

		$min = 0;
		if (!empty($attr['MIN']) && $tag === 'METER') {
			$min = $attr['MIN'];
		}

		if ($max < $min) {
			$max = $min;
		}

		$value = '';
		if (isset($attr['VALUE']) && ($attr['VALUE'] || $attr['VALUE'] === '0')) {
			$value = $attr['VALUE'];
			if ($value < $min) {
				$value = $min;
			} elseif ($value > $max) {
				$value = $max;
			}
		}

		$low = $min;
		if (!empty($attr['LOW'])) {
			$low = $attr['LOW'];
		}
		if ($low < $min) {
			$low = $min;
		} elseif ($low > $max) {
			$low = $max;
		}
		$high = $max;
		if (!empty($attr['HIGH'])) {
			$high = $attr['HIGH'];
		}
		if ($high < $low) {
			$high = $low;
		} elseif ($high > $max) {
			$high = $max;
		}
		if (!empty($attr['OPTIMUM'])) {
			$optimum = $attr['OPTIMUM'];
		} else {
			$optimum = $min + (($max - $min) / 2);
		}
		if ($optimum < $min) {
			$optimum = $min;
		} elseif ($optimum > $max) {
			$optimum = $max;
		}
		$type = '';
		if (!empty($attr['TYPE'])) {
			$type = $attr['TYPE'];
		}
		$objattr = [];
		$objattr['margin_top'] = 0;
		$objattr['margin_bottom'] = 0;
		$objattr['margin_left'] = 0;
		$objattr['margin_right'] = 0;
		$objattr['padding_top'] = 0;
		$objattr['padding_bottom'] = 0;
		$objattr['padding_left'] = 0;
		$objattr['padding_right'] = 0;
		$objattr['width'] = 0;
		$objattr['height'] = 0;
		$objattr['border_top']['w'] = 0;
		$objattr['border_bottom']['w'] = 0;
		$objattr['border_left']['w'] = 0;
		$objattr['border_right']['w'] = 0;

		$properties = $this->cssManager->MergeCSS('INLINE', $tag, $attr);
		if (isset($properties ['DISPLAY']) && strtolower($properties ['DISPLAY']) === 'none') {
			return;
		}
		$objattr['visibility'] = 'visible';
		if (isset($properties['VISIBILITY'])) {
			$v = strtolower($properties['VISIBILITY']);
			if (($v === 'hidden' || $v === 'printonly' || $v === 'screenonly') && $this->mpdf->visibility === 'visible') {
				$objattr['visibility'] = $v;
			}
		}

		if (isset($properties['MARGIN-TOP'])) {
			$objattr['margin_top'] = $this->sizeConverter->convert(
				$properties['MARGIN-TOP'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['MARGIN-BOTTOM'])) {
			$objattr['margin_bottom'] = $this->sizeConverter->convert(
				$properties['MARGIN-BOTTOM'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['MARGIN-LEFT'])) {
			$objattr['margin_left'] = $this->sizeConverter->convert(
				$properties['MARGIN-LEFT'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['MARGIN-RIGHT'])) {
			$objattr['margin_right'] = $this->sizeConverter->convert(
				$properties['MARGIN-RIGHT'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}

		if (isset($properties['PADDING-TOP'])) {
			$objattr['padding_top'] = $this->sizeConverter->convert(
				$properties['PADDING-TOP'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['PADDING-BOTTOM'])) {
			$objattr['padding_bottom'] = $this->sizeConverter->convert(
				$properties['PADDING-BOTTOM'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['PADDING-LEFT'])) {
			$objattr['padding_left'] = $this->sizeConverter->convert(
				$properties['PADDING-LEFT'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}
		if (isset($properties['PADDING-RIGHT'])) {
			$objattr['padding_right'] = $this->sizeConverter->convert(
				$properties['PADDING-RIGHT'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		}

		if (isset($properties['BORDER-TOP'])) {
			$objattr['border_top'] = $this->mpdf->border_details($properties['BORDER-TOP']);
		}
		if (isset($properties['BORDER-BOTTOM'])) {
			$objattr['border_bottom'] = $this->mpdf->border_details($properties['BORDER-BOTTOM']);
		}
		if (isset($properties['BORDER-LEFT'])) {
			$objattr['border_left'] = $this->mpdf->border_details($properties['BORDER-LEFT']);
		}
		if (isset($properties['BORDER-RIGHT'])) {
			$objattr['border_right'] = $this->mpdf->border_details($properties['BORDER-RIGHT']);
		}

		if (isset($properties['VERTICAL-ALIGN'])) {
			$objattr['vertical-align'] = self::ALIGN[strtolower($properties['VERTICAL-ALIGN'])];
		}
		$w = 0;
		$h = 0;
		if (isset($properties['WIDTH'])) {
			$w = $this->sizeConverter->convert(
				$properties['WIDTH'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		} elseif (isset($attr['WIDTH'])) {
			$w = $this->sizeConverter->convert($attr['WIDTH'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}

		if (isset($properties['HEIGHT'])) {
			$h = $this->sizeConverter->convert(
				$properties['HEIGHT'],
				$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
				$this->mpdf->FontSize,
				false
			);
		} elseif (isset($attr['HEIGHT'])) {
			$h = $this->sizeConverter->convert($attr['HEIGHT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
		}

		if (isset($properties['OPACITY']) && $properties['OPACITY'] > 0 && $properties['OPACITY'] <= 1) {
			$objattr['opacity'] = $properties['OPACITY'];
		}
		if ($this->mpdf->HREF) {
			if (strpos($this->mpdf->HREF, '.') === false && strpos($this->mpdf->HREF, '@') !== 0) {
				$href = $this->mpdf->HREF;
				while (array_key_exists($href, $this->mpdf->internallink)) {
					$href = '#' . $href;
				}
				$this->mpdf->internallink[$href] = $this->mpdf->AddLink();
				$objattr['link'] = $this->mpdf->internallink[$href];
			} else {
				$objattr['link'] = $this->mpdf->HREF;
			}
		}
		$extraheight = $objattr['padding_top'] + $objattr['padding_bottom'] + $objattr['margin_top']
			+ $objattr['margin_bottom'] + $objattr['border_top']['w'] + $objattr['border_bottom']['w'];

		$extrawidth = $objattr['padding_left'] + $objattr['padding_right'] + $objattr['margin_left']
			+ $objattr['margin_right'] + $objattr['border_left']['w'] + $objattr['border_right']['w'];

		$meter = new \Mpdf\Meter();
		$svg = $meter->makeSVG(strtolower($tag), $type, $value, $max, $min, $optimum, $low, $high);
		//Save to local file
		$srcpath = $this->cache->write('/_tempSVG' . uniqid(random_int(1, 100000), true) . '_' . strtolower($tag) . '.svg', $svg);
		$orig_srcpath = $srcpath;
		$this->mpdf->GetFullPath($srcpath);

		$info = $this->imageProcessor->getImage($srcpath, true, true, $orig_srcpath);
		if (!$info) {
			$info = $this->imageProcessor->getImage($this->mpdf->noImageFile);
			if ($info) {
				$srcpath = $this->mpdf->noImageFile;
				$w = ($info['w'] * (25.4 / $this->mpdf->dpi));
				$h = ($info['h'] * (25.4 / $this->mpdf->dpi));
			}
		}
		if (!$info) {
			return;
		}

		$objattr['file'] = $srcpath;

		// Default width and height calculation if needed
		if ($w == 0 && $h == 0) {
			// SVG units are pixels
			$w = $this->mpdf->FontSize / (10 / Mpdf::SCALE) * abs($info['w']) / Mpdf::SCALE;
			$h = $this->mpdf->FontSize / (10 / Mpdf::SCALE) * abs($info['h']) / Mpdf::SCALE;
		}

		// IF WIDTH OR HEIGHT SPECIFIED
		if ($w == 0) {
			$w = $info['h'] ? abs($h * $info['w'] / $info['h']) : INF;
		}
		if ($h == 0) {
			$h = $info['w'] ? abs($w * $info['h'] / $info['w']) : INF;
		}

		// Resize to maximum dimensions of page
		$maxWidth = $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'];
		$maxHeight = $this->mpdf->h - ($this->mpdf->tMargin + $this->mpdf->bMargin + 1);
		if ($this->mpdf->fullImageHeight) {
			$maxHeight = $this->mpdf->fullImageHeight;
		}
		if (($w + $extrawidth) > ($maxWidth + 0.0001)) { // mPDF 5.7.4  0.0001 to allow for rounding errors when w==maxWidth
			$w = $maxWidth - $extrawidth;
			$h = abs($w * $info['h'] / $info['w']);
		}

		if ($h + $extraheight > $maxHeight) {
			$h = $maxHeight - $extraheight;
			$w = abs($h * $info['w'] / $info['h']);
		}
		$objattr['type'] = 'image';
		$objattr['itype'] = $info['type'];

		$objattr['orig_h'] = $info['h'];
		$objattr['orig_w'] = $info['w'];
		$objattr['wmf_x'] = $info['x'];
		$objattr['wmf_y'] = $info['y'];
		$objattr['height'] = $h + $extraheight;
		$objattr['width'] = $w + $extrawidth;
		$objattr['image_height'] = $h;
		$objattr['image_width'] = $w;
		$e = "\xbb\xa4\xactype=image,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
		if ($this->mpdf->tableLevel) {
			$this->mpdf->_saveCellTextBuffer($e, $this->mpdf->HREF);
			$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += $objattr['width'];
		} else {
			$this->mpdf->_saveTextBuffer($e, $this->mpdf->HREF);
		}
	}

	public function close(&$ahtml, &$ihtml)
	{
		parent::close($ahtml, $ihtml);
		$this->mpdf->ignorefollowingspaces = false;
		$this->mpdf->inMeter = false;
	}
}
