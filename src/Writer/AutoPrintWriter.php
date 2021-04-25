<?php

namespace Mpdf\Writer;

use Mpdf\Strict;
use Mpdf\Mpdf;

final class AutoPrintWriter
{

	use Strict;

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Mpdf\Writer\BaseWriter
	 */
	private $writer;

	public function __construct(Mpdf $mpdf, BaseWriter $writer)
	{
		$this->mpdf = $mpdf;
		$this->writer = $writer;
	}

	public function writeAutoPrint() // _putautoprint
	{
		$this->writer->object();
		$this->mpdf->auto_print_obj_id = $this->mpdf->n;
		$this->writer->write('<<');
		$this->writer->write('/S /Named ');
		$this->writer->write('/Type /Action');
		$this->writer->write('/N /Print');
		$this->writer->write('>>');
		$this->writer->write('endobj');
	}

}
