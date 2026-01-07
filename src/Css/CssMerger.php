<?php

namespace Mpdf\Css;

use Mpdf\Color\ColorConverter;
use Mpdf\CssManager;
use Mpdf\Exception\InvalidArgumentException;
use Mpdf\Mpdf;
use Mpdf\Utils\Arrays;

class CssMerger
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var CssManager
	 */
	private $cssManager;

	/**
	 * @var \Mpdf\Css\NormalizeProperties
	 */
	private $normalizeProperties;

	/**
	 * @var \Mpdf\Css\InlineStyleParser
	 */
	private $inlineStyleParser;

	/**
	 * @var \Mpdf\Css\SelectorParser
	 */
	private $selectorParser;

	/**
	 * @var \Mpdf\Css\InlinePropertyConverter
	 */
	private $inlinePropertyConverter;

	/**
	 * @var \Mpdf\Color\ColorConverter
	 */
	private $colorConverter;

	/**
	 * @var array
	 */
	private $cssProperties = [];

	/**
	 * @var \Mpdf\Css\BorderMerger
	 */
	private $borderMerger;

	/**
	 * @var bool When true, state outside this object will be modified
	 * @internal self::previewBlockCss() uses this property to look ahead without affecting state
	 */
	private $sideEffects = true;

	public function __construct(
		Mpdf $mpdf,
		NormalizeProperties $normalizeProperties,
		InlineStyleParser $inlineStyleParser,
		SelectorParser $selectorParser,
		InlinePropertyConverter $inlinePropertyConverter,
		ColorConverter $colorConverter,
		BorderMerger $borderMerger
	) {
		$this->mpdf = $mpdf;
		$this->normalizeProperties = $normalizeProperties;
		$this->inlineStyleParser = $inlineStyleParser;
		$this->selectorParser = $selectorParser;
		$this->inlinePropertyConverter = $inlinePropertyConverter;
		$this->colorConverter = $colorConverter;
		$this->borderMerger = $borderMerger;
	}

	/**
	 * Make the CssManager state available to the merger
	 *
	 * @param CssManager $cssManager
	 * @return void
	 *
	 * @internal Temporary method. Required until the global CssManager properties/state is refactored
	 */
	public function setCssManager(CssManager $cssManager)
	{
		$this->cssManager = $cssManager;
	}

	/**
	 * Merge CSS properties for an HTML element.
	 *
	 * Main method for applying CSS to an element. Combines CSS from multiple sources
	 * including default styles, stylesheets, inline styles, and inherited properties.
	 * Handles inheritance type (BLOCK, INLINE, TABLE, TOPTABLE) and applies
	 * appropriate cascading rules.
	 *
	 * @param string $inherit Inheritance context (BLOCK, INLINE, TABLE, TOPTABLE)
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes including CLASS, ID, STYLE
	 * @return array Merged CSS properties array
	 */
	public function merge($inherit, $tag, $attr)
	{
		$this->cssProperties = [];

		$attr = is_array($attr) ? $attr : [];

		$classes = [];
		if (isset($attr['CLASS'])) {
			// filter out classes that don't have any CSS applied, which reduces likelyhood of O(2^N) memory issue
			$rawClasses = preg_split('/\s+/', $attr['CLASS']);
			$rawClasses = array_intersect($rawClasses, $this->cssManager->getUsedClassNames());
			$maxDepth = $this->cssManager->getMaxClassDepth();

			$classes = array_map(function ($combination) {
				return implode('.', $combination);
			}, Arrays::allUniqueSortedCombinations($rawClasses, $maxDepth));
		}

		if (!isset($attr['ID'])) {
			$attr['ID'] = '';
		}

		$languageCode = '';
		if (!isset($attr['LANG'])) {
			$attr['LANG'] = '';
		} else {
			$attr['LANG'] = strtolower($attr['LANG']);
			if (strlen($attr['LANG']) === 5) {
				$languageCode = substr($attr['LANG'], 0, 2);
			}
		}

		$this->mergeTableCascadingCss($inherit, $tag, $attr, $classes);
		$this->mergeBlockCascadingCss($inherit, $tag, $attr, $classes);
		$this->mergeInlineAttributes($tag, $attr);
		$this->mergeDefaultCss($tag);
		$this->mergeTableSpecificCss($tag, $attr);
		$this->mergeStylesheetSelectors($tag, $attr, $classes, $languageCode);
		$this->mergeTagSpecificSelectors($tag, $attr, $classes, $languageCode);
		$this->mergeDescendantSelectors($inherit, $tag, $attr, $classes);
		$this->mergeInlineStyle($tag, $attr);

		return $this->cssProperties;
	}

	/**
	 * Preview block-level CSS without creating the block.
	 *
	 * Looks ahead to determine what CSS would be applied to a block element
	 * without actually creating it. Used for planning layout and spacing.
	 *
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes array
	 * @return array CSS properties that would be applied
	 */
	public function previewBlockCss($tag, $attr)
	{
		$this->sideEffects = false;
		$results = $this->merge('BLOCK', $tag, $attr);
		$this->sideEffects = true;

		return $results;
	}

	/**
	 * Merge table cascading CSS.
	 *
	 * Handles inheritance and cascading of CSS properties for tables.
	 *
	 * @param string $inherit Inheritance type (TOPTABLE, TABLE, BLOCK)
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @return void
	 */
	protected function mergeTableCascadingCss($inherit, $tag, $attr, $classes)
	{
		if (! in_array($inherit, [ 'TOPTABLE', 'TABLE' ], true) || !$this->sideEffects) {
			return;
		}

		if ($inherit === 'TOPTABLE') {
			// Save Cascading CSS e.g. "div.topic p" at this block level
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['cascadeCSS'])) {
				$this->cssManager->tablecascadeCSS[0] = $this->mpdf->blk[$this->mpdf->blklvl]['cascadeCSS'];
			} else {
				$this->cssManager->tablecascadeCSS[0] = $this->cssManager->cascadeCSS;
			}
		}

		// Cascade everything from last level that is not an actual property, or defined by current tag/attributes
		if (isset($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1]) && is_array($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1])) {
			foreach ($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1] as $k => $v) {
				$this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl][$k] = $v;
			}
		}

		$this->mergeFullCssRules(
			$this->cssManager->cascadeCSS,
			$this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl],
			$tag,
			$classes,
			$attr['ID'],
			$attr['LANG']
		);

		// Cascading forward CSS
		if (isset($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1])) {
			$this->mergeFullCssRules(
				$this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1],
				$this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl],
				$tag,
				$classes,
				$attr['ID'],
				$attr['LANG']
			);
		}
	}

	/**
	 * Merge block cascading CSS.
	 *
	 * Handles inheritance and cascading of CSS properties for block elements.
	 *
	 * @param string $inherit Inheritance type (TOPTABLE, TABLE, BLOCK)
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @return void
	 */
	protected function mergeBlockCascadingCss($inherit, $tag, $attr, $classes)
	{
		if ($inherit !== 'BLOCK') {
			return;
		}

		$currentBlock = isset($this->mpdf->blk[$this->mpdf->blklvl]) ? $this->mpdf->blk[$this->mpdf->blklvl] : [];
		$currentBlockHasCascade = isset($currentBlock['cascadeCSS']) && is_array($currentBlock['cascadeCSS']);
		$currentBlock['cascadeCSS'] = $currentBlockHasCascade ? $currentBlock['cascadeCSS'] : [];

		$previousBlockLevel = $this->getBlockLevel();
		$previousBlock = isset($this->mpdf->blk[$previousBlockLevel]) ? $this->mpdf->blk[$previousBlockLevel] : [];
		$previousBlockHasCascade = isset($previousBlock['cascadeCSS']) && is_array($previousBlock['cascadeCSS']);
		$previousBlock['cascadeCSS'] = $previousBlockHasCascade ? $previousBlock['cascadeCSS'] : [];

		foreach ($previousBlock['cascadeCSS'] as $k => $v) {
			$currentBlock['cascadeCSS'][$k] = $v;
		}

		// Save Cascading CSS e.g. "div.topic p" at this block level
		$this->mergeFullCssRules(
			$this->cssManager->cascadeCSS,
			$currentBlock['cascadeCSS'],
			$tag,
			$classes,
			$attr['ID'],
			$attr['LANG']
		);

		// Cascading forward CSS
		$this->mergeFullCssRules(
			$previousBlock['cascadeCSS'],
			$currentBlock['cascadeCSS'],
			$tag,
			$classes,
			$attr['ID'],
			$attr['LANG']
		);

		// Set the new block info
		if ($this->sideEffects) {
			$this->mpdf->blk[$this->mpdf->blklvl] = $currentBlock;
		}

		// Block properties which are inherited
		if (!empty($previousBlock['margin_collapse'])) {
			$this->cssProperties['MARGIN-COLLAPSE'] = 'COLLAPSE';
		}

		// custom tag, but follows CSS principle that border-collapse is inherited
		if (!empty($previousBlock['line_height'])) {
			$this->cssProperties['LINE-HEIGHT'] = $previousBlock['line_height'];
		}

		// mPDF 6
		if (!empty($previousBlock['line_stacking_strategy'])) {
			$this->cssProperties['LINE-STACKING-STRATEGY'] = $previousBlock['line_stacking_strategy'];
		}

		if (!empty($previousBlock['line_stacking_shift'])) {
			$this->cssProperties['LINE-STACKING-SHIFT'] = $previousBlock['line_stacking_shift'];
		}

		if (!empty($previousBlock['direction'])) {
			$this->cssProperties['DIRECTION'] = $previousBlock['direction'];
		}

		// mPDF 6  Lists
		if ($tag === 'LI' && !empty($previousBlock['list_style_type'])) {
			$this->cssProperties['LIST-STYLE-TYPE'] = $previousBlock['list_style_type'];
		}

		if (!empty($previousBlock['list_style_image'])) {
			$this->cssProperties['LIST-STYLE-IMAGE'] = $previousBlock['list_style_image'];
		}

		if (!empty($previousBlock['list_style_position'])) {
			$this->cssProperties['LIST-STYLE-POSITION'] = $previousBlock['list_style_position'];
		}

		if (!empty($previousBlock['align'])) {
			switch ($previousBlock['align']) {
				case 'L':
					$this->cssProperties['TEXT-ALIGN'] = 'left';
					break;

				case 'J':
					$this->cssProperties['TEXT-ALIGN'] = 'justify';
					break;

				case 'R':
					$this->cssProperties['TEXT-ALIGN'] = 'right';
					break;

				case 'C':
					$this->cssProperties['TEXT-ALIGN'] = 'center';
					break;
			}
		}

		if (!empty($previousBlock['bgcolorarray']) && ($this->mpdf->ColActive || $this->mpdf->keep_block_together)) {
			// Doesn't officially inherit, but default value is transparent (?=inherited)
			$cor = $previousBlock['bgcolorarray'];
			$this->cssProperties['BACKGROUND-COLOR'] = $this->colorConverter->colAtoString($cor);
		}

		if (isset($previousBlock['text_indent'])) {
			$this->cssProperties['TEXT-INDENT'] = $previousBlock['text_indent'];
		}

		if (isset($previousBlock['InlineProperties'])) {
			$converted = $this->inlinePropertyConverter->convert($previousBlock['InlineProperties']);
			$this->cssProperties = array_merge($this->cssProperties, $converted); // mPDF 5.7.1
		}
	}

	/**
	 * Merge inline HTML attributes e.g. .. ALIGN="CENTER"
	 *
	 * Converts HTML attributes to CSS properties.
	 *
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes
	 * @return void
	 */
	protected function mergeInlineAttributes($tag, $attr)
	{
		if (!empty($attr['DIR'])) {
			$this->cssProperties['DIRECTION'] = $attr['DIR'];
		}

		if (!empty($attr['LANG'])) {
			$this->cssProperties['LANG'] = $attr['LANG'];
		}

		if (!empty($attr['COLOR'])) {
			$this->cssProperties['COLOR'] = $attr['COLOR'];
		}

		if ($tag !== 'INPUT') {
			if (!empty($attr['WIDTH'])) {
				$this->cssProperties['WIDTH'] = $attr['WIDTH'];
			}

			if (!empty($attr['HEIGHT'])) {
				$this->cssProperties['HEIGHT'] = $attr['HEIGHT'];
			}
		}

		if ($tag === 'FONT') {
			if (!empty($attr['FACE'])) {
				$this->cssProperties['FONT-FAMILY'] = $attr['FACE'];
			}

			$size = isset($attr['SIZE']) ? $attr['SIZE'] : '';
			if ($size === '+1') {
				$this->cssProperties['FONT-SIZE'] = '120%';
			} elseif ($size === '-1') {
				$this->cssProperties['FONT-SIZE'] = '86%';
			} elseif ($size === '1') {
				$this->cssProperties['FONT-SIZE'] = 'XX-SMALL';
			} elseif ($size === '2') {
				$this->cssProperties['FONT-SIZE'] = 'X-SMALL';
			} elseif ($size === '3') {
				$this->cssProperties['FONT-SIZE'] = 'SMALL';
			} elseif ($size === '4') {
				$this->cssProperties['FONT-SIZE'] = 'MEDIUM';
			} elseif ($size === '5') {
				$this->cssProperties['FONT-SIZE'] = 'LARGE';
			} elseif ($size === '6') {
				$this->cssProperties['FONT-SIZE'] = 'X-LARGE';
			} elseif ($size === '7') {
				$this->cssProperties['FONT-SIZE'] = 'XX-LARGE';
			}
		}

		if (!empty($attr['VALIGN'])) {
			$this->cssProperties['VERTICAL-ALIGN'] = $attr['VALIGN'];
		}

		if (!empty($attr['VSPACE'])) {
			$this->cssProperties['MARGIN-TOP'] = $attr['VSPACE'];
			$this->cssProperties['MARGIN-BOTTOM'] = $attr['VSPACE'];
		}

		if (!empty($attr['HSPACE'])) {
			$this->cssProperties['MARGIN-LEFT'] = $attr['HSPACE'];
			$this->cssProperties['MARGIN-RIGHT'] = $attr['HSPACE'];
		}
	}

	/**
	 * Merge default CSS for the tag.
	 *
	 * @param string $tag HTML tag name
	 * @return void
	 */
	protected function mergeDefaultCss($tag)
	{
		if (!isset($this->mpdf->defaultCSS[$tag])) {
			return;
		}

		$zp = $this->normalizeProperties->normalize($this->mpdf->defaultCSS[$tag]);
		if (is_array($zp)) {  // Default overwrites Inherited
			$this->cssProperties = array_merge($this->cssProperties, $zp);  // !! Note other way round !!
			$this->mergeBorderProperties($zp);
		}
	}

	/**
	 * Merge table specific CSS (CELLSPACING, CELLPADDING).
	 *
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes
	 * @return void
	 */
	protected function mergeTableSpecificCss($tag, $attr)
	{
		if (!in_array($tag, ['TABLE', 'TD', 'TH'], true)) {
			return;
		}

		// cellSpacing overwrites TABLE default but not specific CSS set on table
		if ($tag === 'TABLE') {
			$cellSpacing = isset($attr['CELLSPACING']) ? $attr['CELLSPACING'] : '';
			if ($cellSpacing !== '') {
				$this->cssProperties['BORDER-SPACING-H'] = $this->cssProperties['BORDER-SPACING-V'] = $cellSpacing;
			}
			return;
		}

		$tableCell = isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]) ? $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]] : [];

		// cellPadding overwrites TD/TH default but not specific CSS set on cell
		$cellPadding = isset($tableCell['cell_padding']) ? $tableCell['cell_padding'] : '';
		if (!empty($cellPadding) || $cellPadding === '0') {
			$this->cssProperties['PADDING-LEFT'] = $cellPadding;
			$this->cssProperties['PADDING-RIGHT'] = $cellPadding;
			$this->cssProperties['PADDING-TOP'] = $cellPadding;
			$this->cssProperties['PADDING-BOTTOM'] = $cellPadding;
		}
	}

	/**
	 * Merge stylesheet selectors.
	 *
	 * Applies CSS rules from stylesheets based on tag, class, ID,
	 *
	 * @param string $tag HTML tag
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @param string $languageCode Short language code (e.g. 'en')
	 * @return void
	 */
	protected function mergeStylesheetSelectors($tag, $attr, $classes, $languageCode)
	{
		// STYLESHEET TAG e.g. h1  p  div  table
		if (isset($this->cssManager->CSS[$tag])) {
			$zp = $this->cssManager->CSS[$tag];
			if ($tag === 'TD' || $tag === 'TH') {
				$this->setDominanceFromProperties($zp, 9);
			}

			if (is_array($zp)) {
				$this->cssProperties = array_merge($this->cssProperties, $zp);
				$this->mergeBorderProperties($zp);
			}
		}

		// STYLESHEET CLASS e.g. .smallone{}  .redletter{}
		foreach ($classes as $class) {
			$zp = [];
			if (!empty($this->cssManager->CSS['CLASS>>' . $class])) {
				$zp = $this->cssManager->CSS['CLASS>>' . $class];
			}

			if ($tag === 'TD' || $tag === 'TH') {
				$this->setDominanceFromProperties($zp, 9);
			}

			if (is_array($zp)) {
				$this->cssProperties = array_merge($this->cssProperties, $zp);
				$this->mergeBorderProperties($zp);
			}
		}

		// STYLESHEET nth-child SELECTOR e.g. tr:nth-child(odd)  td:nth-child(2n+1)
		if ($tag === 'TR' || $tag === 'TD' || $tag === 'TH') {
			$regex = '/(([\-+]?\d*)?N([\-+]\d+)?|[\-+]?\d+|ODD|EVEN)/';

			foreach ($this->cssManager->CSS as $key => $selector) {
				if (!preg_match('/' . $tag . '>>SELECTORNTHCHILD>>(.*)/', $key, $m)) {
					continue;
				}

				$select = false;
				switch ($tag) {
					case 'TR':
						$row = $this->mpdf->row;
						$tableCell = isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]) ? $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]] : [];

						$theadCount = !empty($tableCell['is_thead']) ? count($tableCell['is_thead']) : 0;
						$tfootCount = !empty($tableCell['is_tfoot']) ? count($tableCell['is_tfoot']) : 0;

						if ($this->mpdf->tabletfoot) {
							$row -= $theadCount;
						} elseif (!$this->mpdf->tablethead) {
							$row -= ($theadCount + $tfootCount);
						}

						if (preg_match($regex, $m[1], $a)) { // mPDF 5.7.4
							$select = $this->selectorParser->matchesNthChild($a, $row);
						}
						break;

					case 'TH':
					case 'TD':
						if (preg_match($regex, $m[1], $a)) { // mPDF 5.7.4
							$select = $this->selectorParser->matchesNthChild($a, $this->mpdf->col);
						}
						break;
				}

				if ($select) {
					$zp = $this->cssManager->CSS[$tag . '>>SELECTORNTHCHILD>>' . $m[1]];
					if ($tag === 'TD' || $tag === 'TH') {
						$this->setDominanceFromProperties($zp, 9);
					}

					if (is_array($zp)) {
						$this->cssProperties = array_merge($this->cssProperties, $zp);
						$this->mergeBorderProperties($zp);
					}
				}

			}
		}

		// STYLESHEET LANG e.g. [lang=fr]{} or :lang(fr)
		if (isset($attr['LANG'])) {
			if (!empty($this->cssManager->CSS['LANG>>' . $attr['LANG']])) {
				$zp = $this->cssManager->CSS['LANG>>' . $attr['LANG']];
				if ($tag === 'TD' || $tag === 'TH') {
					$this->setDominanceFromProperties($zp, 9);
				}

				if (is_array($zp)) {
					$this->cssProperties = array_merge($this->cssProperties, $zp);
					$this->mergeBorderProperties($zp);
				}
			} elseif (!empty($this->cssManager->CSS['LANG>>' . $languageCode])) {
				$zp = $this->cssManager->CSS['LANG>>' . $languageCode];
				if ($tag === 'TD' || $tag === 'TH') {
					$this->setDominanceFromProperties($zp, 9);
				}

				if (is_array($zp)) {
					$this->cssProperties = array_merge($this->cssProperties, $zp);
					$this->mergeBorderProperties($zp);
				}
			}
		}

		// STYLESHEET ID e.g. #smallone{}  #redletter{}
		if (!empty($attr['ID']) && !empty($this->cssManager->CSS['ID>>' . $attr['ID']])) {
			$zp = $this->cssManager->CSS['ID>>' . $attr['ID']];
			if ($tag === 'TD' || $tag === 'TH') {
				$this->setDominanceFromProperties($zp, 9);
			}

			if (is_array($zp)) {
				$this->cssProperties = array_merge($this->cssProperties, $zp);
				$this->mergeBorderProperties($zp);
			}
		}
	}

	/**
	 * Merge tag specific selectors (Tag.Class, Tag#ID, etc.).
	 *
	 * @param string $tag HTML tag
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @param string $languageCode Short language code (e.g. 'en')
	 * @return void
	 */
	protected function mergeTagSpecificSelectors($tag, $attr, $classes, $languageCode)
	{
		// STYLESHEET CLASS e.g. p.smallone{}  div.redletter{}
		foreach ($classes as $class) {
			$zp = [];
			if (!empty($this->cssManager->CSS[$tag . '>>CLASS>>' . $class])) {
				$zp = $this->cssManager->CSS[$tag . '>>CLASS>>' . $class];
			}

			if ($tag === 'TD' || $tag === 'TH') {
				$this->setDominanceFromProperties($zp, 9);
			}

			if (is_array($zp)) {
				$this->cssProperties = array_merge($this->cssProperties, $zp);
				$this->mergeBorderProperties($zp);
			}
		}

		// STYLESHEET LANG e.g. [lang=fr]{} or :lang(fr)
		if (isset($attr['LANG'])) {
			if (!empty($this->cssManager->CSS[$tag . '>>LANG>>' . $attr['LANG']])) {
				$zp = $this->cssManager->CSS[$tag . '>>LANG>>' . $attr['LANG']];
				if ($tag === 'TD' || $tag === 'TH') {
					$this->setDominanceFromProperties($zp, 9);
				}

				if (is_array($zp)) {
					$this->cssProperties = array_merge($this->cssProperties, $zp);
					$this->mergeBorderProperties($zp);
				}
			} elseif (!empty($this->cssManager->CSS[$tag . '>>LANG>>' . $languageCode])) {
				$zp = $this->cssManager->CSS[$tag . '>>LANG>>' . $languageCode];
				if ($tag === 'TD' || $tag === 'TH') {
					$this->setDominanceFromProperties($zp, 9);
				}

				if (is_array($zp)) {
					$this->cssProperties = array_merge($this->cssProperties, $zp);
					$this->mergeBorderProperties($zp);
				}
			}
		}

		// STYLESHEET CLASS e.g. p#smallone{}  div#redletter{}
		if (isset($attr['ID']) && !empty($this->cssManager->CSS[$tag . '>>ID>>' . $attr['ID']])) {
			$zp = $this->cssManager->CSS[$tag . '>>ID>>' . $attr['ID']];
			if ($tag === 'TD' || $tag === 'TH') {
				$this->setDominanceFromProperties($zp, 9);
			}

			if (is_array($zp)) {
				$this->cssProperties = array_merge($this->cssProperties, $zp);
				$this->mergeBorderProperties($zp);
			}
		}
	}

	/**
	 * Merge cascaded CSS properties (BLOCK, INLINE, TABLE).
	 *
	 * @param string $inherit Inheritance context
	 * @param string $tag HTML tag
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @return void
	 */
	protected function mergeDescendantSelectors($inherit, $tag, $attr, $classes)
	{
		if ($inherit === 'TOPTABLE' || $inherit === 'TABLE') {
			$this->mergeTableDescendantSelectors($tag, $attr, $classes);
			return;
		}

		$level = $this->getBlockLevel($inherit);
		if (!isset($this->mpdf->blk[$level]['cascadeCSS'])) {
			return;
		}

		$cascadeCSS = $this->mpdf->blk[$level]['cascadeCSS'];
		$this->mergeDescendantCss($cascadeCSS, $tag, $attr, $classes);

		if ($this->sideEffects) {
			$this->mpdf->blk[$level]['cascadeCSS'] = $cascadeCSS;
		}
	}

	/**
	 * Merge table cascaded CSS.
	 *
	 * @param string $tag HTML tag
	 * @param array $attr HTML attributes
	 * @param array $classes Array of class names
	 * @return void
	 */
	protected function mergeTableDescendantSelectors($tag, $attr, $classes)
	{
		$node = isset($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1]) ? $this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1] : [];
		if (empty($node)) {
			return;
		}

		// don't check for 'depth' and do set border dominance
		$this->setMergedCss($node[$tag], false, 9);
		foreach ($classes as $class) {
			$this->setMergedCss($node['CLASS>>' . $class], false, 9);
		}

		// STYLESHEET nth-child SELECTOR e.g. tr:nth-child(odd)  td:nth-child(2n+1)
		if ($tag === 'TR' || $tag === 'TD' || $tag === 'TH') {
			foreach ($node as $k => $val) {
				if (!preg_match('/' . $tag . '>>SELECTORNTHCHILD>>(.*)/', $k, $m)) {
					continue;
				}

				$select = false;
				$regex = '/(([\-+]?\d*)?N([\-+]\d+)?|[\-+]?\d+|ODD|EVEN)/';
				if ($tag === 'TR') {
					$row = $this->mpdf->row;
					$table = isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]) ? $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]] : [];
					$tableHeadCount = isset($table['is_thead']) ? count($table['is_thead']) : 0;
					$tableFootCount = isset($table['is_tfoot']) ? count($table['is_tfoot']) : 0;

					if ($this->mpdf->tabletfoot) {
						$row -= $tableHeadCount;
					} elseif (!$this->mpdf->tablethead) {
						$row -= ($tableHeadCount + $tableFootCount);
					}

					if (preg_match($regex, $m[1], $a)) { // mPDF 5.7.4
						$select = $this->selectorParser->matchesNthChild($a, $row);
					}
				} elseif (($tag === 'TD' || $tag === 'TH') && preg_match($regex, $m[1], $a)) {
					$select = $this->selectorParser->matchesNthChild($a, $this->mpdf->col);
				}

				if ($select) {
					$this->setMergedCss($node[$tag . '>>SELECTORNTHCHILD>>' . $m[1]], false, 9);
				}
			}
		}

		$this->setMergedCss($node['ID>>' . $attr['ID']], false, 9);
		foreach ($classes as $class) {
			$this->setMergedCss($node[$tag . '>>CLASS>>' . $class], false, 9);
		}

		$this->setMergedCss($node[$tag . '>>ID>>' . $attr['ID']], false, 9);

		if ($this->sideEffects) {
			$this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl - 1] = $node;
		}
	}

	/**
	 * Apply descendant CSS rules.
	 *
	 * @param array $cascadeCSS
	 * @param string $tag
	 * @param array $attr
	 * @param array $classes
	 */
	protected function mergeDescendantCss($cascadeCSS, $tag, $attr, $classes)
	{
		if (empty($cascadeCSS)) {
			return;
		}

		$this->setMergedCss($cascadeCSS[$tag]);
		foreach ($classes as $class) {
			$this->setMergedCss($cascadeCSS['CLASS>>' . $class]);
		}

		$this->setMergedCss($cascadeCSS['ID>>' . $attr['ID']]);
		foreach ($classes as $class) {
			$this->setMergedCss($cascadeCSS[$tag . '>>CLASS>>' . $class]);
		}

		$this->setMergedCss($cascadeCSS[$tag . '>>ID>>' . $attr['ID']]);
	}

	/**
	 * Merge CSS properties into target array.
	 *
	 * Internal method to merge CSS properties from source into target.
	 * Used for CSS cascading.
	 *
	 * @param array $property Source CSS properties
	 * @param array $target Target CSS properties (modified by reference)
	 * @return void
	 */
	protected function mergeCssProperties($property, &$target)
	{
		if (empty($property)) {
			return;
		}

		$target = $target ? Arrays::uniqueRecursiveMerge($target, $property) : $property;
	}

	/**
	 * Merge Nth-child CSS selectors.
	 *
	 * Handles :nth-child() pseudo-class logic for TR, TD, and TH tags.
	 *
	 * @param array $sourceSelectors Source CSS selector array
	 * @param array $targetProperties Target CSS properties (passed by reference)
	 * @param string $tag HTML tag name
	 * @return void
	 */
	protected function mergeNthChildCss($sourceSelectors, &$targetProperties, $tag)
	{
		if (!in_array($tag, ['TR', 'TH', 'TD'], true) || empty($sourceSelectors)) {
			return;
		}

		$regex = '/(([\-+]?\d*)?N([\-+]\d+)?|[\-+]?\d+|ODD|EVEN)/';

		foreach ($sourceSelectors as $key => $selector) {
			if (!preg_match('/' . $tag . '>>SELECTORNTHCHILD>>(.*)/', $key, $m)) {
				continue;
			}

			$select = false;
			switch ($tag) {
				case 'TR':
					$row = $this->mpdf->row;
					$tableCell = isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]) ? $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]] : [];

					$theadCount = !empty($tableCell['is_thead']) ? count($tableCell['is_thead']) : 0;
					$tfootCount = !empty($tableCell['is_tfoot']) ? count($tableCell['is_tfoot']) : 0;

					if ($this->mpdf->tabletfoot) {
						$row -= $theadCount;
					} elseif (!$this->mpdf->tablethead) {
						$row -= ($theadCount + $tfootCount);
					}

					if (preg_match($regex, $m[1], $a)) { // mPDF 5.7.4
						$select = $this->selectorParser->matchesNthChild($a, $row);
					}
					break;

				case 'TH':
				case 'TD':
					if (preg_match($regex, $m[1], $a)) { // mPDF 5.7.4
						$select = $this->selectorParser->matchesNthChild($a, $this->mpdf->col);
					}
					break;
			}

			if ($select) {
				$this->mergeCssProperties($sourceSelectors[$tag . '>>SELECTORNTHCHILD>>' . $m[1]], $targetProperties);
			}
		}
	}

	/**
	 * Merge full CSS rules including tag, class, ID, and lang selectors.
	 *
	 * Applies CSS rules from various selector types (tag, class, ID, language)
	 * to the target CSS properties array. Handles CSS cascading and specificity.
	 *
	 * @param array $p Source CSS selector array
	 * @param array $t Target CSS properties (modified by reference)
	 * @param string $tag HTML tag name
	 * @param array $classes Array of class names
	 * @param string $id Element ID
	 * @param string $lang Language code
	 * @return void
	 */
	protected function mergeFullCssRules($p, &$t, $tag, $classes, $id, $lang)
	{
		// mPDF 6
		if (isset($p[$tag])) {
			$this->mergeCssProperties($p[$tag], $t);
		}

		// STYLESHEET CLASS e.g. .smallone{}  .redletter{}
		foreach ($classes as $class) {
			if (isset($p['CLASS>>' . $class])) {
				$this->mergeCssProperties($p['CLASS>>' . $class], $t);
			}
		}

		// STYLESHEET nth-child SELECTOR e.g. tr:nth-child(odd)  td:nth-child(2n+1)
		$this->mergeNthChildCss($p, $t, $tag);

		// STYLESHEET CLASS e.g. [lang=fr]{} or :lang(fr)
		if (isset($lang) && isset($p['LANG>>' . $lang])) {
			$this->mergeCssProperties($p['LANG>>' . $lang], $t);
		}

		// STYLESHEET CLASS e.g. #smallone{}  #redletter{}
		if (isset($id) && isset($p['ID>>' . $id])) {
			$this->mergeCssProperties($p['ID>>' . $id], $t);
		}

		// STYLESHEET CLASS e.g. .smallone{}  .redletter{}
		foreach ($classes as $class) {
			if (isset($p[$tag . '>>CLASS>>' . $class])) {
				$this->mergeCssProperties($p[$tag . '>>CLASS>>' . $class], $t);
			}
		}

		// STYLESHEET CLASS e.g. [lang=fr]{} or :lang(fr)
		if (isset($lang) && isset($p[$tag . '>>LANG>>' . $lang])) {
			$this->mergeCssProperties($p[$tag . '>>LANG>>' . $lang], $t);
		}

		// STYLESHEET CLASS e.g. #smallone{}  #redletter{}
		if (isset($id) && isset($p[$tag . '>>ID>>' . $id])) {
			$this->mergeCssProperties($p[$tag . '>>ID>>' . $id], $t);
		}
	}

	/**
	 * Merge inline style attribute CSS.
	 *
	 * @param string $tag HTML tag name
	 * @param array $attr HTML attributes
	 * @return void
	 */
	protected function mergeInlineStyle($tag, $attr)
	{
		// INLINE STYLE e.g. style="CSS:property"
		if (!isset($attr['STYLE'])) {
			return;
		}

		$zp = $this->inlineStyleParser->parse($attr['STYLE']);
		if ($tag === 'TD' || $tag === 'TH') {
			$this->setDominanceFromProperties($zp, 9);
		}

		if (is_array($zp)) {
			$this->cssProperties = array_merge($this->cssProperties, $zp);
			$this->mergeBorderProperties($zp);
		}
	}

	/**
	 * Merge CSS properties with existing properties.
	 *
	 * @param array $property Source CSS properties
	 * @param bool $strictMode Use default strict mode
	 * @param bool|int $borderDominanceLevel Border dominance level (or false)
	 * @return void
	 */
	protected function setMergedCss(&$property, $strictMode = true, $borderDominanceLevel = false)
	{
		if (!isset($property)) {
			return;
		}

		$depth = isset($property['depth']) ? $property['depth'] : 0;
		if ($depth < 2 && $strictMode) {
			return;
		}

		if ($borderDominanceLevel) {
			$this->setDominanceFromProperties($property, $borderDominanceLevel);
		}

		if (is_array($property)) {
			$this->cssProperties = array_merge($this->cssProperties, $property);
			$this->mergeBorderProperties($property);
		}
	}

	/**
	 * Merge borders into CSS properties.
	 *
	 * @param array $properties properties to merge
	 * @return void
	 */
	protected function mergeBorderProperties($properties)
	{
		$this->borderMerger->mergeBorderProperties($properties, $this->cssProperties);
	}

	/**
	 * Set border dominance level for table cells.
	 *
	 * Used in table rendering to determine which cell borders take
	 * precedence when cells share borders.
	 *
	 * @param array $prop CSS properties containing border definitions
	 * @param int $val Dominance level value
	 * @return void
	 */
	public function setDominanceFromProperties($prop, $val)
	{
		if (!$this->sideEffects) {
			return;
		}

		$this->borderMerger->setDominanceFromProperties($prop, $val);
	}

	/**
	 * Set border dominance level for a specific side.
	 *
	 * @param string $side T|R|B|L
	 * @param int $val Dominance value
	 * @throws InvalidArgumentException
	 */
	public function setBorderDominance($side, $val)
	{
		$this->borderMerger->setBorderDominance($side, $val);
	}

	/**
	 * Get border dominance level for a specific side.
	 *
	 * @param string $side T|R|B|L
	 * @return int Dominance value
	 */
	public function getBorderDominance($side)
	{
		return $this->borderMerger->getBorderDominance($side);
	}

	/**
	 * @return int
	 */
	protected function getBlockLevel($inherit = 'BLOCK')
	{
		if (!$this->sideEffects || $inherit !== 'BLOCK') {
			return $this->mpdf->blklvl;
		}

		return $this->mpdf->blklvl - 1;
	}
}
