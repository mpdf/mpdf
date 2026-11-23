# mPDF Font Package

## Introduction

Lohit Kannada is a Unicode font for the Kannada script. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Kannada

## Installation

```bash
composer require mpdf/font-lohit-kannada
```

## Usage

This package automatically registers the following fonts with mPDF:

- `lohitkannada`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: lohitkannada;
    }
</style>

<h1>Hello World in lohitkannada</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\LohitKannada\Registration as LohitKannadaRegistration;

$registry = new FontRegistry([
    new LohitKannadaRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.