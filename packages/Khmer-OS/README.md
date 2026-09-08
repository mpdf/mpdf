# mPDF Font Package

## Introduction

A font for the Khmer language of Cambodia. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Khmer
* Latin

## Installation

```bash
composer require mpdf/font-khmer-os
```

## Usage

This package automatically registers the following fonts with mPDF:

- `khmeros`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: khmeros;
    }
</style>

<h1>Hello World in khmeros</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\KhmerOs\Registration as KhmerOsRegistration;

$registry = new FontRegistry([
    new KhmerOsRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.