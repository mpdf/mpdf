<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue570Test extends \Mpdf\BaseMpdfTest
{

	public function testPageBreakInsideAvoidBackgrounds()
	{
		$before = str_repeat('<p>Before</p>', 20);
		$after = str_repeat('<p>After</p>', 10);

		$html = "
			$before

			<table>
				<tr>
					<td>Normal table cell</td>
				</tr>
			</table>

			<div style=\"page-break-inside:avoid\">
				<table>
					<tr>
						<td bgcolor=\"red\">Red table cell</td>
					</tr>
				</table>

				$after
			</div>";

		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');

		$num_red_boxes = substr_count($out, 'q 1.000 0.000 0.000 rg');

		$this->assertEquals(1, $num_red_boxes);
	}

}
