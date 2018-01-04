<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue400Test extends \Mpdf\BaseMpdfTest
{

	public function testNonNumericValue()
	{
		$html = '<style>
			.myfixed2 {
				position: fixed;
				overflow: auto;
				right: 0;
				bottom: 0mm;
				width: 65mm;
				border: 1px solid #880000;
				background-color: #FFEEDD;
				background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
				padding: 0.5em;
				font-family:sans;
				margin: 0;
				rotate: 90;
			}
		</style>
		<div class="myfixed2">2 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.</div>';

		$this->mpdf->WriteHtml($html);
	}

}
