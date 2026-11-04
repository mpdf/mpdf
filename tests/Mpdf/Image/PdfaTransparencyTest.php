<?php

namespace Mpdf\Image;

use Mpdf\Mpdf;

class PdfaTransparencyTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testPdfA3KeepsTheAlphaChannelAsSoftMask()
	{
		$pdf = $this->renderTransparentPng('3-B');

		$this->assertNotFalse(strpos($pdf, '/SMask'), 'PDF/A-3 permits transparency, the soft mask has to stay');
	}

	public function testPdfA2KeepsTheAlphaChannelAsSoftMask()
	{
		$pdf = $this->renderTransparentPng('2-B');

		$this->assertNotFalse(strpos($pdf, '/SMask'), 'PDF/A-2 permits transparency, the soft mask has to stay');
	}

	public function testPdfA1RemovesTheAlphaChannel()
	{
		$pdf = $this->renderTransparentPng('1-B');

		$this->assertFalse(strpos($pdf, '/SMask'), 'PDF/A-1 prohibits transparency');
	}

	/**
	 * Renders a 4x4 fully transparent PNG into a PDF/A document of the given version.
	 *
	 * @param string $pdfaVersion
	 *
	 * @return string
	 */
	private function renderTransparentPng($pdfaVersion)
	{
		$image = imagecreatetruecolor(4, 4);
		imagealphablending($image, false);
		imagesavealpha($image, true);
		imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
		ob_start();
		imagepng($image);
		$png = ob_get_clean();

		$mpdf = new Mpdf(['PDFA' => true, 'PDFAauto' => true, 'PDFAversion' => $pdfaVersion]);
		$mpdf->WriteHTML('<img src="data:image/png;base64,' . base64_encode($png) . '">');
		$output = $mpdf->Output('', 'S');
		$mpdf->cleanup();

		return $output;
	}

}
