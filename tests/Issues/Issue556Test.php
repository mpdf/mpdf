<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue556Test extends \Mpdf\BaseMpdfTest
{

	public function testBackgroundCoverWide()
	{
		$html = '<div style="position:absolute;width:5in;height:5in;top:0;left:0;background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAAUAQMAAAAgFiiUAAAABlBMVEXMzMyWlpYU2uzLAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAADElEQVQImWNgGL4AAADcAAGk2YNWAAAAAElFTkSuQmCC) center center no-repeat; background-size: cover"></div>';
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('', 'S');
	}

	public function testBackgroundCoverTall()
	{
		$html = '<div style="position:absolute;width:5in;height:5in;top:0;left:0;background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAABQAQMAAAAjjsc0AAAABlBMVEXMzMyWlpYU2uzLAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAADUlEQVQYlWNgGAWUAAABQAABuwBcGQAAAABJRU5ErkJggg==) center center no-repeat; background-size: cover"></div>';
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('', 'S');
	}

}
