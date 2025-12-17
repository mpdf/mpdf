# mPDF Font Package

## Introduction

This package adds monochrome emoji support to mPDF using the open source font Noto Emoji. This package is used with the [mPDF library](https://github.com/mpdf/mpdf).

## Installation

```bash
composer require mpdf/font-emoji
```

## Usage

This package automatically registers the following fonts with mPDF:

- `emoji`

Example usage in mPDF:
```php
<?php

$mpdf = new \Mpdf\Mpdf( ['useSubstitution' => true]);
$mpdf->WriteHTML('🥳🧁🍰🎁🎂🎈🎺');

$mpdf->OutputHttpDownload('example.pdf');
```

## Advanced Installation

To manually register the font package with mPDF, you can initialize the package's `Registration` class and pass it to the `FontRegistry` constructor:

```php
<?php

use Mpdf\Mpdf;
use Mpdf\Fonts\FontRegistry;
use Mpdf\Fonts\Emoji\Registration as EmojiRegistration;

$registry = new FontRegistry([
    new EmojiRegistration(),
]);

$mpdf = new Mpdf([
    'useSubstitution' => true
    'fontRegistry' => $registry
]);
```

## License

This package is licensed under the MIT License. Fonts are licensed separately by their authors, and can be found in the [`/fonts/LICENSE.txt`](./fonts/LICENSE.txt) file.