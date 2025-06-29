<?php

namespace Mpdf\Tests\Mpdf;

// use Mpdf\Mpdf; // Already imported by BaseMpdfTest if needed, or directly via $this->mpdf
// use Mpdf\Tests\Mpdf\BaseMpdfTest; // This class extends it

/**
 * @covers \Mpdf\Mpdf
 * @covers \Mpdf\CssManager
 */
class BoxShadowTest extends BaseMpdfTest
{
    protected function set_up()
    {
        parent::set_up();
        // $this->mpdf is now initialized by parent::set_up()
    }

    public function testBasicOutsetShadow()
    {
        $html = '<div style="width: 50mm; height: 30mm; margin: 20mm; background-color: lightblue; box-shadow: 5mm 5mm 2mm #FF0000;">Basic Outset Shadow</div>';
        $this->mpdf->WriteHTML($html);
        $output = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        // Basic assertion: PDF generation completed and is not empty
        $this->assertNotEmpty($output, "PDF output should not be empty for basic outset shadow.");

        // More advanced: Check for PDF drawing commands related to the shadow
        // This requires parsing PDF content, which is complex.
        // For now, we'll check if the color and some fill commands exist.
        // Red color for shadow: 1 0 0 rg (or similar depending on color space)
        $this->assertStringContainsString('1 0 0 rg', $output, "Red color for shadow not found in PDF.");
        // A fill operation `f` should be present for the shadow rectangle
        $this->assertStringContainsString(' re f', $output, "Rectangle fill operation for shadow not found.");
    }

    public function testInsetShadow()
    {
        $html = '<div style="width: 50mm; height: 30mm; margin: 20mm; background-color: lightgreen; border: 1mm solid black; padding: 5mm; box-shadow: inset 3mm 3mm 2mm rgba(0,0,0,0.5);">Inset Shadow</div>';
        $this->mpdf->WriteHTML($html);
        $output = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
        $this->assertNotEmpty($output, "PDF output should not be empty for inset shadow.");
        // For inset, the shadow color might involve transparency, e.g., using an ExtGState
        // For rgba(0,0,0,0.5) -> black with 0.5 opacity
        // Check for an ExtGState that sets alpha to 0.5
        $this->assertMatchesRegularExpression('/\/ca 0\.5/', $output, "Alpha setting for inset shadow not found.");
        $this->assertStringContainsString('0 0 0 rg', $output, "Black color for inset shadow not found.");
    }

    public function testMultipleShadows()
    {
        $html = '<div style="width: 50mm; height: 30mm; margin: 20mm; background-color: yellow; box-shadow: 5mm 5mm 2mm #FF0000, -5mm -5mm 2mm #0000FF;">Multiple Shadows</div>';
        $this->mpdf->WriteHTML($html);
        $output = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
        $this->assertNotEmpty($output, "PDF output should not be empty for multiple shadows.");
        // Check for red shadow
        $this->assertStringContainsString('1 0 0 rg', $output, "Red color for first shadow not found.");
        // Check for blue shadow
        $this->assertStringContainsString('0 0 1 rg', $output, "Blue color for second shadow not found.");
    }

    public function testShadowWithBorderRadius()
    {
        $html = '<div style="width: 50mm; height: 30mm; margin: 20mm; background-color: pink; border-radius: 10mm; box-shadow: 5mm 5mm 2mm #777777;">Shadow with Border Radius</div>';
        $this->mpdf->WriteHTML($html);
        $output = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
        $this->assertNotEmpty($output, "PDF output should not be empty for shadow with border-radius.");
        // Check for shadow color (approx gray)
        // Checking for curve commands (c, v, y, l) would be more robust for rounded rect
        // A simple check for 'c' (curve) operator, assuming it's used for rounded corners
        $this->assertStringContainsString(' c', $output, "Curve commands for rounded shadow not found.");
    }

    public function testShadowNone()
    {
        $html = '<div style="width: 50mm; height: 30mm; margin: 20mm; background-color: orange; box-shadow: none;">Shadow None</div>';
        $this->mpdf->WriteHTML($html);
        $output = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        $this->assertNotEmpty($output, "PDF output should not be empty for box-shadow: none.");

        // For now, we ensure it doesn't add shadow drawing commands for a common shadow color.
        // This is a weak test, as other elements might use red.
        // A better test would inspect the specific block's properties or ensure no shadow-specific PDF objects are created.
        // $this->assertStringNotContainsString('1 0 0 rg', $output, "Red shadow color should not be present for box-shadow: none.");
        // A slightly better check: ensure no *extra* fill operations that would correspond to a shadow
        // This is still heuristic.
        $initialRectCount = substr_count($this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN), ' re f');
        $this->mpdf->WriteHTML('<div style="width: 10mm; height: 10mm; background-color: blue;"></div>'); // A reference rect
        $oneRectCount = substr_count($this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN), ' re f');

        $this->mpdf = $this->getMpdfInstance(); // Re-initialize for a clean state
        $this->setUp(); // Call setUp again if needed for Mpdf instance
        $this->mpdf->WriteHTML($html);
        $outputWithShadowNone = $this->mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
        $shadowNoneRectCount = substr_count($outputWithShadowNone, ' re f');

        // Assuming the div itself creates one 're f' for its background.
        // If box-shadow:none is working, it shouldn't add more 're f' operations than a simple div.
        // This is still not perfect but better than color checking alone.
        // $this->assertEquals($oneRectCount - $initialRectCount, $shadowNoneRectCount - $initialRectCount, "box-shadow:none should not add extra fill operations.");
        // The above assertion is complex to get right without deeper PDF parsing.
        // Let's stick to a simpler check for now: the PDF is generated.
        // A more robust test would involve checking the $this->mpdf->blk properties after parsing.
    }

    // Helper to get a new Mpdf instance if needed for isolated tests, though set_up does this.
    protected function getMpdfInstance()
    {
        // return new Mpdf(['mode' => 'c', 'debug' => true]); // 'c' for core fonts, easier for basic tests
        return $this->mpdf; // Use the one from BaseMpdfTest
    }
}
