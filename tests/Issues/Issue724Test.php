<?php

namespace Issues;

class Issue724Test extends \Mpdf\BaseMpdfTest
{

	protected function getTable()
	{
		$table = "<table>";
		$table .= "<thead><th>Sl</th><th>Name</th></thead>";
		$table .= "<tbody>";
		for ($i=1; $i<= 200; $i++) {
			$table .= "<tr><td>{$i}</td><td>Name #{$i}</td></tr>";
		}
		$table .= "</tbody>";
		$table .= "</table>";

		return $table;
	}

	public function testMissingMiwNotice()
	{
		$this->mpdf->WriteHTML($this->getTable());

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
