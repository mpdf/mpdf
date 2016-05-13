<?php

namespace Mpdf\Css;

class DefaultCss
{

	public static $definition = array(
		'BODY' => array(
			'FONT-FAMILY' => 'serif',
			'FONT-SIZE' => '11pt',
			'TEXT-INDENT' => '0pt',
			'LINE-HEIGHT' => 'normal',
			'MARGIN-COLLAPSE' => 'collapse', // Custom property to collapse top/bottom margins at top/bottom of page - ignored in tables/lists
			'HYPHENS' => 'manual',
			'FONT-KERNING' => 'auto',
		),
		'P' => array(
			'MARGIN' => '1.12em 0',
		),
		'H1' => array(
			'FONT-SIZE' => '2em',
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '0.67em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'H2' => array(
			'FONT-SIZE' => '1.5em',
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '0.75em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'H3' => array(
			'FONT-SIZE' => '1.17em',
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '0.83em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'H4' => array(
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '1.12em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'H5' => array(
			'FONT-SIZE' => '0.83em',
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '1.5em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'H6' => array(
			'FONT-SIZE' => '0.75em',
			'FONT-WEIGHT' => 'bold',
			'MARGIN' => '1.67em 0',
			'PAGE-BREAK-AFTER' => 'avoid',
		),
		'HR' => array(
			'COLOR' => '#888888',
			'TEXT-ALIGN' => 'center',
			'WIDTH' => '100%',
			'HEIGHT' => '0.2mm',
			'MARGIN-TOP' => '0.83em',
			'MARGIN-BOTTOM' => '0.83em',
		),
		'PRE' => array(
			'MARGIN' => '0.83em 0',
			'FONT-FAMILY' => 'monospace',
		),
		'S' => array(
			'TEXT-DECORATION' => 'line-through',
		),
		'STRIKE' => array(
			'TEXT-DECORATION' => 'line-through',
		),
		'DEL' => array(
			'TEXT-DECORATION' => 'line-through',
		),
		'SUB' => array(
			'VERTICAL-ALIGN' => 'sub',
			'FONT-SIZE' => '55%', /* Recommended 0.83em */
		),
		'SUP' => array(
			'VERTICAL-ALIGN' => 'super',
			'FONT-SIZE' => '55%', /* Recommended 0.83em */
		),
		'U' => array(
			'TEXT-DECORATION' => 'underline',
		),
		'INS' => array(
			'TEXT-DECORATION' => 'underline',
		),
		'B' => array(
			'FONT-WEIGHT' => 'bold',
		),
		'STRONG' => array(
			'FONT-WEIGHT' => 'bold',
		),
		'I' => array(
			'FONT-STYLE' => 'italic',
		),
		'CITE' => array(
			'FONT-STYLE' => 'italic',
		),
		'Q' => array(
			'FONT-STYLE' => 'italic',
		),
		'EM' => array(
			'FONT-STYLE' => 'italic',
		),
		'VAR' => array(
			'FONT-STYLE' => 'italic',
		),
		'SAMP' => array(
			'FONT-FAMILY' => 'monospace',
		),
		'CODE' => array(
			'FONT-FAMILY' => 'monospace',
		),
		'KBD' => array(
			'FONT-FAMILY' => 'monospace',
		),
		'TT' => array(
			'FONT-FAMILY' => 'monospace',
		),
		'SMALL' => array(
			'FONT-SIZE' => '83%',
		),
		'BIG' => array(
			'FONT-SIZE' => '117%',
		),
		'ACRONYM' => array(
			'FONT-SIZE' => '77%',
			'FONT-WEIGHT' => 'bold',
		),
		'ADDRESS' => array(
			'FONT-STYLE' => 'italic',
		),
		'BLOCKQUOTE' => array(
			'MARGIN-LEFT' => '40px',
			'MARGIN-RIGHT' => '40px',
			'MARGIN-TOP' => '1.12em',
			'MARGIN-BOTTOM' => '1.12em',
		),
		'A' => array(
			'COLOR' => '#0000FF',
			'TEXT-DECORATION' => 'underline',
		),
		'UL' => array(
			'PADDING' => '0 auto',
			'MARGIN-TOP' => '0.83em',
			'MARGIN-BOTTOM' => '0.83em',
		),
		'OL' => array(
			'PADDING' => '0 auto',
			'MARGIN-TOP' => '0.83em',
			'MARGIN-BOTTOM' => '0.83em',
		),
		'DL' => array(
			'MARGIN' => '1.67em 0',
		),
		'DT' => array(),
		'DD' => array(
			'PADDING-LEFT' => '40px',
		),
		'TABLE' => array(
			'MARGIN' => '0',
			'BORDER-COLLAPSE' => 'separate',
			'BORDER-SPACING' => '2px',
			'EMPTY-CELLS' => 'show',
			'LINE-HEIGHT' => '1.2',
			'VERTICAL-ALIGN' => 'middle',
			'HYPHENS' => 'manual',
			'FONT-KERNING' => 'auto',
		),
		'THEAD' => array(),
		'TFOOT' => array(),
		'TH' => array(
			'FONT-WEIGHT' => 'bold',
			'TEXT-ALIGN' => 'center',
			'PADDING-LEFT' => '0.1em',
			'PADDING-RIGHT' => '0.1em',
			'PADDING-TOP' => '0.1em',
			'PADDING-BOTTOM' => '0.1em',
		),
		'TD' => array(
			'PADDING-LEFT' => '0.1em',
			'PADDING-RIGHT' => '0.1em',
			'PADDING-TOP' => '0.1em',
			'PADDING-BOTTOM' => '0.1em',
		),
		'CAPTION' => array(
			'TEXT-ALIGN' => 'center',
		),
		'IMG' => array(
			'MARGIN' => '0',
			'VERTICAL-ALIGN' => 'baseline',
			'IMAGE-RENDERING' => 'auto',
		),
		'INPUT' => array(
			'FONT-FAMILY' => 'sans-serif',
			'VERTICAL-ALIGN' => 'middle',
			'FONT-SIZE' => '0.9em',
		),
		'SELECT' => array(
			'FONT-FAMILY' => 'sans-serif',
			'FONT-SIZE' => '0.9em',
			'VERTICAL-ALIGN' => 'middle',
		),
		'TEXTAREA' => array(
			'FONT-FAMILY' => 'monospace',
			'FONT-SIZE' => '0.9em',
			'VERTICAL-ALIGN' => 'text-bottom',
		),
		'MARK' => array(
			'BACKGROUND-COLOR' => 'yellow',
		),
	);

}
