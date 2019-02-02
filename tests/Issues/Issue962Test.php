<?php

namespace Issues;

class Issue962Test extends \Mpdf\BaseMpdfTest
{
	public function setUp()
	{
		parent::setUp();
		$fontDirPath = $_SERVER['DOCUMENT_ROOT']. '/../web/assets/fonts';

		$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs      = $defaultConfig['fontDir'];

		$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
		$fontData          = $defaultFontConfig['fontdata'];

		// Add all required fonts to $font array
		# 1. Basic mpdf-Fonts
		$fontArr = $fontData;
		# 2. Font-Awesome-Fonts
		$fontArr = $fontArr + [
			'fontawesome' => [
				'I' => 'fa-brands-400.ttf',
				'L' => 'fa-light-300.ttf',
				'R' => 'fa-regular-400.ttf',
				'B' => 'fa-solid-900.ttf',
			]
		];

		$this->mpdf = new Mpdf([
			'biDirectional' => true,
			'fontdata' => $fontArr,
			'fontDir'  => array_merge($fontDirs, [$fontDirPath]),
		]);
	}

	public function testStarsIconsFontAwesome()
	{
		$this->mpdf->WriteHTML('	
			&#xf005;
		<strong>&#xf005;</strong>
		<span style="font-weight: bold">&#xf5c0;</span>'); // this line doesn't work
		$this->mpdf->Close();
	}

}
