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
			$this->writer->write('/Resources <<' . $this->collectFormObjectResources() . '>>');

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

	private function collectFormObjectResources()
	{
		$resources = [];
		$extGStates = [];

		foreach ($this->mpdf->extgstates as $key => $extGState) {
			if (!isset($extGState['fo']) || !$extGState['fo']) {
				continue;
			}

			if (isset($extGState['trans'])) {
				$extGStates[] = '/' . $extGState['trans'] . ' ' . $extGState['n'] . ' 0 R';
			} else {
				$extGStates[] = '/GS' . $key . ' ' . $extGState['n'] . ' 0 R';
			}
		}

		if ($extGStates !== []) {
			$resources[] = '/ExtGState <<' . implode('', $extGStates) . '>>';
		}

		if (isset($this->mpdf->gradients) && count($this->mpdf->gradients) > 0) {
			$shadings = [];

			foreach ($this->mpdf->gradients as $id => $gradient) {
				if (!isset($gradient['fo']) || !$gradient['fo'] || !isset($gradient['id'])) {
					continue;
				}

				$shadings[] = '/Sh' . $id . ' ' . $gradient['id'] . ' 0 R';
			}

			if ($shadings !== []) {
				$resources[] = '/Shading <<' . implode('', $shadings) . '>>';
			}
		}

		$fonts = [];

		foreach ($this->mpdf->fonts as $font) {
			if (!isset($font['type'])) {
				continue;
			}

			if ($font['type'] === 'TTF' && (!isset($font['used']) || !$font['used'])) {
				continue;
			}

			if (!isset($font['fo']) || !$font['fo']) {
				continue;
			}

			if ($font['type'] === 'TTF' && ($font['sip'] || $font['smp'])) {
				foreach ($font['n'] as $index => $fontReference) {
					$fonts[] = '/F' . $font['subsetfontids'][$index] . ' ' . $fontReference . ' 0 R';
				}

				continue;
			}

			$fonts[] = '/F' . $font['i'] . ' ' . $font['n'] . ' 0 R';
		}

		if ($fonts !== []) {
			$resources[] = '/Font <<' . implode('', $fonts) . '>>';
		}

		$resources[] = '/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]';

		return implode('', $resources);
	}
}
