# mPDF Font Package

## Introduction

Lanna Alif is a Unicode font for the Tai Tham script. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Tai Tham
* Latin

## Installation

```bash
composer require mpdf/font-lanna-alif
```

## Usage

This package automatically registers the following fonts with mPDF:

- `lannaalif`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: lannaalif;
    }
</style>

<h1>Hello World in lannaalif</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\LannaAlif\Registration as LannaAlifRegistration;

$registry = new FontRegistry([
    new LannaAlifRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.