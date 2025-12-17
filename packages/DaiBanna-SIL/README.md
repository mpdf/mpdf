# mPDF Font Package

## Introduction

Dai Banna provides a libre and open font family for the New Tai Lue (Xishuangbanna Dai) script. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* New Tai Lue
* Latin

## Installation

```bash
composer require mpdf/font-daibanna-sil
```

## Usage

This package automatically registers the following fonts with mPDF:

- `daibannasil` (regular, italic, bold, bold-italic)

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: daibannasil;
    }
</style>

<h1>Hello World in daibannasil</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\DaiBannaSil\Registration as DaiBannaSilRegistration;

$registry = new FontRegistry([
    new DaiBannaSilRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.