<?php

namespace Issues;

class Issue1204Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * Addresses Issue:
	 *
	 * is_file(): Unable to find the wrapper "chrome-extension" - did you forget to enable it when you configured PHP?
	 *
	 * @throws \Mpdf\MpdfException
	 */
	public function testIgnoresNonHttpURIs()
	{
		$mpdf = new \Mpdf\Mpdf();

		$mpdf->WriteHTML('<a href="chrome-extension://fooobarextension"></a>');
		$mpdf->WriteHTML('<img src="chrome-extension://fooobarextension">');
	}

}
