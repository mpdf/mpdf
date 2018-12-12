<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue900Test extends \Mpdf\BaseMpdfTest
{
	public function testMergePdfWithLinks()
	{
		$tempfile = tempnam($this->mpdf->tempDir, 'i900-raw');

		$this->mpdf->WriteHTML('<!DOCTYPE html><html><body><a href="https://example.org">My Link</a></body></html>');
		$this->mpdf->Output($tempfile, DESTINATION::FILE);

		// Reset MPDF
		$this->setUp();

		$this->mpdf->SetImportUse();
		$pageCount = $this->mpdf->SetSourceFile($tempfile);

		for ($page = 1; $page <= $pageCount; $page++) {
			$this->mpdf->AddPage();
			$template = $this->mpdf->ImportPage($page);
			$this->mpdf->UseTemplate($template);
		}

		unlink($tempfile);

		$importedFile = $this->mpdf->Output('', Destination::STRING_RETURN);

		self::assertContains(
			'https://example.org',
			$importedFile,
			'Link-Target should be in the imported PDF.'
		);
		self::assertContains(
			'/Annot /Subtype /Link',
			$importedFile,
			'The Link should be in the imported PDF.'
		);
	}
}
