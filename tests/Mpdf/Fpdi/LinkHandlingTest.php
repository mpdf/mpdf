<?php

namespace Mpdf\Fpdi;

use Mpdf\Mpdf;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfString;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfReader\DataStructure\Rectangle;
use setasign\Fpdi\PdfReader\PageBoundaries;
use setasign\Fpdi\PdfReader\PdfReader;

/**
 * This test class is a copy of the original class of the FPDI test-suite located at:
 *     https://github.com/Setasign/FPDI/blob/master/tests/functional/LinkHandling/AbstractTest.php
 *
 * It has some minor changes in view to fewer parameters or mPDF specific behavior.
 */
class LinkHandlingTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	protected function getInstance($orientation='P', $size='A4')
	{
		return new Mpdf(['orientation' => $orientation, 'format' => $size]);
	}

	protected function save($pdf)
	{
		return $pdf->OutputBinaryData();
	}

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
				$quadPoints = PdfTypeDumper::dump(PdfArray::ensure($linkAnnotation->value['QuadPoints'], count($linkData['quadPoints'])));
				$this->assertEqualsWithDelta($linkData['quadPoints'], $quadPoints, $delta, 'QuadPoints @Page ' . $pageNo . '/' . $idx);
			}

			if (isset($linkData['f'])) {
				$this->assertEquals(
					$linkData['f'],
					PdfTypeDumper::dump(PdfNumeric::ensure($linkAnnotation->value['F']))
				);
			}

			if (isset($linkData['border'])) {
				if ($linkData['border'] === false) {
					$this->assertFalse(isset($linkAnnotation->value['Border']));
				} else {
					$this->assertEquals(
						$linkData['border'],
						PdfTypeDumper::dump(PdfArray::ensure($linkAnnotation->value['Border']))
					);
				}
			}

			if (isset($linkData['color'])) {
				if ($linkData['color'] === false) {
					$this->assertFalse(isset($linkAnnotation->value['C']));
				} else {
					$this->assertEqualsWithDelta(
						$linkData['color'],
						PdfTypeDumper::dump(PdfArray::ensure($linkAnnotation->value['C'])),
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
						$this->assertEquals($value, PdfTypeDumper::dump($bs[$key]));
					}
				}
			}
		}
	}

	public function testLinkHandling1($filename = __DIR__ . '/../../data/pdfs/links/links.pdf')
	{
		$pdf = $this->getInstance();
		$pdf->AddPage();
		$pdf->setSourceFile($filename);
		$tplId = $pdf->importPage(1);
		$pdf->useTemplate($tplId, [
			'x' => 20,
			'y' => 20,
			'width' => 100,
			'height' => 100
		]);
		$pdf->Rect(20, 20, 100, 100);

		$pdfString = $this->save($pdf);
//		file_put_contents(__DIR__ . '/test.pdf', $pdfString);

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

		return $pdfString;
	}

	public function testLinkHandlingWithIndirectReferencesInAnnotation()
	{
		$this->testLinkHandling1(__DIR__ . '/../../data/pdfs/links/links-with-indirect-references.pdf');
	}

	/**
	 * Take the result of testLinkHandling1 and re-place it with the same settings.
	 * @depends testLinkHandling1
	 */
	public function testLinkHandling2($previous)
	{
		$pdf = $this->getInstance();
		$pdf->AddPage();
		$pdf->setSourceFile(StreamReader::createByString($previous));
		$tplId = $pdf->importPage(1, PageBoundaries::CROP_BOX, true, true);
		$pdf->useTemplate($tplId, [
			'x' => 20,
			'y' => 20,
			'width' => 100,
			'height' => 100
		]);
		$pdf->Rect(20, 20, 100, 100);

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com/#1',
				'rect' => [139.99, 758.0, 156.54, 759.75],
			],
			[
				'uri' => 'https://www.setasign.com/#2',
				'rect' => [96.34, 752.03, 200.76, 754.6],
			],
			[
				'uri' => 'https://www.setasign.com/#4',
				'rect' => [172.51, 739.23, 205.39, 740.7],
			],
			[
				'uri' => 'https://www.setasign.com/#5',
				'rect' => [110.6, 736.1, 122.25, 737.57],
			],
			[
				'uri' => 'https://demos.setasign.com/?some=(get paramert/with special signs',
				'rect' => [136.88, 731.4, 159.25, 733.15],
			],
			[
				'uri' => 'https://www.setasign.com/#3',
				'rect' => [96.57, 743.92, 205.5, 747.23],
				'quadPoints' => [198.85, 745.48, 205.5, 745.48, 205.5, 747.23, 198.85, 747.23, 96.57, 743.92, 102.3, 743.92, 102.3, 745.67, 96.57, 745.67]
			]
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);

		return $pdfString;
	}

	/**
	 * Re-import the page of the previous test with the same positioning
	 * and size will end in the same rect and quad-points values.
	 *
	 * @depends testLinkHandling2
	 */
	public function testLinkHandling3($previous)
	{
		$pdf = $this->getInstance();
		$pdf->AddPage();
		$pdf->setSourceFile(StreamReader::createByString($previous));
		$tplId = $pdf->importPage(1, PageBoundaries::CROP_BOX, true, true);
		$pdf->useTemplate($tplId);

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com/#1',
				'rect' => [139.99, 758.0, 156.54, 759.75],
			],
			[
				'uri' => 'https://www.setasign.com/#2',
				'rect' => [96.34, 752.03, 200.76, 754.6],
			],
			[
				'uri' => 'https://www.setasign.com/#4',
				'rect' => [172.51, 739.23, 205.39, 740.7],
			],
			[
				'uri' => 'https://www.setasign.com/#5',
				'rect' => [110.6, 736.1, 122.25, 737.57],
			],
			[
				'uri' => 'https://demos.setasign.com/?some=(get paramert/with special signs',
				'rect' => [136.88, 731.4, 159.25, 733.15],
			],
			[
				'uri' => 'https://www.setasign.com/#3',
				'rect' => [96.57, 743.92, 205.5, 747.23],
				'quadPoints' => [198.85, 745.48, 205.5, 745.48, 205.5, 747.23, 198.85, 747.23, 96.57, 743.92, 102.3, 743.92, 102.3, 745.67, 96.57, 745.67]
			]
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	public function testMixOfImportedAndDefaultLinks()
	{
		$pdf = $this->getInstance();
		$pdf->AddPage();
		$pdf->SetFont('Helvetica', '', 12);
		$pdf->Write(10, 'Welcome to FPDI', 0, 'http://www.fpdi.de');
		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/links.pdf');
		$tplId = $pdf->importPage(2);
		$pdf->useTemplate($tplId, [
			'x' => 5,
			'y' => 20,
			'width' => 150,
			'height' => 100
		]);
		$pdf->Rect(5, 20, 150, 100);

		$pdf->SetY(120);
		$pdf->Write(10, '...and mPDF', 0, 'https://mpdf.github.io/');

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

		$expectedLinks = [
			[
				'uri' => 'http://www.fpdi.de',
				'rect' => [42.52, 774.982, 552.76, 789.382],
			],
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [54.74, 696.06, 371.59, 705.9],
				'quadPoints' => [352.81, 700.71, 371.59, 700.71, 371.59, 705.9, 352.81, 705.9, 54.74, 696.06, 134.92, 696.06, 134.92, 701.25, 54.74, 701.25]
			],
			[
				'uri' => 'https://mpdf.github.io/',
				'rect' => [42.52, 480.179, 552.76, 494.579],
			],
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);
	}

	public function testReuseOfImportedPageWithLinks()
	{
		$pdf = $this->getInstance();

		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/links.pdf');
		$tplId = $pdf->importPage(2, PageBoundaries::CROP_BOX, true, true);

		$pdf->AddPage('L');
		$size = $pdf->useTemplate($tplId, 0, 0, 140);
		$pdf->useTemplate($tplId, $size['width'] + 10, 0, 140);

		$pdf->AddPage('L');
		$pdf->useTemplate($tplId, 0, 0, 140);

		$pdf->AddPage('P');
		$pdf->useTemplate($tplId, 0, 0, 140);

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [37.86, 418.8, 333.59, 438.28],
				'quadPoints' => [316.06, 428.0, 333.59, 428.0, 333.59, 438.28, 316.06, 438.28, 37.86, 418.8, 112.7, 418.8, 112.7, 429.08, 37.86, 429.08]
			],
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [463.06, 418.8, 758.79, 438.28],
				'quadPoints' => [741.26, 428.0, 758.79, 428.0, 758.79, 438.28, 741.26, 438.28, 463.06, 418.8, 537.9, 418.8, 537.9, 429.08, 463.06, 429.08]
			],
		];

		$reader = new PdfReader(new PdfParser(StreamReader::createByString($pdfString)));
		$this->compareExpectedLinks(1, $expectedLinks, $reader);

		// page 2
		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [37.86, 418.8, 333.59, 438.28],
				'quadPoints' => [316.06, 428.0, 333.59, 428.0, 333.59, 438.28, 316.06, 438.28, 37.86, 418.8, 112.7, 418.8, 112.7, 429.08, 37.86, 429.08]
			]
		];

		$this->compareExpectedLinks(2, $expectedLinks, $reader);

		// page 3
		$expectedLinks = [
			[
				'uri' => 'https://www.setasign.com',
				'rect' => [37.86, 665.41, 333.59, 684.89],
				'quadPoints' => [316.06, 674.61, 333.59, 674.61, 333.59, 684.89, 316.06, 684.89, 37.86, 665.41, 112.7, 665.41, 112.7, 675.69, 37.86, 675.69]
			]
		];

		$this->compareExpectedLinks(3, $expectedLinks, $reader);
	}

	public function testImportOfRotatedPagesWithLinks()
	{
		$pdf = $this->getInstance();
		$pageCount = $pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/rotated-pages.pdf');
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$pdf->AddPage();
			$tplId = $pdf->importPage($pageNo, PageBoundaries::CROP_BOX, true, true);
			$pdf->useTemplate($tplId, ['adjustPageSize' => true]);
		}

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

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

	public function testImportOfDifferentPageBoundaries()
	{
		/* This behavior is questionable:
		 *   Currently we import all link annotations. Also annotations which are outside of a page boundary.
		 *   This may change in the future.
		 */
		$pdf = $this->getInstance();
		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/boxes.pdf');

		$artBox = $pdf->importPage(1, PageBoundaries::ART_BOX);
		$trimBox = $pdf->importPage(1, PageBoundaries::TRIM_BOX);
		$bleedBox = $pdf->importPage(1, PageBoundaries::BLEED_BOX);
		$cropBox = $pdf->importPage(1, PageBoundaries::CROP_BOX);

		$pdf->AddPage('L');
		$pdf->useTemplate($artBox, 0, 0, null, 160);
		$pdf->useTemplate($trimBox, 70, 0, null, 160);
		$pdf->useTemplate($bleedBox, 140, 0, null, 160);
		$pdf->useTemplate($cropBox, 210, 0, null, 160);

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

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

	public function testImportOfSpecialPageBoundaries()
	{
		$pdf = $this->getInstance();
		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/[-1000 -1000 -500 -500].pdf');

		$pdf->AddPage();
		$tplId = $pdf->importPage(1, PageBoundaries::CROP_BOX, true, true);
		$s = $pdf->useTemplate($tplId, 20, 10, 150);
		$pdf->Rect(20, 10, 150, $s['height']);

		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/[1000 500 -1000 -500]-R90.pdf');
		$tplId = $pdf->importPage(1, PageBoundaries::CROP_BOX, true, true);
		$s = $pdf->useTemplate($tplId, 20, 200, 50);
		$pdf->Rect(20, 200, 50, $s['height']);

		$pdfString = $this->save($pdf);
//        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

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

	public function testLinkInUtf16Encoding()
	{
		$pdf = $this->getInstance();
		$pdf->AddPage();
		// This file has its link in UTF-16BE saved.
		$pdf->setSourceFile(__DIR__ . '/../../data/pdfs/links/tuto6.pdf');
		$tplId = $pdf->importPage(2, PageBoundaries::CROP_BOX, true, true);
		$pdf->useTemplate($tplId);
		$pdfString = $this->save($pdf);
        file_put_contents(__DIR__ . '/test.pdf', $pdfString);

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
}
