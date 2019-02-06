<?php

namespace Issues;

use Mpdf\Mpdf;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Issue890Test extends \Mpdf\BaseMpdfTest
{

	public function setUp()
	{
		parent::setUp();

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
			'biDirectional' => true,
		]);
	}

	public function testCharacterSubstitutionOnEmptyCache()
	{
		$this->mpdf->WriteHTML('&#10004;');

		$this->assertEquals('dejavusanscondensed', $this->mpdf->flowingBlockAttr['font'][0]['family']);
	}

}
