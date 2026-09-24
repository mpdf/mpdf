# mPDF Font Package

## Introduction

Quivira is a serif font with a wide variety of unicode characters. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Cyrillic
* Armenian
* Hebrew
* Thai
* Georgian
* Cherokee
* Canadian Aboriginal
* Ogham
* Runic
* Tagalog
* Hanunoo
* Buhid
* Tagbanwa
* Braille
* Coptic
* Glagolitic
* Tifinagh
* Vai
* Samaritan
* Lisu

## Installation

```bash
composer require mpdf/font-quivira
```

## Usage

This package automatically registers the following fonts with mPDF:

- `quivira`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: quivira;
    }
</style>

<h1>Hello World in quivira</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Quivira\Registration as QuiviraRegistration;

$registry = new FontRegistry([
    new QuiviraRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.