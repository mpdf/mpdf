# mPDF Font Package

## Introduction

The Free family of fonts includes sans, serif, and mono font varieties. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Cyrillic
* Armenian
* Hebrew
* Arabic
* Syriac
* Thaana
* Devanagari
* Bengali
* Gurmukhi
* Hujarati
* Oriya
* Tamil
* Malayalam
* Sinhala
* Thai
* Georgian
* Ethiopic
* Cherokee
* Canadian Aboriginal
* Hanunoo
* Tai Le
* Buginese
* Coptic
* Glagolitic
* Tifinagh
* Vai

## Installation

```bash
composer require mpdf/font-free-family
```

## Usage

This package automatically registers the following fonts with mPDF:

- `freesans` (sans-serif)
- `freeserif` (serif)
- `freemono` (monospace)

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: freesans;
    }

    .serif {
        font-family: freeserif;
    }
</style>

<h1>Hello World in freesans</h1>

<p class="serif">Some text in freeserif</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\FreeFamily\Registration as FreeFamilyRegistration;

$registry = new FontRegistry([
    new FreeFamilyRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.