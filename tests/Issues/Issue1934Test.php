<?php

namespace Issues;

use Mpdf\Tag\Option;
use Mpdf\Cache;
use Mpdf\CssManager;
use Mpdf\Form;
use Mpdf\Otl;
use Mpdf\TableOfContents;
use Mpdf\SizeConverter;
use Mpdf\Image\ImageProcessor;
use Mpdf\Language\LanguageToFontInterface;
use Mpdf\Color\ColorConverter;

class Issue1934Test extends \Mpdf\BaseMpdfTest
{
	public function testWithFailingHtmlSnippet()
	{
		$html = '<select><option value="this option tag has the value">Option 1</option><option selected>Option 2</option></select>';
		
		$this->mpdf->WriteHTML($html);
	}
}
