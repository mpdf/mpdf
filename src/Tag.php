<?php

namespace Mpdf;

use Mpdf\Color\ColorConverter;

use Mpdf\Image\ImageProcessor;

use Mpdf\Language\LanguageToFontInterface;

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

		switch ($tag) {
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

		}//end of switch
	}

	public function CloseTag($tag, &$ahtml, &$ihtml)
	{
		if ($object = $this->getTagInstance($tag)) {
			return $object->close($ahtml, $ihtml);
		}
	}
}
