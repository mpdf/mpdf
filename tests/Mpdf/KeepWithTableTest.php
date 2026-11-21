<?php

namespace Mpdf;

use Mpdf\Output\Destination;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfType;
use setasign\Fpdi\PdfReader\PdfReader;

class KeepWithTableTest extends BaseMpdfTest
{

	/**
	 * @dataProvider linkTypesProvider
	 */
	public function testEarlierLinkDestinationsRemainInPlaceWhenLaterTablesMove($headingHtml, $tableOfContents)
	{
		$this->mpdf = new Mpdf([
			'mode' => 'c',
			'default_font' => 'helvetica',
			'margin_top' => 20,
			'use_kwt' => true,
			'h2toc' => $tableOfContents ? ['H2' => 0] : [],
		]);

		if ($tableOfContents) {
			$this->mpdf->WriteHTML('<tocpagebreak links="on" />');
		} else {
			$this->mpdf->WriteHTML('<p><a href="#FIRSTHEADING">First</a> '
				. '<a href="#SECONDHEADING">Second</a> <a href="#THIRDHEADING">Third</a></p><pagebreak />');
		}

		$this->mpdf->WriteHTML(sprintf($headingHtml, 'FIRSTHEADING')
			. '<table><tr><td>First table</td></tr></table>');

		foreach (['SECONDHEADING', 'THIRDHEADING'] as $heading) {
			$this->mpdf->WriteHTML('<pagebreak /><p>Preceding content</p>');
			$this->mpdf->SetY(270);
			$this->mpdf->WriteHTML(sprintf($headingHtml, $heading)
				. '<table><tr><td style="height:40mm">Later table</td></tr></table>');
		}

		$export = $this->mpdf->Output('', Destination::STRING_RETURN);
		$reader = new PdfReader(new PdfParser(StreamReader::createByString($export)));
		$parser = $reader->getParser();
		$annotations = PdfType::resolve($reader->getPage(1)->getAttribute('Annots'), $parser)->value;
		$linksPerHeading = $tableOfContents ? 2 : 1;

		$this->assertCount(3 * $linksPerHeading, $annotations);

		foreach ([2 => 'FIRSTHEADING', 4 => 'SECONDHEADING', 6 => 'THIRDHEADING'] as $pageNumber => $heading) {
			$page = $reader->getPage($pageNumber);
			$this->assertStringContainsString($heading, $page->getContentStream());

			foreach (array_splice($annotations, 0, $linksPerHeading) as $annotation) {
				$link = PdfType::resolve($annotation, $parser);
				$destination = $link->value['Dest']->value;

				$this->assertSame($page->getPageObject()->objectNumber, $destination[0]->value);
				$this->assertEqualsWithDelta((297 - 20) * Mpdf::SCALE, $destination[3]->value, 20);
			}
		}
	}

	public function linkTypesProvider()
	{
		return [
			'automatic TOC entries' => ['<h2>%1$s</h2>', true],
			'explicit TOC entries' => ['<div keep-with-table="1"><tocentry content="%1$s" />%1$s</div>', true],
			'named anchors' => ['<div keep-with-table="1"><a name="%1$s"></a>%1$s</div>', false],
		];
	}

}
