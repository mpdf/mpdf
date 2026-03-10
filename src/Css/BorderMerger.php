<?php

namespace Mpdf\Css;

use Mpdf\Exception\InvalidArgumentException;

class BorderMerger
{
	/**
	 * @var array<int> Border dominance levels for cell borders (top/right/bottom/left)
	 */
	private $borderDominance = [
		'T' => 0,
		'R' => 0,
		'B' => 0,
		'L' => 0,
	];

	/**
	 * Merge borders into CSS properties.
	 *
	 * @param array $newProperties properties to merge from
	 * @param array $cssProperties current CSS properties (passed by reference)
	 * @return void
	 */
	public function mergeBorderProperties($newProperties, &$cssProperties)
	{
		foreach (['TOP', 'RIGHT', 'BOTTOM', 'LEFT'] as $side) {
			$this->mergeSideBorder($side, $newProperties, $cssProperties);
		}
	}

	/**
	 * Merge border properties for a specific side.
	 *
	 * Helper method for mergeBorderProperties to handle merging of individual side properties
	 * (style, width, color) into the shorthand border property.
	 *
	 * @param string $side Side to merge (TOP, RIGHT, BOTTOM, LEFT)
	 * @param array $properties Source border properties
	 * @param array $cssProperties Target CSS properties (passed by reference)
	 * @return void
	 */
	protected function mergeSideBorder($side, $properties, &$cssProperties)
	{
		// Merges $a['BORDER-TOP-STYLE'] to $cssProperties['BORDER-TOP'] etc.
		$defaults = [
			'WIDTH' => '0px',
			'STYLE' => 'none',
			'COLOR' => '#000000'
		];

		$borderKey = 'BORDER-' . $side;
		$currentBorder = isset($cssProperties[$borderKey]) ? trim($cssProperties[$borderKey]) : '';

		foreach (['STYLE', 'WIDTH', 'COLOR'] as $el) {
			$propertyKey = $borderKey . '-' . $el;
			if (!isset($properties[$propertyKey])) {
				continue;
			}

			$value = trim($properties[$propertyKey]);
			if ($currentBorder) {
				// Update existing border value
				if ($el === 'STYLE') {
					$cssProperties[$borderKey] = preg_replace('/(\S+)\s+(\S+)\s+(\S+)/', '\\1 ' . $value . ' \\3', $currentBorder);
				} elseif ($el === 'WIDTH') {
					$cssProperties[$borderKey] = preg_replace('/(\S+)\s+(\S+)\s+(\S+)/', $value . ' \\2 \\3', $currentBorder);
				} else { // COLOR
					$cssProperties[$borderKey] = preg_replace('/(\S+)\s+(\S+)\s+(\S+)/', '\\1 \\2 ' . $value, $currentBorder);
				}

				$currentBorder = $cssProperties[$borderKey]; // Update current border for next iteration
			} else {
				// Build new border from scratch with defaults
				if (!isset($borderParts)) {
					$borderParts = $defaults;
				}

				$borderParts[$el] = $value;
				$cssProperties[$borderKey] = $borderParts['WIDTH'] . ' ' . $borderParts['STYLE'] . ' ' . $borderParts['COLOR'];
				$currentBorder = $cssProperties[$borderKey];
			}
		}
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
		if (!empty($prop['BORDER-TOP'])) {
			$this->setBorderDominance('T', $val);
		}

		if (!empty($prop['BORDER-RIGHT'])) {
			$this->setBorderDominance('R', $val);
		}

		if (!empty($prop['BORDER-BOTTOM'])) {
			$this->setBorderDominance('B', $val);
		}

		if (!empty($prop['BORDER-LEFT'])) {
			$this->setBorderDominance('L', $val);
		}
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
		if (!isset($this->borderDominance[$side])) {
			throw new InvalidArgumentException('Invalid border dominance value:' . $side);
		}

		$this->borderDominance[$side] = (int) $val;
	}

	/**
	 * Get border dominance level for a specific side.
	 *
	 * @param string $side T|R|B|L
	 * @return int Dominance value
	 */
	public function getBorderDominance($side)
	{
		return isset($this->borderDominance[$side]) ? $this->borderDominance[$side] : 0;
	}
}
