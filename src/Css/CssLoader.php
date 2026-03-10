<?php

namespace Mpdf\Css;

use Mpdf\AssetFetcher;
use Mpdf\Cache;
use Mpdf\Exception\AssetFetchingException;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Utils\Path;

class CssLoader
{

	/**
	 * @var Mpdf
	 */
	private $mpdf;

	/**
	 * @var AssetFetcher
	 */
	private $assetFetcher;

	/**
	 * @var Cache
	 */
	private $cache;

	public function __construct(Mpdf $mpdf, AssetFetcher $assetFetcher, Cache $cache)
	{
		$this->mpdf = $mpdf;
		$this->assetFetcher = $assetFetcher;
		$this->cache = $cache;
	}

	/**
	 * Fetch and return the CSS from $path
	 *
	 * @param string $path
	 * @return string
	 * @throws MpdfException If asset fetching issue, is through when $mpdf->debug = true
	 */
	public function loadStylesheet($path)
	{
		$path = preg_replace('/\.css\?.*$/', '.css', $path);

		try {
			$data = $this->assetFetcher->fetchDataFromPath($path);
			if (!$data) {
				$path = !$this->mpdf->basepathIsLocal ? Path::normalizeLocalFilePath($path) : $path;
				$data = $this->assetFetcher->fetchDataFromPath($path);
			}
		} catch (AssetFetchingException $e) {
			$data = ''; // do nothing
			if ($this->mpdf->debug) {
				throw new MpdfException($e->getMessage(), 0, E_ERROR, null, null, $e);
			}
		}

		return $data;
	}

	/**
	 * Extract external stylesheet URLs from HTML.
	 *
	 * Finds all external CSS file references including:
	 * - <link rel="stylesheet" href="...">
	 * - <link href="..." rel="stylesheet">
	 * - @import url(...)
	 * - @import "..."
	 *
	 * @param string $html HTML content to scan
	 * @return array Array of CSS file URLs
	 */
	public function extractExternalStylesheetUrls($html)
	{
		$cssUrls = [];

		// <link rel="stylesheet" href="...">
		if (preg_match_all('/<link[^>]*rel=["\']stylesheet["\'][^>]*href=["\']([^>"\']*)["\'].*?>/si', $html, $cxt)) {
			$cssUrls = $cxt[1];
		}

		// <link href="..." rel="stylesheet">
		if (preg_match_all('/<link[^>]*href=["\']([^>"\']*)["\'][^>]*?rel=["\']stylesheet["\'].*?>/si', $html, $cxt)) {
			$cssUrls = array_merge($cssUrls, $cxt[1]);
		}

		// @import url(...)
		if (preg_match_all('/@import url\([\'\"]{0,1}(\S*?\.css(\?[^\s\'\"]+)?)[\'\"]{0,1}\)\;?/si', $html, $cxt)) {
			$cssUrls = array_merge($cssUrls, $cxt[1]);
		}

		// @import "..."
		if (preg_match_all('/@import (?!url)[\'\"]{0,1}(\S*?\.css(\?[^\s\'\"]+)?)[\'\"]{0,1}\;?/si', $html, $cxt)) {
			$cssUrls = array_merge($cssUrls, $cxt[1]);
		}

		return $cssUrls;
	}

	/**
	 * Locate embedded @import stylesheets in other stylesheets and fix url paths
	 * (including background-images) relative to stylesheet
	 *
	 * @param string $stylesheetCss
	 * @param string $path
	 * @param array $externalCss
	 * @param int $externalCssCount
	 * @return string
	 */
	public function processExternalCssImports($stylesheetCss, $path, &$externalCss, &$externalCssCount)
	{
		$css = '';

		$cssBasePath = preg_replace('/\/[^\/]*$/', '', $path) . '/';
		if (preg_match_all('/@import url\([\'\"]{0,1}(.*?\.css(\?\S+)?)[\'\"]{0,1}\)/si', $stylesheetCss, $cxtem)) {
			foreach ($cxtem[1] as $cxtembedded) {
				// path is relative to original stylesheet!!
				$externalCss[] = Path::relativeToAbsolutePath($cxtembedded, $cssBasePath);
				$externalCssCount++;
			}
		}

		$css .= ' ' . $this->resolveBackgroundUrls($stylesheetCss, $cssBasePath);

		return $css;
	}

	/**
	 * Resolve background image URLs in CSS.
	 *
	 * Converts relative URLs to absolute paths using Path::relativeToAbsolute.
	 * Skips data URIs which are already absolute.
	 *
	 * @param string $cssStr CSS string potentially containing background URLs
	 * @param string|null $basePath Optional base path for resolving relative URLs
	 * @return string CSS string with resolved URLs
	 */
	public function resolveBackgroundUrls($cssStr, $basePath = null)
	{
		if (!preg_match_all('/(background[^;]*url\s*\(\s*[\'"]{0,1})([^)\'"]*)([\'"]{0,1}\s*\))/si', $cssStr, $cxtem)) {
			return $cssStr;
		}

		$basePath = $basePath ?: $this->mpdf->basepath;

		foreach ($cxtem[0] as $i => $value) {
			$embedded = $cxtem[2][$i];
			if (!preg_match('/^data:image/i', $embedded)) {
				$newPath = Path::relativeToAbsolutePath($embedded, $basePath);
				$cssStr = str_replace($cxtem[0][$i], ($cxtem[1][$i] . $newPath . $cxtem[3][$i]), $cssStr);
			}
		}

		return $cssStr;
	}

	/**
	 * Process data URI images in CSS.
	 *
	 * Converts data URI images to temporary files for processing.
	 * Example: url(data:image/png;base64,...) becomes url("tempfile.png")
	 *
	 * @param string $cssStr CSS string potentially containing data URIs
	 * @return string CSS string with data URIs replaced by temp file references
	 * @throws \Random\RandomException
	 */
	public function processDataUriImages($cssStr)
	{
		preg_match_all("/(url\(data:image\/(jpeg|gif|png);base64,(.*?)\))/si", $cssStr, $idata);
		if (count($idata[0]) === 0) {
			return $cssStr;
		}

		foreach ($idata[0] as $i => $value) {
			$file = $this->cache->write('_tempCSSidata' . random_int(1, 10000) . '_' . $i . '.' . $idata[2][$i], base64_decode($idata[3][$i]));
			$cssStr = str_replace($idata[0][$i], 'url("' . $file . '")', $cssStr);
		}

		return $cssStr;
	}
}
