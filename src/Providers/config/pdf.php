<?php
/**
 * Created by PhpStorm.
 * User: ghans
 * Date: 19/12/17
 * Time: 04:48 PM
 */
return [
    'mode'                  => '',
    'format'                => 'US Letter',
    'defaultFontSize'       => '',
    'defaultFont'           => 'OpenSans',
    'marginLeft'            => 10,
    'marginRight'           => 10,
    'marginTop'             => 40,
    'marginBottom'          => 35,
    'marginHeader'          => 10,
    'marginFooter'          => 5,
    'orientation'           => 'portrait',
    'title'                 => 'Laravel mpdf wrapper',
    'author'                => 'ghans',
    'watermark'             => '',
    'showWatermark'         => false,
    'watermarkFont'         => 'DejaVuSansCondensed',
    'displayMode'           => 'fullpage',
    'watermarkTextAlpha'    => 0.1,
    'protection'            => [
        /*
        | SetProtection – Encrypts and sets the PDF document permissions
        |
        | https://mpdf.github.io/reference/mpdf-functions/setprotection.html
        */
        'permissions' => [
            'copy' => false,
            'print' => true,
            'modify' => false,
            'annot-forms' => false,
            'fill-forms' => false,
            'extract' => false,
            'assemble' => false,
            'print-highres' => false,
        ],
        'user_password' => null,
        'owner_password' => null,
        'length' => 40,
    ],
];