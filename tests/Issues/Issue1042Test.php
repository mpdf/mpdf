<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue1042Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedIndex()
	{
		$html = '
<table border="0" cellpadding="0" cellspacing="0" width="800">
	<tr>
		<td align=right colspan=2 width="*"><strong>The above statement was read and acceptance confirmed</strong></td>
	</tr>
</table>
';

		$this->mpdf->WriteHtml($html, 2);

		$out = $this->mpdf->Output('', 'S');
	}

}
