<?php

namespace Issues;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PagenoTextAlignTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * Extract x-coordinates of text positioning commands (Td) from a PDF page buffer.
	 */
	private function extractTextXPositions($pageContent)
	{
		$positions = [];
		// Match "X Y Td" text positioning operators in the PDF content stream
		if (preg_match_all('/([0-9.]+)\s+[0-9.]+\s+Td/', $pageContent, $matches)) {
			foreach ($matches[1] as $x) {
				$positions[] = round((float) $x, 2);
			}
		}
		return $positions;
	}

	/**
	 * Test that {PAGENO} in right-aligned text produces the same right edge
	 * as equivalent static text.
	 */
	public function testPagenoRightAlignmentMatchesStaticText()
	{
		// Render with static text
		$mpdf1 = new Mpdf(['mode' => 'c']);
		$mpdf1->WriteHTML(
			'<div style="text-align: right; width: 60mm; border: 1px solid red;">Page 1 of 1</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$staticPage = $mpdf1->pages[1];

		// Render with placeholders
		$mpdf2 = new Mpdf(['mode' => 'c']);
		$mpdf2->WriteHTML(
			'<div style="text-align: right; width: 60mm; border: 1px solid blue;">Page {PAGENO} of {nb}</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$placeholderPage = $mpdf2->pages[1];

		// Extract text x-positions from both page buffers
		$staticPositions = $this->extractTextXPositions($staticPage);
		$placeholderPositions = $this->extractTextXPositions($placeholderPage);

		$this->assertNotEmpty($staticPositions, 'Static text should have text positioning commands');
		$this->assertNotEmpty($placeholderPositions, 'Placeholder text should have text positioning commands');

		// The first text Td x-position should be close (within 1pt tolerance)
		// Both are right-aligned in a 60mm container, so x-positions should nearly match
		// (small difference is acceptable due to different actual text widths)
		$staticX = $staticPositions[0];
		$placeholderX = $placeholderPositions[0];

		// With the bug, the placeholder version has a much lower x (shifted left by ~67pt / 23.6mm)
		// After the fix, the difference should be small (just the natural width difference between
		// "Page 1 of 1" and "Page 1 of 1" after replacement — which is zero for a 1-page doc)
		$this->assertEqualsWithDelta(
			$staticX,
			$placeholderX,
			5.0, // 5pt tolerance (< 2mm)
			"Right-aligned text with {PAGENO}/{nb} should have similar x-position as static text. " .
			"Static x={$staticX}, Placeholder x={$placeholderX}, diff=" . abs($staticX - $placeholderX)
		);
	}

	/**
	 * Test that {PAGENO} in center-aligned text is similarly positioned as static text.
	 */
	public function testPagenoCenterAlignmentMatchesStaticText()
	{
		$mpdf1 = new Mpdf(['mode' => 'c']);
		$mpdf1->WriteHTML(
			'<div style="text-align: center; width: 60mm;">Page 1 of 1</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$staticPage = $mpdf1->pages[1];

		$mpdf2 = new Mpdf(['mode' => 'c']);
		$mpdf2->WriteHTML(
			'<div style="text-align: center; width: 60mm;">Page {PAGENO} of {nb}</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$placeholderPage = $mpdf2->pages[1];

		$staticPositions = $this->extractTextXPositions($staticPage);
		$placeholderPositions = $this->extractTextXPositions($placeholderPage);

		$this->assertNotEmpty($staticPositions);
		$this->assertNotEmpty($placeholderPositions);

		$this->assertEqualsWithDelta(
			$staticPositions[0],
			$placeholderPositions[0],
			5.0,
			"Center-aligned text with {PAGENO}/{nb} should have similar x-position as static text"
		);
	}

	/**
	 * Test that the PDF output is valid when using placeholders with right-align.
	 */
	public function testPagenoRightAlignProducesValidPdf()
	{
		$mpdf = new Mpdf(['mode' => 'c']);
		$mpdf->WriteHTML(
			'<div style="text-align: right;">Page {PAGENO} of {nb}</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$output = $mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

	/**
	 * Test that left-aligned text with placeholders is unaffected (regression check).
	 */
	public function testPagenoLeftAlignUnaffected()
	{
		$mpdf1 = new Mpdf(['mode' => 'c']);
		$mpdf1->WriteHTML(
			'<div style="text-align: left; width: 60mm;">Page 1 of 1</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$staticPage = $mpdf1->pages[1];

		$mpdf2 = new Mpdf(['mode' => 'c']);
		$mpdf2->WriteHTML(
			'<div style="text-align: left; width: 60mm;">Page {PAGENO} of {nb}</div>',
			\Mpdf\HTMLParserMode::HTML_BODY
		);
		$placeholderPage = $mpdf2->pages[1];

		$staticPositions = $this->extractTextXPositions($staticPage);
		$placeholderPositions = $this->extractTextXPositions($placeholderPage);

		$this->assertNotEmpty($staticPositions);
		$this->assertNotEmpty($placeholderPositions);

		// Left-aligned text should start at the same position regardless of content
		$this->assertEqualsWithDelta(
			$staticPositions[0],
			$placeholderPositions[0],
			0.5,
			"Left-aligned text should not be affected by placeholder width"
		);
	}

}
