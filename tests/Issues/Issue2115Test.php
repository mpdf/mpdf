<?php

namespace Issues;

class Issue2115Test extends \Mpdf\BaseMpdfTest
{

	private $server;

	protected function set_up()
	{
		// Relative hrefs are resolved against a basepath built from the HTTP host,
		// which other tests may leave set, so run these without one
		$this->server = $_SERVER;
		unset($_SERVER['HTTP_HOST'], $_SERVER['SERVER_NAME']);

		parent::set_up();
	}

	protected function tear_down()
	{
		parent::tear_down();

		$_SERVER = $this->server;
	}

	/**
	 * @dataProvider externalProvider
	 */
	public function testLinkWithKnownSchemeAndNoDotIsExternal($href)
	{
		$this->mpdf->WriteHTML('<a href="' . $href . '">link</a>');

		$this->assertSame([$href], $this->linkTargets());
	}

	/**
	 * @dataProvider externalProvider
	 */
	public function testImageLinkWithKnownSchemeAndNoDotIsExternal($href)
	{
		$this->mpdf->WriteHTML('<a href="' . $href . '"><img src="' . __DIR__ . '/../data/img/bayeux2.jpg" width="20"></a>');

		$this->assertSame([$href], $this->linkTargets());
	}

	/**
	 * @dataProvider externalProvider
	 */
	public function testMeterLinkWithKnownSchemeAndNoDotIsExternal($href)
	{
		$this->mpdf->WriteHTML('<a href="' . $href . '"><meter value="0.5"></meter></a>');

		$this->assertSame([$href], $this->linkTargets());
	}

	public function externalProvider()
	{
		return [
			['tel:0123456789'],
			['tel:+201234567890'],
			['sms:0123456789'],
			['http://localhost/report'],
			['https://localhost/report'],
			['mailto:user@localhost'],
			['TEL:0123456789'],
			['HTTP://localhost'],
		];
	}

	/**
	 * @dataProvider internalProvider
	 */
	public function testOtherDotlessLinksStayInternal($html)
	{
		$this->mpdf->WriteHTML($html);

		$targets = $this->linkTargets();

		$this->assertCount(1, $targets);
		$this->assertIsInt($targets[0]);
	}

	public function internalProvider()
	{
		return [
			['<a name="top"></a><a href="#top">top</a>'],
			['<a name="top"></a><a href="top">top</a>'],
			['<a name="chapter:1"></a><a href="chapter:1">chapter</a>'],
			['<a href="javascript:alert(1)">js</a>'],
			['<a href="C:\file">file</a>'],
			['<a href="c:foo">file</a>'],
		];
	}

	public function testTelLinkIsWrittenAsUriAction()
	{
		$this->mpdf->WriteHTML('<a href="tel:0123456789">call</a>');

		$pdf = $this->mpdf->Output('', 'S');

		$this->assertNotFalse(strpos($pdf, '/A <</S /URI /URI (tel:0123456789)>>'));
		$this->assertFalse(strpos($pdf, '/Dest'));
	}

	private function linkTargets()
	{
		$targets = [];

		foreach ($this->mpdf->PageLinks as $links) {
			foreach ($links as $link) {
				$targets[] = $link[4];
			}
		}

		return $targets;
	}

}
