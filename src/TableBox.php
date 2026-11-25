<?php

namespace Mpdf;

/**
 * One table box. Built by Tag\Table::open(), filled by Tr/Td/THead/TFoot,
 * laid out by Mpdf::_tableColumnWidth/_tableWidth/_tableHeight/_tableWrite.
 *
 * ArrayAccess keeps the legacy $table['key'] call sites working during migration.
 */
class TableBox implements \ArrayAccess
{

	/** Legacy array keys that are not valid property names */
	const KEYS = [
		'thead-underline' => 'theadUnderline',
		'background-image' => 'backgroundImage',
		'trbackground-images' => 'trBackgroundImages',
		'pagebreak-before' => 'pagebreakBefore',
		'trborder-left' => 'trborderLeft',
		'trborder-right' => 'trborderRight',
		'trborder-top' => 'trborderTop',
		'trborder-bottom' => 'trborderBottom',
	];

	// --- identity / nesting ---------------------------------------------

	/** @var int nesting depth, 1 = top level */
	public $level;

	/** @var int ordinal within its level (Mpdf::$tbctr[$level]) */
	public $levelid;

	/** @var int[]|null [row, col, parent levelid]; null at level 1 */
	public $nestedpos;

	/** @var string 'ltr'|'rtl' */
	public $direction = 'ltr';

	// --- parent parser state parked here while a nested table is parsed --

	public $baseProperties;

	public $currrow;

	public $currcol;

	/** @var array[] row => col => cell array (snapshot of Mpdf::$cell) */
	public $cells = [];

	// --- grid ------------------------------------------------------------

	public $nr = 0;

	public $nc = 0;

	/** @var array[] col => ['miw' => , 'maw' => , 'w' => , 'wpercent' => ] */
	public $wc = [];

	/** @var float[] row => height */
	public $hr = [];

	/** @var float[]|null col => text length; null until measured (readers isset() it) */
	public $l;

	public $tl = 0;

	public $miw = 0;

	public $maw = 0;

	/** @var float|null null until CSS width or _tableWidth sets it (readers isset() it) */
	public $w;

	public $h = 0;

	public $wpercent = 0;

	/** @var float[] col => colspan width correction */
	public $csp = [];

	/** @var int[] col => page when overflow:visible splits columns */
	public $colPg = [];

	// --- alignment / text -----------------------------------------------

	/** @var false|string 'L'|'R'|'C' */
	public $a = false;

	public $va = false;

	public $txta = false;

	public $cellLineHeight;

	public $cellLineStackingStrategy = 'inline-line-height';

	public $cellLineStackingShift = 'consider-shifts';

	/** @var false|array col => ['maxs0' => , 'maxs1' => ] */
	public $decimal_align = false;

	// --- box ---------------------------------------------------------------

	public $margin = ['L' => false, 'R' => false, 'T' => false, 'B' => false];

	public $padding = ['L' => false, 'R' => false, 'T' => false, 'B' => false];

	/** @var false|int Border::* bitmask */
	public $border = false;

	public $border_details = [
		'L' => ['w' => 0, 'style' => ''],
		'R' => ['w' => 0, 'style' => ''],
		'T' => ['w' => 0, 'style' => ''],
		'B' => ['w' => 0, 'style' => ''],
	];

	public $max_cell_border_width = ['L' => 0, 'R' => 0, 'T' => 0, 'B' => 0];

	public $borders_separate = false;

	public $border_spacing_H = false;

	public $border_spacing_V = false;

	/** @var array|null ['border' => , 'border_details' => ]; only when Mpdf::$simpleTables */
	public $simple;

	public $cell_padding = false;

	public $topntail = false;

	public $theadUnderline = false;

	/** @var string ''|'hide'|'show' */
	public $empty_cells = '';

	/** @var string|null 'hidden'|'wrap'|'visible'|'auto'; unset() by Table when not allowed */
	public $overflow;

	public $page_break_after;

	// --- background -------------------------------------------------------

	/** @var false|array -1 => table colour, row => row colour */
	public $bgcolor = false;

	public $gradient;

	public $backgroundImage;

	public $trgradients = [];

	public $trBackgroundImages = [];

	// --- rows ---------------------------------------------------------------

	/** @var true[] row => true */
	public $is_thead = [];

	/** @var true[] row => true */
	public $is_tfoot = [];

	public $headernrows = 0;

	public $footernrows = 0;

	/** @var string[] row => 'avoid' */
	public $pagebreakBefore = [];

	public $trborderLeft = [];

	public $trborderRight = [];

	public $trborderTop = [];

	public $trborderBottom = [];

	public function __construct($level, $levelid, $direction = 'ltr')
	{
		$this->level = $level;
		$this->levelid = $levelid;
		$this->direction = $direction;
	}

	public function isNested()
	{
		return $this->level > 1;
	}

	public function isRtl()
	{
		return $this->direction === 'rtl';
	}

	/**
	 * @return int|null levelid of the table this one is nested in
	 */
	public function parentId()
	{
		return $this->nestedpos ? $this->nestedpos[2] : null;
	}

	/**
	 * Space one side of the grid takes outside the cells: margin plus half the outer border
	 *
	 * @param string $side 'L'|'R'|'T'|'B'
	 */
	public function outerWidth($side)
	{
		$border = $this->borders_separate ? $this->border_details[$side]['w'] : $this->max_cell_border_width[$side];
		return $this->margin[$side] + $border / 2;
	}

	public function headRowCount()
	{
		return count($this->is_thead);
	}

	public function footRowCount()
	{
		return count($this->is_tfoot);
	}

	public function ensureColumns($n)
	{
		$this->nc = max($this->nc, $n);
	}

	/**
	 * @param int $row -1 for the table itself
	 */
	public function setBackgroundColor($row, $colour)
	{
		if ($this->bgcolor === false) {
			$this->bgcolor = [];
		}
		$this->bgcolor[$row] = $colour;
	}

	/**
	 * Widen the decimal-align slots of $col to fit $s0 (before the point) and $s1 (after)
	 */
	public function trackDecimalAlign($col, $s0, $s1)
	{
		if ($this->decimal_align === false) {
			$this->decimal_align = [];
		}
		$d = isset($this->decimal_align[$col]) ? $this->decimal_align[$col] : ['maxs0' => 0, 'maxs1' => 0];
		$this->decimal_align[$col] = ['maxs0' => max($d['maxs0'], $s0), 'maxs1' => max($d['maxs1'], $s1)];
	}

	// --- legacy array bridge ------------------------------------------------

	#[\ReturnTypeWillChange]
	public function offsetExists($key)
	{
		return isset($this->{$this->prop($key)});
	}

	/**
	 * Returns by reference so $table['wc'][$i]['miw'] = ... still works
	 */
	#[\ReturnTypeWillChange]
	public function &offsetGet($key)
	{
		return $this->{$this->prop($key)};
	}

	#[\ReturnTypeWillChange]
	public function offsetSet($key, $value)
	{
		$this->{$this->prop($key)} = $value;
	}

	#[\ReturnTypeWillChange]
	public function offsetUnset($key)
	{
		$this->{$this->prop($key)} = null;
	}

	private function prop($key)
	{
		$prop = array_key_exists($key, self::KEYS) ? self::KEYS[$key] : $key;
		if (!property_exists($this, $prop)) {
			throw new MpdfException(sprintf('Unknown table property "%s"', $key));
		}
		return $prop;
	}
}
