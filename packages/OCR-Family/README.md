# mPDF Font Package

## Introduction

OCR-A and OCR-B barcode fonts. OCR-A is the font used for the ISBNs on books; and OCR-B is used for the human-readable digits on UPC/EAN bar codes. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin

## Installation

```bash
composer require mpdf/font-ocr-family
```

## Usage

This package automatically registers the following fonts with mPDF:

- `ocra`
- `ocrb`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    .ocra {
        font-family: ocra;
    }

    .ocrb {
        font-family: ocrb;
    }
</style>

<p class="ocra">Some text in ocra</p>
<p class="ocrb">Some text in ocrb</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\OcrFamily\Registration as OcrFamilyRegistration;

$registry = new FontRegistry([
    new OcrFamilyRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.