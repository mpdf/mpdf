<?php

namespace Issues;

class Issue2090Test extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testMetaDataWriterEscaping()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->PDFA = true;

		$metaData = [
			'Title', 'Author', 'Subject', 'Keywords', 'Creator'
		];

		foreach ($metaData as $metaDatum) {
			$methodName = 'Set' . $metaDatum;
			$mpdf->$methodName('&' . $metaDatum . '<>');
		}

		$output = $mpdf->output('', 'S');

		foreach ($metaData as $metaDatum) {
			$this->assertStringContainsString('>&amp;' . $metaDatum . '&lt;&gt;<', $output);
		}
	}

}
