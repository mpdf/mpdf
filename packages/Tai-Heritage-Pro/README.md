# mPDF Font Package

## Introduction

Tai Heritage Pro is a Tai Viet font designed to reflect the traditional hand-written style of the script that is treasured by the Tai people of Vietnam. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Tai Viet
* Latin

## Installation

```bash
composer require mpdf/font-tai-heritage-pro
```

## Usage

This package automatically registers the following fonts with mPDF:

- `taiheritagepro`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: taiheritagepro;
    }
</style>

<h1>Hello World in taiheritagepro</h1>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\TaiHeritagePro\Registration as TaiHeritageProRegistration;

$registry = new FontRegistry([
    new TaiHeritageProRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.