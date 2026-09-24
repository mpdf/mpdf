<?php

namespace Mpdf\Fonts\Fixtures;

use Mpdf\Fonts\FontRegistration;

class TestFontRegistrationWithId extends FontRegistration
{
	private $id;

	private $font;

	public function __construct($id, $font)
	{
		$this->id = $id;
		$this->font = $font;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getDirectory()
	{
		return '/tmp/' . $this->id;
	}

	public function getFonts()
	{
		return [
			$this->font => [
				'R' => $this->font . '.ttf',
			]
		];
	}
}
