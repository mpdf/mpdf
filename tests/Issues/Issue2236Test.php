<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue2236Test extends \Mpdf\BaseMpdfTest
{

	protected function set_up()
	{
		parent::set_up();

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($this->mpdf->tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::CHILD_FIRST
		);

		/** @var \DirectoryIterator $item */
		foreach ($iterator as $item) {
			if ($item->isFile() && $item->getFilename() !== '.gitignore') {
				unlink($item->getPathname());
			}
		}

		$this->mpdf = new Mpdf([
			'useSubstitutions' => true,
		]);
	}

	public function testBackupSipFontSubstitutionOnEmptyCache()
	{
		$this->mpdf->WriteHTML('<p style="font-family: dejavusans">&#x20BB7;</p>');

		$this->assertEquals('sun-extb', $this->mpdf->flowingBlockAttr['font'][0]['family']);
	}

}
