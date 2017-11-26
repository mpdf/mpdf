<?php

namespace Mpdf;

use Mpdf\Color\ColorConverter;

use Mpdf\Css\Border;

use Mpdf\Image\ImageProcessor;

use Mpdf\Language\LanguageToFontInterface;

use Mpdf\Utils\UtfString;

class Tag
{

	/**
	 * @var \Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Mpdf\Cache
	 */
	private $cache;

	/**
	 * @var \Mpdf\CssManager
	 */
	private $cssManager;

	/**
	 * @var \Mpdf\Form
	 */
	private $form;

	/**
	 * @var \Mpdf\Otl
	 */
	private $otl;

	/**
	 * @var \Mpdf\TableOfContents
	 */
	private $tableOfContents;

	/**
	 * @var \Mpdf\SizeConverter
	 */
	private $sizeConverter;

	/**
	 * @var \Mpdf\Color\ColorConverter
	 */
	private $colorConverter;

	/**
	 * @var \Mpdf\Image\ImageProcessor
	 */
	private $imageProcessor;

	/**
	 * @var \Mpdf\Language\LanguageToFontInterface
	 */
	private $languageToFont;

	/**
	 * @param \Mpdf\Mpdf $mpdf
	 * @param \Mpdf\Cache $cache
	 * @param \Mpdf\CssManager $cssManager
	 * @param \Mpdf\Form $form
	 * @param \Mpdf\Otl $otl
	 * @param \Mpdf\TableOfContents $tableOfContents
	 * @param \Mpdf\SizeConverter $sizeConverter
	 * @param \Mpdf\Color\ColorConverter $colorConverter
	 * @param \Mpdf\Image\ImageProcessor $imageProcessor
	 * @param \Mpdf\Language\LanguageToFontInterface $languageToFont
	 */
	public function __construct(
		Mpdf $mpdf,
		Cache $cache,
		CssManager $cssManager,
		Form $form,
		Otl $otl,
		TableOfContents $tableOfContents,
		SizeConverter $sizeConverter,
		ColorConverter $colorConverter,
		ImageProcessor $imageProcessor,
		LanguageToFontInterface $languageToFont
	) {

		$this->mpdf = $mpdf;
		$this->cache = $cache;
		$this->cssManager = $cssManager;
		$this->form = $form;
		$this->otl = $otl;
		$this->tableOfContents = $tableOfContents;
		$this->sizeConverter = $sizeConverter;
		$this->colorConverter = $colorConverter;
		$this->imageProcessor = $imageProcessor;
		$this->languageToFont = $languageToFont;
	}

	/**
	 * @param string $tag The tag name
	 * @return \Mpdf\Tag\Tag
	 */
	private function getTagInstance($tag)
	{
		$className = 'Mpdf\Tag\\' . $tag;
		if (class_exists($className)) {
			return new $className(
				$this->mpdf,
				$this->cache,
				$this->cssManager,
				$this->form,
				$this->otl,
				$this->tableOfContents,
				$this->sizeConverter,
				$this->colorConverter,
				$this->imageProcessor,
				$this->languageToFont
			);
		}
	}

	public function OpenTag($tag, $attr, &$ahtml, &$ihtml)
	{
		// Correct for tags where HTML5 specifies optional end tags excluding table elements (cf WriteHTML() )
		if ($this->mpdf->allow_html_optional_endtags) {
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['tag'])) {
				$closed = false;
				// li end tag may be omitted if immediately followed by another li element
				if (!$closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'LI' && $tag == 'LI') {
					$this->CloseTag('LI', $ahtml, $ihtml);
					$closed = true;
				}
				// dt end tag may be omitted if immediately followed by another dt element or a dd element
				if (!$closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'DT' && ($tag == 'DT' || $tag == 'DD')) {
					$this->CloseTag('DT', $ahtml, $ihtml);
					$closed = true;
				}
				// dd end tag may be omitted if immediately followed by another dd element or a dt element
				if (!$closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'DD' && ($tag == 'DT' || $tag == 'DD')) {
					$this->CloseTag('DD', $ahtml, $ihtml);
					$closed = true;
				}
				// p end tag may be omitted if immediately followed by an address, article, aside, blockquote, div, dl,
				// fieldset, form, h1, h2, h3, h4, h5, h6, hgroup, hr, main, nav, ol, p, pre, section, table, ul
				if (!$closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'P'
						&& ($tag == 'P' || $tag == 'DIV' || $tag == 'H1' || $tag == 'H2' || $tag == 'H3'
							|| $tag == 'H4' || $tag == 'H5' || $tag == 'H6' || $tag == 'UL' || $tag == 'OL'
							|| $tag == 'TABLE' || $tag == 'PRE' || $tag == 'FORM' || $tag == 'ADDRESS' || $tag == 'BLOCKQUOTE'
							|| $tag == 'CENTER' || $tag == 'DL' || $tag == 'HR' || $tag == 'ARTICLE' || $tag == 'ASIDE'
							|| $tag == 'FIELDSET' || $tag == 'HGROUP' || $tag == 'MAIN' || $tag == 'NAV' || $tag == 'SECTION')) {
					$this->CloseTag('P', $ahtml, $ihtml);
					$closed = true;
				}
				// option end tag may be omitted if immediately followed by another option element
				// (or if it is immediately followed by an optgroup element)
				if (!$closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'OPTION' && $tag == 'OPTION') {
					$this->CloseTag('OPTION', $ahtml, $ihtml);
					$closed = true;
				}
				// Table elements - see also WriteHTML()
				if (!$closed && ($tag == 'TD' || $tag == 'TH') && $this->mpdf->lastoptionaltag == 'TD') {
					$this->CloseTag($this->mpdf->lastoptionaltag, $ahtml, $ihtml);
					$closed = true;
				} // *TABLES*
				if (!$closed && ($tag == 'TD' || $tag == 'TH') && $this->mpdf->lastoptionaltag == 'TH') {
					$this->CloseTag($this->mpdf->lastoptionaltag, $ahtml, $ihtml);
					$closed = true;
				} // *TABLES*
				if (!$closed && $tag == 'TR' && $this->mpdf->lastoptionaltag == 'TR') {
					$this->CloseTag($this->mpdf->lastoptionaltag, $ahtml, $ihtml);
					$closed = true;
				} // *TABLES*
				if (!$closed && $tag == 'TR' && $this->mpdf->lastoptionaltag == 'TD') {
					$this->CloseTag($this->mpdf->lastoptionaltag, $ahtml, $ihtml);
					$this->CloseTag('TR', $ahtml, $ihtml);
					$this->CloseTag('THEAD', $ahtml, $ihtml);
					$closed = true;
				} // *TABLES*
				if (!$closed && $tag == 'TR' && $this->mpdf->lastoptionaltag == 'TH') {
					$this->CloseTag($this->mpdf->lastoptionaltag, $ahtml, $ihtml);
					$this->CloseTag('TR', $ahtml, $ihtml);
					$this->CloseTag('THEAD', $ahtml, $ihtml);
					$closed = true;
				} // *TABLES*
			}
		}

		if ($object = $this->getTagInstance($tag)) {
			return $object->open($attr, $ahtml, $ihtml);
		}

		$align = [
			'left' => 'L',
			'center' => 'C',
			'right' => 'R',
			'top' => 'T',
			'text-top' => 'TT',
			'middle' => 'M',
			'baseline' => 'BS',
			'bottom' => 'B',
			'text-bottom' => 'TB',
			'justify' => 'J'
		];

		switch ($tag) {

			case 'PAGEHEADER':
			case 'PAGEFOOTER':
				$this->mpdf->ignorefollowingspaces = true;
				if ($attr['NAME']) {
					$pname = $attr['NAME'];
				} else {
					$pname = '_nonhtmldefault';
				} // mPDF 6

				$p = []; // mPDF 6
				$p['L'] = [];
				$p['C'] = [];
				$p['R'] = [];
				$p['L']['font-style'] = '';
				$p['C']['font-style'] = '';
				$p['R']['font-style'] = '';

				if (isset($attr['CONTENT-LEFT'])) {
					$p['L']['content'] = $attr['CONTENT-LEFT'];
				}
				if (isset($attr['CONTENT-CENTER'])) {
					$p['C']['content'] = $attr['CONTENT-CENTER'];
				}
				if (isset($attr['CONTENT-RIGHT'])) {
					$p['R']['content'] = $attr['CONTENT-RIGHT'];
				}

				if (isset($attr['HEADER-STYLE']) || isset($attr['FOOTER-STYLE'])) { // font-family,size,weight,style,color
					if ($tag == 'PAGEHEADER') {
						$properties = $this->cssManager->readInlineCSS($attr['HEADER-STYLE']);
					} else {
						$properties = $this->cssManager->readInlineCSS($attr['FOOTER-STYLE']);
					}
					if (isset($properties['FONT-FAMILY'])) {
						$p['L']['font-family'] = $properties['FONT-FAMILY'];
						$p['C']['font-family'] = $properties['FONT-FAMILY'];
						$p['R']['font-family'] = $properties['FONT-FAMILY'];
					}
					if (isset($properties['FONT-SIZE'])) {
						$p['L']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
						$p['C']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
						$p['R']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
					}
					if (isset($properties['FONT-WEIGHT']) && $properties['FONT-WEIGHT'] == 'bold') {
						$p['L']['font-style'] = 'B';
						$p['C']['font-style'] = 'B';
						$p['R']['font-style'] = 'B';
					}
					if (isset($properties['FONT-STYLE']) && $properties['FONT-STYLE'] == 'italic') {
						$p['L']['font-style'] .= 'I';
						$p['C']['font-style'] .= 'I';
						$p['R']['font-style'] .= 'I';
					}
					if (isset($properties['COLOR'])) {
						$p['L']['color'] = $properties['COLOR'];
						$p['C']['color'] = $properties['COLOR'];
						$p['R']['color'] = $properties['COLOR'];
					}
				}
				if (isset($attr['HEADER-STYLE-LEFT']) || isset($attr['FOOTER-STYLE-LEFT'])) {
					if ($tag == 'PAGEHEADER') {
						$properties = $this->cssManager->readInlineCSS($attr['HEADER-STYLE-LEFT']);
					} else {
						$properties = $this->cssManager->readInlineCSS($attr['FOOTER-STYLE-LEFT']);
					}
					if (isset($properties['FONT-FAMILY'])) {
						$p['L']['font-family'] = $properties['FONT-FAMILY'];
					}
					if (isset($properties['FONT-SIZE'])) {
						$p['L']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
					}
					if (isset($properties['FONT-WEIGHT']) && $properties['FONT-WEIGHT'] == 'bold') {
						$p['L']['font-style'] = 'B';
					}
					if (isset($properties['FONT-STYLE']) && $properties['FONT-STYLE'] == 'italic') {
						$p['L']['font-style'] .='I';
					}
					if (isset($properties['COLOR'])) {
						$p['L']['color'] = $properties['COLOR'];
					}
				}
				if (isset($attr['HEADER-STYLE-CENTER']) || isset($attr['FOOTER-STYLE-CENTER'])) {
					if ($tag == 'PAGEHEADER') {
						$properties = $this->cssManager->readInlineCSS($attr['HEADER-STYLE-CENTER']);
					} else {
						$properties = $this->cssManager->readInlineCSS($attr['FOOTER-STYLE-CENTER']);
					}
					if (isset($properties['FONT-FAMILY'])) {
						$p['C']['font-family'] = $properties['FONT-FAMILY'];
					}
					if (isset($properties['FONT-SIZE'])) {
						$p['C']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
					}
					if (isset($properties['FONT-WEIGHT']) && $properties['FONT-WEIGHT'] == 'bold') {
						$p['C']['font-style'] = 'B';
					}
					if (isset($properties['FONT-STYLE']) && $properties['FONT-STYLE'] == 'italic') {
						$p['C']['font-style'] .= 'I';
					}
					if (isset($properties['COLOR'])) {
						$p['C']['color'] = $properties['COLOR'];
					}
				}
				if (isset($attr['HEADER-STYLE-RIGHT']) || isset($attr['FOOTER-STYLE-RIGHT'])) {
					if ($tag == 'PAGEHEADER') {
						$properties = $this->cssManager->readInlineCSS($attr['HEADER-STYLE-RIGHT']);
					} else {
						$properties = $this->cssManager->readInlineCSS($attr['FOOTER-STYLE-RIGHT']);
					}
					if (isset($properties['FONT-FAMILY'])) {
						$p['R']['font-family'] = $properties['FONT-FAMILY'];
					}
					if (isset($properties['FONT-SIZE'])) {
						$p['R']['font-size'] = $this->sizeConverter->convert($properties['FONT-SIZE']) * Mpdf::SCALE;
					}
					if (isset($properties['FONT-WEIGHT']) && $properties['FONT-WEIGHT'] == 'bold') {
						$p['R']['font-style'] = 'B';
					}
					if (isset($properties['FONT-STYLE']) && $properties['FONT-STYLE'] == 'italic') {
						$p['R']['font-style'] .= 'I';
					}
					if (isset($properties['COLOR'])) {
						$p['R']['color'] = $properties['COLOR'];
					}
				}
				if (isset($attr['LINE']) && $attr['LINE']) { // 0|1|on|off
					if ($attr['LINE'] == '1' || strtoupper($attr['LINE']) == 'ON') {
						$lineset = 1;
					} else {
						$lineset = 0;
					}
					$p['line'] = $lineset;
				}
				// mPDF 6
				if ($tag == 'PAGEHEADER') {
					$this->mpdf->DefHeaderByName($pname, $p);
				} else {
					$this->mpdf->DefFooterByName($pname, $p);
				}
				break;


			case 'SETPAGEHEADER': // mPDF 6
			case 'SETPAGEFOOTER':
			case 'SETHTMLPAGEHEADER':
			case 'SETHTMLPAGEFOOTER':
				$this->mpdf->ignorefollowingspaces = true;
				if (isset($attr['NAME']) && $attr['NAME']) {
					$pname = $attr['NAME'];
				} elseif ($tag == 'SETPAGEHEADER' || $tag == 'SETPAGEFOOTER') {
					$pname = '_nonhtmldefault';
				} // mPDF 6
				else {
					$pname = '_default';
				}
				if (isset($attr['PAGE']) && $attr['PAGE']) {  // O|odd|even|E|ALL|[blank]
					if (strtoupper($attr['PAGE']) == 'O' || strtoupper($attr['PAGE']) == 'ODD') {
						$side = 'odd';
					} elseif (strtoupper($attr['PAGE']) == 'E' || strtoupper($attr['PAGE']) == 'EVEN') {
						$side = 'even';
					} elseif (strtoupper($attr['PAGE']) == 'ALL') {
						$side = 'both';
					} else {
						$side = 'odd';
					}
				} else {
					$side = 'odd';
				}
				if (isset($attr['VALUE']) && $attr['VALUE']) {  // -1|1|on|off
					if ($attr['VALUE'] == '1' || strtoupper($attr['VALUE']) == 'ON') {
						$set = 1;
					} elseif ($attr['VALUE'] == '-1' || strtoupper($attr['VALUE']) == 'OFF') {
						$set = 0;
					} else {
						$set = 1;
					}
				} else {
					$set = 1;
				}
				if (isset($attr['SHOW-THIS-PAGE']) && $attr['SHOW-THIS-PAGE'] && ($tag == 'SETHTMLPAGEHEADER' || $tag == 'SETPAGEHEADER')) {
					$write = 1;
				} else {
					$write = 0;
				}
				if ($side == 'odd' || $side == 'both') {
					if ($set && ($tag == 'SETHTMLPAGEHEADER' || $tag == 'SETPAGEHEADER')) {
						$this->mpdf->SetHTMLHeader($this->mpdf->pageHTMLheaders[$pname], 'O', $write);
					} elseif ($set && ($tag == 'SETHTMLPAGEFOOTER' || $tag == 'SETPAGEFOOTER')) {
						$this->mpdf->SetHTMLFooter($this->mpdf->pageHTMLfooters[$pname], 'O');
					} elseif ($tag == 'SETHTMLPAGEHEADER' || $tag == 'SETPAGEHEADER') {
						$this->mpdf->SetHTMLHeader('', 'O');
					} else {
						$this->mpdf->SetHTMLFooter('', 'O');
					}
				}
				if ($side == 'even' || $side == 'both') {
					if ($set && ($tag == 'SETHTMLPAGEHEADER' || $tag == 'SETPAGEHEADER')) {
						$this->mpdf->SetHTMLHeader($this->mpdf->pageHTMLheaders[$pname], 'E', $write);
					} elseif ($set && ($tag == 'SETHTMLPAGEFOOTER' || $tag == 'SETPAGEFOOTER')) {
						$this->mpdf->SetHTMLFooter($this->mpdf->pageHTMLfooters[$pname], 'E');
					} elseif ($tag == 'SETHTMLPAGEHEADER' || $tag == 'SETPAGEHEADER') {
						$this->mpdf->SetHTMLHeader('', 'E');
					} else {
						$this->mpdf->SetHTMLFooter('', 'E');
					}
				}
				break;


			case 'TOCPAGEBREAK': // custom-tag - set Marker for insertion later of ToC AND adds PAGEBREAK
				list($isbreak, $toc_id) = $this->tableOfContents->openTagTOCPAGEBREAK($attr);
				if ($isbreak) {
					break;
				}
				if (!isset($attr['RESETPAGENUM']) || $attr['RESETPAGENUM'] < 1) {
					$attr['RESETPAGENUM'] = 1;
				} // mPDF 6
			// No break - continues as PAGEBREAK...
			/* -- END TOC -- */


			case 'PAGE_BREAK': //custom-tag
			case 'PAGEBREAK': //custom-tag
			case 'NEWPAGE': //custom-tag
			case 'FORMFEED':
				if (isset($attr['SHEET-SIZE'])) {
					// Convert to same types as accepted in initial mPDF() A4, A4-L, or array(w,h)
					$prop = preg_split('/\s+/', trim($attr['SHEET-SIZE']));
					if (count($prop) == 2) {
						$newformat = [$this->sizeConverter->convert($prop[0]), $this->sizeConverter->convert($prop[1])];
					} else {
						$newformat = $attr['SHEET-SIZE'];
					}
				} else {
					$newformat = '';
				}

				$save_blklvl = $this->mpdf->blklvl;
				$save_blk = $this->mpdf->blk;
				$save_silp = $this->mpdf->saveInlineProperties();
				$save_ilp = $this->mpdf->InlineProperties;
				$save_bflp = $this->mpdf->InlineBDF;
				$save_bflpc = $this->mpdf->InlineBDFctr; // mPDF 6

				$mgr = $mgl = $mgt = $mgb = $mgh = $mgf = '';
				if (isset($attr['MARGIN-RIGHT'])) {
					$mgr = $this->sizeConverter->convert($attr['MARGIN-RIGHT'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				if (isset($attr['MARGIN-LEFT'])) {
					$mgl = $this->sizeConverter->convert($attr['MARGIN-LEFT'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				if (isset($attr['MARGIN-TOP'])) {
					$mgt = $this->sizeConverter->convert($attr['MARGIN-TOP'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				if (isset($attr['MARGIN-BOTTOM'])) {
					$mgb = $this->sizeConverter->convert($attr['MARGIN-BOTTOM'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				if (isset($attr['MARGIN-HEADER'])) {
					$mgh = $this->sizeConverter->convert($attr['MARGIN-HEADER'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				if (isset($attr['MARGIN-FOOTER'])) {
					$mgf = $this->sizeConverter->convert($attr['MARGIN-FOOTER'], $this->mpdf->w, $this->mpdf->FontSize, false);
				}
				$ohname = $ehname = $ofname = $efname = '';
				if (isset($attr['ODD-HEADER-NAME'])) {
					$ohname = $attr['ODD-HEADER-NAME'];
				}
				if (isset($attr['EVEN-HEADER-NAME'])) {
					$ehname = $attr['EVEN-HEADER-NAME'];
				}
				if (isset($attr['ODD-FOOTER-NAME'])) {
					$ofname = $attr['ODD-FOOTER-NAME'];
				}
				if (isset($attr['EVEN-FOOTER-NAME'])) {
					$efname = $attr['EVEN-FOOTER-NAME'];
				}
				$ohvalue = $ehvalue = $ofvalue = $efvalue = 0;
				if (isset($attr['ODD-HEADER-VALUE']) && ($attr['ODD-HEADER-VALUE'] == '1' || strtoupper($attr['ODD-HEADER-VALUE']) == 'ON')) {
					$ohvalue = 1;
				} elseif (isset($attr['ODD-HEADER-VALUE']) && ($attr['ODD-HEADER-VALUE'] == '-1' || strtoupper($attr['ODD-HEADER-VALUE']) == 'OFF')) {
					$ohvalue = -1;
				}
				if (isset($attr['EVEN-HEADER-VALUE']) && ($attr['EVEN-HEADER-VALUE'] == '1' || strtoupper($attr['EVEN-HEADER-VALUE']) == 'ON')) {
					$ehvalue = 1;
				} elseif (isset($attr['EVEN-HEADER-VALUE']) && ($attr['EVEN-HEADER-VALUE'] == '-1' || strtoupper($attr['EVEN-HEADER-VALUE']) == 'OFF')) {
					$ehvalue = -1;
				}
				if (isset($attr['ODD-FOOTER-VALUE']) && ($attr['ODD-FOOTER-VALUE'] == '1' || strtoupper($attr['ODD-FOOTER-VALUE']) == 'ON')) {
					$ofvalue = 1;
				} elseif (isset($attr['ODD-FOOTER-VALUE']) && ($attr['ODD-FOOTER-VALUE'] == '-1' || strtoupper($attr['ODD-FOOTER-VALUE']) == 'OFF')) {
					$ofvalue = -1;
				}
				if (isset($attr['EVEN-FOOTER-VALUE']) && ($attr['EVEN-FOOTER-VALUE'] == '1' || strtoupper($attr['EVEN-FOOTER-VALUE']) == 'ON')) {
					$efvalue = 1;
				} elseif (isset($attr['EVEN-FOOTER-VALUE']) && ($attr['EVEN-FOOTER-VALUE'] == '-1' || strtoupper($attr['EVEN-FOOTER-VALUE']) == 'OFF')) {
					$efvalue = -1;
				}

				if (isset($attr['ORIENTATION']) && (strtoupper($attr['ORIENTATION']) == 'L' || strtoupper($attr['ORIENTATION']) == 'LANDSCAPE')) {
					$orient = 'L';
				} elseif (isset($attr['ORIENTATION']) && (strtoupper($attr['ORIENTATION']) == 'P' || strtoupper($attr['ORIENTATION']) == 'PORTRAIT')) {
					$orient = 'P';
				} else {
					$orient = $this->mpdf->CurOrientation;
				}

				if (isset($attr['PAGE-SELECTOR']) && $attr['PAGE-SELECTOR']) {
					$pagesel = $attr['PAGE-SELECTOR'];
				} else {
					$pagesel = '';
				}

				// mPDF 6 pagebreaktype
				$pagebreaktype = $this->mpdf->defaultPagebreakType;
				if ($tag == 'FORMFEED') {
					$pagebreaktype = 'slice';
				} // can be overridden by PAGE-BREAK-TYPE
				$startpage = $this->mpdf->page;
				if (isset($attr['PAGE-BREAK-TYPE'])) {
					if (strtolower($attr['PAGE-BREAK-TYPE']) == 'cloneall'
							|| strtolower($attr['PAGE-BREAK-TYPE']) == 'clonebycss'
							|| strtolower($attr['PAGE-BREAK-TYPE']) == 'slice') {
						$pagebreaktype = strtolower($attr['PAGE-BREAK-TYPE']);
					}
				}
				if ($tag == 'TOCPAGEBREAK') {
					$pagebreaktype = 'cloneall';
				} elseif ($this->mpdf->ColActive) {
					$pagebreaktype = 'cloneall';
				} // Any change in headers/footers (may need to _getHtmlHeight), or page size/orientation, @page selector, or margins - force cloneall
				elseif ($mgr !== '' || $mgl !== '' || $mgt !== '' || $mgb !== '' || $mgh !== '' || $mgf !== '' ||
					$ohname !== '' || $ehname !== '' || $ofname !== '' || $efname !== '' ||
					$ohvalue || $ehvalue || $ofvalue || $efvalue ||
					$orient != $this->mpdf->CurOrientation || $newformat || $pagesel) {
					$pagebreaktype = 'cloneall';
				}

				// mPDF 6 pagebreaktype
				$this->mpdf->_preForcedPagebreak($pagebreaktype);

				$this->mpdf->ignorefollowingspaces = true;


				$resetpagenum = '';
				$pagenumstyle = '';
				$suppress = '';
				if (isset($attr['RESETPAGENUM'])) {
					$resetpagenum = $attr['RESETPAGENUM'];
				}
				if (isset($attr['PAGENUMSTYLE'])) {
					$pagenumstyle = $attr['PAGENUMSTYLE'];
				}
				if (isset($attr['SUPPRESS'])) {
					$suppress = $attr['SUPPRESS'];
				}

				if ($tag == 'TOCPAGEBREAK') {
					$type = 'NEXT-ODD';
				} elseif (isset($attr['TYPE'])) {
					$type = strtoupper($attr['TYPE']);
				} else {
					$type = '';
				}

				if ($type == 'E' || $type == 'EVEN') {
					$this->mpdf->AddPage(
						$orient,
						'E',
						$resetpagenum,
						$pagenumstyle,
						$suppress,
						$mgl,
						$mgr,
						$mgt,
						$mgb,
						$mgh,
						$mgf,
						$ohname,
						$ehname,
						$ofname,
						$efname,
						$ohvalue,
						$ehvalue,
						$ofvalue,
						$efvalue,
						$pagesel,
						$newformat
					);
				} elseif ($type == 'O' || $type == 'ODD') {
					$this->mpdf->AddPage(
						$orient,
						'O',
						$resetpagenum,
						$pagenumstyle,
						$suppress,
						$mgl,
						$mgr,
						$mgt,
						$mgb,
						$mgh,
						$mgf,
						$ohname,
						$ehname,
						$ofname,
						$efname,
						$ohvalue,
						$ehvalue,
						$ofvalue,
						$efvalue,
						$pagesel,
						$newformat
					);
				} elseif ($type == 'NEXT-ODD') {
					$this->mpdf->AddPage(
						$orient,
						'NEXT-ODD',
						$resetpagenum,
						$pagenumstyle,
						$suppress,
						$mgl,
						$mgr,
						$mgt,
						$mgb,
						$mgh,
						$mgf,
						$ohname,
						$ehname,
						$ofname,
						$efname,
						$ohvalue,
						$ehvalue,
						$ofvalue,
						$efvalue,
						$pagesel,
						$newformat
					);
				} elseif ($type == 'NEXT-EVEN') {
					$this->mpdf->AddPage(
						$orient,
						'NEXT-EVEN',
						$resetpagenum,
						$pagenumstyle,
						$suppress,
						$mgl,
						$mgr,
						$mgt,
						$mgb,
						$mgh,
						$mgf,
						$ohname,
						$ehname,
						$ofname,
						$efname,
						$ohvalue,
						$ehvalue,
						$ofvalue,
						$efvalue,
						$pagesel,
						$newformat
					);
				} else {
					$this->mpdf->AddPage(
						$orient,
						'',
						$resetpagenum,
						$pagenumstyle,
						$suppress,
						$mgl,
						$mgr,
						$mgt,
						$mgb,
						$mgh,
						$mgf,
						$ohname,
						$ehname,
						$ofname,
						$efname,
						$ohvalue,
						$ehvalue,
						$ofvalue,
						$efvalue,
						$pagesel,
						$newformat
					);
				}

				/* -- TOC -- */
				if ($tag == 'TOCPAGEBREAK') {
					if ($toc_id) {
						$this->tableOfContents->m_TOC[$toc_id]['TOCmark'] = $this->mpdf->page;
					} else {
						$this->tableOfContents->TOCmark = $this->mpdf->page;
					}
				}
				/* -- END TOC -- */

				// mPDF 6 pagebreaktype
				$this->mpdf->_postForcedPagebreak($pagebreaktype, $startpage, $save_blk, $save_blklvl);

				$this->mpdf->InlineProperties = $save_ilp;
				$this->mpdf->InlineBDF = $save_bflp;
				$this->mpdf->InlineBDFctr = $save_bflpc; // mPDF 6
				$this->mpdf->restoreInlineProperties($save_silp);

				break;

			/* -- END INDEX -- */

			/* -- COLUMNS -- */

			case 'COLUMN_BREAK': //custom-tag
			case 'COLUMNBREAK': //custom-tag
			case 'NEWCOLUMN': //custom-tag
				$this->mpdf->ignorefollowingspaces = true;
				$this->mpdf->NewColumn();
				$this->mpdf->ColumnAdjust = false; // disables all column height adjustment for the page.
				break;

			/* -- END COLUMNS -- */

			// INLINE PHRASES OR STYLES
			case 'SUB':
			case 'SUP':
			case 'ACRONYM':
			case 'BIG':
			case 'SMALL':
			case 'INS':
			case 'S':
			case 'STRIKE':
			case 'DEL':
			case 'STRONG':
			case 'CITE':
			case 'Q':
			case 'EM':
			case 'B':
			case 'I':
			case 'U':
			case 'SAMP':
			case 'CODE':
			case 'KBD':
			case 'TT':
			case 'VAR':
			case 'FONT':
			case 'MARK':
			case 'TIME':
			case 'BDO': // mPDF 6
			case 'BDI':
			case 'SPAN':
				/* -- ANNOTATIONS -- */
				if ($this->mpdf->title2annots && isset($attr['TITLE'])) {
					$objattr = [];
					$objattr['margin_top'] = 0;
					$objattr['margin_bottom'] = 0;
					$objattr['margin_left'] = 0;
					$objattr['margin_right'] = 0;
					$objattr['width'] = 0;
					$objattr['height'] = 0;
					$objattr['border_top']['w'] = 0;
					$objattr['border_bottom']['w'] = 0;
					$objattr['border_left']['w'] = 0;
					$objattr['border_right']['w'] = 0;

					$objattr['CONTENT'] = $attr['TITLE'];
					$objattr['type'] = 'annot';
					$objattr['POS-X'] = 0;
					$objattr['POS-Y'] = 0;
					$objattr['ICON'] = 'Comment';
					$objattr['AUTHOR'] = '';
					$objattr['SUBJECT'] = '';
					$objattr['OPACITY'] = $this->mpdf->annotOpacity;
					$objattr['COLOR'] = $this->colorConverter->convert('yellow', $this->mpdf->PDFAXwarnings);
					$annot = "\xbb\xa4\xactype=annot,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
				}
				/* -- END ANNOTATIONS -- */

				// mPDF 5.7.3 Inline tags
				if (!isset($this->mpdf->InlineProperties[$tag])) {
					$this->mpdf->InlineProperties[$tag] = [$this->mpdf->saveInlineProperties()];
				} else {
					$this->mpdf->InlineProperties[$tag][] = $this->mpdf->saveInlineProperties();
				}
				if (isset($annot)) {  // *ANNOTATIONS*
					if (!isset($this->mpdf->InlineAnnots[$tag])) {
						$this->mpdf->InlineAnnots[$tag] = [$annot];
					} // *ANNOTATIONS*
					else {
						$this->mpdf->InlineAnnots[$tag][] = $annot;
					} // *ANNOTATIONS*
				} // *ANNOTATIONS*

				$properties = $this->cssManager->MergeCSS('INLINE', $tag, $attr);
				if (!empty($properties)) {
					$this->mpdf->setCSS($properties, 'INLINE');
				}

				// mPDF 6 Bidirectional formatting for inline elements
				$bdf = false;
				$bdf2 = '';
				$popd = '';

				// Get current direction
				if (isset($this->mpdf->blk[$this->mpdf->blklvl]['direction'])) {
					$currdir = $this->mpdf->blk[$this->mpdf->blklvl]['direction'];
				} else {
					$currdir = 'ltr';
				}
				if ($this->mpdf->tableLevel
						&& isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['direction'])
						&& $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['direction'] == 'rtl') {
					$currdir = 'rtl';
				}
				if (isset($attr['DIR']) and $attr['DIR'] != '') {
					$currdir = strtolower($attr['DIR']);
				}
				if (isset($properties['DIRECTION'])) {
					$currdir = strtolower($properties['DIRECTION']);
				}

				// mPDF 6 bidi
				// cf. http://www.w3.org/TR/css3-writing-modes/#unicode-bidi
				if ($tag == 'BDO') {
					if (isset($attr['DIR']) and strtolower($attr['DIR']) == 'rtl') {
						$bdf = 0x202E;
						$popd = 'RLOPDF';
					} // U+202E RLO
					elseif (isset($attr['DIR']) and strtolower($attr['DIR']) == 'ltr') {
						$bdf = 0x202D;
						$popd = 'LROPDF';
					} // U+202D LRO
				} elseif ($tag == 'BDI') {
					if (isset($attr['DIR']) and strtolower($attr['DIR']) == 'rtl') {
						$bdf = 0x2067;
						$popd = 'RLIPDI';
					} // U+2067 RLI
					elseif (isset($attr['DIR']) and strtolower($attr['DIR']) == 'ltr') {
						$bdf = 0x2066;
						$popd = 'LRIPDI';
					} // U+2066 LRI
					else {
						$bdf = 0x2068;
						$popd = 'FSIPDI';
					} // U+2068 FSI
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'bidi-override') {
					if ($currdir == 'rtl') {
						$bdf = 0x202E;
						$popd = 'RLOPDF';
					} // U+202E RLO
					else {
						$bdf = 0x202D;
						$popd = 'LROPDF';
					} // U+202D LRO
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'embed') {
					if ($currdir == 'rtl') {
						$bdf = 0x202B;
						$popd = 'RLEPDF';
					} // U+202B RLE
					else {
						$bdf = 0x202A;
						$popd = 'LREPDF';
					} // U+202A LRE
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'isolate') {
					if ($currdir == 'rtl') {
						$bdf = 0x2067;
						$popd = 'RLIPDI';
					} // U+2067 RLI
					else {
						$bdf = 0x2066;
						$popd = 'LRIPDI';
					} // U+2066 LRI
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'isolate-override') {
					if ($currdir == 'rtl') {
						$bdf = 0x2067;
						$bdf2 = 0x202E;
						$popd = 'RLIRLOPDFPDI';
					} // U+2067 RLI // U+202E RLO
					else {
						$bdf = 0x2066;
						$bdf2 = 0x202D;
						$popd = 'LRILROPDFPDI';
					} // U+2066 LRI  // U+202D LRO
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'plaintext') {
					$bdf = 0x2068;
					$popd = 'FSIPDI'; // U+2068 FSI
				} else {
					if (isset($attr['DIR']) and strtolower($attr['DIR']) == 'rtl') {
						$bdf = 0x202B;
						$popd = 'RLEPDF';
					} // U+202B RLE
					elseif (isset($attr['DIR']) and strtolower($attr['DIR']) == 'ltr') {
						$bdf = 0x202A;
						$popd = 'LREPDF';
					} // U+202A LRE
				}

				if ($bdf) {
					// mPDF 5.7.3 Inline tags
					if (!isset($this->mpdf->InlineBDF[$tag])) {
						$this->mpdf->InlineBDF[$tag] = [[$popd, $this->mpdf->InlineBDFctr]];
					} else {
						$this->mpdf->InlineBDF[$tag][] = [$popd, $this->mpdf->InlineBDFctr];
					}
					$this->mpdf->InlineBDFctr++;
					if ($bdf2) {
						$bdf2 = UtfString::code2utf($bdf);
					}
					$this->mpdf->OTLdata = [];
					if ($this->mpdf->tableLevel) {
						$this->mpdf->_saveCellTextBuffer(UtfString::code2utf($bdf) . $bdf2);
					} else {
						$this->mpdf->_saveTextBuffer(UtfString::code2utf($bdf) . $bdf2);
					}
					$this->mpdf->biDirectional = true;
				}

				break;

			case 'PROGRESS':
			case 'METER':
				$this->mpdf->inMeter = true;

				if (isset($attr['MAX']) && $attr['MAX']) {
					$max = $attr['MAX'];
				} else {
					$max = 1;
				}
				if (isset($attr['MIN']) && $attr['MIN'] && $tag == 'METER') {
					$min = $attr['MIN'];
				} else {
					$min = 0;
				}
				if ($max < $min) {
					$max = $min;
				}

				if (isset($attr['VALUE']) && ($attr['VALUE'] || $attr['VALUE'] === '0')) {
					$value = $attr['VALUE'];
					if ($value < $min) {
						$value = $min;
					} elseif ($value > $max) {
						$value = $max;
					}
				} else {
					$value = '';
				}

				if (isset($attr['LOW']) && $attr['LOW']) {
					$low = $attr['LOW'];
				} else {
					$low = $min;
				}
				if ($low < $min) {
					$low = $min;
				} elseif ($low > $max) {
					$low = $max;
				}
				if (isset($attr['HIGH']) && $attr['HIGH']) {
					$high = $attr['HIGH'];
				} else {
					$high = $max;
				}
				if ($high < $low) {
					$high = $low;
				} elseif ($high > $max) {
					$high = $max;
				}
				if (isset($attr['OPTIMUM']) && $attr['OPTIMUM']) {
					$optimum = $attr['OPTIMUM'];
				} else {
					$optimum = $min + (($max - $min) / 2);
				}
				if ($optimum < $min) {
					$optimum = $min;
				} elseif ($optimum > $max) {
					$optimum = $max;
				}
				if (isset($attr['TYPE']) && $attr['TYPE']) {
					$type = $attr['TYPE'];
				} else {
					$type = '';
				}
				$objattr = [];
				$objattr['margin_top'] = 0;
				$objattr['margin_bottom'] = 0;
				$objattr['margin_left'] = 0;
				$objattr['margin_right'] = 0;
				$objattr['padding_top'] = 0;
				$objattr['padding_bottom'] = 0;
				$objattr['padding_left'] = 0;
				$objattr['padding_right'] = 0;
				$objattr['width'] = 0;
				$objattr['height'] = 0;
				$objattr['border_top']['w'] = 0;
				$objattr['border_bottom']['w'] = 0;
				$objattr['border_left']['w'] = 0;
				$objattr['border_right']['w'] = 0;

				$properties = $this->cssManager->MergeCSS('INLINE', $tag, $attr);
				if (isset($properties ['DISPLAY']) && strtolower($properties ['DISPLAY']) == 'none') {
					return;
				}
				$objattr['visibility'] = 'visible';
				if (isset($properties['VISIBILITY'])) {
					$v = strtolower($properties['VISIBILITY']);
					if (($v == 'hidden' || $v == 'printonly' || $v == 'screenonly') && $this->mpdf->visibility == 'visible') {
						$objattr['visibility'] = $v;
					}
				}

				if (isset($properties['MARGIN-TOP'])) {
					$objattr['margin_top'] = $this->sizeConverter->convert(
						$properties['MARGIN-TOP'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['MARGIN-BOTTOM'])) {
					$objattr['margin_bottom'] = $this->sizeConverter->convert(
						$properties['MARGIN-BOTTOM'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['MARGIN-LEFT'])) {
					$objattr['margin_left'] = $this->sizeConverter->convert(
						$properties['MARGIN-LEFT'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['MARGIN-RIGHT'])) {
					$objattr['margin_right'] = $this->sizeConverter->convert(
						$properties['MARGIN-RIGHT'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}

				if (isset($properties['PADDING-TOP'])) {
					$objattr['padding_top'] = $this->sizeConverter->convert(
						$properties['PADDING-TOP'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['PADDING-BOTTOM'])) {
					$objattr['padding_bottom'] = $this->sizeConverter->convert(
						$properties['PADDING-BOTTOM'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['PADDING-LEFT'])) {
					$objattr['padding_left'] = $this->sizeConverter->convert(
						$properties['PADDING-LEFT'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}
				if (isset($properties['PADDING-RIGHT'])) {
					$objattr['padding_right'] = $this->sizeConverter->convert(
						$properties['PADDING-RIGHT'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				}

				if (isset($properties['BORDER-TOP'])) {
					$objattr['border_top'] = $this->mpdf->border_details($properties['BORDER-TOP']);
				}
				if (isset($properties['BORDER-BOTTOM'])) {
					$objattr['border_bottom'] = $this->mpdf->border_details($properties['BORDER-BOTTOM']);
				}
				if (isset($properties['BORDER-LEFT'])) {
					$objattr['border_left'] = $this->mpdf->border_details($properties['BORDER-LEFT']);
				}
				if (isset($properties['BORDER-RIGHT'])) {
					$objattr['border_right'] = $this->mpdf->border_details($properties['BORDER-RIGHT']);
				}

				if (isset($properties['VERTICAL-ALIGN'])) {
					$objattr['vertical-align'] = $align[strtolower($properties['VERTICAL-ALIGN'])];
				}
				$w = 0;
				$h = 0;
				if (isset($properties['WIDTH'])) {
					$w = $this->sizeConverter->convert(
						$properties['WIDTH'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				} elseif (isset($attr['WIDTH'])) {
					$w = $this->sizeConverter->convert($attr['WIDTH'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
				}

				if (isset($properties['HEIGHT'])) {
					$h = $this->sizeConverter->convert(
						$properties['HEIGHT'],
						$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
						$this->mpdf->FontSize,
						false
					);
				} elseif (isset($attr['HEIGHT'])) {
					$h = $this->sizeConverter->convert($attr['HEIGHT'], $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'], $this->mpdf->FontSize, false);
				}

				if (isset($properties['OPACITY']) && $properties['OPACITY'] > 0 && $properties['OPACITY'] <= 1) {
					$objattr['opacity'] = $properties['OPACITY'];
				}
				if ($this->mpdf->HREF) {
					if (strpos($this->mpdf->HREF, ".") === false && strpos($this->mpdf->HREF, "@") !== 0) {
						$href = $this->mpdf->HREF;
						while (array_key_exists($href, $this->mpdf->internallink)) {
							$href = "#" . $href;
						}
						$this->mpdf->internallink[$href] = $this->mpdf->AddLink();
						$objattr['link'] = $this->mpdf->internallink[$href];
					} else {
						$objattr['link'] = $this->mpdf->HREF;
					}
				}
				$extraheight = $objattr['padding_top'] + $objattr['padding_bottom'] + $objattr['margin_top']
					+ $objattr['margin_bottom'] + $objattr['border_top']['w'] + $objattr['border_bottom']['w'];

				$extrawidth = $objattr['padding_left'] + $objattr['padding_right'] + $objattr['margin_left']
					+ $objattr['margin_right'] + $objattr['border_left']['w'] + $objattr['border_right']['w'];

				$meter = new Meter();
				$svg = $meter->makeSVG(strtolower($tag), $type, $value, $max, $min, $optimum, $low, $high);
				//Save to local file
				$srcpath = $this->cache->write('/_tempSVG' . uniqid(random_int(1, 100000), true) . '_' . strtolower($tag) . '.svg', $svg);
				$orig_srcpath = $srcpath;
				$this->mpdf->GetFullPath($srcpath);

				$info = $this->imageProcessor->getImage($srcpath, true, true, $orig_srcpath);
				if (!$info) {
					$info = $this->imageProcessor->getImage($this->mpdf->noImageFile);
					if ($info) {
						$srcpath = $this->mpdf->noImageFile;
						$w = ($info['w'] * (25.4 / $this->mpdf->dpi));
						$h = ($info['h'] * (25.4 / $this->mpdf->dpi));
					}
				}
				if (!$info) {
					break;
				}

				$objattr['file'] = $srcpath;

				// Default width and height calculation if needed
				if ($w == 0 and $h == 0) {
					// SVG units are pixels
					$w = $this->mpdf->FontSize / (10 / Mpdf::SCALE) * abs($info['w']) / Mpdf::SCALE;
					$h = $this->mpdf->FontSize / (10 / Mpdf::SCALE) * abs($info['h']) / Mpdf::SCALE;
				}

				// IF WIDTH OR HEIGHT SPECIFIED
				if ($w == 0) {
					$w = $info['h'] ? abs($h * $info['w'] / $info['h']) : INF;
				}
				if ($h == 0) {
					$h = $info['w'] ? abs($w * $info['h'] / $info['w']) : INF;
				}

				// Resize to maximum dimensions of page
				$maxWidth = $this->mpdf->blk[$this->mpdf->blklvl]['inner_width'];
				$maxHeight = $this->mpdf->h - ($this->mpdf->tMargin + $this->mpdf->bMargin + 1);
				if ($this->mpdf->fullImageHeight) {
					$maxHeight = $this->mpdf->fullImageHeight;
				}
				if (($w + $extrawidth) > ($maxWidth + 0.0001)) { // mPDF 5.7.4  0.0001 to allow for rounding errors when w==maxWidth
					$w = $maxWidth - $extrawidth;
					$h = abs($w * $info['h'] / $info['w']);
				}

				if ($h + $extraheight > $maxHeight) {
					$h = $maxHeight - $extraheight;
					$w = abs($h * $info['w'] / $info['h']);
				}
				$objattr['type'] = 'image';
				$objattr['itype'] = $info['type'];

				$objattr['orig_h'] = $info['h'];
				$objattr['orig_w'] = $info['w'];
				$objattr['wmf_x'] = $info['x'];
				$objattr['wmf_y'] = $info['y'];
				$objattr['height'] = $h + $extraheight;
				$objattr['width'] = $w + $extrawidth;
				$objattr['image_height'] = $h;
				$objattr['image_width'] = $w;
				$e = "\xbb\xa4\xactype=image,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
				$properties = [];
				if ($this->mpdf->tableLevel) {
					$this->mpdf->_saveCellTextBuffer($e, $this->mpdf->HREF);
					$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += $objattr['width'];
				} else {
					$this->mpdf->_saveTextBuffer($e, $this->mpdf->HREF);
				}

				break;


			case 'BR':
				// Added mPDF 3.0 Float DIV - CLEAR
				if (isset($attr['STYLE'])) {
					$properties = $this->cssManager->readInlineCSS($attr['STYLE']);
					if (isset($properties['CLEAR'])) {
						$this->mpdf->ClearFloats(strtoupper($properties['CLEAR']), $this->mpdf->blklvl);
					} // *CSS-FLOAT*
				}

				// mPDF 6 bidi
				// Inline
				// If unicode-bidi set, any embedding levels, isolates, or overrides started by
				// the inline box are closed at the br and reopened on the other side
				$blockpre = '';
				$blockpost = '';
				if (isset($this->mpdf->blk[$this->mpdf->blklvl]['bidicode'])) {
					$blockpre = $this->mpdf->_setBidiCodes('end', $this->mpdf->blk[$this->mpdf->blklvl]['bidicode']);
					$blockpost = $this->mpdf->_setBidiCodes('start', $this->mpdf->blk[$this->mpdf->blklvl]['bidicode']);
				}

				// Inline
				// If unicode-bidi set, any embedding levels, isolates, or overrides started by
				// the inline box are closed at the br and reopened on the other side
				$inlinepre = '';
				$inlinepost = '';
				$iBDF = [];
				if (count($this->mpdf->InlineBDF)) {
					foreach ($this->mpdf->InlineBDF as $k => $ib) {
						foreach ($ib as $ib2) {
							$iBDF[$ib2[1]] = $ib2[0];
						}
					}
					if (count($iBDF)) {
						ksort($iBDF);
						for ($i = count($iBDF) - 1; $i >= 0; $i--) {
							$inlinepre .= $this->mpdf->_setBidiCodes('end', $iBDF[$i]);
						}
						for ($i = 0; $i < count($iBDF); $i++) {
							$inlinepost .= $this->mpdf->_setBidiCodes('start', $iBDF[$i]);
						}
					}
				}

				/* -- TABLES -- */
				if ($this->mpdf->tableLevel) {
					if ($this->mpdf->blockjustfinished) {
						$this->mpdf->_saveCellTextBuffer($blockpre . $inlinepre . "\n" . $inlinepost . $blockpost);
					}

					$this->mpdf->_saveCellTextBuffer($blockpre . $inlinepre . "\n" . $inlinepost . $blockpost);
					if (!isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'])) {
						$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
					} elseif ($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] < $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s']) {
						$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
					}
					$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] = 0; // reset
				} else {
					/* -- END TABLES -- */
					if (count($this->mpdf->textbuffer)) {
						$this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0] = preg_replace(
							'/ $/',
							'',
							$this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0]
						);
						if (!empty($this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][18])) {
							$this->otl->trimOTLdata($this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][18], false, true);
						} // *OTL*
					}
					$this->mpdf->_saveTextBuffer($blockpre . $inlinepre . "\n" . $inlinepost . $blockpost);
				} // *TABLES*
				$this->mpdf->ignorefollowingspaces = true;
				$this->mpdf->blockjustfinished = false;

				$this->mpdf->linebreakjustfinished = true;
				break;


			// *********** BLOCKS  ********************


			case 'PRE':
				$this->mpdf->ispre = true; // ADDED - Prevents left trim of textbuffer in printbuffer()

			case 'DIV':
			case 'FORM':
			case 'CENTER':
			case 'BLOCKQUOTE':
			case 'ADDRESS':
			case 'CAPTION':
			case 'P':
			case 'H1':
			case 'H2':
			case 'H3':
			case 'H4':
			case 'H5':
			case 'H6':
			case 'DL':
			case 'DT':
			case 'DD':
			case 'UL': // mPDF 6  Lists
			case 'OL': // mPDF 6
			case 'LI': // mPDF 6
			case 'FIELDSET':
			case 'DETAILS':
			case 'SUMMARY':
			case 'ARTICLE':
			case 'ASIDE':
			case 'FIGURE':
			case 'FIGCAPTION':
			case 'FOOTER':
			case 'HEADER':
			case 'HGROUP':
			case 'NAV':
			case 'SECTION':
			case 'MAIN':
				// mPDF 6  Lists
				$this->mpdf->lastoptionaltag = '';

				// mPDF 6 bidi
				// Block
				// If unicode-bidi set on current clock, any embedding levels, isolates, or overrides are closed (not inherited)
				if (isset($this->mpdf->blk[$this->mpdf->blklvl]['bidicode'])) {
					$blockpost = $this->mpdf->_setBidiCodes('end', $this->mpdf->blk[$this->mpdf->blklvl]['bidicode']);
					if ($blockpost) {
						$this->mpdf->OTLdata = [];
						if ($this->mpdf->tableLevel) {
							$this->mpdf->_saveCellTextBuffer($blockpost);
						} else {
							$this->mpdf->_saveTextBuffer($blockpost);
						}
					}
				}


				$p = $this->cssManager->PreviewBlockCSS($tag, $attr);
				if (isset($p['DISPLAY']) && strtolower($p['DISPLAY']) == 'none') {
					$this->mpdf->blklvl++;
					$this->mpdf->blk[$this->mpdf->blklvl]['hide'] = true;
					$this->mpdf->blk[$this->mpdf->blklvl]['tag'] = $tag;  // mPDF 6
					return;
				}
				if ($tag == 'CAPTION') {
					// position is written in AdjstHTML
					if (isset($attr['POSITION']) && strtolower($attr['POSITION']) == 'bottom') {
						$divpos = 'B';
					} else {
						$divpos = 'T';
					}
					if (isset($attr['ALIGN']) && strtolower($attr['ALIGN']) == 'bottom') {
						$cappos = 'B';
					} elseif (isset($p['CAPTION-SIDE']) && strtolower($p['CAPTION-SIDE']) == 'bottom') {
						$cappos = 'B';
					} else {
						$cappos = 'T';
					}
					if (isset($attr['ALIGN'])) {
						unset($attr['ALIGN']);
					}
					if ($cappos != $divpos) {
						$this->mpdf->blklvl++;
						$this->mpdf->blk[$this->mpdf->blklvl]['hide'] = true;
						$this->mpdf->blk[$this->mpdf->blklvl]['tag'] = $tag;  // mPDF 6
						return;
					}
				}

				/* -- FORMS -- */
				if ($tag == 'FORM') {
					if (isset($attr['METHOD']) && strtolower($attr['METHOD']) == 'get') {
						$this->form->formMethod = 'GET';
					} else {
						$this->form->formMethod = 'POST';
					}
					if (isset($attr['ACTION'])) {
						$this->form->formAction = $attr['ACTION'];
					} else {
						$this->form->formAction = '';
					}
				}
				/* -- END FORMS -- */


				/* -- CSS-POSITION -- */
				if ((isset($p['POSITION'])
						&& (strtolower($p['POSITION']) == 'fixed'
						|| strtolower($p['POSITION']) == 'absolute'))
						&& $this->mpdf->blklvl == 0) {
					if ($this->mpdf->inFixedPosBlock) {
						throw new \Mpdf\MpdfException("Cannot nest block with position:fixed or position:absolute");
					}
					$this->mpdf->inFixedPosBlock = true;
					return;
				}
				/* -- END CSS-POSITION -- */
				// Start Block
				$this->mpdf->ignorefollowingspaces = true;

				if ($this->mpdf->blockjustfinished && !count($this->mpdf->textbuffer)
						&& $this->mpdf->y != $this->mpdf->tMargin
						&& $this->mpdf->collapseBlockMargins) {
					$lastbottommargin = $this->mpdf->lastblockbottommargin;
				} else {
					$lastbottommargin = 0;
				}
				$this->mpdf->lastblockbottommargin = 0;
				$this->mpdf->blockjustfinished = false;


				$this->mpdf->InlineBDF = []; // mPDF 6
				$this->mpdf->InlineBDFctr = 0; // mPDF 6
				$this->mpdf->InlineProperties = [];
				$this->mpdf->divbegin = true;

				$this->mpdf->linebreakjustfinished = false;

				/* -- TABLES -- */
				if ($this->mpdf->tableLevel) {
					// If already something on the line
					if ($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] > 0 && !$this->mpdf->nestedtablejustfinished) {
						$this->mpdf->_saveCellTextBuffer("\n");
						if (!isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'])) {
							$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
						} elseif ($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] < $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s']) {
							$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
						}
						$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] = 0; // reset
					}
					// Cannot set block properties inside table - use Bold to indicate h1-h6
					if ($tag == 'CENTER' && $this->mpdf->tdbegin) {
						$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['a'] = $align['center'];
					}

					$this->mpdf->InlineProperties['BLOCKINTABLE'] = $this->mpdf->saveInlineProperties();
					$properties = $this->cssManager->MergeCSS('', $tag, $attr);
					if (!empty($properties)) {
						$this->mpdf->setCSS($properties, 'INLINE');
					}

					// mPDF 6  Lists
					if ($tag == 'UL' || $tag == 'OL') {
						$this->mpdf->listlvl++;
						if (isset($attr['START'])) {
							$this->mpdf->listcounter[$this->mpdf->listlvl] = intval($attr['START']) - 1;
						} else {
							$this->mpdf->listcounter[$this->mpdf->listlvl] = 0;
						}
						$this->mpdf->listitem = [];
						if ($tag == 'OL') {
							$this->mpdf->listtype[$this->mpdf->listlvl] = 'decimal';
						} elseif ($tag == 'UL') {
							if ($this->mpdf->listlvl % 3 == 1) {
								$this->mpdf->listtype[$this->mpdf->listlvl] = 'disc';
							} elseif ($this->mpdf->listlvl % 3 == 2) {
								$this->mpdf->listtype[$this->mpdf->listlvl] = 'circle';
							} else {
								$this->mpdf->listtype[$this->mpdf->listlvl] = 'square';
							}
						}
					}

					// mPDF 6  Lists - in Tables
					if ($tag == 'LI') {

						if ($this->mpdf->listlvl == 0) { //in case of malformed HTML code. Example:(...)</p><li>Content</li><p>Paragraph1</p>(...)
							$this->mpdf->listlvl++; // first depth level
							$this->mpdf->listcounter[$this->mpdf->listlvl] = 0;
						}

						$this->mpdf->listcounter[$this->mpdf->listlvl] ++;
						$this->mpdf->listitem = [];
						//if in table - output here as a tabletextbuffer
						//position:inside OR position:outside (always output in table as position:inside)

						$decToAlpha = new Conversion\DecToAlpha();
						$decToRoman = new Conversion\DecToRoman();

						switch ($this->mpdf->listtype[$this->mpdf->listlvl]) {
							case 'upper-alpha':
							case 'upper-latin':
							case 'A':
								$blt = $decToAlpha->convert($this->mpdf->listcounter[$this->mpdf->listlvl], true) . $this->mpdf->list_number_suffix;
								break;
							case 'lower-alpha':
							case 'lower-latin':
							case 'a':
								$blt = $decToAlpha->convert($this->mpdf->listcounter[$this->mpdf->listlvl], false) . $this->mpdf->list_number_suffix;
								break;
							case 'upper-roman':
							case 'I':
								$blt = $decToRoman->convert($this->mpdf->listcounter[$this->mpdf->listlvl], true) . $this->mpdf->list_number_suffix;
								break;
							case 'lower-roman':
							case 'i':
								$blt = $decToRoman->convert($this->mpdf->listcounter[$this->mpdf->listlvl], false) . $this->mpdf->list_number_suffix;
								break;
							case 'decimal':
							case '1':
								$blt = $this->mpdf->listcounter[$this->mpdf->listlvl] . $this->mpdf->list_number_suffix;
								break;
							default:
								if ($this->mpdf->listlvl % 3 == 1 && $this->mpdf->_charDefined($this->mpdf->CurrentFont['cw'], 8226)) {
									$blt = "\xe2\x80\xa2";
								} // &#8226;
								elseif ($this->mpdf->listlvl % 3 == 2 && $this->mpdf->_charDefined($this->mpdf->CurrentFont['cw'], 9900)) {
									$blt = "\xe2\x9a\xac";
								} // &#9900;
								elseif ($this->mpdf->listlvl % 3 == 0 && $this->mpdf->_charDefined($this->mpdf->CurrentFont['cw'], 9642)) {
									$blt = "\xe2\x96\xaa";
								} // &#9642;
								else {
									$blt = '-';
								}
								break;
						}

						// change to &nbsp; spaces
						if ($this->mpdf->usingCoreFont) {
							$ls = str_repeat(chr(160) . chr(160), ($this->mpdf->listlvl - 1) * 2) . $blt . ' ';
						} else {
							$ls = str_repeat("\xc2\xa0\xc2\xa0", ($this->mpdf->listlvl - 1) * 2) . $blt . ' ';
						}
						$this->mpdf->_saveCellTextBuffer($ls);
						$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += $this->mpdf->GetStringWidth($ls);
					}

					break;
				}
				/* -- END TABLES -- */

				if ($this->mpdf->lastblocklevelchange == 1) {
					$blockstate = 1;
				} // Top margins/padding only
				elseif ($this->mpdf->lastblocklevelchange < 1) {
					$blockstate = 0;
				} // NO margins/padding
				$this->mpdf->printbuffer($this->mpdf->textbuffer, $blockstate);
				$this->mpdf->textbuffer = [];

				$save_blklvl = $this->mpdf->blklvl;
				$save_blk = $this->mpdf->blk;

				$this->mpdf->Reset();

				$pagesel = '';
				/* -- CSS-PAGE -- */
				if (isset($p['PAGE'])) {
					$pagesel = $p['PAGE'];
				}  // mPDF 6 (uses $p - preview of properties so blklvl can be incremented after page-break)
				/* -- END CSS-PAGE -- */

				// If page-box has changed AND/OR PAGE-BREAK-BEFORE
				// mPDF 6 (uses $p - preview of properties so blklvl can be imcremented after page-break)
				if (!$this->mpdf->tableLevel && (($pagesel && (!isset($this->mpdf->page_box['current'])
								|| $pagesel != $this->mpdf->page_box['current']))
								|| (isset($p['PAGE-BREAK-BEFORE'])
								&& $p['PAGE-BREAK-BEFORE']))) {
					// mPDF 6 pagebreaktype
					$startpage = $this->mpdf->page;
					$pagebreaktype = $this->mpdf->defaultPagebreakType;
					$this->mpdf->lastblocklevelchange = -1;
					if ($this->mpdf->ColActive) {
						$pagebreaktype = 'cloneall';
					}
					if ($pagesel && (!isset($this->mpdf->page_box['current']) || $pagesel != $this->mpdf->page_box['current'])) {
						$pagebreaktype = 'cloneall';
					}
					$this->mpdf->_preForcedPagebreak($pagebreaktype);

					if (isset($p['PAGE-BREAK-BEFORE'])) {
						if (strtoupper($p['PAGE-BREAK-BEFORE']) == 'RIGHT') {
							$this->mpdf->AddPage(
								$this->mpdf->CurOrientation,
								'NEXT-ODD',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								0,
								0,
								0,
								0,
								$pagesel
							);
						} elseif (strtoupper($p['PAGE-BREAK-BEFORE']) == 'LEFT') {
							$this->mpdf->AddPage(
								$this->mpdf->CurOrientation,
								'NEXT-EVEN',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								0,
								0,
								0,
								0,
								$pagesel
							);
						} elseif (strtoupper($p['PAGE-BREAK-BEFORE']) == 'ALWAYS') {
							$this->mpdf->AddPage($this->mpdf->CurOrientation, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, $pagesel);
						} elseif ($this->mpdf->page_box['current'] != $pagesel) {
							$this->mpdf->AddPage($this->mpdf->CurOrientation, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, $pagesel);
						} // *CSS-PAGE*
					} /* -- CSS-PAGE -- */
					// Must Add new page if changed page properties
					elseif (!isset($this->mpdf->page_box['current']) || $pagesel != $this->mpdf->page_box['current']) {
						$this->mpdf->AddPage($this->mpdf->CurOrientation, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, $pagesel);
					}
					/* -- END CSS-PAGE -- */

					// mPDF 6 pagebreaktype
					$this->mpdf->_postForcedPagebreak($pagebreaktype, $startpage, $save_blk, $save_blklvl);
				}

				// mPDF 6 pagebreaktype - moved after pagebreak
				$this->mpdf->blklvl++;
				$currblk = & $this->mpdf->blk[$this->mpdf->blklvl];
				$this->mpdf->initialiseBlock($currblk);
				$prevblk = & $this->mpdf->blk[$this->mpdf->blklvl - 1];
				$currblk['tag'] = $tag;
				$currblk['attr'] = $attr;

				$properties = $this->cssManager->MergeCSS('BLOCK', $tag, $attr); // mPDF 6 - moved to after page-break-before
				// mPDF 6 page-break-inside:avoid
				if (isset($properties['PAGE-BREAK-INSIDE']) && strtoupper($properties['PAGE-BREAK-INSIDE']) == 'AVOID'
						&& !$this->mpdf->ColActive && !$this->mpdf->keep_block_together && !isset($attr['PAGEBREAKAVOIDCHECKED'])) {
					// avoid re-iterating using PAGEBREAKAVOIDCHECKED; set in CloseTag
					$currblk['keep_block_together'] = 1;
					$currblk['array_i'] = $ihtml; // mPDF 6
					$this->mpdf->kt_y00 = $this->mpdf->y;
					$this->mpdf->kt_p00 = $this->mpdf->page;
					$this->mpdf->keep_block_together = 1;
				}
				if ($lastbottommargin && isset($properties['MARGIN-TOP']) && $properties['MARGIN-TOP'] && empty($properties['FLOAT'])) {
					$currblk['lastbottommargin'] = $lastbottommargin;
				}

				if (isset($properties['Z-INDEX']) && $this->mpdf->current_layer == 0) {
					$v = intval($properties['Z-INDEX']);
					if ($v > 0) {
						$currblk['z-index'] = $v;
						$this->mpdf->BeginLayer($v);
					}
				}


				// mPDF 6  Lists
				// List-type set by attribute
				if ($tag == 'OL' || $tag == 'UL' || $tag == 'LI') {
					if (isset($attr['TYPE']) && $attr['TYPE']) {
						$listtype = $attr['TYPE'];
						switch ($listtype) {
							case 'A':
								$listtype = 'upper-latin';
								break;
							case 'a':
								$listtype = 'lower-latin';
								break;
							case 'I':
								$listtype = 'upper-roman';
								break;
							case 'i':
								$listtype = 'lower-roman';
								break;
							case '1':
								$listtype = 'decimal';
								break;
						}
						$currblk['list_style_type'] = $listtype;
					}
				}

				$this->mpdf->setCSS($properties, 'BLOCK', $tag); //name(id/class/style) found in the CSS array!
				$currblk['InlineProperties'] = $this->mpdf->saveInlineProperties();

				if (isset($properties['VISIBILITY'])) {
					$v = strtolower($properties['VISIBILITY']);
					if (($v == 'hidden' || $v == 'printonly' || $v == 'screenonly') && $this->mpdf->visibility == 'visible' && !$this->mpdf->tableLevel) {
						$currblk['visibility'] = $v;
						$this->mpdf->SetVisibility($v);
					}
				}

				// mPDF 6
				if (isset($attr['ALIGN']) && $attr['ALIGN']) {
					$currblk['block-align'] = $align[strtolower($attr['ALIGN'])];
				}


				if (isset($properties['HEIGHT'])) {
					$currblk['css_set_height'] = $this->sizeConverter->convert(
						$properties['HEIGHT'],
						($this->mpdf->h - $this->mpdf->tMargin - $this->mpdf->bMargin),
						$this->mpdf->FontSize,
						false
					);
					if (($currblk['css_set_height'] + $this->mpdf->y) > $this->mpdf->PageBreakTrigger
							&& $this->mpdf->y > $this->mpdf->tMargin + 5
							&& $currblk['css_set_height'] < ($this->mpdf->h - ($this->mpdf->tMargin + $this->mpdf->bMargin))) {
						$this->mpdf->AddPage($this->mpdf->CurOrientation);
					}
				} else {
					$currblk['css_set_height'] = false;
				}


				// Added mPDF 3.0 Float DIV
				if (isset($prevblk['blockContext'])) {
					$currblk['blockContext'] = $prevblk['blockContext'];
				} // *CSS-FLOAT*

				if (isset($properties['CLEAR'])) {
					$this->mpdf->ClearFloats(strtoupper($properties['CLEAR']), $this->mpdf->blklvl - 1);
				} // *CSS-FLOAT*

				$container_w = $prevblk['inner_width'];
				$bdr = $currblk['border_right']['w'];
				$bdl = $currblk['border_left']['w'];
				$pdr = $currblk['padding_right'];
				$pdl = $currblk['padding_left'];

				if (isset($currblk['css_set_width'])) {
					$setwidth = $currblk['css_set_width'];
				} else {
					$setwidth = 0;
				}

				/* -- CSS-FLOAT -- */
				if (isset($properties['FLOAT']) && strtoupper($properties['FLOAT']) == 'RIGHT' && !$this->mpdf->ColActive) {

					// Cancel Keep-Block-together
					$currblk['keep_block_together'] = false;
					$this->mpdf->kt_y00 = '';
					$this->mpdf->keep_block_together = 0;

					$this->mpdf->blockContext++;
					$currblk['blockContext'] = $this->mpdf->blockContext;

					list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);

					// DIV is too narrow for text to fit!
					$maxw = $container_w - $l_width - $r_width;
					$doubleCharWidth = (2 * $this->mpdf->GetCharWidth('W', false));
					if (($setwidth + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) > $maxw
							|| ($maxw - ($currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr)) < (2 * $this->mpdf->GetCharWidth('W', false))) {
						// Too narrow to fit - try to move down past L or R float
						if ($l_max < $r_max && ($setwidth + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $r_width)
								&& (($container_w - $r_width) - ($currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('LEFT', $this->mpdf->blklvl - 1);
						} elseif ($r_max < $l_max && ($setwidth + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $l_width)
								&& (($container_w - $l_width) - ($currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('RIGHT', $this->mpdf->blklvl - 1);
						} else {
							$this->mpdf->ClearFloats('BOTH', $this->mpdf->blklvl - 1);
						}
						list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);
					}

					if ($r_exists) {
						$currblk['margin_right'] += $r_width;
					}

					$currblk['float'] = 'R';
					$currblk['float_start_y'] = $this->mpdf->y;

					if (isset($currblk['css_set_width'])) {
						$currblk['margin_left'] = $container_w - ($setwidth + $bdl + $pdl + $bdr + $pdr + $currblk['margin_right']);
						$currblk['float_width'] = ($setwidth + $bdl + $pdl + $bdr + $pdr + $currblk['margin_right']);
					} else {
						// *** If no width set - would need to buffer and keep track of max width, then Right-align if not full width
						// and do borders and backgrounds - For now - just set to maximum width left

						if ($l_exists) {
							$currblk['margin_left'] += $l_width;
						}
						$currblk['css_set_width'] = $container_w - ($currblk['margin_left'] + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr);

						$currblk['float_width'] = ($currblk['css_set_width'] + $bdl + $pdl + $bdr + $pdr + $currblk['margin_right']);
					}

				} elseif (isset($properties['FLOAT']) && strtoupper($properties['FLOAT']) == 'LEFT' && !$this->mpdf->ColActive) {
					// Cancel Keep-Block-together
					$currblk['keep_block_together'] = false;
					$this->mpdf->kt_y00 = '';
					$this->mpdf->keep_block_together = 0;

					$this->mpdf->blockContext++;
					$currblk['blockContext'] = $this->mpdf->blockContext;

					list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);

					// DIV is too narrow for text to fit!
					$maxw = $container_w - $l_width - $r_width;
					$doubleCharWidth = (2 * $this->mpdf->GetCharWidth('W', false));
					if (($setwidth + $currblk['margin_left'] + $bdl + $pdl + $bdr + $pdr) > $maxw
							|| ($maxw - ($currblk['margin_left'] + $bdl + $pdl + $bdr + $pdr)) < (2 * $this->mpdf->GetCharWidth('W', false))) {
						// Too narrow to fit - try to move down past L or R float
						if ($l_max < $r_max && ($setwidth + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $r_width)
							&& (($container_w - $r_width) - ($currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('LEFT', $this->mpdf->blklvl - 1);
						} elseif ($r_max < $l_max && ($setwidth + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $l_width)
							&& (($container_w - $l_width) - ($currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('RIGHT', $this->mpdf->blklvl - 1);
						} else {
							$this->mpdf->ClearFloats('BOTH', $this->mpdf->blklvl - 1);
						}
						list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);
					}

					if ($l_exists) {
						$currblk['margin_left'] += $l_width;
					}

					$currblk['float'] = 'L';
					$currblk['float_start_y'] = $this->mpdf->y;
					if ($setwidth) {
						$currblk['margin_right'] = $container_w - ($setwidth + $bdl + $pdl + $bdr + $pdr + $currblk['margin_left']);
						$currblk['float_width'] = ($setwidth + $bdl + $pdl + $bdr + $pdr + $currblk['margin_left']);
					} else {
						// *** If no width set - would need to buffer and keep track of max width, then Right-align if not full width
						// and do borders and backgrounds - For now - just set to maximum width left

						if ($r_exists) {
							$currblk['margin_right'] += $r_width;
						}
						$currblk['css_set_width'] = $container_w - ($currblk['margin_left'] + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr);

						$currblk['float_width'] = ($currblk['css_set_width'] + $bdl + $pdl + $bdr + $pdr + $currblk['margin_left']);
					}
				} else {
					// Don't allow overlap - if floats present - adjust padding to avoid overlap with Floats
					list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);
					$maxw = $container_w - $l_width - $r_width;

					$pdl = is_int($pdl) ? $pdl : 0;
					$pdr = is_int($pdr) ? $pdr : 0;

					$doubleCharWidth = (2 * $this->mpdf->GetCharWidth('W', false));
					if (($setwidth + $currblk['margin_left'] + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) > $maxw
						|| ($maxw - ($currblk['margin_right'] + $currblk['margin_left'] + $bdl + $pdl + $bdr + $pdr)) < $doubleCharWidth) {
							// Too narrow to fit - try to move down past L or R float
						if ($l_max < $r_max && ($setwidth + $currblk['margin_left'] + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $r_width)
								&& (($container_w - $r_width) - ($currblk['margin_right'] + $currblk['margin_left'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('LEFT', $this->mpdf->blklvl - 1);
						} elseif ($r_max < $l_max && ($setwidth + $currblk['margin_left'] + $currblk['margin_right'] + $bdl + $pdl + $bdr + $pdr) <= ($container_w - $l_width)
								&& (($container_w - $l_width) - ($currblk['margin_right'] + $currblk['margin_left'] + $bdl + $pdl + $bdr + $pdr)) > $doubleCharWidth) {
							$this->mpdf->ClearFloats('RIGHT', $this->mpdf->blklvl - 1);
						} else {
							$this->mpdf->ClearFloats('BOTH', $this->mpdf->blklvl - 1);
						}
						list($l_exists, $r_exists, $l_max, $r_max, $l_width, $r_width) = $this->mpdf->GetFloatDivInfo($this->mpdf->blklvl - 1);
					}
					if ($r_exists) {
						$currblk['padding_right'] = max(($r_width - $currblk['margin_right'] - $bdr), $pdr);
					}
					if ($l_exists) {
						$currblk['padding_left'] = max(($l_width - $currblk['margin_left'] - $bdl), $pdl);
					}
				}
				/* -- END CSS-FLOAT -- */


				/* -- BORDER-RADIUS -- */
				// Automatically increase padding if required for border-radius
				if ($this->mpdf->autoPadding && !$this->mpdf->ColActive) {
					if ($currblk['border_radius_TL_H'] > $currblk['padding_left'] && $currblk['border_radius_TL_V'] > $currblk['padding_top']) {
						if ($currblk['border_radius_TL_H'] > $currblk['border_radius_TL_V']) {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_TL_H'],
								$currblk['border_radius_TL_V'],
								$currblk['padding_left'],
								$currblk['padding_top']
							);
						} else {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_TL_V'],
								$currblk['border_radius_TL_H'],
								$currblk['padding_top'],
								$currblk['padding_left']
							);
						}
					}
					if ($currblk['border_radius_TR_H'] > $currblk['padding_right'] && $currblk['border_radius_TR_V'] > $currblk['padding_top']) {
						if ($currblk['border_radius_TR_H'] > $currblk['border_radius_TR_V']) {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_TR_H'],
								$currblk['border_radius_TR_V'],
								$currblk['padding_right'],
								$currblk['padding_top']
							);
						} else {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_TR_V'],
								$currblk['border_radius_TR_H'],
								$currblk['padding_top'],
								$currblk['padding_right']
							);
						}
					}
					if ($currblk['border_radius_BL_H'] > $currblk['padding_left'] && $currblk['border_radius_BL_V'] > $currblk['padding_bottom']) {
						if ($currblk['border_radius_BL_H'] > $currblk['border_radius_BL_V']) {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_BL_H'],
								$currblk['border_radius_BL_V'],
								$currblk['padding_left'],
								$currblk['padding_bottom']
							);
						} else {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_BL_V'],
								$currblk['border_radius_BL_H'],
								$currblk['padding_bottom'],
								$currblk['padding_left']
							);
						}
					}
					if ($currblk['border_radius_BR_H'] > $currblk['padding_right'] && $currblk['border_radius_BR_V'] > $currblk['padding_bottom']) {
						if ($currblk['border_radius_BR_H'] > $currblk['border_radius_BR_V']) {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_BR_H'],
								$currblk['border_radius_BR_V'],
								$currblk['padding_right'],
								$currblk['padding_bottom']
							);
						} else {
							$this->mpdf->_borderPadding(
								$currblk['border_radius_BR_V'],
								$currblk['border_radius_BR_H'],
								$currblk['padding_bottom'],
								$currblk['padding_right']
							);
						}
					}
				}
				/* -- END BORDER-RADIUS -- */

				// Hanging indent - if negative indent: ensure padding is >= indent
				if (!isset($currblk['text_indent'])) {
					$currblk['text_indent'] = null;
				}
				if (!isset($currblk['inner_width'])) {
					$currblk['inner_width'] = null;
				}
				$cbti = $this->sizeConverter->convert(
					$currblk['text_indent'],
					$this->mpdf->blk[$this->mpdf->blklvl]['inner_width'],
					$this->mpdf->FontSize,
					false
				);
				if ($cbti < 0) {
					$hangind = -($cbti);
					if (isset($currblk['direction']) && $currblk['direction'] == 'rtl') { // *OTL*
						$currblk['padding_right'] = max($currblk['padding_right'], $hangind); // *OTL*
					} // *OTL*
					else { // *OTL*
						$currblk['padding_left'] = max($currblk['padding_left'], $hangind);
					} // *OTL*
				}

				if (isset($currblk['css_set_width'])) {
					if (isset($properties['MARGIN-LEFT']) && isset($properties['MARGIN-RIGHT'])
							&& strtolower($properties['MARGIN-LEFT']) == 'auto' && strtolower($properties['MARGIN-RIGHT']) == 'auto') {
						// Try to reduce margins to accomodate - if still too wide, set margin-right/left=0 (reduces width)
						$anyextra = $prevblk['inner_width'] - ($currblk['css_set_width'] + $currblk['border_left']['w']
								+ $currblk['padding_left'] + $currblk['border_right']['w'] + $currblk['padding_right']);
						if ($anyextra > 0) {
							$currblk['margin_left'] = $currblk['margin_right'] = $anyextra / 2;
						} else {
							$currblk['margin_left'] = $currblk['margin_right'] = 0;
						}
					} elseif (isset($properties['MARGIN-LEFT']) && strtolower($properties['MARGIN-LEFT']) == 'auto') {
						// Try to reduce margin-left to accomodate - if still too wide, set margin-left=0 (reduces width)
						$currblk['margin_left'] = $prevblk['inner_width'] - ($currblk['css_set_width']
								+ $currblk['border_left']['w'] + $currblk['padding_left'] + $currblk['border_right']['w']
								+ $currblk['padding_right'] + $currblk['margin_right']);
						if ($currblk['margin_left'] < 0) {
							$currblk['margin_left'] = 0;
						}
					} elseif (isset($properties['MARGIN-RIGHT']) && strtolower($properties['MARGIN-RIGHT']) == 'auto') {
						// Try to reduce margin-right to accomodate - if still too wide, set margin-right=0 (reduces width)
						$currblk['margin_right'] = $prevblk['inner_width'] - ($currblk['css_set_width']
								+ $currblk['border_left']['w'] + $currblk['padding_left']
								+ $currblk['border_right']['w'] + $currblk['padding_right'] + $currblk['margin_left']);
						if ($currblk['margin_right'] < 0) {
							$currblk['margin_right'] = 0;
						}
					} else {
						if ($currblk['direction'] == 'rtl') { // *OTL*
							// Try to reduce margin-left to accomodate - if still too wide, set margin-left=0 (reduces width)
							$currblk['margin_left'] = $prevblk['inner_width'] - ($currblk['css_set_width']
									+ $currblk['border_left']['w'] + $currblk['padding_left'] + $currblk['border_right']['w']
									+ $currblk['padding_right'] + $currblk['margin_right']); // *OTL*
							if ($currblk['margin_left'] < 0) { // *OTL*
								$currblk['margin_left'] = 0; // *OTL*
							} // *OTL*
						} // *OTL*
						else { // *OTL*
							// Try to reduce margin-right to accomodate - if still too wide, set margin-right=0 (reduces width)
							$currblk['margin_right'] = $prevblk['inner_width'] - ($currblk['css_set_width']
									+ $currblk['border_left']['w'] + $currblk['padding_left'] + $currblk['border_right']['w']
									+ $currblk['padding_right'] + $currblk['margin_left']);
							if ($currblk['margin_right'] < 0) {
								$currblk['margin_right'] = 0;
							}
						} // *OTL*
					}
				}

				$currblk['outer_left_margin'] = $prevblk['outer_left_margin'] + $currblk['margin_left']
					+ $prevblk['border_left']['w'] + $prevblk['padding_left'];

				$currblk['outer_right_margin'] = $prevblk['outer_right_margin'] + $currblk['margin_right']
					+ $prevblk['border_right']['w'] + $prevblk['padding_right'];

				$currblk['width'] = $this->mpdf->pgwidth - ($currblk['outer_right_margin'] + $currblk['outer_left_margin']);

				$currblk['padding_left'] = is_numeric($currblk['padding_left']) ? $currblk['padding_left'] : 0;
				$currblk['padding_right'] = is_numeric($currblk['padding_right']) ? $currblk['padding_right'] : 0;

				$currblk['inner_width'] = $currblk['width']
					- ($currblk['border_left']['w'] + $currblk['padding_left'] + $currblk['border_right']['w'] + $currblk['padding_right']);

				// Check DIV is not now too narrow to fit text
				$mw = 2 * $this->mpdf->GetCharWidth('W', false);
				if ($currblk['inner_width'] < $mw) {
					$currblk['padding_left'] = 0;
					$currblk['padding_right'] = 0;
					$currblk['border_left']['w'] = 0.2;
					$currblk['border_right']['w'] = 0.2;
					$currblk['margin_left'] = 0;
					$currblk['margin_right'] = 0;
					$currblk['outer_left_margin'] = $prevblk['outer_left_margin'] + $currblk['margin_left']
						+ $prevblk['border_left']['w'] + $prevblk['padding_left'];
					$currblk['outer_right_margin'] = $prevblk['outer_right_margin'] + $currblk['margin_right']
						+ $prevblk['border_right']['w'] + $prevblk['padding_right'];
					$currblk['width'] = $this->mpdf->pgwidth - ($currblk['outer_right_margin'] + $currblk['outer_left_margin']);
					$currblk['inner_width'] = $this->mpdf->pgwidth - ($currblk['outer_right_margin']
							+ $currblk['outer_left_margin'] + $currblk['border_left']['w'] + $currblk['padding_left']
							+ $currblk['border_right']['w'] + $currblk['padding_right']);
					// if ($currblk['inner_width'] < $mw) { throw new \Mpdf\MpdfException("DIV is too narrow for text to fit!"); }
				}

				$this->mpdf->x = $this->mpdf->lMargin + $currblk['outer_left_margin'];

				/* -- BACKGROUNDS -- */
				if (isset($properties['BACKGROUND-IMAGE']) && $properties['BACKGROUND-IMAGE']
						&& !$this->mpdf->kwt && !$this->mpdf->ColActive && !$this->mpdf->keep_block_together) {
					$ret = $this->mpdf->SetBackground($properties, $currblk['inner_width']);
					if ($ret) {
						$currblk['background-image'] = $ret;
					}
				}
				/* -- END BACKGROUNDS -- */

				/* -- TABLES -- */
				if ($this->mpdf->use_kwt && isset($attr['KEEP-WITH-TABLE']) && !$this->mpdf->ColActive && !$this->mpdf->keep_block_together) {
					$this->mpdf->kwt = true;
					$this->mpdf->kwt_y0 = $this->mpdf->y;
					//$this->mpdf->kwt_x0 = $this->mpdf->x;
					$this->mpdf->kwt_x0 = $this->mpdf->lMargin; // mPDF 6
					$this->mpdf->kwt_height = 0;
					$this->mpdf->kwt_buffer = [];
					$this->mpdf->kwt_Links = [];
					$this->mpdf->kwt_Annots = [];
					$this->mpdf->kwt_moved = false;
					$this->mpdf->kwt_saved = false;
					$this->mpdf->kwt_Reference = [];
					$this->mpdf->kwt_BMoutlines = [];
					$this->mpdf->kwt_toc = [];
				} else {
					/* -- END TABLES -- */
					$this->mpdf->kwt = false;
				} // *TABLES*

				// Save x,y coords in case we need to print borders...
				$currblk['y0'] = $this->mpdf->y;
				$currblk['initial_y0'] = $this->mpdf->y; // mPDF 6
				$currblk['x0'] = $this->mpdf->x;
				$currblk['initial_x0'] = $this->mpdf->x; // mPDF 6
				$currblk['initial_startpage'] = $this->mpdf->page;
				$currblk['startpage'] = $this->mpdf->page; // mPDF 6
				$this->mpdf->oldy = $this->mpdf->y;

				$this->mpdf->lastblocklevelchange = 1;

				// mPDF 6  Lists
				if ($tag == 'OL' || $tag == 'UL') {
					$this->mpdf->listlvl++;
					if (isset($attr['START']) && $attr['START']) {
						$this->mpdf->listcounter[$this->mpdf->listlvl] = intval($attr['START']) - 1;
					} else {
						$this->mpdf->listcounter[$this->mpdf->listlvl] = 0;
					}
					$this->mpdf->listitem = [];

					// List-type
					if (!isset($currblk['list_style_type']) || !$currblk['list_style_type']) {
						if ($tag == 'OL') {
							$currblk['list_style_type'] = 'decimal';
						} elseif ($tag == 'UL') {
							if ($this->mpdf->listlvl % 3 == 1) {
								$currblk['list_style_type'] = 'disc';
							} elseif ($this->mpdf->listlvl % 3 == 2) {
								$currblk['list_style_type'] = 'circle';
							} else {
								$currblk['list_style_type'] = 'square';
							}
						}
					}

					// List-image
					if (!isset($currblk['list_style_image']) || !$currblk['list_style_image']) {
						$currblk['list_style_image'] = 'none';
					}

					// List-position
					if (!isset($currblk['list_style_position']) || !$currblk['list_style_position']) {
						$currblk['list_style_position'] = 'outside';
					}

					// Default indentation using padding
					if (strtolower($this->mpdf->list_auto_mode) == 'mpdf' && isset($currblk['list_style_position'])
							&& $currblk['list_style_position'] == 'outside' && isset($currblk['list_style_image'])
							&& $currblk['list_style_image'] == 'none' && (!isset($currblk['list_style_type'])
							|| !preg_match('/U\+([a-fA-F0-9]+)/i', $currblk['list_style_type']))) {
						$autopadding = $this->mpdf->_getListMarkerWidth($currblk, $ahtml, $ihtml);
						if ($this->mpdf->listlvl > 1 || $this->mpdf->list_indent_first_level) {
							$autopadding += $this->sizeConverter->convert(
								$this->mpdf->list_indent_default,
								$currblk['inner_width'],
								$this->mpdf->FontSize,
								false
							);
						}
						// autopadding value is applied to left or right according
						// to dir of block. Once a CSS value is set for padding it overrides this default value.
						if (isset($properties['PADDING-RIGHT']) && $properties['PADDING-RIGHT'] == 'auto'
								&& isset($currblk['direction']) && $currblk['direction'] == 'rtl') {
							$currblk['padding_right'] = $autopadding;
						} elseif (isset($properties['PADDING-LEFT']) && $properties['PADDING-LEFT'] == 'auto') {
							$currblk['padding_left'] = $autopadding;
						}
					} else {
						// Initial default value is set by $this->mpdf->list_indent_default in config.php; this value is applied to left or right according
						// to dir of block. Once a CSS value is set for padding it overrides this default value.
						if (isset($properties['PADDING-RIGHT']) && $properties['PADDING-RIGHT'] == 'auto'
								&& isset($currblk['direction']) && $currblk['direction'] == 'rtl') {
							$currblk['padding_right'] = $this->sizeConverter->convert(
								$this->mpdf->list_indent_default,
								$currblk['inner_width'],
								$this->mpdf->FontSize,
								false
							);
						} elseif (isset($properties['PADDING-LEFT']) && $properties['PADDING-LEFT'] == 'auto') {
							$currblk['padding_left'] = $this->sizeConverter->convert(
								$this->mpdf->list_indent_default,
								$currblk['inner_width'],
								$this->mpdf->FontSize,
								false
							);
						}
					}
				}


				// mPDF 6  Lists
				if ($tag == 'LI') {
					if ($this->mpdf->listlvl == 0) { //in case of malformed HTML code. Example:(...)</p><li>Content</li><p>Paragraph1</p>(...)
						$this->mpdf->listlvl++; // first depth level
						$this->mpdf->listcounter[$this->mpdf->listlvl] = 0;
					}
					$this->mpdf->listcounter[$this->mpdf->listlvl] ++;
					$this->mpdf->listitem = [];

					// Listitem-type
					$this->mpdf->_setListMarker($currblk['list_style_type'], $currblk['list_style_image'], $currblk['list_style_position']);
				}

				// mPDF 6 Bidirectional formatting for block elements
				$bdf = false;
				$bdf2 = '';
				$popd = '';

				// Get current direction
				if (isset($currblk['direction'])) {
					$currdir = $currblk['direction'];
				} else {
					$currdir = 'ltr';
				}
				if (isset($attr['DIR']) and $attr['DIR'] != '') {
					$currdir = strtolower($attr['DIR']);
				}
				if (isset($properties['DIRECTION'])) {
					$currdir = strtolower($properties['DIRECTION']);
				}

				// mPDF 6 bidi
				// cf. http://www.w3.org/TR/css3-writing-modes/#unicode-bidi
				if (isset($properties ['UNICODE-BIDI'])
						&& (strtolower($properties ['UNICODE-BIDI']) == 'bidi-override' || strtolower($properties ['UNICODE-BIDI']) == 'isolate-override')) {
					if ($currdir == 'rtl') {
						$bdf = 0x202E;
						$popd = 'RLOPDF';
					} // U+202E RLO
					else {
						$bdf = 0x202D;
						$popd = 'LROPDF';
					} // U+202D LRO
				} elseif (isset($properties ['UNICODE-BIDI']) && strtolower($properties ['UNICODE-BIDI']) == 'plaintext') {
					$bdf = 0x2068;
					$popd = 'FSIPDI'; // U+2068 FSI
				}
				if ($bdf) {
					if ($bdf2) {
						$bdf2 = UtfString::code2utf($bdf);
					}
					$this->mpdf->OTLdata = [];
					if ($this->mpdf->tableLevel) {
						$this->mpdf->_saveCellTextBuffer(code2utf($bdf) . $bdf2);
					} else {
						$this->mpdf->_saveTextBuffer(code2utf($bdf) . $bdf2);
					}
					$this->mpdf->biDirectional = true;
					$currblk['bidicode'] = $popd;
				}

				break;


			// *********** FORM ELEMENTS ********************

			/* -- FORMS -- */
			case 'SELECT':
				$this->mpdf->lastoptionaltag = ''; // Save current HTML specified optional endtag
				$this->mpdf->InlineProperties[$tag] = $this->mpdf->saveInlineProperties();
				$properties = $this->cssManager->MergeCSS('', $tag, $attr);
				if (isset($properties['FONT-FAMILY'])) {
					$this->mpdf->SetFont($properties['FONT-FAMILY'], $this->mpdf->FontStyle, 0, false);
				}
				if (isset($properties['FONT-SIZE'])) {
					$mmsize = $this->sizeConverter->convert($properties['FONT-SIZE'], $this->mpdf->default_font_size / Mpdf::SCALE);
					$this->mpdf->SetFontSize($mmsize * Mpdf::SCALE, false);
				}
				if (isset($attr['SPELLCHECK']) && strtolower($attr['SPELLCHECK']) == 'true') {
					$this->mpdf->selectoption['SPELLCHECK'] = true;
				}

				if (isset($properties['COLOR'])) {
					$this->mpdf->selectoption['COLOR'] = $this->colorConverter->convert($properties['COLOR'], $this->mpdf->PDFAXwarnings);
				}
				$this->mpdf->specialcontent = "type=select";
				if (isset($attr['DISABLED'])) {
					$this->mpdf->selectoption['DISABLED'] = $attr['DISABLED'];
				}
				if (isset($attr['READONLY'])) {
					$this->mpdf->selectoption['READONLY'] = $attr['READONLY'];
				}
				if (isset($attr['REQUIRED'])) {
					$this->mpdf->selectoption['REQUIRED'] = $attr['REQUIRED'];
				}
				if (isset($attr['EDITABLE'])) {
					$this->mpdf->selectoption['EDITABLE'] = $attr['EDITABLE'];
				}
				if (isset($attr['TITLE'])) {
					$this->mpdf->selectoption['TITLE'] = $attr['TITLE'];
				}
				if (isset($attr['MULTIPLE'])) {
					$this->mpdf->selectoption['MULTIPLE'] = $attr['MULTIPLE'];
				}
				if (isset($attr['SIZE']) && $attr['SIZE'] > 1) {
					$this->mpdf->selectoption['SIZE'] = $attr['SIZE'];
				}
				if ($this->mpdf->useActiveForms) {
					if (isset($attr['NAME'])) {
						$this->mpdf->selectoption['NAME'] = $attr['NAME'];
					}
					if (isset($attr['ONCHANGE'])) {
						$this->mpdf->selectoption['ONCHANGE'] = $attr['ONCHANGE'];
					}
				}

				$properties = [];
				break;

			/* -- END FORMS -- */

			/* -- TABLES -- */

			case 'TR':
				$this->mpdf->lastoptionaltag = $tag; // Save current HTML specified optional endtag
				$this->cssManager->tbCSSlvl++;
				$this->mpdf->row++;
				$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['nr'] ++;
				$this->mpdf->col = -1;
				$properties = $this->cssManager->MergeCSS('TABLE', $tag, $attr);

				if (!$this->mpdf->simpleTables && (!isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['borders_separate'])
						|| !$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['borders_separate'])) {
					if (isset($properties['BORDER-LEFT']) && $properties['BORDER-LEFT']) {
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-left'][$this->mpdf->row] = $properties['BORDER-LEFT'];
					}
					if (isset($properties['BORDER-RIGHT']) && $properties['BORDER-RIGHT']) {
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-right'][$this->mpdf->row] = $properties['BORDER-RIGHT'];
					}
					if (isset($properties['BORDER-TOP']) && $properties['BORDER-TOP']) {
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-top'][$this->mpdf->row] = $properties['BORDER-TOP'];
					}
					if (isset($properties['BORDER-BOTTOM']) && $properties['BORDER-BOTTOM']) {
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-bottom'][$this->mpdf->row] = $properties['BORDER-BOTTOM'];
					}
				}

				if (isset($properties['BACKGROUND-COLOR'])) {
					$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['bgcolor'][$this->mpdf->row] = $properties['BACKGROUND-COLOR'];
				} elseif (isset($attr['BGCOLOR'])) {
					$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['bgcolor'][$this->mpdf->row] = $attr['BGCOLOR'];
				}

				/* -- BACKGROUNDS -- */
				if (isset($properties['BACKGROUND-GRADIENT']) && !$this->mpdf->kwt && !$this->mpdf->ColActive) {
					$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trgradients'][$this->mpdf->row] = $properties['BACKGROUND-GRADIENT'];
				}

				if (isset($properties['BACKGROUND-IMAGE']) && $properties['BACKGROUND-IMAGE'] && !$this->mpdf->kwt && !$this->mpdf->ColActive) {
					$ret = $this->mpdf->SetBackground($properties, $currblk['inner_width']);
					if ($ret) {
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trbackground-images'][$this->mpdf->row] = $ret;
					}
				}
				/* -- END BACKGROUNDS -- */

				if (isset($properties['TEXT-ROTATE'])) {
					$this->mpdf->trow_text_rotate = $properties['TEXT-ROTATE'];
				}
				if (isset($attr['TEXT-ROTATE'])) {
					$this->mpdf->trow_text_rotate = $attr['TEXT-ROTATE'];
				}

				if ($this->mpdf->tablethead) {
					$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['is_thead'][$this->mpdf->row] = true;
				}
				if ($this->mpdf->tabletfoot) {
					$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['is_tfoot'][$this->mpdf->row] = true;
				}
				$properties = [];
				break;
			/* -- END TABLES -- */
		}//end of switch
	}

	public function CloseTag($tag, &$ahtml, &$ihtml)
	{
		if ($object = $this->getTagInstance($tag)) {
			return $object->close($ahtml, $ihtml);
		}
	// mPDF 6


		if ($tag == 'FONT' || $tag == 'SPAN' || $tag == 'CODE' || $tag == 'KBD' || $tag == 'SAMP' || $tag == 'TT'
			|| $tag == 'VAR' || $tag == 'INS' || $tag == 'STRONG' || $tag == 'CITE' || $tag == 'SUB' || $tag == 'SUP'
			|| $tag == 'S' || $tag == 'STRIKE' || $tag == 'DEL' || $tag == 'Q' || $tag == 'EM' || $tag == 'B'
			|| $tag == 'I' || $tag == 'U' | $tag == 'SMALL' || $tag == 'BIG' || $tag == 'ACRONYM' || $tag == 'MARK'
			|| $tag == 'TIME' || $tag == 'PROGRESS' || $tag == 'METER' || $tag == 'BDO' || $tag == 'BDI'
		) {
			$annot = false; // mPDF 6
			$bdf = false; // mPDF 6
			// mPDF 5.7.3 Inline tags
			if ($tag == 'PROGRESS' || $tag == 'METER') {
				if (isset($this->mpdf->InlineProperties[$tag]) && $this->mpdf->InlineProperties[$tag]) {
					$this->mpdf->restoreInlineProperties($this->mpdf->InlineProperties[$tag]);
				}
				unset($this->mpdf->InlineProperties[$tag]);
				if (isset($this->mpdf->InlineAnnots[$tag]) && $this->mpdf->InlineAnnots[$tag]) {
					$annot = $this->mpdf->InlineAnnots[$tag];
				} // *ANNOTATIONS*
				unset($this->mpdf->InlineAnnots[$tag]); // *ANNOTATIONS*
			} else {
				if (isset($this->mpdf->InlineProperties[$tag]) && count($this->mpdf->InlineProperties[$tag])) {
					$tmpProps = array_pop($this->mpdf->InlineProperties[$tag]); // mPDF 5.7.4
					$this->mpdf->restoreInlineProperties($tmpProps);
				}
				if (isset($this->mpdf->InlineAnnots[$tag]) && count($this->mpdf->InlineAnnots[$tag])) {  // *ANNOTATIONS*
					$annot = array_pop($this->mpdf->InlineAnnots[$tag]);  // *ANNOTATIONS*
				} // *ANNOTATIONS*
				if (isset($this->mpdf->InlineBDF[$tag]) && count($this->mpdf->InlineBDF[$tag])) {  // mPDF 6
					$bdfarr = array_pop($this->mpdf->InlineBDF[$tag]);
					$bdf = $bdfarr[0];
				}
			}

			/* -- ANNOTATIONS -- */
			if ($annot) { // mPDF 6
				if ($this->mpdf->tableLevel) { // *TABLES*
					$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['textbuffer'][] = [$annot]; // *TABLES*
				} // *TABLES*
				else { // *TABLES*
					$this->mpdf->textbuffer[] = [$annot];
				} // *TABLES*
			}
			/* -- END ANNOTATIONS -- */

			// mPDF 6 bidi
			// mPDF 6 Bidirectional formatting for inline elements
			if ($bdf) {
				$popf = $this->mpdf->_setBidiCodes('end', $bdf);
				$this->mpdf->OTLdata = [];
				if ($this->mpdf->tableLevel) {
					$this->mpdf->_saveCellTextBuffer($popf);
				} else {
					$this->mpdf->_saveTextBuffer($popf);
				}
			}
		} // End of (most) Inline elements eg SPAN


		if ($tag == 'METER' || $tag == 'PROGRESS') {
			$this->mpdf->ignorefollowingspaces = false;
			$this->mpdf->inMeter = false;
		}

		/* -- FORMS -- */
		// *********** FORM ELEMENTS ********************

		if ($tag == 'SELECT') {
			$this->mpdf->ignorefollowingspaces = false;
			$this->mpdf->lastoptionaltag = '';
			$texto = '';
			$OTLdata = false;
			if (isset($this->mpdf->selectoption['SELECTED'])) {
				$texto = $this->mpdf->selectoption['SELECTED'];
			}
			if (isset($this->mpdf->selectoption['SELECTED-OTLDATA'])) {
				$OTLdata = $this->mpdf->selectoption['SELECTED-OTLDATA'];
			}

			if ($this->mpdf->useActiveForms) {
				$w = $this->mpdf->selectoption['MAXWIDTH'];
			} else {
				$w = $this->mpdf->GetStringWidth($texto, true, $OTLdata);
			}
			if ($w == 0) {
				$w = 5;
			}
			$objattr['type'] = 'select';
			$objattr['text'] = $texto;
			$objattr['OTLdata'] = $OTLdata;
			if (isset($this->mpdf->selectoption['NAME'])) {
				$objattr['fieldname'] = $this->mpdf->selectoption['NAME'];
			}
			if (isset($this->mpdf->selectoption['READONLY'])) {
				$objattr['readonly'] = true;
			}
			if (isset($this->mpdf->selectoption['REQUIRED'])) {
				$objattr['required'] = true;
			}
			if (isset($this->mpdf->selectoption['SPELLCHECK'])) {
				$objattr['spellcheck'] = true;
			}
			if (isset($this->mpdf->selectoption['EDITABLE'])) {
				$objattr['editable'] = true;
			}
			if (isset($this->mpdf->selectoption['ONCHANGE'])) {
				$objattr['onChange'] = $this->mpdf->selectoption['ONCHANGE'];
			}
			if (isset($this->mpdf->selectoption['ITEMS'])) {
				$objattr['items'] = $this->mpdf->selectoption['ITEMS'];
			}
			if (isset($this->mpdf->selectoption['MULTIPLE'])) {
				$objattr['multiple'] = $this->mpdf->selectoption['MULTIPLE'];
			}
			if (isset($this->mpdf->selectoption['DISABLED'])) {
				$objattr['disabled'] = $this->mpdf->selectoption['DISABLED'];
			}
			if (isset($this->mpdf->selectoption['TITLE'])) {
				$objattr['title'] = $this->mpdf->selectoption['TITLE'];
			}
			if (isset($this->mpdf->selectoption['COLOR'])) {
				$objattr['color'] = $this->mpdf->selectoption['COLOR'];
			}
			if (isset($this->mpdf->selectoption['SIZE'])) {
				$objattr['size'] = $this->mpdf->selectoption['SIZE'];
			}
			if (isset($objattr['size']) && $objattr['size'] > 1) {
				$rows = $objattr['size'];
			} else {
				$rows = 1;
			}

			$objattr['fontfamily'] = $this->mpdf->FontFamily;
			$objattr['fontsize'] = $this->mpdf->FontSizePt;

			$objattr['width'] = $w + ($this->form->form_element_spacing['select']['outer']['h'] * 2)
				+ ($this->form->form_element_spacing['select']['inner']['h'] * 2) + ($this->mpdf->FontSize * 1.4);

			$objattr['height'] = ($this->mpdf->FontSize * $rows) + ($this->form->form_element_spacing['select']['outer']['v'] * 2)
				+ ($this->form->form_element_spacing['select']['inner']['v'] * 2);

			$e = "\xbb\xa4\xactype=select,objattr=" . serialize($objattr) . "\xbb\xa4\xac";

			// Clear properties - tidy up
			$properties = [];

			// Output it to buffers
			if ($this->mpdf->tableLevel) { // *TABLES*
				$this->mpdf->_saveCellTextBuffer($e, $this->mpdf->HREF);
				$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += $objattr['width']; // *TABLES*
			} // *TABLES*
			else { // *TABLES*
				$this->mpdf->_saveTextBuffer($e, $this->mpdf->HREF);
			} // *TABLES*

			$this->mpdf->selectoption = [];
			$this->mpdf->specialcontent = '';

			if ($this->mpdf->InlineProperties[$tag]) {
				$this->mpdf->restoreInlineProperties($this->mpdf->InlineProperties[$tag]);
			}
			unset($this->mpdf->InlineProperties[$tag]);
		}
		/* -- END FORMS -- */

		// *********** BLOCKS ********************
		// mPDF 6  Lists
		if ($tag == 'P' || $tag == 'DIV' || $tag == 'H1' || $tag == 'H2' || $tag == 'H3' || $tag == 'H4'
			|| $tag == 'H5' || $tag == 'H6' || $tag == 'PRE' || $tag == 'FORM' || $tag == 'ADDRESS'
			|| $tag == 'BLOCKQUOTE' || $tag == 'CENTER' || $tag == 'DT' || $tag == 'DD' || $tag == 'DL'
			|| $tag == 'CAPTION' || $tag == 'FIELDSET' || $tag == 'UL' || $tag == 'OL' || $tag == 'LI'
			|| $tag == 'ARTICLE' || $tag == 'ASIDE' || $tag == 'FIGURE' || $tag == 'FIGCAPTION' || $tag == 'FOOTER'
			|| $tag == 'HEADER' || $tag == 'HGROUP' || $tag == 'MAIN' || $tag == 'NAV' || $tag == 'SECTION'
			|| $tag == 'DETAILS' || $tag == 'SUMMARY'
		) {
			// mPDF 6 bidi
			// Block
			// If unicode-bidi set, any embedding levels, isolates, or overrides started by this box are closed
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['bidicode'])) {
				$blockpost = $this->mpdf->_setBidiCodes('end', $this->mpdf->blk[$this->mpdf->blklvl]['bidicode']);
				if ($blockpost) {
					$this->mpdf->OTLdata = [];
					if ($this->mpdf->tableLevel) {
						$this->mpdf->_saveCellTextBuffer($blockpost);
					} else {
						$this->mpdf->_saveTextBuffer($blockpost);
					}
				}
			}

			$this->mpdf->ignorefollowingspaces = true; //Eliminate exceeding left-side spaces
			$this->mpdf->blockjustfinished = true;

			$this->mpdf->lastblockbottommargin = $this->mpdf->blk[$this->mpdf->blklvl]['margin_bottom'];
			// mPDF 6  Lists
			if ($tag == 'UL' || $tag == 'OL') {
				if ($this->mpdf->listlvl > 0 && $this->mpdf->tableLevel) {
					if (isset($this->mpdf->listtype[$this->mpdf->listlvl])) {
						unset($this->mpdf->listtype[$this->mpdf->listlvl]);
					}
				}
				$this->mpdf->listlvl--;
				$this->mpdf->listitem = [];
			}
			if ($tag == 'LI') {
				$this->mpdf->listitem = [];
			}

			if (preg_match('/^H\d/', $tag) && !$this->mpdf->tableLevel && !$this->mpdf->writingToC) {
				if (isset($this->mpdf->h2toc[$tag]) || isset($this->mpdf->h2bookmarks[$tag])) {
					$content = '';
					if (count($this->mpdf->textbuffer) == 1) {
						$content = $this->mpdf->textbuffer[0][0];
					} else {
						for ($i = 0; $i < count($this->mpdf->textbuffer); $i++) {
							if (substr($this->mpdf->textbuffer[$i][0], 0, 3) != "\xbb\xa4\xac") { //inline object
								$content .= $this->mpdf->textbuffer[$i][0];
							}
						}
					}
					/* -- TOC -- */
					if (isset($this->mpdf->h2toc[$tag])) {
						$objattr = [];
						$objattr['type'] = 'toc';
						$objattr['toclevel'] = $this->mpdf->h2toc[$tag];
						$objattr['CONTENT'] = htmlspecialchars($content);
						$e = "\xbb\xa4\xactype=toc,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
						array_unshift($this->mpdf->textbuffer, [$e]);
					}
					/* -- END TOC -- */
					/* -- BOOKMARKS -- */
					if (isset($this->mpdf->h2bookmarks[$tag])) {
						$objattr = [];
						$objattr['type'] = 'bookmark';
						$objattr['bklevel'] = $this->mpdf->h2bookmarks[$tag];
						$objattr['CONTENT'] = $content;
						$e = "\xbb\xa4\xactype=toc,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
						array_unshift($this->mpdf->textbuffer, [$e]);
					}
					/* -- END BOOKMARKS -- */
				}
			}

			/* -- TABLES -- */
			if ($this->mpdf->tableLevel) {
				if ($this->mpdf->linebreakjustfinished) {
					$this->mpdf->blockjustfinished = false;
				}
				if (isset($this->mpdf->InlineProperties['BLOCKINTABLE'])) {
					if ($this->mpdf->InlineProperties['BLOCKINTABLE']) {
						$this->mpdf->restoreInlineProperties($this->mpdf->InlineProperties['BLOCKINTABLE']);
					}
					unset($this->mpdf->InlineProperties['BLOCKINTABLE']);
				}
				if ($tag == 'PRE') {
					$this->mpdf->ispre = false;
				}
				return;
			}
			/* -- END TABLES -- */
			$this->mpdf->lastoptionaltag = '';
			$this->mpdf->divbegin = false;

			$this->mpdf->linebreakjustfinished = false;

			$this->mpdf->x = $this->mpdf->lMargin + $this->mpdf->blk[$this->mpdf->blklvl]['outer_left_margin'];

			/* -- CSS-FLOAT -- */
			// If float contained in a float, need to extend bottom to allow for it
			$currpos = $this->mpdf->page * 1000 + $this->mpdf->y;
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos']) && $this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] > $currpos) {
				$old_page = $this->mpdf->page;
				$new_page = intval($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] / 1000);
				if ($old_page != $new_page) {
					$s = $this->mpdf->PrintPageBackgrounds();
					// Writes after the marker so not overwritten later by page background etc.
					$this->mpdf->pages[$this->mpdf->page] = preg_replace(
						'/(___BACKGROUND___PATTERNS' . $this->mpdf->uniqstr . ')/',
						'\\1' . "\n" . $s . "\n",
						$this->mpdf->pages[$this->mpdf->page]
					);
					$this->mpdf->pageBackgrounds = [];
					$this->mpdf->page = $new_page;
					$this->mpdf->ResetMargins();
					$this->mpdf->Reset();
					$this->mpdf->pageoutput[$this->mpdf->page] = [];
				}
				// mod changes operands to integers before processing
				$this->mpdf->y = (($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] * 1000) % 1000000) / 1000;
			}
			/* -- END CSS-FLOAT -- */


			//Print content
			if ($this->mpdf->lastblocklevelchange == 1) {
				$blockstate = 3;
			} // Top & bottom margins/padding
			elseif ($this->mpdf->lastblocklevelchange == -1) {
				$blockstate = 2;
			} // Bottom margins/padding only
			else {
				$blockstate = 0;
			}
			// called from after e.g. </table> </div> </div> ...    Outputs block margin/border and padding
			if (count($this->mpdf->textbuffer) && $this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1]) {
				if (substr($this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0], 0, 3) != "\xbb\xa4\xac") { // not special content
					// Right trim last content and adjust OTLdata
					if (preg_match('/[ ]+$/', $this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0], $m)) {
						$strip = strlen($m[0]);
						$this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0] = substr(
							$this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0],
							0,
							(strlen($this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][0]) - $strip)
						);
						/* -- OTL -- */
						if (isset($this->mpdf->CurrentFont['useOTL']) && $this->mpdf->CurrentFont['useOTL']) {
							$this->otl->trimOTLdata($this->mpdf->textbuffer[count($this->mpdf->textbuffer) - 1][18], false, true); // mPDF 6  ZZZ99K
						}
						/* -- END OTL -- */
					}
				}
			}

			if (count($this->mpdf->textbuffer) == 0 && $this->mpdf->lastblocklevelchange != 0) {
				/*$this->mpdf->newFlowingBlock(
					$this->mpdf->blk[$this->mpdf->blklvl]['width'],
					$this->mpdf->lineheight,
					'',
					false,
					2,
					true,
					(isset($this->mpdf->blk[$this->mpdf->blklvl]['direction']) ? $this->mpdf->blk[$this->mpdf->blklvl]['direction'] : 'ltr')
				);*/

				$this->mpdf->newFlowingBlock(
					$this->mpdf->blk[$this->mpdf->blklvl]['width'],
					$this->mpdf->lineheight,
					'',
					false,
					$blockstate,
					true,
					(isset($this->mpdf->blk[$this->mpdf->blklvl]['direction']) ? $this->mpdf->blk[$this->mpdf->blklvl]['direction'] : 'ltr')
				);

				$this->mpdf->finishFlowingBlock(true); // true = END of flowing block
				$this->mpdf->PaintDivBB('', $blockstate);
			} else {
				$this->mpdf->printbuffer($this->mpdf->textbuffer, $blockstate);
			}


			$this->mpdf->textbuffer = [];

			if ($this->mpdf->kwt) {
				$this->mpdf->kwt_height = $this->mpdf->y - $this->mpdf->kwt_y0;
			}

			/* -- CSS-IMAGE-FLOAT -- */
			$this->mpdf->printfloatbuffer();
			/* -- END CSS-IMAGE-FLOAT -- */

			if ($tag == 'PRE') {
				$this->mpdf->ispre = false;
			}

			/* -- CSS-FLOAT -- */
			if ($this->mpdf->blk[$this->mpdf->blklvl]['float'] == 'R') {
				// If width not set, here would need to adjust and output buffer
				$s = $this->mpdf->PrintPageBackgrounds();
				// Writes after the marker so not overwritten later by page background etc.
				$this->mpdf->pages[$this->mpdf->page] = preg_replace('/(___BACKGROUND___PATTERNS' . $this->mpdf->uniqstr . ')/', '\\1' . "\n" . $s . "\n", $this->mpdf->pages[$this->mpdf->page]);
				$this->mpdf->pageBackgrounds = [];
				$this->mpdf->Reset();
				$this->mpdf->pageoutput[$this->mpdf->page] = [];

				for ($i = ($this->mpdf->blklvl - 1); $i >= 0; $i--) {
					if (isset($this->mpdf->blk[$i]['float_endpos'])) {
						$this->mpdf->blk[$i]['float_endpos'] = max($this->mpdf->blk[$i]['float_endpos'], ($this->mpdf->page * 1000 + $this->mpdf->y));
					} else {
						$this->mpdf->blk[$i]['float_endpos'] = $this->mpdf->page * 1000 + $this->mpdf->y;
					}
				}

				$this->mpdf->floatDivs[] = [
					'side' => 'R',
					'startpage' => $this->mpdf->blk[$this->mpdf->blklvl]['startpage'],
					'y0' => $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y'],
					'startpos' => ($this->mpdf->blk[$this->mpdf->blklvl]['startpage'] * 1000 + $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y']),
					'endpage' => $this->mpdf->page,
					'y1' => $this->mpdf->y,
					'endpos' => ($this->mpdf->page * 1000 + $this->mpdf->y),
					'w' => $this->mpdf->blk[$this->mpdf->blklvl]['float_width'],
					'blklvl' => $this->mpdf->blklvl,
					'blockContext' => $this->mpdf->blk[$this->mpdf->blklvl - 1]['blockContext']
				];

				$this->mpdf->y = $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y'];
				$this->mpdf->page = $this->mpdf->blk[$this->mpdf->blklvl]['startpage'];
				$this->mpdf->ResetMargins();
				$this->mpdf->pageoutput[$this->mpdf->page] = [];
			}
			if ($this->mpdf->blk[$this->mpdf->blklvl]['float'] == 'L') {
				// If width not set, here would need to adjust and output buffer
				$s = $this->mpdf->PrintPageBackgrounds();
				// Writes after the marker so not overwritten later by page background etc.
				$this->mpdf->pages[$this->mpdf->page] = preg_replace('/(___BACKGROUND___PATTERNS' . $this->mpdf->uniqstr . ')/', '\\1' . "\n" . $s . "\n", $this->mpdf->pages[$this->mpdf->page]);
				$this->mpdf->pageBackgrounds = [];
				$this->mpdf->Reset();
				$this->mpdf->pageoutput[$this->mpdf->page] = [];

				for ($i = ($this->mpdf->blklvl - 1); $i >= 0; $i--) {
					if (isset($this->mpdf->blk[$i]['float_endpos'])) {
						$this->mpdf->blk[$i]['float_endpos'] = max($this->mpdf->blk[$i]['float_endpos'], ($this->mpdf->page * 1000 + $this->mpdf->y));
					} else {
						$this->mpdf->blk[$i]['float_endpos'] = $this->mpdf->page * 1000 + $this->mpdf->y;
					}
				}

				$this->mpdf->floatDivs[] = [
					'side' => 'L',
					'startpage' => $this->mpdf->blk[$this->mpdf->blklvl]['startpage'],
					'y0' => $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y'],
					'startpos' => ($this->mpdf->blk[$this->mpdf->blklvl]['startpage'] * 1000 + $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y']),
					'endpage' => $this->mpdf->page,
					'y1' => $this->mpdf->y,
					'endpos' => ($this->mpdf->page * 1000 + $this->mpdf->y),
					'w' => $this->mpdf->blk[$this->mpdf->blklvl]['float_width'],
					'blklvl' => $this->mpdf->blklvl,
					'blockContext' => $this->mpdf->blk[$this->mpdf->blklvl - 1]['blockContext']
				];

				$this->mpdf->y = $this->mpdf->blk[$this->mpdf->blklvl]['float_start_y'];
				$this->mpdf->page = $this->mpdf->blk[$this->mpdf->blklvl]['startpage'];
				$this->mpdf->ResetMargins();
				$this->mpdf->pageoutput[$this->mpdf->page] = [];
			}
			/* -- END CSS-FLOAT -- */

			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['visibility']) && $this->mpdf->blk[$this->mpdf->blklvl]['visibility'] != 'visible') {
				$this->mpdf->SetVisibility('visible');
			}

			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['page_break_after'])) {
				$page_break_after = $this->mpdf->blk[$this->mpdf->blklvl]['page_break_after'];
			} else {
				$page_break_after = '';
			}

			//Reset values
			$this->mpdf->Reset();

			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['z-index']) && $this->mpdf->blk[$this->mpdf->blklvl]['z-index'] > 0) {
				$this->mpdf->EndLayer();
			}

			// mPDF 6 page-break-inside:avoid
			if ($this->mpdf->blk[$this->mpdf->blklvl]['keep_block_together']) {
				$movepage = false;
				// If page-break-inside:avoid section has broken to new page but fits on one side - then move:
				if (($this->mpdf->page - $this->mpdf->kt_p00) == 1 && $this->mpdf->y < $this->mpdf->kt_y00) {
					$movepage = true;
				}
				if (($this->mpdf->page - $this->mpdf->kt_p00) > 0) {
					for ($i = $this->mpdf->page; $i > $this->mpdf->kt_p00; $i--) {
						unset($this->mpdf->pages[$i]);
						if (isset($this->mpdf->blk[$this->mpdf->blklvl]['bb_painted'][$i])) {
							unset($this->mpdf->blk[$this->mpdf->blklvl]['bb_painted'][$i]);
						}
						if (isset($this->mpdf->blk[$this->mpdf->blklvl]['marginCorrected'][$i])) {
							unset($this->mpdf->blk[$this->mpdf->blklvl]['marginCorrected'][$i]);
						}
						if (isset($this->mpdf->pageoutput[$i])) {
							unset($this->mpdf->pageoutput[$i]);
						}
					}
					$this->mpdf->page = $this->mpdf->kt_p00;
				}
				$this->mpdf->keep_block_together = 0;
				$this->mpdf->pageoutput[$this->mpdf->page] = [];

				$this->mpdf->y = $this->mpdf->kt_y00;
				$ihtml = $this->mpdf->blk[$this->mpdf->blklvl]['array_i'] - 1;

				$ahtml[$ihtml + 1] .= ' pagebreakavoidchecked="true";'; // avoid re-iterating; read in OpenTag()

				unset($this->mpdf->blk[$this->mpdf->blklvl]);
				$this->mpdf->blklvl--;

				for ($blklvl = 1; $blklvl <= $this->mpdf->blklvl; $blklvl++) {
					$this->mpdf->blk[$blklvl]['y0'] = $this->mpdf->blk[$blklvl]['initial_y0'];
					$this->mpdf->blk[$blklvl]['x0'] = $this->mpdf->blk[$blklvl]['initial_x0'];
					$this->mpdf->blk[$blklvl]['startpage'] = $this->mpdf->blk[$blklvl]['initial_startpage'];
				}

				if (isset($this->mpdf->blk[$this->mpdf->blklvl]['x0'])) {
					$this->mpdf->x = $this->mpdf->blk[$this->mpdf->blklvl]['x0'];
				} else {
					$this->mpdf->x = $this->mpdf->lMargin;
				}

				$this->mpdf->lastblocklevelchange = 0;
				$this->mpdf->ResetMargins();
				if ($movepage) {
					$this->mpdf->AddPage();
				}
				return;
			}

			if ($this->mpdf->blklvl > 0) { // ==0 SHOULDN'T HAPPEN - NOT XHTML
				if ($this->mpdf->blk[$this->mpdf->blklvl]['tag'] == $tag) {
					unset($this->mpdf->blk[$this->mpdf->blklvl]);
					$this->mpdf->blklvl--;
				}
				//else { echo $tag; exit; }	// debug - forces error if incorrectly nested html tags
			}

			$this->mpdf->lastblocklevelchange = -1;
			// Reset Inline-type properties
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['InlineProperties'])) {
				$this->mpdf->restoreInlineProperties($this->mpdf->blk[$this->mpdf->blklvl]['InlineProperties']);
			}

			$this->mpdf->x = $this->mpdf->lMargin + $this->mpdf->blk[$this->mpdf->blklvl]['outer_left_margin'];

			if (!$this->mpdf->tableLevel && $page_break_after) {
				$save_blklvl = $this->mpdf->blklvl;
				$save_blk = $this->mpdf->blk;
				$save_silp = $this->mpdf->saveInlineProperties();
				$save_ilp = $this->mpdf->InlineProperties;
				$save_bflp = $this->mpdf->InlineBDF;
				$save_bflpc = $this->mpdf->InlineBDFctr; // mPDF 6
				// mPDF 6 pagebreaktype
				$startpage = $this->mpdf->page;
				$pagebreaktype = $this->mpdf->defaultPagebreakType;
				if ($this->mpdf->ColActive) {
					$pagebreaktype = 'cloneall';
				}

				// mPDF 6 pagebreaktype
				$this->mpdf->_preForcedPagebreak($pagebreaktype);

				if ($page_break_after == 'RIGHT') {
					$this->mpdf->AddPage($this->mpdf->CurOrientation, 'NEXT-ODD', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0);
				} elseif ($page_break_after == 'LEFT') {
					$this->mpdf->AddPage($this->mpdf->CurOrientation, 'NEXT-EVEN', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0);
				} else {
					$this->mpdf->AddPage($this->mpdf->CurOrientation, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0);
				}

				// mPDF 6 pagebreaktype
				$this->mpdf->_postForcedPagebreak($pagebreaktype, $startpage, $save_blk, $save_blklvl);

				$this->mpdf->InlineProperties = $save_ilp;
				$this->mpdf->InlineBDF = $save_bflp;
				$this->mpdf->InlineBDFctr = $save_bflpc; // mPDF 6
				$this->mpdf->restoreInlineProperties($save_silp);
			}
			// mPDF 6 bidi
			// Block
			// If unicode-bidi set, any embedding levels, isolates, or overrides reopened in the continuing block
			if (isset($this->mpdf->blk[$this->mpdf->blklvl]['bidicode'])) {
				$blockpre = $this->mpdf->_setBidiCodes('start', $this->mpdf->blk[$this->mpdf->blklvl]['bidicode']);
				if ($blockpre) {
					$this->mpdf->OTLdata = [];
					if ($this->mpdf->tableLevel) {
						$this->mpdf->_saveCellTextBuffer($blockpre);
					} else {
						$this->mpdf->_saveTextBuffer($blockpre);
					}
				}
			}
		}


		/* -- TABLES -- */

		if ($tag == 'TR' && $this->mpdf->tableLevel) {
			// If Border set on TR - Update right border
			if (isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-left'][$this->mpdf->row])) {
				$c = & $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col];
				if ($c) {
					if ($this->mpdf->packTableData) {
						$cell = $this->mpdf->_unpackCellBorder($c['borderbin']);
					} else {
						$cell = $c;
					}
					$cell['border_details']['R'] = $this->mpdf->border_details(
						$this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['trborder-right'][$this->mpdf->row]
					);
					$this->mpdf->setBorder($cell['border'], Border::RIGHT, $cell['border_details']['R']['s']);
					if ($this->mpdf->packTableData) {
						$c['borderbin'] = $this->mpdf->_packCellBorder($cell);
						unset($c['border']);
						unset($c['border_details']);
					} else {
						$c = $cell;
					}
				}
			}
			$this->mpdf->lastoptionaltag = '';
			unset($this->cssManager->tablecascadeCSS[$this->cssManager->tbCSSlvl]);
			$this->cssManager->tbCSSlvl--;
			$this->mpdf->trow_text_rotate = '';
			$this->mpdf->tabletheadjustfinished = false;
		}

	}
}
