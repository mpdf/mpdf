# mPDF Font Package

## Introduction

A helper package that installs a subset of fonts (30MB) designed to support most common languages worldwide. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

## Installation

```bash
composer require mpdf/font-bundle-lite
```

## Usage

This is a meta-package. Installing it will automatically install and register the following font packages with mPDF:

- `mpdf/font-myanmar-bundle`
- `mpdf/font-garuda`
- `mpdf/font-mph-2b-damase`
- `mpdf/font-quivira`
- `mpdf/font-middle-east-scripts-bundle`
- `mpdf/font-free-family`
- `mpdf/font-dejavu-family`

Refer to each package for the specific fonts that can be used.

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf([
    'baseScript' => \Mpdf\Ucdn::SCRIPT_LATIN, // change this if your primary script is not latin (used so autoScriptToLang will ignore this script)
    'useSubsitutions' => true, // if a font is missing a character it will substitute with a similar character from another font
    'autoScriptToLang' => true, // automatically marks up HTML text using the lang attribute
    'autoLangToFont' => true, // automatically matches the lang attribute to a suitable font (ignoring the current font-family)
    'autoVietnamese' => true, //automatically marks up Vietnamese languages with the lang attribute
    'autoArabic' => true, // automatically marks up Arabic languages with the lang attribute
]);
$mpdf->WriteHTML('
<h1>Hello World</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## License

This package is licensed under the MIT License.