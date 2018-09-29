<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue205Test extends \Mpdf\BaseMpdfTest
{

	public function testBackgroundImagePatternSteps()
	{
		$style  = 'background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAALklEQVR42u3NMREAAAgEIL//mVkzOLhBAdJT7yKRSCQSiUQikUgkEolEIpFIbhbYFE2LUERN2gAAAABJRU5ErkJggg==);';
		$style .= 'background-repeat: no-repeat;';
		$style .= 'background-size: 350px 350px';
		$style .= 'height: 1500px;';

		$html = '<div style ="' . $style . '"></div>';

		$this->mpdf->WriteHtml($html);

		$output = $this->mpdf->Output('', 'S');

		$xStepPosition = strpos($output, 'XStep 512');
		$this->assertTrue($xStepPosition > 0);

		$yStepPosition = strpos($output, 'XStep 512');
		$this->assertTrue($yStepPosition > 0);
	}

}
