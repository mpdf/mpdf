# mPDF Font Package

## Introduction

Sun-Ext is a serif unicode font that includes extensive CJK (Chinese/Japanise/Korean) characters. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Cyrillic
* Armenian
* Hebrew
* Arabic
* Syriac
* Thaana
* Devanagari
* Bengali
* Gurmukhi
* Gujarati
* Oriya
* Tamil
* Telugu
* Kannada
* Malayalam
* Thai
* Leo
* Tibetan
* Myanmar
* Georgian
* Hangul
* Ethiopic
* Cherokee
* Canadian Aboriginal
* Ogham
* Runic
* Khmer
* Mongolian
* Hiragana
* Katakana
* Bopomofo
* Han
* Yi
* Buhid
* Limbu
* Braille
* Coptic

## Installation

```bash
composer require mpdf/font-sunext
```

## Usage

This package automatically registers the following fonts with mPDF:

- `sun-exta`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: sun-exta;
    }
</style>

<h1>Hello World in sun-exta</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\SunExt\Registration as SunExtRegistration;

$registry = new FontRegistry([
    new SunExtRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.