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
}
