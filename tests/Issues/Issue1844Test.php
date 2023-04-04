<?php

namespace Issues;

class Issue1844Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedIndex()
	{
		$html = '
		<?xml version="1.0" encoding="utf-8" ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title></title>
		</head>
		<body>
		<h1 style="page-break-before: avoid;">Header</h1>

		<p class="noindent">Text</p>

		</body>
		</html>';

		$this->mpdf->WriteHtml($html);

		$this->assertStringStartsWith('%PDF-', $this->mpdf->OutputBinaryData());
	}

}
