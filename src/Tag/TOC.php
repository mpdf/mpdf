<?php

namespace Mpdf\Tag;


class TOC extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		//added custom-tag - set Marker for insertion later of ToC
		$this->tableOfContents->openTagTOC($attr);
	}

	public function close($tag, &$ahtml, &$ihtml)
	{

	}
}