<?php

namespace Mpdf;

class PageFormat
{

	/**
	 * This method returns an array of width and height of given named format.
	 *
	 * Returned values are milimeters multiplied by scale factor of 72 / 25.4
	 *
	 * @param string $name
	 * @return float[] Width and height of given format
	 */
	public static function getSizeFromName($name)
	{
		$format = strtoupper($name);
		$formats = array(
			'4A0' => array(4767.87, 6740.79),
			'2A0' => array(3370.39, 4767.87),
			'A0' => array(2383.94, 3370.39),
			'A1' => array(1683.78, 2383.94),
			'A2' => array(1190.55, 1683.78),
			'A3' => array(841.89, 1190.55),
			'A4' => array(595.28, 841.89),
			'A5' => array(419.53, 595.28),
			'A6' => array(297.64, 419.53),
			'A7' => array(209.76, 297.64),
			'A8' => array(147.40, 209.76),
			'A9' => array(104.88, 147.40),
			'A10' => array(73.70, 104.88),
			'B0' => array(2834.65, 4008.19),
			'B1' => array(2004.09, 2834.65),
			'B2' => array(1417.32, 2004.09),
			'B3' => array(1000.63, 1417.32),
			'B4' => array(708.66, 1000.63),
			'B5' => array(498.90, 708.66),
			'B6' => array(354.33, 498.90),
			'B7' => array(249.45, 354.33),
			'B8' => array(175.75, 249.45),
			'B9' => array(124.72, 175.75),
			'B10' => array(87.87, 124.72),
			'C0' => array(2599.37, 3676.54),
			'C1' => array(1836.85, 2599.37),
			'C2' => array(1298.27, 1836.85),
			'C3' => array(918.43, 1298.27),
			'C4' => array(649.13, 918.43),
			'C5' => array(459.21, 649.13),
			'C6' => array(323.15, 459.21),
			'C7' => array(229.61, 323.15),
			'C8' => array(161.57, 229.61),
			'C9' => array(113.39, 161.57),
			'C10' => array(79.37, 113.39),
			'RA0' => array(2437.80, 3458.27),
			'RA1' => array(1729.13, 2437.80),
			'RA2' => array(1218.90, 1729.13),
			'RA3' => array(864.57, 1218.90),
			'RA4' => array(609.45, 864.57),
			'SRA0' => array(2551.18, 3628.35),
			'SRA1' => array(1814.17, 2551.18),
			'SRA2' => array(1275.59, 1814.17),
			'SRA3' => array(907.09, 1275.59),
			'SRA4' => array(637.80, 907.09),
			'LETTER' => array(612.00, 792.00),
			'LEGAL' => array(612.00, 1008.00),
			'LEDGER' => array(1224.00, 792.00),
			'TABLOID' => array(792.00, 1224.00),
			'EXECUTIVE' => array(521.86, 756.00),
			'FOLIO' => array(612.00, 936.00),
			'B' => array(362.83, 561.26), // 'B' format paperback size 128x198mm
			'A' => array(314.65, 504.57), // 'A' format paperback size 111x178mm
			'DEMY' => array(382.68, 612.28), // 'Demy' format paperback size 135x216mm
			'ROYAL' => array(433.70, 663.30), // 'Royal' format paperback size 153x234mm
		);

		if (!isset($formats[$format])) {
			throw new MpdfException(sprintf('Unknown page format %s'));
		}

		return $formats[$format];
	}

}
