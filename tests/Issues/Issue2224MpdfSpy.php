<?php

namespace Issues;

class Issue2224MpdfSpy extends \Mpdf\Mpdf
{

	public $capturedTables = [];

	public function _tableWrite(&$table, $split = false, $startrow = 0, $startcol = 0, $splitpg = 0, $rety = 0)
	{
		// Capture the parsed cell data before the table gets rendered and cleared
		$this->capturedTables[] = $table;

		parent::_tableWrite($table, $split, $startrow, $startcol, $splitpg, $rety);
	}

}
