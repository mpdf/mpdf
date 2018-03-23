<?php

namespace Issues;

class Issue654Test extends \Mpdf\BaseMpdfTest
{
	public function testMinWidthUndefined()
	{
		$html = '<table cellpadding="0" cellspacing="0" width="1500">
			<tr>
				<td width="750">
					<table cellspacing="0" width="750">
						<tr>
							<td width="750">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
		$this->mpdf->WriteHTML($html);

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
