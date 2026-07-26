<?php

namespace Mpdf;

class AdjustHtmlTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	protected function set_up()
	{
		parent::set_up();

		$this->mpdf = new Mpdf(['use_kwt' => true]);
	}

	public function testAdjustHtmlWithLongStringWithoutHeadingFollowedByTable()
	{
		$html = file_get_contents(__DIR__.'/../data/html/long-string-without-kwt-match.html');
		$adjustedHtml = $this->mpdf->AdjustHTML($html);

		$this->assertNotEmpty($adjustedHtml);
	}

	public function testAdjustHtmlWithLongStringWithHeadingFollowedByTable()
	{
		$html = file_get_contents(__DIR__.'/../data/html/long-string-with-kwt-match.html');
		$adjustedHtml = $this->mpdf->AdjustHTML($html);

		$this->assertNotEmpty($adjustedHtml);
		$this->assertStringContainsString('<h1 keep-with-table="1">', $adjustedHtml);
		$this->assertStringContainsString('<h2 class="name" keep-with-table="1">', $adjustedHtml);
		$this->assertStringContainsString('<h3 keep-with-table="1">', $adjustedHtml);
		$this->assertStringContainsString('<h4 style="color: #CCC" keep-with-table="1">', $adjustedHtml);
		$this->assertStringContainsString('<h5 align="center" style="color: red" keep-with-table="1">', $adjustedHtml);
		$this->assertStringContainsString('<h6 class="name" keep-with-table="1">', $adjustedHtml);
	}

	public function testAdjustHtmlPreservesMultipleSpacesInDoubleQuotedAttributes()
	{
		$html = '<img src="/tmp/Photo  Name.jpg" width="50"><a href="/path/to/my  file.pdf">link</a>';
		$adjustedHtml = $this->mpdf->AdjustHTML($html);

		$this->assertStringContainsString('Photo  Name.jpg', $adjustedHtml);
		$this->assertStringContainsString('my  file.pdf', $adjustedHtml);
		$this->assertStringNotContainsString('Photo Name.jpg', $adjustedHtml);
	}

	public function testAdjustHtmlPreservesMultipleSpacesInSingleQuotedAttributes()
	{
		$html = "<img src='/tmp/Photo  Name.jpg' width='50'>";
		$adjustedHtml = $this->mpdf->AdjustHTML($html);

		$this->assertStringContainsString('Photo  Name.jpg', $adjustedHtml);
		$this->assertStringNotContainsString('Photo Name.jpg', $adjustedHtml);
	}

	public function testAdjustHtmlStillCollapsesSpacesInTextContent()
	{
		$html = '<p>Hello    world</p>';
		$adjustedHtml = $this->mpdf->AdjustHTML($html);

		$this->assertStringContainsString('Hello world', $adjustedHtml);
		$this->assertStringNotContainsString('Hello    world', $adjustedHtml);
	}
}
