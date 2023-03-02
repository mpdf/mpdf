<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1831Test extends \Mpdf\BaseMpdfTest
{

	public function testDoubleHyphen()
	{
		$mpdf = new Mpdf(['format' => [100, 100]]);
		$mpdf->SetCompression(false);

		$html = '
		<html><body>
<p style="font-size: 12px; hyphens: auto">Please visit me at my place in blab Paul-Sorge-Strasse 123, 20214 Hamburg, Germany</p>
<p style="font-size: 12px; hyphens: auto">Please visit me at my place in blab Paul–Sorge–Strasse 123, 20214 Hamburg, Germany</p>
</body></html>
';

		$mpdf->WriteHtml($html, 2);

		$out = $mpdf->Output('', 'S');
	}

}
