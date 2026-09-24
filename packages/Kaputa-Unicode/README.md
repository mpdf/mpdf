# mPDF Font Package

## Introduction

Kaputa is one the most known and widely used Sinhala Unicode fonts. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Sinhala
* Latin

## Installation

```bash
composer require mpdf/font-kaputa-unicode
```

## Usage

This package automatically registers the following fonts with mPDF:

- `kaputaunicode`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: kaputaunicode;
    }
</style>

<h1>Hello World in kaputaunicode</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\KaputaUnicode\Registration as KaputaUnicodeRegistration;

$registry = new FontRegistry([
    new KaputaUnicodeRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.