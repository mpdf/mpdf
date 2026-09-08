<?php

namespace Mpdf\Fonts\Fixtures;

use Mpdf\Fonts\FontRegistration;

class TestFontRegistrationA extends FontRegistration
{
	public function getDirectory()
	{
		return '/tmp/a';
	}

	public function getFonts()
	{
		return [
			'fontA' => [
				'R' => 'fontA.ttf',
			]
		];
	}

	public function getFontAliases()
	{
		/* Two aliases for one font: both are legitimate entries in a map keyed by alias */
		return [
			'aliasOne' => 'fontA',
			'aliasTwo' => 'fontA',
		];
	}

	public function getLineBreakDictionaries()
	{
		return [
			'T' => '/tmp/a/linebrdictT.dat',
		];
	}
}
