<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1056Test extends \Mpdf\BaseMpdfTest
{

	public function testValidCollationGroupRequire()
	{
		$html = '
<indexentry content="FOO" /><pagebreak /><INDEXINSERT COLLATION-GROUP="../../../FOOBAR" foo="bar">dsfsadf</INDEXINSERT>
';

		$this->mpdf->WriteHtml($html, 2);

		$out = $this->mpdf->Output('', 'S');
	}

	public function testInvalidCollationGroupRequire()
	{
		$html = '
<indexentry content="foo" /><pagebreak /><INDEXINSERT COLLATION-GROUP="Albanian_Albania" foo="foo">dsfsadf</INDEXINSERT>
';

		$this->mpdf->WriteHtml($html, 2);

		$out = $this->mpdf->Output('', 'S');
	}

}
