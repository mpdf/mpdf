# mPDF Font Package

## Introduction

Jomolhari is a font designed by Christopher Fynn for displaying Tibetan and Dzongkha text. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Tibetan
* Latin

## Installation

```bash
composer require mpdf/font-jomolhari
```

## Usage

This package automatically registers the following fonts with mPDF:

- `jomolhari`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: jomolhari;
    }
</style>

<h1>Hello World in jomolhari</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Jomolhari\Registration as JomolhariRegistration;

$registry = new FontRegistry([
    new JomolhariRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.