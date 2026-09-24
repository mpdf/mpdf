<?php

namespace Mpdf;

use fpdi_pdf_parser;
use Mpdf\Pdf\Protection;
use Mpdf\Pdf\Protection\UniqidGenerator;
use Mpdf\Writer\BaseWriter;
use pdf_parser;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfBoolean;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfName;
use setasign\Fpdi\PdfParser\Type\PdfNull;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfString;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfReader\DataStructure\Rectangle;
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

		$pdf->SetProtection(['copy', 'print'], '', 'password', 128);

		$string = new PdfString();
		$string->value = '\040\t\n\f\040';

		$pdf->writePdfType($string);

		// (xxxxx)
		$string = substr($pdf->buffer->writeToString(), 1, -1);
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
			$backgroundName = ($pageNo & 1) === 1 ? 'TPL0' : 'TPL1';
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

	/**
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	public function testExternalLinksAndStyles()
	{
		$mpdf = new Mpdf();
		$mpdf->AddPage();
		$mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/links.pdf');
		$tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId, [
			'x' => 20,
			'y' => 20,
			'width' => 100,
			'height' => 100
		]);
		$mpdf->Rect(20, 20, 100, 100);

		$pdfString = $mpdf->OutputBinaryData();

		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com/#1',
				'rect' => [174.93, 761.11, 209.68, 766.3],
				'color' => false,
				'border' => [0, 0, 1, [3]],
				'borderStyle' => [
					'D' => [3],
					'S' => 'D',
					'Type' => 'Border',
					'W' => 1
				]
			],
			[
				'uri' => 'https://www.setasign.com/#2',
				'rect' => [83.26, 743.38, 302.55, 751.01],
				'f' => 4,
				'color' => [1, 0, 0],
				'border' => false,
				'borderStyle' => [
					'S' => 'S',
					'W' => 1,
				]
			],
			[
				'uri' => 'https://www.setasign.com/#4',
				'rect' => [243.22, 705.37, 312.27, 709.72],
				'f' => 4,
				'color' => [1, 0, 0],
				'border' => false,
				'borderStyle' => [
					'D' => [3],
					'S' => 'D',
					'W' => 0
				],
			],
			[
				'uri' => 'https://www.setasign.com/#5',
				'rect' => [113.21, 696.08, 137.67, 700.43],
				'f' => 4,
				'color' => [0.376471, 0.74902, 0],
				'borderStyle' => [
					'W' => 1,
					'S' => 'D',
					'D' => [3]
				]

			],
			[
				'uri' => 'https://demos.setasign.com/?some=(get paramert/with special signs',
				'rect' => [168.4, 682.12, 215.38, 687.31],
				'border' => [0, 0, 1, [3]],
				'borderStyle' => [
					'W' => 1,
					'S' => 'D',
					'D' => [3]
				]
			],
			[
				'uri' => 'https://www.setasign.com/#3',
				'rect' => [83.74, 719.29, 312.5, 729.13],
				'quadPoints' => [298.54, 723.94, 312.50, 723.94, 312.50, 729.13, 298.54, 729.13, 83.74, 719.29, 95.78, 719.29, 95.78, 724.48, 83.74, 724.48],
				'color' => [0.25, 0.333328, 1],
				'border' => [0, 0, 3],
				'borderStyle' => [
					'S' => 'S',
					'W' => 3
				]
			]
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	/**
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	public function testExternalLinksOnRotatedPages()
	{
		$mpdf = new Mpdf();
		$pageCount = $mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/rotated-pages.pdf');
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$mpdf->AddPage();
			$tplId = $mpdf->importPage($pageNo);
			$mpdf->useTemplate($tplId, ['adjustPageSize' => true]);
		}

		$pdfString = $mpdf->OutputBinaryData();

		$expectedPageLinks = [
			1 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [414.56, 151.34, 447.88, 297.64],
				]
			],
			2 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [135.77, 394.01, 297.64, 427.33],
				]
			],
			3 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [394.01, 297.64, 427.33, 459.51],
				]
			],
			4 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [297.64, 414.56, 459.51, 447.88],
				]
			],
			5 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [414.56, 135.77, 447.88, 297.64],
				]
			],
			6 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [394.01, 297.64, 427.33, 453.26],
				]
			],
			7 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [126.45, 394.01, 297.64, 427.33],
				]
			],
			8 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [414.56, 126.45, 447.88, 297.64],
				]
			],
			9 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [297.64, 414.56, 468.83, 447.88],
				]
			],
			10 => [
				[
					'uri' => 'https://www.setasign.com',
					'rect' => [394.01, 297.64, 427.33, 468.83],
				]
			]
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));

		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$this->compareExpectedLinks($pageNo, $expectedPageLinks[$pageNo], $reader);
		}
	}

	/**
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	*/
	public function testExternalLinksWithDifferentPageBoundaries()
	{
		$mpdf = new Mpdf();
		$mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/boxes.pdf');

		$artBox = $mpdf->importPage(1, PageBoundaries::ART_BOX);
		$trimBox = $mpdf->importPage(1, PageBoundaries::TRIM_BOX);
		$bleedBox = $mpdf->importPage(1, PageBoundaries::BLEED_BOX);
		$cropBox = $mpdf->importPage(1, PageBoundaries::CROP_BOX);

		$mpdf->addPage('L');
		$mpdf->useImportedPage($artBox, 0, 0, null, 160);
		$mpdf->useImportedPage($trimBox, 70, 0, null, 160);
		$mpdf->useImportedPage($bleedBox, 140, 0, null, 160);
		$mpdf->useImportedPage($cropBox, 210, 0, null, 160);

		$pdfString = $mpdf->OutputBinaryData();

		$expectedLinks = [
			[
				'uri' => 'https://demos.setasign.com/?ArtBox',
				'rect' => [2.67, 144.4, 42.7, 159.83],
			],
			[
				'uri' => 'https://demos.setasign.com/?TrimBox',
				'rect' => [-24.01, 117.73, 25.64, 133.15],
			],
			[
				'uri' => 'https://demos.setasign.com/?BleedBox',
				'rect' => [-50.69, 91.05, 6.4, 106.47],
			],
			[
				'uri' => 'https://demos.setasign.com/?CropBox',
				'rect' => [-77.37, 64.37, -25.48, 79.79]
			],
			[
				'uri' => 'https://demos.setasign.com/?ArtBox',
				'rect' => [224.68, 167.99, 260.5, 181.79],
			],
			[
				'uri' => 'https://demos.setasign.com/?TrimBox',
				'rect' => [200.81, 144.12, 245.24, 157.92],
			],
			[
				'uri' => 'https://demos.setasign.com/?BleedBox',
				'rect' => [176.94, 120.25, 228.02, 134.05],
			],
			[
				'uri' => 'https://demos.setasign.com/?CropBox',
				'rect' => [153.07, 96.38, 199.5, 110.18]
			],
			[
				'uri' => 'https://demos.setasign.com/?ArtBox',
				'rect' => [442.2, 187.09, 474.61, 199.57],
			],
			[
				'uri' => 'https://demos.setasign.com/?TrimBox',
				'rect' => [420.61, 165.49, 460.8, 177.98],
			],
			[
				'uri' => 'https://demos.setasign.com/?BleedBox',
				'rect' => [399.01, 143.9, 445.23, 156.38],
			],
			[
				'uri' => 'https://demos.setasign.com/?CropBox',
				'rect' => [377.41, 122.3, 419.42, 134.78]
			],
			[
				'uri' => 'https://demos.setasign.com/?ArtBox',
				'rect' => [656.41, 202.87, 685.99, 214.26],
			],
			[
				'uri' => 'https://demos.setasign.com/?TrimBox',
				'rect' => [636.69, 183.15, 673.38, 194.54],
			],
			[
				'uri' => 'https://demos.setasign.com/?BleedBox',
				'rect' => [616.97, 163.43, 659.17, 174.83],
			],
			[
				'uri' => 'https://demos.setasign.com/?CropBox',
				'rect' => [597.25, 143.71, 635.6, 155.11]
			]
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	/**
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	public function testImportOfSpecialPageBoundaries()
	{
		$mpdf = new Mpdf();
		$mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/[-1000 -1000 -500 -500].pdf');

		$mpdf->AddPage();
		$tplId = $mpdf->importPage(1);
		$s = $mpdf->useTemplate($tplId, 20, 10, 150);
		$mpdf->Rect(20, 10, 150, $s['height']);

		$mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/[1000 500 -1000 -500]-R90.pdf');
		$tplId = $mpdf->importPage(1);
		$s = $mpdf->useTemplate($tplId, 20, 200, 50);
		$mpdf->Rect(20, 200, 50, $s['height']);

		$pdfString = $mpdf->OutputBinaryData();

		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [141.73, 466.5, 514.43, 501.89],
				'borderStyle' => [
					'D' => [3],
					'S' => 'D',
					'W' => 1
				],
				'border' => [
					0,
					0,
					1,
					[3]
				]
			],
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [69.72, 191.01, 75.62, 260.79],
				'borderStyle' => [
					'D' => [3],
					'S' => 'D',
					'W' => 1
				],
				'border' => [
					0,
					0,
					1,
					[3]
				]
			],
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	/**
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	public function testLinkInUtf16Encoding()
	{
		$mpdf = new Mpdf();
		$mpdf->AddPage();
		// This file has its link in UTF-16BE saved.
		$mpdf->setSourceFile(__DIR__ . '/../data/pdfs/external-links/tuto6.pdf');
		$tplId = $mpdf->importPage(2);
		$mpdf->useTemplate($tplId);
		$pdfString = $mpdf->OutputBinaryData();

		$expectedLinks = [
			[
				// the strings are in UTF-16BE: http://pdf.wtf/ümlaut
				'uri' => "\xFE\xFF\x00h\x00t\x00t\x00p\x00:\x00/\x00/\x00p\x00d\x00f\x00.\x00w\x00t\x00f\x00/\x00\xfc\x00m\x00l\x00a\x00u\x00t",
				'rect' => [28.35, 749.82, 113.39, 807.87],
			],
			[
				'uri' => "\xFE\xFF\x00h\x00t\x00t\x00p\x00:\x00/\x00/\x00p\x00d\x00f\x00.\x00w\x00t\x00f\x00/\x00\xfc\x00m\x00l\x00a\x00u\x00t",
				'rect' => [387.18, 756.93, 468.87, 770.93],
			],
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	/**
	 * Verify the generated PDF has the imported links on the correct page and in the correct position.
	 *
	 * This method comes from the FPDI library.
	 *
	 * @param int $pageNo
	 * @param array $expectedLinks
	 * @param PdfReader $reader
	 * @param float $delta
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	protected function compareExpectedLinks($pageNo, array $expectedLinks, PdfReader $reader, $delta = 0.01)
	{
		$parser = $reader->getParser();
		$pageDict = $reader->getPage($pageNo)->getPageDictionary();
		$annots = PdfType::resolve($pageDict->value['Annots'], $parser);
		$this->assertInstanceOf(PdfArray::class, $annots);

		$this->assertCount(count($expectedLinks), $annots->value);

		foreach ($expectedLinks as $idx => $linkData) {
			$linkAnnotation = PdfType::resolve($annots->value[$idx], $parser);

			$this->assertEquals($linkData['uri'], PdfString::unescape($linkAnnotation->value['A']->value['URI']->value));
			$rect = Rectangle::byPdfArray($linkAnnotation->value['Rect'], $parser);
			$rectValues = $rect->toArray();

			$this->assertEqualsWithDelta($linkData['rect'], $rectValues, $delta, 'Rect @Page ' . $pageNo . '/' . $idx);

			if (!isset($linkData['quadPoints'])) {
				$this->assertFalse(isset($linkAnnotation->value['QuadPoints']));
			} else {
				$quadPoints = $this->dumpPdfType(PdfArray::ensure($linkAnnotation->value['QuadPoints'], count($linkData['quadPoints'])));
				$this->assertEqualsWithDelta($linkData['quadPoints'], $quadPoints, $delta, 'QuadPoints @Page ' . $pageNo . '/' . $idx);
			}

			if (isset($linkData['f'])) {
				$this->assertEquals(
					$linkData['f'],
					$this->dumpPdfType(PdfNumeric::ensure($linkAnnotation->value['F']))
				);
			}

			if (isset($linkData['border'])) {
				if ($linkData['border'] === false) {
					$this->assertFalse(isset($linkAnnotation->value['Border']));
				} else {
					$this->assertEquals(
						$linkData['border'],
						$this->dumpPdfType(PdfArray::ensure($linkAnnotation->value['Border']))
					);
				}
			}

			if (isset($linkData['color'])) {
				if ($linkData['color'] === false) {
					$this->assertFalse(isset($linkAnnotation->value['C']));
				} else {
					$this->assertEqualsWithDelta(
						$linkData['color'],
						$this->dumpPdfType(PdfArray::ensure($linkAnnotation->value['C'])),
						$delta,
						''
					);
				}
			}

			if (isset($linkData['borderStyle'])) {
				if ($linkData['borderStyle'] === false) {
					$this->assertFalse(isset($linkAnnotation->value['BS']));
				} else {
					// we cannot compare the complete dictionary because the order may differ because TCPDF
					// uses its own logic to create the BS entry
					$bs = PdfDictionary::ensure($linkAnnotation->value['BS'])->value;
					foreach ($linkData['borderStyle'] as $key => $value) {
						$this->assertEquals($value, $this->dumpPdfType($bs[$key]));
					}
				}
			}
		}
	}

	/**
	 * This method comes from the FPDI library.
	 *
	 * @param PdfType $value
	 * @return array|mixed|string
	 * @copyright Copyright (c) Setasign GmbH & Co. KG (https://www.setasign.com)
	 * @license http://opensource.org/licenses/mit-license The MIT License
	 */
	protected function dumpPdfType(PdfType $value)
	{
		switch (get_class($value)) {
			case PdfName::class:
			case PdfNumeric::class:
			case PdfBoolean::class:
				return $value->value;
			case PdfString::class:
				return PdfString::unescape($value->value);
			case PdfArray::class:
				$result = [];
				foreach ($value->value as $entry) {
					$result[] = $this->dumpPdfType($entry);
				}
				return $result;
			case PdfDictionary::class:
				$result = [];
				foreach ($value->value as $key => $entry) {
					$result[$key] = $this->dumpPdfType($entry);
				}
				return $result;
			default:
				throw new \InvalidArgumentException(
					'Dump of PdfType "' . get_class($value) . '" is not implemented yet.'
				);
		}
	}
}
