# mPDF Font Package

## Introduction

Pothana2000 is a Unicode compliant OpenType font for Telugu and was created by Dr. Tirumala Krishna Desikacharyulu. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Telugu

## Installation

```bash
composer require mpdf/font-pothana-2000
```

## Usage

This package automatically registers the following fonts with mPDF:

- `pothana2000`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: pothana2000;
    }
</style>

<h1>Hello World in pothana2000</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Pothana2000\Registration as Pothana2000Registration;

$registry = new FontRegistry([
    new Pothana2000Registration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.