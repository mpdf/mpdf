<?php

namespace Mpdf\Tag;

use Mpdf\Mpdf;
use ReflectionClass;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class BaseTagTestCase extends TestCase
{
	/**
	 * @var Mpdf
	 */
	protected $mpdf;

	protected function set_up()
	{
		parent::set_up();
		$this->mpdf = new Mpdf();
		$this->mpdf->AddPage();
		
		// Initialize font to ensure font properties are set for tag tests
		$this->mpdf->SetFont('Arial', '', 12);
		$this->mpdf->blk = [ [] ];
		$this->mpdf->initialiseBlock($this->mpdf->blk[0]);
		$this->mpdf->blk[0]['width'] = & $this->mpdf->pgwidth;
		$this->mpdf->blk[0]['inner_width'] = & $this->mpdf->pgwidth;
		$this->mpdf->blk[0]['blockContext'] = $this->mpdf->blockContext;
		
		// Initialize InlineProperties for block 0 - required by BlockTag
		$this->mpdf->blk[0]['InlineProperties'] = $this->mpdf->saveInlineProperties();
		
		if (!isset($this->mpdf->InlineProperties)) {
			$this->mpdf->InlineProperties = [];
		}
		
		// Initialize table context for table-related tag tests
		if (!isset($this->mpdf->table)) {
			$this->mpdf->table = [];
		}
		if (!isset($this->mpdf->cell)) {
			$this->mpdf->cell = [];
		}
	}

	public function tear_down()
	{
		parent::tear_down();

		unset($this->mpdf);
	}

	protected function getService($serviceName)
	{
		$reflection = new ReflectionClass($this->mpdf);
		
		// Check if property exists (declared or dynamic)
		if ($reflection->hasProperty($serviceName)) {
			$property = $reflection->getProperty($serviceName);
			$property->setAccessible(true);
			return $property->getValue($this->mpdf);
		}

		throw new \Exception("Service '$serviceName' not found on Mpdf instance.");
	}

	protected function createTag($className)
	{
		return new $className(
			$this->mpdf,
			$this->getService('cache'),
			$this->getService('cssManager'),
			$this->getService('form'),
			$this->getService('otl'),
			$this->getService('tableOfContents'),
			$this->getService('sizeConverter'),
			$this->getService('colorConverter'),
			$this->getService('imageProcessor'),
			$this->getService('languageToFont')
		);
	}
}
