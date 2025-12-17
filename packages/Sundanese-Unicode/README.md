# mPDF Font Package

## Introduction

Sundanese Unicode is a font with traditional script used by the Sundanese people of West Java, Indonesia. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Sundanese

## Installation

```bash
composer require mpdf/font-sundanese-unicode
```

## Usage

This package automatically registers the following fonts with mPDF:

- `sundaneseunicode` (regular)

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: sundaneseunicode;
    }
</style>

<h1>Hello World in sundaneseunicode</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\SundaneseUnicode\Registration as SundaneseUnicodeRegistration;

$registry = new FontRegistry([
    new SundaneseUnicodeRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.