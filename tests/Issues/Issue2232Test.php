<?php

namespace Issues;

class Issue2232Test extends \Mpdf\BaseMpdfTest
{

	public function testOpaqueSvgIsWrittenWithoutTransparencyGroup()
	{
		$output = $this->getOutput('<circle cx="50" cy="50" r="50" fill="black"/>');

		$this->assertSame(1, preg_match_all('#/Subtype /Form\s*(/Group \d+ 0 R)?#', $output, $matches));
		$this->assertSame('', $matches[1][0]);
	}

	/**
	 * @dataProvider translucentSvgProvider
	 */
	public function testTranslucentSvgIsWrittenWithTransparencyGroup($svg)
	{
		$output = $this->getOutput($svg);

		$this->assertSame(1, preg_match('#/Subtype /Form\s*/Group (\d+) 0 R#', $output, $matches));
		$this->assertSame(1, preg_match('#\n' . $matches[1] . ' 0 obj\s*<</Type /Group\s*/S /Transparency\s*>>#', $output));
	}

	public function translucentSvgProvider()
	{
		return [
			'fill-opacity' => ['<circle cx="50" cy="50" r="50" fill="black" fill-opacity="0.5"/>'],
			'stroke-opacity' => ['<circle cx="50" cy="50" r="40" fill="none" stroke="black" stroke-width="5" stroke-opacity="0.5"/>'],
			'opacity' => ['<circle cx="50" cy="50" r="50" fill="black" style="opacity: 0.5"/>'],
			'text opacity' => ['<text x="10" y="50" fill-opacity="0.5">Text</text>'],
			'gradient stop-opacity' => ['<defs><linearGradient id="g"><stop offset="0" stop-color="black" stop-opacity="0.2"/><stop offset="1" stop-color="black"/></linearGradient></defs><rect width="100" height="100" fill="url(#g)"/>'],
		];
	}

	private function getOutput($svgBody)
	{
		$this->mpdf->SetCompression(false);
		$this->mpdf->WriteHTML('<p>Before the SVG</p>'
			. '<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">' . $svgBody . '</svg>'
			. '<table border="1"><tr><td>After the SVG</td></tr></table>');

		return $this->mpdf->OutputBinaryData();
	}

}
