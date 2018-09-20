<?php

namespace Mpdf\Writer;

use Mpdf\Mpdf;
use Mpdf\Pdf\Protection;

class BaseWriter
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Mpdf\Pdf\Protection
	 */
	private $protection;

	public function __construct(Mpdf $mpdf, Protection $protection)
	{
		$this->mpdf = $mpdf;
		$this->protection = $protection;
	}

	public function write($s, $ln = true)
	{
		if ($this->mpdf->state === 2) {
			$this->endPage($s, $ln);
		} else {
			$this->mpdf->buffer .= $s . ($ln ? "\n" : '');
		}
	}

	public function object($obj_id = false, $onlynewobj = false)
	{
		if (!$obj_id) {
			$obj_id = ++$this->mpdf->n;
		}

		// Begin a new object
		if (!$onlynewobj) {
			$this->mpdf->offsets[$obj_id] = strlen($this->mpdf->buffer);
			$this->write($obj_id . ' 0 obj');
			$this->mpdf->_current_obj_id = $obj_id; // for later use with encryption
		}
	}

	function stream($s)
	{
		if ($this->mpdf->encrypted) {
			$s = $this->protection->rc4($this->protection->objectKey($this->mpdf->_current_obj_id), $s);
		}

		$this->write('stream');
		$this->write($s);
		$this->write('endstream');
	}

	private function endPage($s, $ln)
	{
		if ($this->mpdf->bufferoutput) {

			$this->mpdf->headerbuffer.= $s . "\n";

		} elseif ($this->mpdf->ColActive && !$this->mpdf->processingHeader && !$this->mpdf->processingFooter) {

			// Captures everything in buffer for columns; Almost everything is sent from fn. Cell() except:
			// Images sent from Image() or
			// later sent as write($textto) in printbuffer
			// Line()

			if (preg_match('/q \d+\.\d\d+ 0 0 (\d+\.\d\d+) \d+\.\d\d+ \d+\.\d\d+ cm \/(I|FO)\d+ Do Q/', $s, $m)) { // Image data

				$h = ($m[1] / Mpdf::SCALE);
				// Update/overwrite the lowest bottom of printing y value for a column
				$this->mpdf->ColDetails[$this->mpdf->CurrCol]['bottom_margin'] = $this->mpdf->y + $h;

			} elseif ($this->mpdf->tableLevel > 0 && preg_match('/\d+\.\d\d+ \d+\.\d\d+ \d+\.\d\d+ ([\-]{0,1}\d+\.\d\d+) re/', $s, $m)) { // Rect in table

				$h = ($m[1] / Mpdf::SCALE);
				// Update/overwrite the lowest bottom of printing y value for a column
				$this->mpdf->ColDetails[$this->mpdf->CurrCol]['bottom_margin'] = max($this->mpdf->ColDetails[$this->mpdf->CurrCol]['bottom_margin'], $this->mpdf->y + $h);

			} elseif (isset($this->mpdf->ColDetails[$this->mpdf->CurrCol]['bottom_margin'])) {

				$h = $this->mpdf->ColDetails[$this->mpdf->CurrCol]['bottom_margin'] - $this->mpdf->y;

			} else {

				$h = 0;

			}

			if ($h < 0) {
				$h = -$h;
			}

			$this->mpdf->columnbuffer[] = [
				's' => $s, // Text string to output
				'col' => $this->mpdf->CurrCol, // Column when printed
				'x' => $this->mpdf->x, // x when printed
				'y' => $this->mpdf->y, // this->y when printed (after column break)
				'h' => $h        // actual y at bottom when printed = y+h
			];

		} elseif ($this->mpdf->table_rotate && !$this->mpdf->processingHeader && !$this->mpdf->processingFooter) {

			// Captures eveything in buffer for rotated tables;
			$this->mpdf->tablebuffer .= $s . "\n";

		} elseif ($this->mpdf->kwt && !$this->mpdf->processingHeader && !$this->mpdf->processingFooter) {

			// Captures eveything in buffer for keep-with-table (h1-6);
			$this->mpdf->kwt_buffer[] = [
				's' => $s, // Text string to output
				'x' => $this->mpdf->x, // x when printed
				'y' => $this->mpdf->y, // y when printed
			];

		} elseif ($this->mpdf->keep_block_together && !$this->mpdf->processingHeader && !$this->mpdf->processingFooter) {
			// do nothing
		} else {
			$this->mpdf->pages[$this->mpdf->page] .= $s . ($ln ? "\n" : '');
		}
	}

}
