# mPDF Font Package

## Introduction

The Ancient Scripts collection – designed by George Douros – includes Aegean, Aegyptus, and Akkadian fonts. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Greek
* Coptic
* Egyptian Hieroglyphs

## Installation

```bash
composer require mpdf/font-ancient-scripts-bundle
```

## Usage

This package automatically registers the following fonts with mPDF:

- `aegyptus`
- `aegean`
- `akkadian`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    h1 {
        font-family: aegyptus;
    }

    .aegean {
        font-family: aegean;
    }

    .akkadian {
        font-family: akkadian;
    }
</style>

<h1>Hello World in aegyptus</h1>
<p class="aegean">Some text in aegean</p>
<p class="akkadian">Some text in akkadian</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\AncientScriptsBundle\Registration as AncientScriptsBundleRegistration;

$registry = new FontRegistry([
    new AncientScriptsBundleRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.