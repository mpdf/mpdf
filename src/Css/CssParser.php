<?php

namespace Mpdf\Css;

use Mpdf\Utils\Arrays;
use Mpdf\Utils\Path;
use Mpdf\Mpdf;
use Mpdf\Cache;
use Mpdf\SizeConverter;
use Mpdf\Color\ColorConverter;
use Mpdf\AssetFetcher;

class CssParser
{
	/**
	 * @var Mpdf
	 */
	private $mpdf;

	/**
	 * @var CssLoader
	 */
	private $cssLoader;

	/**
	 * @var MediaQueryProcessor
	 */
	private $mediaQueryProcessor;

	/**
	 * @var CommentParser
	 */
	private $commentParser;

	/**
	 * @var InlineStyleParser
	 */
	private $inlineStyleParser;

	/**
	 * @var SelectorParser
	 */
	private $selectorParser;

	/**
	 * @var NormalizeProperties
	 */
	private $normalizeProperties;

	/**
	 * @var ShadowParser
	 */
	private $shadowParser;

	/**
	 * CSS for simple selectors.
	 *
	 * Stores CSS properties for simple selectors (depth 1).
	 * Format:
	 * [
	 *   'P' => [
	 *     'COLOR' => '#FF0000',
	 *     'FONT-SIZE' => '12pt',
	 *   ],
	 *   'CLASS>>MYCLASS' => [
	 *     'BORDER' => '1px solid black',
	 *   ],
	 *   ...
	 * ]
	 *
	 * @var array
	 */
	private $css = [];

	/**
	 * CSS for cascaded selectors.
	 *
	 * Stores CSS properties for nested/cascaded selectors (depth > 1).
	 * Format is a nested array mirroring the selector hierarchy.
	 * Example for "DIV.myclass P":
	 * [
	 *   'DIV' => [
	 *     'CLASS>>MYCLASS' => [
	 *       'P' => [
	 *         'COLOR' => '#0000FF',
	 *         'depth' => 3
	 *       ]
	 *     ]
	 *   ]
	 * ]
	 *
	 * @var array
	 */
	private $cascadeCSS = [];

	/**
	 * @var array An index used to filter redundant class names before passing to Arrays::allUniqueSortedCombinations
	 */
	private $usedClassNames = [];

	/**
	 * @var int Maximum number of classes found in a single selector
	 */
	private $maxClassDepth = 1;

	public function __construct(
		Mpdf $mpdf,
		Cache $cache,
		SizeConverter $sizeConverter,
		ColorConverter $colorConverter,
		AssetFetcher $assetFetcher
	) {
		$this->mpdf = $mpdf;
		$this->normalizeProperties = new NormalizeProperties($mpdf, $sizeConverter, $colorConverter);
		$this->cssLoader = new CssLoader($mpdf, $assetFetcher, $cache);
		$this->mediaQueryProcessor = new MediaQueryProcessor($mpdf);
		$this->commentParser = new CommentParser();
		$this->inlineStyleParser = new InlineStyleParser($this->normalizeProperties);
		$this->selectorParser = new SelectorParser($mpdf);
		$this->shadowParser = new ShadowParser($mpdf, $sizeConverter, $colorConverter);
	}

	/**
	 * Read and parse CSS from HTML content.
	 *
	 * @param string $html HTML content containing CSS
	 * @return string
	 */
	public function parse($html)
	{
		$this->css = [];
		$this->cascadeCSS = [];

		$ind = 0;
		$css = '';

		$html = $this->mediaQueryProcessor->filterByMediaQuery($html, '/<style[^>]*media=["\']([^"\'>]*)["\'].*?<\/style>/is');
		$html = $this->mediaQueryProcessor->filterByMediaQuery($html, '/<link[^>]*media=["\']([^"\'>]*)["\'].*?>/is');
		$html = $this->commentParser->removeCommentsFromStyleBlocks($html);
		$html = $this->commentParser->removeHtmlComments($html);

		$externalCss = $this->cssLoader->extractExternalStylesheetUrls($html);
		$externalCssCount = count($externalCss);
		while ($externalCssCount) {
			$path = htmlspecialchars_decode($externalCss[$ind]);
			$path = Path::relativeToAbsolutePath($path, $this->mpdf->basepath);
			if (strpos($path, '//') === false) { // mPDF 5.7.3
				$path = preg_replace('/\.css\?.*$/', '.css', $path);
			}

			$stylesheetCss = $this->cssLoader->loadStylesheet($path);
			if ($stylesheetCss) {
				$css .= $this->cssLoader->processExternalCssImports($stylesheetCss, $path, $externalCss, $externalCssCount);
			}

			$externalCssCount--;
			$ind++;
		}

		// CSS as <style> in HTML document
		$regexp = '/<style.*?>(.*?)<\/style>/si';
		if (preg_match_all($regexp, $html, $cssBlock)) {
			$css .= ' ' . $this->cssLoader->resolveBackgroundUrls(implode(' ', $cssBlock[1]));
		}

		$css = preg_replace('|/\*.*?\*/|s', ' ', $css);
		$css = preg_replace('/[\s\n\r\t\f]/s', ' ', $css);
		$css = $this->mediaQueryProcessor->processMediaQueries($css);
		$css = $this->cssLoader->processDataUriImages($css);
		$css = preg_replace('/(<\!\-\-|\-\->)/s', ' ', $css);
		$css = $this->inlineStyleParser->processUrlsInCss($css);

		$this->processCssString($css);

		// Remove CSS (tags and content), if any (it can be <style> or <style type="txt/css">)
		$html = preg_replace('/<style.*?>(.*?)<\/style>/si', '', $html);

		return $html;
	}

	/**
	 * @return array
	 */
	public function getCss()
	{
		return $this->css;
	}

	/**
	 * @return array
	 */
	public function getCascadeCss()
	{
		return $this->cascadeCSS;
	}

	/**
	 * @return array
	 */
	public function getUsedClassNames()
	{
		return array_keys($this->usedClassNames);
	}

	/**
	 * @return int
	 */
	public function getMaxClassDepth()
	{
		return $this->maxClassDepth;
	}

	/**
	 * @param string $css
	 * @return void
	 */
	private function processCssString($css)
	{
		preg_match_all('/(.*?)\{(.*?)\}/', $css, $styles);
		$count = count($styles[1]);
		for ($i = 0; $i < $count; $i++) {
			$classProperties = $this->parseCssProperties($styles[2][$i]);
			$tagName = strtoupper(trim($styles[1][$i]));

			$tags = explode(',', $tagName);
			foreach ($tags as $tag) {
				$this->processCssSelector($tag, $classProperties);
			}
		}
	}

	/**
	 * Process a CSS selector.
	 *
	 * @param string $selector Selector string
	 * @param array $classProperties CSS properties
	 * @return void
	 */
	private function processCssSelector($selector, $classProperties)
	{
		// store classes in an index for faster lookups
		if (strpos($selector, '.') !== false && preg_match_all('/\.([a-zA-Z0-9_\-]+)/', $selector, $matches)) {
			foreach ($matches[1] as $className) {
				$this->usedClassNames[$className] = true;
			}

			$classCount = count($matches[1]);
			if ($classCount > $this->maxClassDepth) {
				$this->maxClassDepth = $classCount;
			}
		}

		if (preg_match('/NTH-CHILD\((\s*(([\-+]?\d*)N(\s*[\-+]\s*\d+)?|[\-+]?\d+|ODD|EVEN)\s*)\)/', $selector, $m)) {
			$selector = preg_replace('/NTH-CHILD\(.*\)/', 'NTH-CHILD(' . str_replace(' ', '', $m[1]) . ')', $selector);
		}

		$tags = preg_split('/\s+/', trim($selector));
		$level = count($tags);
		if (trim($tags[0]) === '@PAGE') {
			$tag = $this->selectorParser->parsePageSelector($tags);
			if ($tag && isset($this->css[$tag])) {
				$this->css[$tag] = Arrays::uniqueRecursiveMerge($this->css[$tag], $classProperties);
			} elseif ($tag) {
				$this->css[$tag] = $classProperties;
			}

			return;
		}

		if ($level === 1) {
			$tag = $this->selectorParser->parseSimpleSelector($tags);
			if ($tag && isset($this->css[$tag])) {
				$this->css[$tag] = Arrays::uniqueRecursiveMerge($this->css[$tag], $classProperties);
			} elseif ($tag) {
				$this->css[$tag] = $classProperties;
			}
			return;
		}

		$cascade = $this->selectorParser->parseCascadedSelector($tags);
		if (empty($cascade)) {
			return;
		}

		$cascadeCSS = &$this->cascadeCSS;
		foreach ($cascade as $tag) {
			$cascadeCSS = &$cascadeCSS[$tag];
		}

		$cascadeCSS = Arrays::uniqueRecursiveMerge($cascadeCSS, $classProperties);
		$cascadeCSS['depth'] = $level;
	}

	/**
	 * Parse CSS property string into an array.
	 *
	 * @param string $rawStyles CSS style string (e.g. "color: red; font-size: 12px")
	 * @return array Associative array of CSS properties
	 */
	public function parseCssProperties($rawStyles)
	{
		$classProperties = [];
		$styles = explode(';', trim($rawStyles));

		foreach ($styles as $style) {
			if (empty(trim($style))) {
				continue;
			}

			// Changed to allow style="background: url('http://www.bpm1.com/bg.jpg')"
			$tmp = explode(':', $style, 2);
			$property = strtoupper(trim($tmp[0]));
			$value = isset($tmp[1]) ? $tmp[1] : '';

			$value = str_replace('%ZZ', ';', $value); // restore URL placeholder
			$value = preg_replace('/\s*!important/i', '', $value);
			$value = trim($value);

			if (empty($property) || strlen($value) === 0) {
				continue;
			}

			// Ignores -webkit-gradient so doesn't override -moz-
			if (($property === 'BACKGROUND-IMAGE' || $property === 'BACKGROUND') &&
				stripos($value, '-webkit-gradient') !== false
			) {
				continue;
			}

			$classProperties[$property] = $value;
		}

		return $this->normalizeProperties->normalize($classProperties);
	}

	/**
	 * Parse inline CSS style attribute.
	 *
	 * @param string $html CSS string from style attribute
	 * @return array Parsed CSS properties
	 */
	public function parseInlineCss($html)
	{
		return $this->inlineStyleParser->parse($html);
	}

	/**
	 * Parse box-shadow CSS property.
	 *
	 * Converts box-shadow CSS property string into array format used internally.
	 * Handles multiple shadows, inset shadows, blur, spread, and colors.
	 *
	 * @param string $value Box-shadow property value
	 * @return array Array of shadow definitions
	 */
	public function parseBoxShadow($value)
	{
		return $this->shadowParser->parseBoxShadow($value);
	}

	/**
	 * Parse text-shadow CSS property.
	 *
	 * Converts text-shadow CSS property string into array format used internally.
	 * Handles multiple shadows, blur, and colors.
	 *
	 * @param string $value Text-shadow property value
	 * @return array Array of text shadow definitions
	 */
	public function parseTextShadow($value)
	{
		return $this->shadowParser->parseTextShadow($value);
	}
}
