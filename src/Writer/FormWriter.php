<?php

namespace Mpdf\Writer;

use Mpdf\Strict;

use Mpdf\Mpdf;

final class FormWriter
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

	public function writeFormObjects() // _putformobjects
	{
		foreach ($this->mpdf->formobjects as $file => $info) {

			$this->writer->object();

			$this->mpdf->formobjects[$file]['n'] = $this->mpdf->n;

			$this->writer->write('<</Type /XObject');
			$this->writer->write('/Subtype /Form');
			$this->writer->write('/Group ' . ($this->mpdf->n + 1) . ' 0 R');
			$this->writer->write('/BBox [' . $info['x'] . ' ' . $info['y'] . ' ' . ($info['w'] + $info['x']) . ' ' . ($info['h'] + $info['y']) . ']');

			if ($this->mpdf->compress) {
				$this->writer->write('/Filter /FlateDecode');
			}

			$data = $this->mpdf->compress ? gzcompress($info['data']) : $info['data'];
			$this->writer->write('/Length ' . strlen($data) . '>>');
			$this->writer->stream($data);

			unset($this->mpdf->formobjects[$file]['data']);

			$this->writer->write('endobj');

			// Required for SVG transparency (opacity) to work
			$this->writer->object();
			$this->writer->write('<</Type /Group');
			$this->writer->write('/S /Transparency');
			$this->writer->write('>>');
			$this->writer->write('endobj');
		}
	}

	public function writeFormXObjects() // _putformxobjects
	{
		$filter = $this->mpdf->compress ? '/Filter /FlateDecode ' : '';

		reset($this->mpdf->tpls);

		foreach ($this->mpdf->tpls as $tplidx => $tpl) {

			$p = $this->mpdf->compress ? gzcompress($tpl['buffer']) : $tpl['buffer'];

			$this->writer->object();
			$this->mpdf->tpls[$tplidx]['n'] = $this->mpdf->n;
			$this->writer->write('<<' . $filter . '/Type /XObject');
			$this->writer->write('/Subtype /Form');
			$this->writer->write('/FormType 1');

			// Left/Bottom/Right/Top
			$this->writer->write(
				sprintf(
					'/BBox [%.2F %.2F %.2F %.2F]',
					$tpl['box']['x'] * Mpdf::SCALE,
					$tpl['box']['y'] * Mpdf::SCALE,
					($tpl['box']['x'] + $tpl['box']['w']) * Mpdf::SCALE,
					($tpl['box']['y'] + $tpl['box']['h']) * Mpdf::SCALE
				)
			);

			if (isset($tpl['box'])) {
				$this->writer->write(
					sprintf(
						'/Matrix [1 0 0 1 %.5F %.5F]',
						-$tpl['box']['x'] * Mpdf::SCALE,
						-$tpl['box']['y'] * Mpdf::SCALE
					)
				);
			}

			$this->writer->write('/Resources ');

			if (isset($tpl['resources'])) {

				$this->mpdf->current_parser = $tpl['parser'];
				$this->mpdf->pdf_write_value($tpl['resources']);

			} else {

				$this->writer->write('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');

				if (isset($this->_res['tpl'][$tplidx]['fonts']) && count($this->mpdf->_res['tpl'][$tplidx]['fonts'])) {
					$this->writer->write('/Font <<');
					foreach ($this->mpdf->_res['tpl'][$tplidx]['fonts'] as $font) {
						$this->writer->write('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
					}
					$this->writer->write('>>');
				}

				if ((isset($this->mpdf->_res['tpl'][$tplidx]['images']) && count($this->mpdf->_res['tpl'][$tplidx]['images'])) ||
					(isset($this->mpdf->_res['tpl'][$tplidx]['tpls']) && count($this->mpdf->_res['tpl'][$tplidx]['tpls']))) {

					$this->writer->write('/XObject <<');

					if (isset($this->_res['tpl'][$tplidx]['images']) && count($this->mpdf->_res['tpl'][$tplidx]['images'])) {
						foreach ($this->mpdf->_res['tpl'][$tplidx]['images'] as $image) {
							$this->writer->write('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
						}
					}

					if (isset($this->_res['tpl'][$tplidx]['tpls']) && count($this->mpdf->_res['tpl'][$tplidx]['tpls'])) {
						foreach ($this->mpdf->_res['tpl'][$tplidx]['tpls'] as $i => $itpl) {
							$this->writer->write($this->mpdf->tplprefix . $i . ' ' . $itpl['n'] . ' 0 R');
						}
					}

					$this->writer->write('>>');
				}

				$this->writer->write('>>');
			}

			$this->writer->write('/Length ' . strlen($p) . ' >>');
			$this->writer->stream($p);
			$this->writer->write('endobj');
		}
	}

}
