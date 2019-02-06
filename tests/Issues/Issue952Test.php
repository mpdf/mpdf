<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue952Test extends \Mpdf\BaseMpdfTest
{

	public function testBoxShadow()
	{
		$this->mpdf->WriteHTML('<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test title</title>
</head>
<body>
	<div class="#content">
		<div style="box-shadow: 1px 1px 1px #0000ff;">
			<div style="box-shadow: 1px 1px 1px #0000ff;">Question text 2</div>
		</div>
	</div>
</body>
</html>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
