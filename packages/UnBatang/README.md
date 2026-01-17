# mPDF Font Package

## Introduction

UnBatang is a serif font. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Korean
* Latin

## Installation

```bash
composer require mpdf/font-unbatang
```

## Usage

This package automatically registers the following fonts with mPDF:

- `unbatang`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: unbatang;
    }
</style>

<h1>Hello World in unbatang</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\UnBatang\Registration as UnBatangRegistration;

$registry = new FontRegistry([
    new UnBatangRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.