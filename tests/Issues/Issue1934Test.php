<?php

namespace Issues;

class Issue1934Test extends \Mpdf\BaseMpdfTest
{
	public function testWithFailingHtmlSnippet()
	{
		$html = '<select><option value="this option tag has the value">Option 1</option><option selected>Option 2</option></select>';
		
		$this->mpdf->WriteHTML($html);
	}
}
