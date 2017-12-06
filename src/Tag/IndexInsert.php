<?php

namespace Mpdf\Tag;

class IndexInsert extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		if (isset($attr['COLLATION'])) {
			$indexCollationLocale = $attr['COLLATION'];
		} else {
			$indexCollationLocale = '';
		}
		if (isset($attr['COLLATION-GROUP'])) {
			$indexCollationGroup = $attr['COLLATION-GROUP'];
		} else {
			$indexCollationGroup = '';
		}
		if (isset($attr['USEDIVLETTERS']) && (strtoupper($attr['USEDIVLETTERS']) == 'OFF'
				|| $attr['USEDIVLETTERS'] == -1
				|| $attr['USEDIVLETTERS'] === '0')) {
			$usedivletters = 0;
		} else {
			$usedivletters = 1;
		}
		if (isset($attr['LINKS']) && (strtoupper($attr['LINKS']) == 'ON' || $attr['LINKS'] == 1)) {
			$links = true;
		} else {
			$links = false;
		}
		$this->mpdf->InsertIndex($usedivletters, $links, $indexCollationLocale, $indexCollationGroup);
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
