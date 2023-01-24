<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1795Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedIndex()
	{
		$html = '
		<!DOCTYPE html>
		<html lang="de">
		<head>
			<title>Test MPDF</title>
			<style>
				:root {
					--header-color-rgb: 0,0,128;
				}
		
				h1 {
					color: rgba(var(--header-color-rgb), 1)
				}
			</style>
		</head>
		<body>
			<h1>Test MPDF with CSS variables</h1>
		</body>
		</html>
';

		$this->mpdf->WriteHtml($html, 2);

		$out = $this->mpdf->Output('', 'S');
	}

}
