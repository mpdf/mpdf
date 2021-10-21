<?php

namespace Mpdf;

use fpdi_pdf_parser;
use Mpdf\Pdf\Protection;
use Mpdf\Pdf\Protection\UniqidGenerator;
use Mpdf\Writer\BaseWriter;
use pdf_parser;
use ReflectionClass;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfNull;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfString;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfReader\PageBoundaries;
use setasign\Fpdi\PdfReader\PdfReader;

/**
 * PHPUnit Testing for the MPDI (FPDI) Functionality
 *
 * @package    mPDF
 * @author     Blue Liquid Designs <admin@blueliquiddesigns.com.au>
 * @copyright  2015 Blue Liquid Designs
 * @license    GPLv2
 */

/**
 * The FPDI/MPDI testing suite
 *
 * @group mpdi
 */
class FpdiTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testReturnValueOfUseTemplate()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/Noisy-Tube.pdf');
		$pageId = $pdf->importPage(1);

		$size = $pdf->useTemplate($pageId, 10, 10, 100);
		$this->assertEquals([
			'width' => 100,
			'height' => 141.42851383223916,
			0 => 100,
			1 => 141.42851383223916,
			'orientation' => 'P'
		], $size);
	}

	public function testBehaviourOnCompressedXref()
	{
		$this->expectException(\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException::class);
		$this->expectExceptionCode(\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException::COMPRESSED_XREF);

		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/compressed-xref.pdf');
	}

	public function testHandlingOfNoneExistingReferencedObjects()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/ReferencesToInvalidObjects.pdf');
		$pdf->AddPage();
		$pdf->useTemplate($pdf->importPage(1));

		$pdfString = $pdf->Output('doc.pdf', 'S');

//        var_dump($pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$xObject = $parser->getIndirectObject(5)->value;

		$resources = PdfType::resolve($xObject->value->value['Resources'], $parser);
		$linkToNull = PdfType::resolve($resources->value['Font']->value['SETA_Test'], $parser);

		$null = PdfType::resolve($linkToNull, $parser);
		$this->assertInstanceOf(PdfNull::class, $null);
	}

	public function testSetSourceFileWithoutUsingIt()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/Noisy-Tube.pdf');
		$pdfString = $pdf->Output('doc.pdf', 'S');

//        var_dump($pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$trailer = $parser->getCrossReference()->getTrailer();

		$this->assertSame(7, $trailer->value['Size']->value);
	}

	public function testGetTemplateSize()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/boundary-boxes.pdf');
		$size = $pdf->getTemplateSize($pdf->importPage(1));
		$this->assertEquals([
			'width' => 420 / Mpdf::SCALE,
			'height' => 920 / Mpdf::SCALE,
			0 => 420 / Mpdf::SCALE,
			1 => 920 / Mpdf::SCALE,
			'orientation' => 'P'
		], $size);

		$size = $pdf->getTemplateSize($pdf->importPage(1, PageBoundaries::ART_BOX));
		$this->assertEquals([
			'width' => 180 / Mpdf::SCALE,
			'height' => 680 / Mpdf::SCALE,
			0 => 180 / Mpdf::SCALE,
			1 => 680 / Mpdf::SCALE,
			'orientation' => 'P'
		], $size);

		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/rotated.pdf');
		$size = $pdf->getTemplateSize($pdf->importPage(1));
		$this->assertEquals([
			'width' => 841.890 / Mpdf::SCALE,
			'height' => 595.280 / Mpdf::SCALE,
			0 => 841.890 / Mpdf::SCALE,
			1 => 595.280 / Mpdf::SCALE,
			'orientation' => 'L'
		], $size);
	}

	/**
	 * This test ensures that a string is unescaped before it is passed to the encryption function.
	 */
	public function testEncryptionOfStringWithOctalValue()
	{
		$pdf = new mPDF();
		$writer = new BaseWriter($pdf, new Protection(new UniqidGenerator()));

		$pdf->SetProtection(['copy','print'], '', 'password', 128);

		$string = new PdfString();
		$string->value = '\040\t\n\f\040';

		$pdf->writePdfType($string);

		// (xxxxx)
		$string = substr($pdf->buffer, 1, -1);
		// we need to unescape the string, to get a comparable value
		$this->assertEquals(5, strlen($writer->unescape($string)));
	}

	public function testImportAndResolvingOfImportedResources()
	{
		$pdf = new Mpdf();
		$pageCount = $pdf->setSourceFile(__DIR__ . '/../data/pdfs/Letterhead.pdf');
		$this->assertSame(1, $pageCount);

		$tpl = $pdf->importPage(1);
		$pdf->AddPage();
		$pdf->useTemplate($tpl);

		$pdfString = $pdf->Output('test.pdf', 'S');

//        file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));

		$trailer = $parser->getCrossReference()->getTrailer();
		$this->assertSame(20, $trailer->value['Size']->value);

		// check global Resources dictionary for imported page:
		$resources = $parser->getIndirectObject(2)->value;

		$xObjects = $resources->value['XObject'];
		/** @var PdfStream $tpl0 */
		$tpl0 = PdfType::resolve($xObjects->value['TPL0'], $parser); // imported page

		// check some resources of the imported page:
		$this->assertInstanceOf(PdfDictionary::class, $tpl0->value);

		$xObjects = $tpl0->value->value['Resources']->value['XObject'];
		$fm0 = PdfType::resolve($xObjects->value['Fm0'], $parser); // imported resource
		// let's check if the resources were imported recursively:
		$fonts = $fm0->value->value['Resources']->value['Font'];
		/** @var PdfDictionary $t1_0 */
		$t1_0 = PdfType::resolve($fonts->value['T1_0'], $parser); // imported font

		$this->assertSame('Font', $t1_0->value['Type']->value);
		$this->assertSame('TRVJLW+FuturaStd-Light', $t1_0->value['BaseFont']->value);
	}

	public function testAdjustPageSize()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/boundary-boxes.pdf');

		$pdf->AddPageByArray(['newformat' => 'A5']);
		$mediaBox = $pdf->importPage(1, PageBoundaries::MEDIA_BOX);
		$pdf->useTemplate($mediaBox, ['adjustPageSize' => true]);

		$pdf->AddPage();
		$artBox = $pdf->importPage(1, PageBoundaries::ART_BOX);
		$pdf->useTemplate($artBox, ['adjustPageSize' => true]);

		$pdf->AddPage();
		$bleedBox = $pdf->importPage(1, PageBoundaries::BLEED_BOX);
		$pdf->useTemplate($bleedBox, ['adjustPageSize' => true]);

		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$pageOne = $pdfReader->getPage(1);
		$this->assertSame([500., 1000.], $pageOne->getWidthAndHeight());

		$pageTwo = $pdfReader->getPage(2);
		$this->assertSame([180., 680.], $pageTwo->getWidthAndHeight());

		$pageThree = $pdfReader->getPage(3);
		$this->assertSame([340., 840.], $pageThree->getWidthAndHeight());
	}

	public function testImportShiftedBoundaries()
	{
		$pdf = new Mpdf();
		$pageCount = $pdf->setSourceFile(__DIR__ . '/../data/pdfs/boxes/[1000 500 -1000 -500].pdf');
		$this->assertSame(1, $pageCount);

		$tpl = $pdf->importPage(1);
		$size = $pdf->getTemplateSize($tpl);

		$pdf->AddPage();
		$pdf->useTemplate($tpl, ['adjustPageSize' => true]);

		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$pageOne = $pdfReader->getPage(1);
		$this->assertSame([2000., 1000.], $pageOne->getWidthAndHeight());
	}

	public function testImportRotated()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/boxes/[1000 500 -1000 -500]-R90.pdf');

		$pdf->AddPage();
		$artBox = $pdf->importPage(1);
		$pdf->useTemplate($artBox, ['adjustPageSize' => true]);

		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$pageOne = $pdfReader->getPage(1);
		$this->assertSame([1000., 2000.], $pageOne->getWidthAndHeight());
	}

	public function testDocTemplate()
	{
		$pdf = new Mpdf();
		$pdf->SetDocTemplate(__DIR__ . '/../data/pdfs/Letterhead.pdf', true);

		$pageCount = $pdf->setSourceFile(__DIR__ . '/../data/pdfs/rotated.pdf');
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$pdf->AddPage();
			$tpl = $pdf->importPage($pageNo);
			$width = 150;
			$size = $pdf->getTemplateSize($tpl, $width);
			$pdf->useTemplate($tpl, ($pdf->w - $width) / 2, ($pdf->h - $size['height']) / 2, $width);
		}

		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$this->assertSame(10, $pdfReader->getPageCount());

		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$page = $pdfReader->getPage($pageNo);

			$contentStream = $page->getContentStream();

			// check for the background
			$this->assertNotFalse(strpos($contentStream, '/TPL0'));
			// and the imported page
			$this->assertNotFalse(strpos($contentStream, '/TPL' . $pageNo));

			$resources = PdfType::resolve($page->getAttribute('Resources'), $parser);
			$tpl0 = PdfType::resolve($resources->value['XObject']->value['TPL0'], $parser);
			$this->assertInstanceOf(PdfStream::class, $tpl0);

			$tplX = PdfType::resolve($resources->value['XObject']->value['TPL' . $pageNo], $parser);
			$this->assertInstanceOf(PdfStream::class, $tplX);
		}
	}

	public function testDocTemplateContinue2pages()
	{
		$pdf = new Mpdf();
		$pdf->SetDocTemplate(__DIR__ . '/../data/pdfs/Letterhead3.pdf', true, true);

		$pageCount = 5;

		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$pdf->AddPage();
		}

		$pdfString = $pdf->Output('test.pdf', 'S');

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$this->assertSame($pageCount, $pdfReader->getPageCount());

		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$page = $pdfReader->getPage($pageNo);
			$contentStream = $page->getContentStream();
			$resources = PdfType::resolve($page->getAttribute('Resources'), $parser);

			// The 1st page should include the 1st template page
			if (1 === $pageNo) {
				$this->assertNotFalse(strpos($contentStream, '/TPL0'));

				$tpl = PdfType::resolve($resources->value['XObject']->value['TPL0'], $parser);
				$this->assertInstanceOf(PdfStream::class, $tpl);
			}

			// The 2nd AND 4th page should include the 2nd template page
			if (2 === $pageNo || 4 === $pageNo) {
				$this->assertNotFalse(strpos($contentStream, '/TPL1'));

				$tpl = PdfType::resolve($resources->value['XObject']->value['TPL1'], $parser);
				$this->assertInstanceOf(PdfStream::class, $tpl);
			}

			// The 3nd AND 5th page should include the 3nd template page
			if (3 === $pageNo || 5 === $pageNo) {
				$this->assertNotFalse(strpos($contentStream, '/TPL2'));

				$tpl = PdfType::resolve($resources->value['XObject']->value['TPL2'], $parser);
				$this->assertInstanceOf(PdfStream::class, $tpl);
			}
		}
	}

	public function testPageTemplate()
	{
		$pdf = new Mpdf();
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/Letterhead.pdf');
		$tplA = $pdf->importPage(1);
		$pdf->setSourceFile(__DIR__ . '/../data/pdfs/Letterhead2.pdf');
		$tplB = $pdf->importPage(1);

		$pageCount = $pdf->setSourceFile(__DIR__ . '/../data/pdfs/rotated.pdf');
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$pdf->SetPageTemplate(($pageNo & 1) === 1 ? $tplA : $tplB);
			$pdf->AddPage();
			$tpl = $pdf->importPage($pageNo);
			$width = 150;
			$size = $pdf->getTemplateSize($tpl, $width);
			$pdf->useTemplate($tpl, ($pdf->w - $width) / 2, ($pdf->h - $size['height']) / 2, $width);
		}

		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$this->assertSame(10, $pdfReader->getPageCount());

		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$page = $pdfReader->getPage($pageNo);

			$contentStream = $page->getContentStream();

			// check for the background
			$backgroundName = ($pageNo & 1) === 1 ? 'TPL0' :  'TPL1';
			$this->assertNotFalse(strpos($contentStream, '/' . $backgroundName));
			// and the imported page
			$this->assertNotFalse(strpos($contentStream, '/TPL' . ($pageNo + 1)));

			$resources = PdfType::resolve($page->getAttribute('Resources'), $parser);
			$tplBg = PdfType::resolve($resources->value['XObject']->value[$backgroundName], $parser);
			$this->assertInstanceOf(PdfStream::class, $tplBg);

			$tplX = PdfType::resolve($resources->value['XObject']->value['TPL' . ($pageNo + 1)], $parser);
			$this->assertInstanceOf(PdfStream::class, $tplX);
		}
	}

	public function testThumbnail()
	{
		$pdf = new Mpdf();
		$pdf->Thumbnail(__DIR__ . '/../data/pdfs/rotated.pdf');
		$pdfString = $pdf->Output('test.pdf', 'S');
//		file_put_contents('test.pdf', $pdfString);

		$parser = new PdfParser(StreamReader::createByString($pdfString));
		$pdfReader = new PdfReader($parser);

		$pageOne = $pdfReader->getPage(1);
		$contentStream = $pageOne->getContentStream();
		$this->assertSame(9, preg_match_all('~/TPL\d~', $contentStream));

		$pageTwo = $pdfReader->getPage(2);
		$contentStream = $pageTwo->getContentStream();
		$this->assertSame(1, preg_match_all('~/TPL\d~', $contentStream));
	}
}
