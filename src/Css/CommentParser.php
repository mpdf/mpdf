<?php

namespace Mpdf\Css;

class CommentParser
{
	/**
	 * Remove mPDF-specific and general HTML comments from content.
	 *
	 * Removes <!--mpdf and mpdf--> markers and all HTML comments.
	 *
	 * @param string $html HTML content to clean
	 * @return string HTML with comments removed
	 */
	public function removeHtmlComments($html)
	{
		$html = preg_replace('/<!--mpdf/i', '', $html);
		$html = preg_replace('/mpdf-->/i', '', $html);
		$html = preg_replace('/<!--.*?-->/s', ' ', $html);
		return $html;
	}

	/**
	 * Remove HTML and CSS comments from style blocks.
	 *
	 * Removes both HTML comments (<!-- -->) and CSS comments from
	 * <style> tag contents while preserving the structure.
	 *
	 * @param string $html HTML content with style tags
	 * @return string HTML with cleaned style blocks
	 */
	public function removeCommentsFromStyleBlocks($html)
	{
		preg_match_all('/<style.*?>(.*?)<\/style>/si', $html, $m);
		if (count($m[1]) === 0) {
			return $html;
		}

		foreach ($m[1] as $style) {
			$sub = str_replace(['<!--', '-->'], ' ', $style);
			$sub = '>' . preg_replace('|/\*.*?\*/|s', ' ', $sub) . '</style>';
			$html = str_replace('>' . $style . '</style>', $sub, $html);
		}

		return $html;
	}
}
