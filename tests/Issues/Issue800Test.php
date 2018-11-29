<?php

namespace Issues;

class Issue800Test extends \PHPUnit_Framework_TestCase
{

	public function testNoNoticeWithNestedTablesAndBorders()
	{
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('
		<style>
			table {
				border-collapse: collapse;
			}
		</style>
		
		<table>
			<tr>
				<td>
					<table border="1">
						<tr>
							<td>
								Test
							</td>
						</tr>
					</table>
				</td>
			</tr>
		
			<tr>
				<td>
					<table border="1">
						<tr>
							<td>
								Test
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>');

		$output = $mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
