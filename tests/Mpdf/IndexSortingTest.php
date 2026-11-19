<?php

namespace Mpdf;

class IndexSortingTest extends BaseMpdfTest
{

	public function testIndexEntriesAreSortedByCollationLocaleWithoutDiagnostics()
	{
		$diagnostics = [];

		set_error_handler(function ($errno, $errstr) use (&$diagnostics) {
			if (error_reporting() & $errno) {
				$diagnostics[] = $errstr;
			}

			return true;
		});

		try {
			$this->mpdf->WriteHTML(
				'<p>zebra<indexentry content="zebra" /></p><p>Apple<indexentry content="Apple" /></p>'
				. '<p>émile<indexentry content="émile" /></p><p>mango<indexentry content="mango" /></p>'
				. '<pagebreak /><indexinsert collation="C" />'
			);
		} finally {
			restore_error_handler();
		}

		$this->assertSame([], $diagnostics);
		$this->assertSame(['Apple', 'mango', 'zebra', 'émile'], array_column($this->mpdf->Reference, 't'));
	}

}
