<?php

namespace Mpdf\Fonts\Fixtures;

use Mpdf\Fonts\FontRegistration;

class TestFontRegistrationB extends FontRegistration
{
	public function getDirectory()
	{
		return '/tmp/b';
	}

	public function getFonts()
	{
		return [
			'fontB' => [
				'R' => 'fontB.ttf',
			]
		];
	}
}
