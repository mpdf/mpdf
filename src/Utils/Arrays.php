<?php

namespace Mpdf\Utils;

class Arrays
{

	public static function get($array, $key, $default = null)
	{
		if (is_array($array) && array_key_exists($key, $array)) {
			return $array[$key];
		}

		if (func_num_args() < 3) {
			throw new \InvalidArgumentException(sprintf('Array does not contain key "%s"', $key));
		}

		return $default;
	}

	/**
	 * Returns an array of all k-combinations from an input array of n elements, where k equals 1..n.
	 * Elements will be sorted and unique in every combination.
	 *
	 * Example: array[one, two] will give:
	 * [
	 *     [one],
	 *     [two],
	 *     [one, two]
	 * ]
	 * @param array $array
	 * @param int|null $maxSize Max depth of the combinations
	 * @return array
	 */
	public static function allUniqueSortedCombinations($array, $maxSize = null)
	{
		$input = array_unique($array);
		if (count($input) <= 1) {
			return [$input];
		}

		sort($input);
		$combinations = [];
		foreach ($input as $value) {
			$combinations[] = [$value];
		}

		$n = count($input);
		for ($k = 2; $k <= $n; $k++) {
			if ($maxSize && $k > $maxSize) {
				break;
			}
			$combinations = array_merge($combinations, self::combinations($input, $k));
		}

		return $combinations;
	}

	/**
	 * Returns an array of unique k-combinations from an input array.
	 *
	 * Example: array=[one, two, three] and k=2 will give:
	 * [
	 *     [one, two],
	 *     [one, three]
	 * ]
	 * @param array $array
	 * @param int $k
	 * @return array
	 */
	public static function combinations($array, $k)
	{
		$n = count($array);
		$combinations = [];
		$indexes = range(0, $k - 1);
		$maxIndexes = range($n - $k, $n - 1);
		do {
			$combination = [];
			foreach ($indexes as $index) {
				$combination[] = $array[$index];
			}
			$combinations[] = $combination;

			$anotherCombination = false;
			$resetFromIndex = -1;
			for ($i = $k - 1; $i >= 0; $i--) {
				if ($indexes[$i] < $maxIndexes[$i]) {
					$indexes[$i]++;
					$anotherCombination = true;
					break;
				}
				$resetFromIndex = $i;
			}

			if ($resetFromIndex > 0) {
				for ($i = $resetFromIndex; $i < $k; $i++) {
					$indexes[$i] = $indexes[$i - 1] + 1;
				}
			}
		} while ($anotherCombination);

		return $combinations;
	}

	/**
	 * Merge arrays recursively, appending integer-like keys and merge string keys.
	 *
	 * @param array ...$arrays Arrays to merge
	 * @return array Merged array
	 */
	public static function uniqueRecursiveMerge(...$arrays)
	{
		$results = array_shift($arrays);

		foreach ($arrays as $array) {
			foreach ($array as $key => $value) {
				if ((string) $key === (string) ((int) $key)) {
					$results[] = $value;
				} elseif (is_array($value) && isset($results[$key]) && is_array($results[$key])) {
					$results[$key] = self::uniqueRecursiveMerge($results[$key], $value);
				} else {
					$results[$key] = $value;
				}
			}
		}

		return $results;
	}
}
