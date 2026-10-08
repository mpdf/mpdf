# mPDF Font Package

## Introduction

MPH 2B Damase serif unicode font created by Mark Williamson. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Cyrillic
* Armenian
* Hebrew
* Arabic
* Thaana
* Bengali
* Georgian
* Cherokee
* Hanunoo
* Limbu
* Tai Le
* Buginese
* Coptic
* Glagolitic
* Tifinagh
* Nagri

## Installation

```bash
composer require mpdf/font-mph-2b-damase
```

## Usage

This package automatically registers the following fonts with mPDF:

- `mph2bdamase`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: mph2bdamase;
    }
</style>

<h1>Hello World in mph2bdamase</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Mph2bDamase\Registration as Mph2bDamaseRegistration;

$registry = new FontRegistry([
    new Mph2bDamaseRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.