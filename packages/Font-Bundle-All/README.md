# mPDF Font Package

## Introduction

A helper package that installs a full suite of fonts (110MB) designed to support most languages worldwide. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

## Installation

```bash
composer require mpdf/font-bundle-all
```

## Usage

This is a meta-package. Installing it will automatically install and register the following font packages with mPDF:

- `mpdf/font-ocr-family`
- `mpdf/font-ancient-scripts-bundle`
- `mpdf/font-aboriginal-family`
- `mpdf/font-tai-heritage-pro`
- `mpdf/font-sundanese-unicode`
- `mpdf/font-pothana-2000`
- `mpdf/font-lohit-kannada`
- `mpdf/font-lanna-alif`
- `mpdf/font-khmer-os`
- `mpdf/font-kaputa-unicode`
- `mpdf/font-jomolhari`
- `mpdf/font-eeyek`
- `mpdf/font-dhyana`
- `mpdf/font-daibanna-sil`
- `mpdf/font-myanmar-bundle`
- `mpdf/font-garuda`
- `mpdf/font-unbatang`
- `mpdf/font-mph-2b-damase`
- `mpdf/font-quivira`
- `mpdf/font-abyssinica-sil`
- `mpdf/font-middle-east-scripts-bundle`
- `mpdf/font-sunext`
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