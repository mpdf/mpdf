# mPDF Font Package

## Introduction

This font package contains a collection of fonts that support scripts used by North American Aboriginals, and is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Cherokee
* Candian Aboriginal
* Latin

## Installation

```bash
composer require mpdf/font-aboriginal-family
```

## Usage

This package automatically registers the following fonts with mPDF:

- `aboriginalsans` (sans-serif)
- `aboriginalserif` (serif)

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: aboriginalsans;
    }

    .h1 {
        font-family: aboriginalserif;
    }
</style>

<h1>Hello World in aboriginalserif</h1>

<p>Some text in the aboriginalsans font-family</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\AboriginalFamily\Registration as AboriginalFamilyRegistration;

$registry = new FontRegistry([
    new AboriginalFamilyRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.