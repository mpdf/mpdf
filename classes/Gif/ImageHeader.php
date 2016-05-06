<?php

namespace Mpdf\Gif;

/**
 * GIF Util - (C) 2003 Yamasoft (S/C)
 *
 * All Rights Reserved
 *
 * This file can be freely copied, distributed, modified, updated by anyone under the only
 * condition to leave the original address (Yamasoft, http://www.yamasoft.com) and this header.
 *
 * @link http://www.yamasoft.com
 */
class ImageHeader
{

	var $m_nLeft;

	var $m_nTop;

	var $m_nWidth;

	var $m_nHeight;

	var $m_bLocalClr;

	var $m_bInterlace;

	var $m_bSorted;

	var $m_nTableSize;

	var $m_colorTable;

	public function __construct()
	{
		unSet($this->m_nLeft);
		unSet($this->m_nTop);
		unSet($this->m_nWidth);
		unSet($this->m_nHeight);
		unSet($this->m_bLocalClr);
		unSet($this->m_bInterlace);
		unSet($this->m_bSorted);
		unSet($this->m_nTableSize);
		unSet($this->m_colorTable);
	}

	function load($lpData, &$hdrLen)
	{
		$hdrLen = 0;

		$this->m_nLeft = $this->w2i(substr($lpData, 0, 2));
		$this->m_nTop = $this->w2i(substr($lpData, 2, 2));
		$this->m_nWidth = $this->w2i(substr($lpData, 4, 2));
		$this->m_nHeight = $this->w2i(substr($lpData, 6, 2));

		if (!$this->m_nWidth || !$this->m_nHeight) {
			return false;
		}

		$b = ord($lpData{8});
		$this->m_bLocalClr = ($b & 0x80) ? true : false;
		$this->m_bInterlace = ($b & 0x40) ? true : false;
		$this->m_bSorted = ($b & 0x20) ? true : false;
		$this->m_nTableSize = 2 << ($b & 0x07);
		$hdrLen = 9;

		if ($this->m_bLocalClr) {
			$this->m_colorTable = new ColorTable();
			if (!$this->m_colorTable->load(substr($lpData, $hdrLen), $this->m_nTableSize)) {
				return false;
			}
			$hdrLen += 3 * $this->m_nTableSize;
		}

		return true;
	}

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}

}
