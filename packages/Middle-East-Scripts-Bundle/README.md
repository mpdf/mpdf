# mPDF Font Package

## Introduction

An mPDF scripts bundle designed for middle-eastern languages. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

**Supported Scripts**: 
* Latin
* Arabic
* Hebrew
* Syriac

## Installation

```bash
composer require mpdf/font-middle-east-scripts-bundle
```

## Usage

This package automatically registers the following fonts with mPDF:

- `lateef`
- `xbriyaz`
- `estrangeloedessa`
- `taameydavidclm`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<style>
    body {
        font-family: xbriyaz;
    }

    .taameydavidclm {
        font-family: taameydavidclm;
    }
</style>

<h1>Hello World in xbriyaz</h1>

<p class="taameydavidclm">Some text in taameydavidclm</p>
');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\ArabicScriptsBundle\Registration as ArabicScriptsBundleRegistration;

$registry = new FontRegistry([
    new ArabicScriptsBundleRegistration(),
]);

$mpdf = new Mpdf([
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in following license files:

- `lateef`: [LICENSE-Lateef.txt](./fonts/LICENSE-Lateef.txt)
- `xbriyaz`: [LICENSE-XB-Riyaz.txt](./fonts/LICENSE-XB-Riyaz.txt)
- `estrangeloedessa`: [LICENSE-Estrangelo-Edessa.txt](./fonts/LICENSE-Estrangelo-Edessa.txt)
- `taameydavidclm`: [LICENSE-TaameyDavidCLM.txt](./fonts/LICENSE-TaameyDavidCLM.txt)