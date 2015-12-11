<?php

/**
 * This file contains Mpdfs refractors WriteHTML() method broken up into smaller
 * pieces which we can (hopefully) unit test.
 *
 * The methods might be moved back into the Mpdf class once completed, but for now this just makes it easier to work with.
 */

/**
 * Class Write_Html
 */
class Write_Html
{

    /**
     * Our mPDF object
     *
     * @var \mPDF
     */
    public $mpdf;

    /**
     * Write_Html constructor.
     *
     * @param mPDF $mpdf
     */
    public function __construct(mPDF $mpdf)
    {
        $this->mpdf = $mpdf;
    }

    /**
     * Writes the HTML to the internal buffer
     *
     * @param mixed|string $html
     * @param int          $mode  0 = default; 1=headerCSS only; 2=HTML body (parts) only; 3 - HTML parses only; 4 - writes HTML headers/Fixed pos DIVs - stores in buffer - for single page only
     * @param bool|true    $init  Clears and sets buffers to Top level block etc.
     * @param bool|true    $close if false Leaves buffers etc. in current state, so that it can continue a block etc.
     *
     * @return mixed
     */
    public function WriteHTML($html, $mode = 0, $init = true, $close = true)
    {

        if (empty($html)) {
            $html = '';
        }

        /* Update the current status */
        $this->maybe_update_progress_bar(1, 0, 'Parsing CSS & Headers');

        /* Clear and set buffers to top level block */
        $this->maybe_reset_buffers($init);

        /* Put content in correct format if we are only processing the header CSS */
        if ($mode == 1) {
            $html = $this->wrap_header_css($html);
        }

        /* If required, we'll convert the HTML encoding to UTF-8 */
        $html = $this->set_character_encoding($html, $mode);

        /**
         * Ensure the UTF-8 string is clean
         *
         * @throws \MpdfException
         */
        $html = $this->mpdf->purify_utf8($html, false);

        /* Parses the <html>, <meta>, <base> and <body> tags and pull out and stores the required attributes and styles from those tags */
        if ($mode < 2) {
            $properties = $this->parse_html_head_styles_and_attributes($html);
        }


        /* @todo continue optimisation from here... */

        if ($mode == 1) {
            return '';
        }


        if ( ! isset($this->mpdf->cssmgr->CSS['BODY'])) {
            $this->mpdf->cssmgr->CSS['BODY'] = array();
        }


        /* -- BACKGROUNDS -- */
        if (isset($properties['BACKGROUND-GRADIENT'])) {
            $this->mpdf->bodyBackgroundGradient = $properties['BACKGROUND-GRADIENT'];
        }


        if (isset($properties['BACKGROUND-IMAGE']) && $properties['BACKGROUND-IMAGE']) {
            $ret = $this->mpdf->SetBackground($properties, $this->mpdf->pgwidth);
            if ($ret) {
                $this->mpdf->bodyBackgroundImage = $ret;
            }
        }
        /* -- END BACKGROUNDS -- */


        /* -- CSS-PAGE -- */
        // If page-box is set
        if ($this->mpdf->state == 0 && ((isset($this->mpdf->cssmgr->CSS['@PAGE']) && $this->mpdf->cssmgr->CSS['@PAGE']) || (isset($this->mpdf->cssmgr->CSS['@PAGE>>PSEUDO>>FIRST']) && $this->mpdf->cssmgr->CSS['@PAGE>>PSEUDO>>FIRST']))) { // mPDF 5.7.3
            $this->mpdf->page_box['current'] = '';
            $this->mpdf->page_box['using']   = true;
            list($pborientation, $pbmgl, $pbmgr, $pbmgt, $pbmgb, $pbmgh, $pbmgf, $hname, $fname, $bg, $resetpagenum, $pagenumstyle, $suppress, $marks, $newformat) = $this->mpdf->SetPagedMediaCSS('',
                false, 'O');
            $this->mpdf->DefOrientation = $this->mpdf->CurOrientation = $pborientation;
            $this->mpdf->orig_lMargin   = $this->mpdf->DeflMargin = $pbmgl;
            $this->mpdf->orig_rMargin   = $this->mpdf->DefrMargin = $pbmgr;
            $this->mpdf->orig_tMargin   = $this->mpdf->tMargin = $pbmgt;
            $this->mpdf->orig_bMargin   = $this->mpdf->bMargin = $pbmgb;
            $this->mpdf->orig_hMargin   = $this->mpdf->margin_header = $pbmgh;
            $this->mpdf->orig_fMargin   = $this->mpdf->margin_footer = $pbmgf;
            list($pborientation, $pbmgl, $pbmgr, $pbmgt, $pbmgb, $pbmgh, $pbmgf, $hname, $fname, $bg, $resetpagenum, $pagenumstyle, $suppress, $marks, $newformat) = $this->mpdf->SetPagedMediaCSS('',
                true, 'O'); // first page
            $this->mpdf->show_marks = $marks;
            if ($hname) {
                $this->mpdf->firstPageBoxHeader = $hname;
            }
            if ($fname) {
                $this->mpdf->firstPageBoxFooter = $fname;
            }
        }
        /* -- END CSS-PAGE -- */


        $parseonly                = false;
        $this->mpdf->bufferoutput = false;

        if ($mode == 3) {
            $parseonly = true;
            // Close any open block tags
            $arr = array();
            $ai  = 0;
            for ($b = $this->mpdf->blklvl; $b > 0; $b--) {
                $this->mpdf->tag->CloseTag($this->mpdf->blk[$b]['tag'], $arr, $ai);
            }
            // Output any text left in buffer
            if (count($this->mpdf->textbuffer)) {
                $this->mpdf->printbuffer($this->mpdf->textbuffer);
            }
            $this->mpdf->textbuffer = array();
        } else if ($mode == 4) {
            // Close any open block tags
            $arr = array();
            $ai  = 0;
            for ($b = $this->mpdf->blklvl; $b > 0; $b--) {
                $this->mpdf->tag->CloseTag($this->mpdf->blk[$b]['tag'], $arr, $ai);
            }
            // Output any text left in buffer
            if (count($this->mpdf->textbuffer)) {
                $this->mpdf->printbuffer($this->mpdf->textbuffer);
            }
            $this->mpdf->bufferoutput = true;
            $this->mpdf->textbuffer   = array();
            $this->mpdf->headerbuffer = '';
            $properties               = $this->mpdf->cssmgr->MergeCSS('BLOCK', 'BODY', '');
            $this->mpdf->setCSS($properties, '', 'BODY');
        }


        mb_internal_encoding('UTF-8');

        $html = $this->mpdf->AdjustHTML($html, $this->mpdf->tabSpaces); //Try to make HTML look more like XHTML


        if ($this->mpdf->autoScriptToLang) {
            $html = $this->mpdf->markScriptToLang($html);
        }


        preg_match_all('/<htmlpageheader([^>]*)>(.*?)<\/htmlpageheader>/si', $html, $h);
        for ($i = 0; $i < count($h[1]); $i++) {
            if (preg_match('/name=[\'|\"](.*?)[\'|\"]/', $h[1][$i], $n)) {
                $this->mpdf->pageHTMLheaders[$n[1]]['html'] = $h[2][$i];
                $this->mpdf->pageHTMLheaders[$n[1]]['h']    = $this->mpdf->_gethtmlheight($h[2][$i]);
            }
        }


        preg_match_all('/<htmlpagefooter([^>]*)>(.*?)<\/htmlpagefooter>/si', $html, $f);
        for ($i = 0; $i < count($f[1]); $i++) {
            if (preg_match('/name=[\'|\"](.*?)[\'|\"]/', $f[1][$i], $n)) {
                $this->mpdf->pageHTMLfooters[$n[1]]['html'] = $f[2][$i];
                $this->mpdf->pageHTMLfooters[$n[1]]['h']    = $this->mpdf->_gethtmlheight($f[2][$i]);
            }
        }

        $html = preg_replace('/<htmlpageheader.*?<\/htmlpageheader>/si', '', $html);
        $html = preg_replace('/<htmlpagefooter.*?<\/htmlpagefooter>/si', '', $html);

        if ($this->mpdf->state == 0 && $mode != 1 && $mode != 3 && $mode != 4) {
            $this->mpdf->AddPage($this->mpdf->CurOrientation);
        }


        if (isset($hname) && preg_match('/^html_(.*)$/i', $hname, $n)) {
            $this->mpdf->SetHTMLHeader($this->mpdf->pageHTMLheaders[$n[1]], 'O', true);
        }


        if (isset($fname) && preg_match('/^html_(.*)$/i', $fname, $n)) {
            $this->mpdf->SetHTMLFooter($this->mpdf->pageHTMLfooters[$n[1]], 'O');
        }


        $html = str_replace('<?', '< ', $html); //Fix '<?XML' bug from HTML code generated by MS Word

        $this->mpdf->checkSIP = false;
        $this->mpdf->checkSMP = false;
        $this->mpdf->checkCJK = false;


        if ($this->mpdf->onlyCoreFonts) {
            $html = $this->mpdf->SubstituteChars($html);
        } else {
            if (preg_match("/([" . $this->mpdf->pregRTLchars . "])/u", $html)) {
                $this->mpdf->biDirectional = true;
            } // *OTL*
            if (preg_match("/([\x{20000}-\x{2FFFF}])/u", $html)) {
                $this->mpdf->checkSIP = true;
            }
            if (preg_match("/([\x{10000}-\x{1FFFF}])/u", $html)) {
                $this->mpdf->checkSMP = true;
            }
            /* -- CJK-FONTS -- */
            if (preg_match("/([" . $this->mpdf->pregCJKchars . "])/u", $html)) {
                $this->mpdf->checkCJK = true;
            }
            /* -- END CJK-FONTS -- */
        }

        // Don't allow non-breaking spaces that are converted to substituted chars or will break anyway and mess up table width calc.
        $html = str_replace('<tta>160</tta>', chr(32), $html);
        $html = str_replace('</tta><tta>', '|', $html);
        $html = str_replace('</tts><tts>', '|', $html);
        $html = str_replace('</ttz><ttz>', '|', $html);

        //Add new supported tags in the DisableTags function
        $html = strip_tags($html,
            $this->mpdf->enabledtags); //remove all unsupported tags, but the ones inside the 'enabledtags' string

        //Explode the string in order to parse the HTML code
        $a = preg_split('/<(.*?)>/ms', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        // ? more accurate regexp that allows e.g. <a name="Silly <name>">
        // if changing - also change in fn.SubstituteChars()
        // $a = preg_split ('/<((?:[^<>]+(?:"[^"]*"|\'[^\']*\')?)+)>/ms', $html, -1, PREG_SPLIT_DELIM_CAPTURE);


        if ($this->mpdf->mb_enc) {
            mb_internal_encoding($this->mpdf->mb_enc);
        }

        $pbc = 0;

        /* Update the current status */
        $this->maybe_update_progress_bar(1, 0);


        $this->mpdf->subPos = -1;
        $cnt                = count($a);

        for ($i = 0; $i < $cnt; $i++) {
            $e = $a[$i];
            if ($i % 2 == 0) {
                //TEXT
                if ($this->mpdf->blk[$this->mpdf->blklvl]['hide']) {
                    continue;
                }
                if ($this->mpdf->inlineDisplayOff) {
                    continue;
                }
                if ($this->mpdf->inMeter) {
                    continue;
                }

                if ($this->mpdf->inFixedPosBlock) {
                    $this->mpdf->fixedPosBlock .= $e;
                    continue;
                } // *CSS-POSITION*
                if (strlen($e) == 0) {
                    continue;
                }

                if ($this->mpdf->ignorefollowingspaces && ! $this->mpdf->ispre) {
                    if (strlen(ltrim($e)) == 0) {
                        continue;
                    }
                    if ($this->mpdf->FontFamily != 'csymbol' && $this->mpdf->FontFamily != 'czapfdingbats' && substr($e,
                            0, 1) == ' '
                    ) {
                        $this->mpdf->ignorefollowingspaces = false;
                        $e                                 = ltrim($e);
                    }
                }

                $this->mpdf->OTLdata = null;  // mPDF 5.7.1

                $e = strcode2utf($e);
                $e = $this->mpdf->lesser_entity_decode($e);

                if ($this->mpdf->usingCoreFont) {
                    // If core font is selected in document which is not onlyCoreFonts - substitute with non-core font
                    if ($this->mpdf->useSubstitutions && ! $this->mpdf->onlyCoreFonts && $this->mpdf->subPos < $i && ! $this->mpdf->specialcontent) {
                        $cnt += $this->mpdf->SubstituteCharsNonCore($a, $i, $e);
                    }
                    // CONVERT ENCODING
                    $e = mb_convert_encoding($e, $this->mpdf->mb_enc, 'UTF-8');
                    if ($this->mpdf->textvar & FT_UPPERCASE) {
                        $e = mb_strtoupper($e, $this->mpdf->mb_enc);
                    } // mPDF 5.7.1
                    else if ($this->mpdf->textvar & FT_LOWERCASE) {
                        $e = mb_strtolower($e, $this->mpdf->mb_enc);
                    } // mPDF 5.7.1
                    else if ($this->mpdf->textvar & FT_CAPITALIZE) {
                        $e = mb_convert_case($e, MB_CASE_TITLE, "UTF-8");
                    } // mPDF 5.7.1
                } else {
                    if ($this->mpdf->checkSIP && $this->mpdf->CurrentFont['sipext'] && $this->mpdf->subPos < $i && ( ! $this->mpdf->specialcontent || ! $this->mpdf->useActiveForms)) {
                        $cnt += $this->mpdf->SubstituteCharsSIP($a, $i, $e);
                    }

                    if ($this->mpdf->useSubstitutions && ! $this->mpdf->onlyCoreFonts && $this->mpdf->CurrentFont['type'] != 'Type0' && $this->mpdf->subPos < $i && ( ! $this->mpdf->specialcontent || ! $this->mpdf->useActiveForms)) {
                        $cnt += $this->mpdf->SubstituteCharsMB($a, $i, $e);
                    }

                    if ($this->mpdf->textvar & FT_UPPERCASE) {
                        $e = mb_strtoupper($e, $this->mpdf->mb_enc);
                    } else if ($this->mpdf->textvar & FT_LOWERCASE) {
                        $e = mb_strtolower($e, $this->mpdf->mb_enc);
                    } else if ($this->mpdf->textvar & FT_CAPITALIZE) {
                        $e = mb_convert_case($e, MB_CASE_TITLE, "UTF-8");
                    }

                    /* -- OTL -- */
                    // Use OTL OpenType Table Layout - GSUB & GPOS
                    if (isset($this->mpdf->CurrentFont['useOTL']) && $this->mpdf->CurrentFont['useOTL'] && ( ! $this->mpdf->specialcontent || ! $this->mpdf->useActiveForms)) {
                        $e                   = $this->mpdf->otl->applyOTL($e, $this->mpdf->CurrentFont['useOTL']);
                        $this->mpdf->OTLdata = $this->mpdf->otl->OTLdata;
                        $this->mpdf->otl->removeChar($e, $this->mpdf->OTLdata,
                            "\xef\xbb\xbf"); // Remove ZWNBSP (also Byte order mark FEFF)
                    } /* -- END OTL -- */ else { // *OTL*
                        // removes U+200E/U+200F LTR and RTL mark and U+200C/U+200D Zero-width Joiner and Non-joiner
                        $e = preg_replace("/[\xe2\x80\x8c\xe2\x80\x8d\xe2\x80\x8e\xe2\x80\x8f]/u", '', $e);
                        $e = preg_replace("/[\xef\xbb\xbf]/u", '', $e); // Remove ZWNBSP (also Byte order mark FEFF)
                    } // *OTL*
                }
                if (($this->mpdf->tts) || ($this->mpdf->ttz) || ($this->mpdf->tta)) {
                    $es = explode('|', $e);
                    $e  = '';
                    foreach ($es AS $val) {
                        $e .= chr($val);
                    }
                }

                //  FORM ELEMENTS
                if ($this->mpdf->specialcontent) {
                    /* -- FORMS -- */
                    //SELECT tag (form element)
                    if ($this->mpdf->specialcontent == "type=select") {
                        $e = ltrim($e);
                        if ( ! empty($this->mpdf->OTLdata)) {
                            $this->mpdf->otl->trimOTLdata($this->mpdf->OTLdata, true, false);
                        } // *OTL*
                        $stringwidth = $this->mpdf->GetStringWidth($e);
                        if ( ! isset($this->mpdf->selectoption['MAXWIDTH']) || $stringwidth > $this->mpdf->selectoption['MAXWIDTH']) {
                            $this->mpdf->selectoption['MAXWIDTH'] = $stringwidth;
                        }
                        if ( ! isset($this->mpdf->selectoption['SELECTED']) || $this->mpdf->selectoption['SELECTED'] == '') {
                            $this->mpdf->selectoption['SELECTED'] = $e;
                            if ( ! empty($this->mpdf->OTLdata)) {
                                $this->mpdf->selectoption['SELECTED-OTLDATA'] = $this->mpdf->OTLdata;
                            } // *OTL*
                        }
                        // Active Forms
                        if (isset($this->mpdf->selectoption['ACTIVE']) && $this->mpdf->selectoption['ACTIVE']) {
                            $this->mpdf->selectoption['ITEMS'][] = array(
                                'exportValue' => $this->mpdf->selectoption['currentVAL'],
                                'content'     => $e,
                                'selected'    => $this->mpdf->selectoption['currentSEL'],
                            );
                        }
                        $this->mpdf->OTLdata = array();
                    } // TEXTAREA
                    else {
                        $objattr             = unserialize($this->mpdf->specialcontent);
                        $objattr['text']     = $e;
                        $objattr['OTLdata']  = $this->mpdf->OTLdata;
                        $this->mpdf->OTLdata = array();
                        $te                  = "\xbb\xa4\xactype=textarea,objattr=" . serialize($objattr) . "\xbb\xa4\xac";
                        if ($this->mpdf->tdbegin) {
                            $this->mpdf->_saveCellTextBuffer($te, $this->mpdf->HREF);
                        } else {
                            $this->mpdf->_saveTextBuffer($te, $this->mpdf->HREF);
                        }
                    }
                    /* -- END FORMS -- */
                } // TABLE
                else if ($this->mpdf->tableLevel) {
                    /* -- TABLES -- */
                    if ($this->mpdf->tdbegin) {
                        if (($this->mpdf->ignorefollowingspaces) && ! $this->mpdf->ispre) {
                            $e = ltrim($e);
                            if ( ! empty($this->mpdf->OTLdata)) {
                                $this->mpdf->otl->trimOTLdata($this->mpdf->OTLdata, true, false);
                            } // *OTL*
                        }
                        if ($e || $e === '0') {
                            if ($this->mpdf->blockjustfinished && $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] > 0) {
                                $this->mpdf->_saveCellTextBuffer("\n");
                                if ( ! isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'])) {
                                    $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
                                } elseif ($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] < $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s']) {
                                    $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['maxs'] = $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'];
                                }
                                $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] = 0; // reset
                            }
                            $this->mpdf->blockjustfinished = false;

                            if ( ! isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['R']) || ! $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['R']) {
                                if (isset($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'])) {
                                    $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += $this->mpdf->GetStringWidth($e,
                                        false, $this->mpdf->OTLdata, $this->mpdf->textvar);
                                } else {
                                    $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] = $this->mpdf->GetStringWidth($e,
                                        false, $this->mpdf->OTLdata, $this->mpdf->textvar);
                                }
                                if ( ! empty($this->mpdf->spanborddet)) {
                                    $this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['s'] += (isset($this->mpdf->spanborddet['L']['w']) ? $this->mpdf->spanborddet['L']['w'] : 0) + (isset($this->mpdf->spanborddet['R']['w']) ? $this->mpdf->spanborddet['R']['w'] : 0);
                                }
                            }
                            $this->mpdf->_saveCellTextBuffer($e, $this->mpdf->HREF);
                            if (substr($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['a'], 0, 1) == 'D') {
                                $dp = $this->mpdf->decimal_align[substr($this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['a'],
                                    0, 2)];
                                $s  = preg_split('/' . preg_quote($dp, '/') . '/', $e,
                                    2);  // ? needs to be /u if not core
                                $s0 = $this->mpdf->GetStringWidth($s[0], false);
                                if (isset($s[1]) && $s[1]) {
                                    $s1 = $this->mpdf->GetStringWidth(($s[1] . $dp), false);
                                } else {
                                    $s1 = 0;
                                }
                                if ( ! isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs0'])) {
                                    $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs0'] = $s0;
                                } else {
                                    $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs0'] = max($s0,
                                        $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs0']);
                                }
                                if ( ! isset($this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs1'])) {
                                    $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs1'] = $s1;
                                } else {
                                    $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs1'] = max($s1,
                                        $this->mpdf->table[$this->mpdf->tableLevel][$this->mpdf->tbctr[$this->mpdf->tableLevel]]['decimal_align'][$this->mpdf->col]['maxs1']);
                                }
                            }

                            if ($this->mpdf->tableLevel == 1 && $this->mpdf->useGraphs) {
                                $this->mpdf->graphs[$this->mpdf->currentGraphId]['data'][$this->mpdf->row][$this->mpdf->col] = $e;
                            }
                            $this->mpdf->nestedtablejustfinished = false;
                            $this->mpdf->linebreakjustfinished   = false;
                        }
                    }
                    /* -- END TABLES -- */
                } // ALL ELSE
                else {
                    if ($this->mpdf->ignorefollowingspaces && ! $this->mpdf->ispre) {
                        $e = ltrim($e);
                        if ( ! empty($this->mpdf->OTLdata)) {
                            $this->mpdf->otl->trimOTLdata($this->mpdf->OTLdata, true, false);
                        } // *OTL*
                    }
                    if ($e || $e === '0') {
                        $this->mpdf->_saveTextBuffer($e, $this->mpdf->HREF);
                    }
                }
                if ($e || $e === '0') {
                    $this->mpdf->ignorefollowingspaces = false;
                } // mPDF 6
                if (substr($e, -1,
                        1) == ' ' && ! $this->mpdf->ispre && $this->mpdf->FontFamily != 'csymbol' && $this->mpdf->FontFamily != 'czapfdingbats'
                ) {
                    $this->mpdf->ignorefollowingspaces = true;
                }
            } else { // TAG **
                if (isset($e[0]) && $e[0] == '/') {
                    /* -- PROGRESS-BAR -- */
                    if ($this->mpdf->progressBar) {  // 10% increments
                        if (intval($i * 10 / $cnt) != $pbc) {
                            $pbc = intval($i * 10 / $cnt);

                            /* Update the current status */
                            $this->maybe_update_progress_bar(1, $pbc * 10, $tag);
                        }
                    }
                    /* -- END PROGRESS-BAR -- */

                    $endtag = trim(strtoupper(substr($e, 1)));


                    /* -- CSS-POSITION -- */
                    // mPDF 6
                    if ($this->mpdf->inFixedPosBlock) {
                        if (in_array($endtag, $this->mpdf->outerblocktags) || in_array($endtag,
                                $this->mpdf->innerblocktags)
                        ) {
                            $this->mpdf->fixedPosBlockDepth--;
                        }
                        if ($this->mpdf->fixedPosBlockDepth == 0) {
                            $this->mpdf->fixedPosBlockSave[] = array(
                                $this->mpdf->fixedPosBlock,
                                $this->mpdf->fixedPosBlockBBox,
                                $this->mpdf->page,
                            );
                            $this->mpdf->fixedPosBlock       = '';
                            $this->mpdf->inFixedPosBlock     = false;
                            continue;
                        }
                        $this->mpdf->fixedPosBlock .= '<' . $e . '>';
                        continue;
                    }
                    /* -- END CSS-POSITION -- */

                    // mPDF 6
                    // Correct for tags where HTML5 specifies optional end tags (see also OpenTag() )
                    if ($this->mpdf->allow_html_optional_endtags && ! $parseonly) {
                        if (isset($this->mpdf->blk[$this->mpdf->blklvl]['tag'])) {
                            $closed = false;
                            // li end tag may be omitted if there is no more content in the parent element
                            if ( ! $closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'LI' && $endtag != 'LI' && (in_array($endtag,
                                        $this->mpdf->outerblocktags) || in_array($endtag, $this->mpdf->innerblocktags))
                            ) {
                                $this->mpdf->tag->CloseTag('LI', $a, $i);
                                $closed = true;
                            }
                            // dd end tag may be omitted if there is no more content in the parent element
                            if ( ! $closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'DD' && $endtag != 'DD' && (in_array($endtag,
                                        $this->mpdf->outerblocktags) || in_array($endtag, $this->mpdf->innerblocktags))
                            ) {
                                $this->mpdf->tag->CloseTag('DD', $a, $i);
                                $closed = true;
                            }
                            // p end tag may be omitted if there is no more content in the parent element and the parent element is not an A element [??????]
                            if ( ! $closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'P' && $endtag != 'P' && (in_array($endtag,
                                        $this->mpdf->outerblocktags) || in_array($endtag, $this->mpdf->innerblocktags))
                            ) {
                                $this->mpdf->tag->CloseTag('P', $a, $i);
                                $closed = true;
                            }
                            // option end tag may be omitted if there is no more content in the parent element
                            if ( ! $closed && $this->mpdf->blk[$this->mpdf->blklvl]['tag'] == 'OPTION' && $endtag != 'OPTION' && (in_array($endtag,
                                        $this->mpdf->outerblocktags) || in_array($endtag, $this->mpdf->innerblocktags))
                            ) {
                                $this->mpdf->tag->CloseTag('OPTION', $a, $i);
                                $closed = true;
                            }
                        }
                        /* -- TABLES -- */
                        // Check for Table tags where HTML specifies optional end tags,
                        if ($endtag == 'TABLE') {
                            if ($this->mpdf->lastoptionaltag == 'THEAD' || $this->mpdf->lastoptionaltag == 'TBODY' || $this->mpdf->lastoptionaltag == 'TFOOT') {
                                $this->mpdf->tag->CloseTag($this->mpdf->lastoptionaltag, $a, $i);
                            }
                            if ($this->mpdf->lastoptionaltag == 'TR') {
                                $this->mpdf->tag->CloseTag('TR', $a, $i);
                            }
                            if ($this->mpdf->lastoptionaltag == 'TD' || $this->mpdf->lastoptionaltag == 'TH') {
                                $this->mpdf->tag->CloseTag($this->mpdf->lastoptionaltag, $a, $i);
                                $this->mpdf->tag->CloseTag('TR', $a, $i);
                            }
                        }
                        if ($endtag == 'THEAD' || $endtag == 'TBODY' || $endtag == 'TFOOT') {
                            if ($this->mpdf->lastoptionaltag == 'TR') {
                                $this->mpdf->tag->CloseTag('TR', $a, $i);
                            }
                            if ($this->mpdf->lastoptionaltag == 'TD' || $this->mpdf->lastoptionaltag == 'TH') {
                                $this->mpdf->tag->CloseTag($this->mpdf->lastoptionaltag, $a, $i);
                                $this->mpdf->tag->CloseTag('TR', $a, $i);
                            }
                        }
                        if ($endtag == 'TR') {
                            if ($this->mpdf->lastoptionaltag == 'TD' || $this->mpdf->lastoptionaltag == 'TH') {
                                $this->mpdf->tag->CloseTag($this->mpdf->lastoptionaltag, $a, $i);
                            }
                        }
                        /* -- END TABLES -- */
                    }


                    // mPDF 6
                    if ($this->mpdf->blk[$this->mpdf->blklvl]['hide']) {
                        if (in_array($endtag, $this->mpdf->outerblocktags) || in_array($endtag,
                                $this->mpdf->innerblocktags)
                        ) {
                            unset($this->mpdf->blk[$this->mpdf->blklvl]);
                            $this->mpdf->blklvl--;
                        }
                        continue;
                    }

                    // mPDF 6
                    $this->mpdf->tag->CloseTag($endtag, $a, $i); // mPDF 6
                } else { // OPENING TAG
                    if ($this->mpdf->blk[$this->mpdf->blklvl]['hide']) {
                        if (strpos($e, ' ')) {
                            $te = strtoupper(substr($e, 0, strpos($e, ' ')));
                        } else {
                            $te = strtoupper($e);
                        }
                        // mPDF 6
                        if ($te == 'THEAD' || $te == 'TBODY' || $te == 'TFOOT' || $te == 'TR' || $te == 'TD' || $te == 'TH') {
                            $this->mpdf->lastoptionaltag = $te;
                        }
                        if (in_array($te, $this->mpdf->outerblocktags) || in_array($te, $this->mpdf->innerblocktags)) {
                            $this->mpdf->blklvl++;
                            $this->mpdf->blk[$this->mpdf->blklvl]['hide'] = true;
                            $this->mpdf->blk[$this->mpdf->blklvl]['tag']  = $te; // mPDF 6
                        }
                        continue;
                    }

                    /* -- CSS-POSITION -- */
                    if ($this->mpdf->inFixedPosBlock) {
                        if (strpos($e, ' ')) {
                            $te = strtoupper(substr($e, 0, strpos($e, ' ')));
                        } else {
                            $te = strtoupper($e);
                        }
                        $this->mpdf->fixedPosBlock .= '<' . $e . '>';
                        if (in_array($te, $this->mpdf->outerblocktags) || in_array($te, $this->mpdf->innerblocktags)) {
                            $this->mpdf->fixedPosBlockDepth++;
                        }
                        continue;
                    }
                    /* -- END CSS-POSITION -- */
                    $regexp = '|=\'(.*?)\'|s'; // eliminate single quotes, if any
                    $e      = preg_replace($regexp, "=\"\$1\"", $e);
                    // changes anykey=anyvalue to anykey="anyvalue" (only do this inside [some] tags)
                    if (substr($e, 0, 10) != 'pageheader' && substr($e, 0, 10) != 'pagefooter' && substr($e, 0,
                            12) != 'tocpagebreak' && substr($e, 0, 10) != 'indexentry' && substr($e, 0, 8) != 'tocentry'
                    ) { // mPDF 6  (ZZZ99H)
                        $regexp = '| (\\w+?)=([^\\s>"]+)|si';
                        $e      = preg_replace($regexp, " \$1=\"\$2\"", $e);
                    }

                    $e = preg_replace('/ (\\S+?)\s*=\s*"/i', " \\1=\"", $e);

                    //Fix path values, if needed
                    $orig_srcpath = '';
                    if ((stristr($e, "href=") !== false) or (stristr($e, "src=") !== false)) {
                        $regexp = '/ (href|src)\s*=\s*"(.*?)"/i';
                        preg_match($regexp, $e, $auxiliararray);
                        if (isset($auxiliararray[2])) {
                            $path = $auxiliararray[2];
                        } else {
                            $path = '';
                        }
                        if (trim($path) != '' && ! (stristr($e, "src=") !== false && substr($path, 0,
                                    4) == 'var:') && substr($path, 0, 1) != '@'
                        ) {
                            $path         = htmlspecialchars_decode($path); // mPDF 5.7.4 URLs
                            $orig_srcpath = $path;
                            $this->mpdf->GetFullPath($path);
                            $regexp = '/ (href|src)="(.*?)"/i';
                            $e      = preg_replace($regexp, ' \\1="' . $path . '"', $e);
                        }
                    }//END of Fix path values
                    //Extract attributes
                    $contents  = array();
                    $contents1 = array();
                    $contents2 = array();
                    // Changed to allow style="background: url('bg.jpg')"
                    // Changed to improve performance; maximum length of \S (attribute) = 16
                    // Increase allowed attribute name to 32 - cutting off "toc-even-header-name" etc.
                    preg_match_all('/\\S{1,32}=["][^"]*["]/', $e, $contents1);
                    preg_match_all('/\\S{1,32}=[\'][^\']*[\']/i', $e, $contents2);

                    $contents = array_merge($contents1, $contents2);
                    preg_match('/\\S+/', $e, $a2);
                    $tag  = (isset($a2[0]) ? strtoupper($a2[0]) : '');
                    $attr = array();
                    if ($orig_srcpath) {
                        $attr['ORIG_SRC'] = $orig_srcpath;
                    }
                    if ( ! empty($contents)) {
                        foreach ($contents[0] as $v) {
                            // Changed to allow style="background: url('bg.jpg')"
                            if (preg_match('/^([^=]*)=["]?([^"]*)["]?$/', $v,
                                    $a3) || preg_match('/^([^=]*)=[\']?([^\']*)[\']?$/', $v, $a3)
                            ) {
                                if (strtoupper($a3[1]) == 'ID' || strtoupper($a3[1]) == 'CLASS') { // 4.2.013 Omits STYLE
                                    $attr[strtoupper($a3[1])] = trim(strtoupper($a3[2]));
                                } // includes header-style-right etc. used for <pageheader>
                                else if (preg_match('/^(HEADER|FOOTER)-STYLE/i', $a3[1])) {
                                    $attr[strtoupper($a3[1])] = trim(strtoupper($a3[2]));
                                } else {
                                    $attr[strtoupper($a3[1])] = trim($a3[2]);
                                }
                            }
                        }
                    }
                    $this->mpdf->tag->OpenTag($tag, $attr, $a, $i); // mPDF 6
                    /* -- CSS-POSITION -- */
                    if ($this->mpdf->inFixedPosBlock) {
                        $this->mpdf->fixedPosBlockBBox  = array($tag, $attr, $this->mpdf->x, $this->mpdf->y);
                        $this->mpdf->fixedPosBlock      = '';
                        $this->mpdf->fixedPosBlockDepth = 1;
                    }
                    /* -- END CSS-POSITION -- */
                    if (preg_match('/\/$/', $e)) {
                        $this->mpdf->tag->CloseTag($tag, $a, $i);
                    }
                }
            } // end TAG
        } //end of	foreach($a as $i=>$e)


        if ($close) {

            // Close any open block tags
            for ($b = $this->mpdf->blklvl; $b > 0; $b--) {
                $this->mpdf->tag->CloseTag($this->mpdf->blk[$b]['tag'], $a, $i);
            }

            // Output any text left in buffer
            if (count($this->mpdf->textbuffer) && ! $parseonly) {
                $this->mpdf->printbuffer($this->mpdf->textbuffer);
            }


            if ( ! $parseonly) {
                $this->mpdf->textbuffer = array();
            }

            /* -- CSS-FLOAT -- */
            // If ended with a float, need to move to end page
            $currpos = $this->mpdf->page * 1000 + $this->mpdf->y;

            if (isset($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos']) && $this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] > $currpos) {
                $old_page = $this->mpdf->page;
                $new_page = intval($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] / 1000);

                if ($old_page != $new_page) {
                    $s = $this->mpdf->PrintPageBackgrounds();
                    // Writes after the marker so not overwritten later by page background etc.
                    $this->mpdf->pages[$this->mpdf->page] = preg_replace('/(___BACKGROUND___PATTERNS' . $this->mpdf->uniqstr . ')/',
                        '\\1' . "\n" . $s . "\n", $this->mpdf->pages[$this->mpdf->page]);
                    $this->mpdf->pageBackgrounds          = array();
                    $this->mpdf->page                     = $new_page;
                    $this->mpdf->ResetMargins();
                    $this->mpdf->Reset();
                    $this->mpdf->pageoutput[$this->mpdf->page] = array();
                }
                $this->mpdf->y = (($this->mpdf->blk[$this->mpdf->blklvl]['float_endpos'] * 1000) % 1000000) / 1000; // mod changes operands to integers before processing
            }

            /* -- END CSS-FLOAT -- */

            /* -- CSS-IMAGE-FLOAT -- */
            $this->mpdf->printfloatbuffer();
            /* -- END CSS-IMAGE-FLOAT -- */

            //Create Internal Links, if needed
            if ( ! empty($this->mpdf->internallink)) {
                foreach ($this->mpdf->internallink as $k => $v) {
                    if (strpos($k, "#") !== false) {
                        continue;
                    } //ignore

                    $ypos    = $v['Y'];
                    $pagenum = $v['PAGE'];
                    $sharp   = "#";

                    while (array_key_exists($sharp . $k, $this->mpdf->internallink)) {
                        $internallink = $this->mpdf->internallink[$sharp . $k];
                        $this->mpdf->SetLink($internallink, $ypos, $pagenum);
                        $sharp .= "#";
                    }
                }
            }

            $this->mpdf->bufferoutput = false;

            /* -- CSS-POSITION -- */
            if (count($this->mpdf->fixedPosBlockSave) && $mode != 4) {
                foreach ($this->mpdf->fixedPosBlockSave AS $fpbs) {
                    $old_page         = $this->mpdf->page;
                    $this->mpdf->page = $fpbs[2];
                    $this->mpdf->WriteFixedPosHTML($fpbs[0], 0, 0, 100, 100, 'auto',
                        $fpbs[1]);  // 0,0,10,10 are overwritten by bbox
                    $this->mpdf->page = $old_page;
                }
                $this->mpdf->fixedPosBlockSave = array();
            }
            /* -- END CSS-POSITION -- */
        }
    }

    /**
     * Check if we need to output the current progress
     *
     * @param integer $element ?
     * @param integer $value   ?
     * @param string  $text    ?
     *
     * @return void
     *
     * @todo determine variable types and their usage
     */
    public function maybe_update_progress_bar($element, $value, $text = '')
    {
        if ($this->mpdf->progressBar) {
            $this->mpdf->UpdateProgressBar($element, $value, $text);
        }
    }

    /**
     * Check if we need to reset the buffers to the top level block
     *
     * @param bool|true $init
     *
     * @return void
     */
    public function maybe_reset_buffers($init = true)
    {
        if ($init) {
            $this->mpdf->headerbuffer         = '';
            $this->mpdf->textbuffer           = array();
            $this->mpdf->fixedPosBlockSave    = array();
            $this->mpdf->blklvl               = 0;
            $this->mpdf->lastblocklevelchange = 0;
            $this->mpdf->blk                  = array();
            $this->mpdf->initialiseBlock($this->mpdf->blk[0]);
            $this->mpdf->blk[0]['width']        = &$this->mpdf->pgwidth;
            $this->mpdf->blk[0]['inner_width']  = &$this->mpdf->pgwidth;
            $this->mpdf->blk[0]['blockContext'] = $this->mpdf->blockContext;
        }
    }

    /**
     * Wrap the text in a HTML <style> tag
     *
     * @param string $styles
     *
     * @return string
     */
    public function wrap_header_css($styles)
    {
        return '<style> ' . $styles . ' </style>';
    }

    /**
     * If the configuration allows it, this method converts the current HTML encoding to UTF-8
     *
     * @param string  $html
     * @param integer $mode Current HTML writing mode
     *
     * @return string
     */
    public function set_character_encoding($html, $mode)
    {
        if ($this->mpdf->allow_charset_conversion) {

            /* Allow charset conversion from HTML when processing full HTML */
            if ($mode === 0) {
                /* Sets $mpdf->charset_in */
                $this->mpdf->ReadCharset($html);
            }

            /* User can manually define the charset so this has to be separate to the above IF statement */
            if ($this->mpdf->charset_in && $mode < 4) {
                $converted_html = iconv($this->mpdf->charset_in, 'UTF-8//TRANSLIT', $html);
                if ($converted_html !== false) {
                    $html = $converted_html;
                }
            }
        }

        return $html;
    }

    /**
     * Parses the <html>, <meta>, <base> and <body> tags and pull out the required attributes and styles
     *
     * Note: Looking at all these preg_match() statements, I can't help but think a DOM Reader class would be the better choice...
     * Once this is unit testable we'll be able to play around with this idea and dicuss which tool from packagist
     * would be the best option (as I would image what ever we choose will eventually be used throughout mPDF)
     *
     * @attr     string $html
     *
     * @return array
     *
     * @internal Not all that happy parsing $html by reference. Might be better to refractor the code further and create
     * a private property so we don't have pass $html back and forward through the object
     */
    public function parse_html_head_styles_and_attributes(&$html)
    {
        /**
         * A container for our parsed CSS and attributes
         *
         * @var array $zproperties
         */
        $zproperties = array();

        /* Gets and sets the HTML <title> and meta tags */
        $this->mpdf->ReadMetaTags($html);

        /* Set the base path if a <base> meta tag exists */
        $this->set_base_path($html);

        /* Extracts the styles from the $html block */
        $html = $this->mpdf->cssmgr->ReadCSS($html);

        /* Attempt to detect the language present in the file in the <html> tag, if configured to do so */
        $html_lang = $this->detect_language_from_html_tag($html);

        /* Attempt to determine the text direction based on the <html dir=""> attribute */
        $zproperties = array_merge($zproperties, $this->get_text_direction_from_html($html));

        /**
         * Check for any attributes and innerHTML on the <body> tag
         *
         * $body_tag_matches array contains the following:
         *
         * [0] is the original string
         * [1] is the matched <body> attributes
         * [2] is the <body> inner HTML
         */
        $body_tag_matches = $this->get_body_attributes_and_content($html);

        if ($body_tag_matches !== false) {

            /* Remove the <body> tag from the HTML by using the inner HTML */
            $html = $body_tag_matches[2];

            /* Parse any attributes and inline styles on the <body> tag */
            $zproperties = array_merge($zproperties, $this->parse_body_styles_and_attributes($body_tag_matches[1], $html_lang));
        }

        /* Merge all the evaluated styles/attributes to the <body> tag using the $pdf->cssmgr */
        $properties = $this->mpdf->cssmgr->MergeCSS('BLOCK', 'BODY', '');

        /* Merge <body> $properties from the $mpdf->cssmgr with our parsed $zproperties */
        if ($zproperties) {
            $properties = $this->mpdf->cssmgr->array_merge_recursive_unique($properties, $zproperties);
        }

        /* If a CSS property exists for the text direction we'll set it on the body */
        if (isset($properties['DIRECTION']) && $properties['DIRECTION']) {
            $this->mpdf->cssmgr->CSS['BODY']['DIRECTION'] = $properties['DIRECTION'];
        }

        /**
         * If no CSS propery exists to determine the direction we'll use the $mpdf->directionality variable
         * This appears it would benefit from being attached as an ELSE or ELSEIF to the above IF
         */
        if ( ! isset($this->mpdf->cssmgr->CSS['BODY']['DIRECTION'])) {
            $this->mpdf->cssmgr->CSS['BODY']['DIRECTION'] = $this->mpdf->directionality;
        } else {
            /* It appears this line should not be in the else statement at all */
            $this->mpdf->SetDirectionality($this->mpdf->cssmgr->CSS['BODY']['DIRECTION']);
        }

        /* Sets all our properties on the BODY */
        $this->mpdf->setCSS($properties, '', 'BODY');

        /* Attach the top-level block with our saved inline styles (is that the body?) */
        $this->mpdf->blk[0]['InlineProperties'] = $this->mpdf->saveInlineProperties();

        return $properties;
    }

    /**
     * If a <base> tag exists we'll set the $mpdf->basepath with it
     *
     * @param string $html
     *
     * @return void
     */
    public function set_base_path($html)
    {
        if (preg_match('/<base[^>]*href=["\']([^"\'>]*)["\']/i', $html, $m)) {
            $this->mpdf->SetBasePath($m[1]);
        }
    }

    /**
     * If configured to do so, and not using core fonts, we'll pull out the language from the
     * <html lang=""> attribute.
     *
     * @param string $html
     *
     * @return string|boolean
     */
    public function detect_language_from_html_tag($html)
    {
        if ($this->mpdf->autoLangToFont && ! $this->mpdf->usingCoreFont && preg_match('/<html [^>]*lang=[\'\"](.*?)[\'\"]/ism', $html, $m)) {
            return $m[1];
        }

        return false;
    }

    /**
     * Sniffs the <html> tag for a `dir="rtl"` attribute and returns the rtl body style
     *
     * @param $html
     *
     * @return array
     */
    public function get_text_direction_from_html($html)
    {
        $properties = array();
        if (preg_match('/<html [^>]*dir=[\'\"]\s*rtl\s*[\'\"]/ism', $html)) {
            $properties['DIRECTION'] = 'rtl';
        }

        return $properties;
    }

    /**
     * Parse the <body> for attributes and inner HTML
     *
     * @param $html
     *
     * @return array|bool
     *
     * Array returned contains:
     *
     * $m[0] is the original string
     * $m[1] is the matched <body> attributes
     * $m[2] is the <body> inner HTML
     */
    public function get_body_attributes_and_content($html)
    {
        if (preg_match('/<body([^>]*)>(.*?)<\/body>/ism', $html, $m) || preg_match('/<body([^>]*)>(.*)$/ism', $html, $m)) {
            return $m;
        }

        return false;
    }

    /**
     * Parses the <body> attributes and sets appropriate properties
     *
     * @param        $attributes The returned preg_match [1] array key from $this->get_body_attributes_and_content()
     * @param string $lang       The <html> lang attribute (if any)
     *
     * @return array
     */
    public function parse_body_styles_and_attributes($attributes, $lang = '')
    {
        $properties = array();

        /* Parse the inline CSS on the <body> tag, if any */
        if (preg_match('/style=[\"](.*?)[\"]/ism', $attributes, $mm) || preg_match('/style=[\'](.*?)[\']/ism', $attributes, $mm)) {
            $properties = $this->mpdf->cssmgr->readInlineCSS($mm[1]);
        }

        /* Attempt to detect the language present in the file in the <body> tag, if configured to do so */
        if (preg_match('/dir=[\'\"]\s*rtl\s*[\'\"]/ism', $attributes)) {
            $properties['DIRECTION'] = 'rtl';
        }

        /* Set the document language if matched from the <html> tag */
        if ( ! empty($lang)) {
            $properties['LANG'] = $lang;
        }

        /* Set the document language if matched from the <body> tag – overrides the <html lang=""> attribute */
        if ($this->mpdf->autoLangToFont && ! $this->mpdf->onlyCoreFonts && preg_match('/lang=[\'\"](.*?)[\'\"]/ism', $attributes, $mm)) {
            $properties['LANG'] = $mm[1];
        }

        return $properties;
    }
}