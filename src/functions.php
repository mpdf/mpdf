<?php

/**
 * Replace a section of an array with the elements in reverse
 *
 * @since mPDF 5.7
 * @param array $arr
 * @param int $offset
 * @param int $length
 */
function array_splice_reverse(array &$arr, $offset, $length)
{
	$tmp = (array_reverse(array_slice($arr, $offset, $length)));
	array_splice($arr, $offset, $length, $tmp);
}

/**
 * @since mPDF 5.7.4
 * @param string $url
 * @return string
 */
function urldecode_parts($url)
{
	$file = $url;
	$query = '';
	if (preg_match('/[?]/', $url)) {
		$bits = preg_split('/[?]/', $url, 2);
		$file = $bits[0];
		$query = '?' . $bits[1];
	}
	$file = rawurldecode($file);
	$query = urldecode($query);

	return $file . $query;
}

/**
 * @param string $str1
 * @param string $str2
 * @param int $start
 * @param int $length
 *
 * @return int
 */
function _strspn($str1, $str2, $start = null, $length = null)
{
	$numargs = func_num_args();
	if ($numargs == 2) {
		return strspn($str1, $str2);
	} else {
		if ($numargs == 3) {
			return strspn($str1, $str2, $start);
		} else {
			return strspn($str1, $str2, $start, $length);
		}
	}
}

/**
 * @param string $str1
 * @param string $str2
 * @param int $start
 * @param int $length
 *
 * @return int
 */
function _strcspn($str1, $str2, $start = null, $length = null)
{
	$numargs = func_num_args();
	if ($numargs == 2) {
		return strcspn($str1, $str2);
	} else {
		if ($numargs == 3) {
			return strcspn($str1, $str2, $start);
		} else {
			return strcspn($str1, $str2, $start, $length);
		}
	}
}

/**
 * @param resource $h
 * @param bool $force
 *
 * @return string
 */
function _fgets(&$h, $force = false)
{
	$startpos = ftell($h);
	$s = fgets($h, 1024);
	if ($force && preg_match("/^([^\r\n]*[\r\n]{1,2})(.)/", trim($s), $ns)) {
		$s = $ns[1];
		fseek($h, $startpos + strlen($s));
	}

	return $s;
}

/**
 * @param string $text
 * @param string $ff
 *
 * @return string
 */
function PreparePreText($text, $ff = '//FF//')
{
	$text = htmlspecialchars($text);
	if ($ff) {
		$text = str_replace($ff, '</pre><formfeed /><pre>', $text);
	}

	return ('<pre>' . $text . '</pre>');
}

if (!function_exists('strcode2utf')) {
	/**
	 * Converts all the &#nnn; and &#xhhh; in a string to Unicode
	 *
	 * @since mPDF 5.7
	 * @param string $str
	 * @param bool $lo
	 *
	 * @return string
	 */
	function strcode2utf($str, $lo = true)
	{
		if ($lo) {
			$str = preg_replace_callback('/\&\#([0-9]+)\;/m', 'code2utf_lo_callback', $str);
			$str = preg_replace_callback('/\&\#x([0-9a-fA-F]+)\;/m', 'codeHex2utf_lo_callback', $str);
		} else {
			$str = preg_replace_callback('/\&\#([0-9]+)\;/m', 'code2utf_callback', $str);
			$str = preg_replace_callback('/\&\#x([0-9a-fA-F]+)\;/m', 'codeHex2utf_callback', $str);
		}

		return $str;
	}
}

function code2utf_callback($matches)
{
	return code2utf($matches[1], 0);
}

function code2utf_lo_callback($matches)
{
	return code2utf($matches[1], 1);
}

function codeHex2utf_callback($matches)
{
	return codeHex2utf($matches[1], 0);
}

function codeHex2utf_lo_callback($matches)
{
	return codeHex2utf($matches[1], 1);
}

if (!function_exists('code2utf')) {
	/**
	 * @param int $num
	 * @param bool $lo
	 *
	 * @return string
	 */
	function code2utf($num, $lo = true)
	{
		//Returns the utf string corresponding to the unicode value
		if ($num < 128) {
			if ($lo) {
				return chr($num);
			} else {
				return '&#' . $num . ';';
			}
		}
		if ($num < 2048) {
			return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
		}
		if ($num < 65536) {
			return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
		}
		if ($num < 2097152) {
			return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
		}

		return '?';
	}
}

if (!function_exists('codeHex2utf')) {
	function codeHex2utf($hex, $lo = true)
	{
		$num = hexdec($hex);
		if (($num < 128) && !$lo) {
			return '&#x' . $hex . ';';
		}

		return code2utf($num, $lo);
	}
}

if (!function_exists('cmp')) {
	function cmp($a, $b)
	{
		return strcoll(strtolower($a['uf']), strtolower($b['uf']));
	}
}
