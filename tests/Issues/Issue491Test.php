<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue491Test extends \Mpdf\BaseMpdfTest
{

	public function htmlProvider()
	{
		return [
			[
				'<table>
					<tbody>
						<tr><td>4</td></tr>
						<tr><td>6</td></tr>
					</tbody>
					<tfoot>
						<tr>
							<td>#SUM#{colsum0}#SUM#</td>
						</tr>
					</tfoot>
				</table>',
				'10'
			], [
				'<table>
					<tbody>
						<tr><td>12,54</td></tr>
						<tr><td>0,12</td></tr>
					</tbody>
					<tfoot>
						<tr>
							<td>#SUM#{colsum2}#SUM#</td>
						</tr>
					</tfoot>
				</table>',
				'12.66'
			], [
				'<table>
					<tbody>
						<tr><td>1.999,39€</td></tr>
						<tr><td>3.214,01€</td></tr>
					</tbody>
					<tfoot>
						<tr>
							<td>#SUM#{colsum2}#SUM#</td>
						</tr>
					</tfoot>
				</table>',
				'5213.40'
			], [
				'<table>
					<tbody>
						<tr><td>1 215 951,21 €</td></tr>
						<tr><td>US$ 2,104,092.99</td></tr>
					</tbody>
					<tfoot>
						<tr>
							<td>#SUM#{colsum0}#SUM#</td>
						</tr>
					</tfoot>
				</table>',
				'3320044'
			]
		];
	}


	/**
	 * @dataProvider htmlProvider
	 */
	public function testColsumInt($html, $expected)
	{
		$this->mpdf->setCompression(false);
		$this->mpdf->WriteHtml($html);
		$out = $this->mpdf->Output('', 'S');
		$iMatchCnt = preg_match('/#SUM#(.*)#SUM#/', $out, $aMatches);
		$this->assertEquals(1, $iMatchCnt, "could not find colsum for '".$html."'");
		$this->assertEquals($expected, $aMatches[1]);
	}

}
