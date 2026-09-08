# mPDF Font Package

## Introduction

Eeyek is a Unicode font for the Meetei Mayek script. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Meetei Mayek
* Latin

## Installation

```bash
composer require mpdf/font-eeyek
```

## Usage

This package automatically registers the following fonts with mPDF:

- `eeyek`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: eeyek;
    }
</style>

<h1>Hello World in eeyek</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Eeyek\Registration as EeyekRegistration;

$registry = new FontRegistry([
    new EeyekRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.