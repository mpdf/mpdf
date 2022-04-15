<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1428Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	/**
	 * @var \Mpdf\Mpdf
	 */
	protected $mpdf;

	protected function set_up()
	{
		$this->mpdf = new Mpdf([
			'fontDir' => [
				__DIR__ . '/../../ttfonts',
				__DIR__ . '/../data/ttf',
			],
			'fontdata' => [
				'manjari' => [
					'R' => 'Manjari-Regular.ttf',
					'useOTL' => 0xFF,

				]
			],
			'default_font' => 'manjari',
		]);
	}

	protected function tear_down()
	{
		$this->mpdf->cleanup();
	}

	public function testOtfArrayError()
	{
		$this->mpdf->WriteHTML('പ്ലാസ്ത്റില്‍');
	}
}
