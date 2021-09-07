<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1115Test extends \Mpdf\BaseMpdfTest
{

	public function testCmykTextShadow()
	{
		$html = '

<html>
<head>
    <style>
        .my-h2-class {
            text-shadow: 2px 2px 0px cmyk(12, 79, 62, 25);
        }
    </style>
</head>

<body>

    <h2 class="my-h2-class">My h2 text</h2>

</body>
</html>

';

		$this->mpdf->setCompression(false);

		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->Output(null, 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertStringContainsString('0.120 0.790 0.620 0.250', $output);
	}

}
