# mPDF Font Package

## Introduction

Abyssinica SIL is a unicode font that supports Ethiopic and Latin languages. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Ethiopic
* Latin

## Installation

```bash
composer require mpdf/font-abyssinica-sil
```

## Usage

This package automatically registers the following fonts with mPDF:

- `abyssinicasil`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: abyssinicasil;
    }
</style>

<h1>Hello World in abyssinicasil</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\AbyssinicaSil\Registration as AbyssinicaSilRegistration;

$registry = new FontRegistry([
    new AbyssinicaSilRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.