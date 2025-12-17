# mPDF Font Package

## Introduction

This font package contains a collection of DejaVu fonts – including serif, sans-serif, and monospace variants – to be used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Cyrillic
* Armenian
* Hebrew
* Arabic
* Georgian
* Canadian Aboriginal
* Ogham
* Braille
* Coptic
* NKO
* Lisu

## Installation

```bash
composer require mpdf/font-dejavu-family
```

## Usage

This package automatically registers the following fonts with mPDF:

- `dejavusans` (sans-serif)
- `dejavusanscondensed` (sans-serif)
- `dejavuserif` (serif)
- `dejavuserifcondensed` (serif)
- `dejavusansmono` (monospace)

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: dejavusans;
    }

    .h1 {
        font-family: dejavusanscondensed;
    }
</style>

<h1>Hello World in dejavusanscondensed</h1>

<p>Some text in the dejavuserif font-family</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\DejavuFamily\Registration as DejavuFamilyRegistration;

$registry = new FontRegistry([
    new DejavuFamilyRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.