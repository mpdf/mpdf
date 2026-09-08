# AGENTS.md

This file provides guidance to coding agents when working with code in this repository.

## Project

mPDF is a PHP library that generates PDF files from UTF-8 encoded HTML. It descends from FPDF and
HTML2FPDF. Published as `mpdf/mpdf` on Packagist, licensed GPL-2.0-only. Docs live at
https://mpdf.github.io (the `mpdf/mpdf.github.io` repo, not this one).

The default branch is `development`, not `master`. PRs go there except for backports.

## Commands

```bash
composer install                    # also chmods ./tmp to 0777 via post-install-cmd

composer test                       # PHPUnit; snapshot group excluded by default
composer test -- --group=snapshot   # snapshot tests only (needs ext-imagick + ghostscript)
composer coverage                   # --coverage-text, needs xdebug

composer cs                         # phpcs, PSR-2-with-tabs ruleset over src utils tests
composer cs:fix                     # phpcbf

vendor/bin/phpstan                  # level 2 over src/, uses phpstan-baseline.neon
```

PHPStan is *not* in `require-dev` — CI installs `phpstan/phpstan:^2.0` on the fly. Install it
yourself before running static analysis locally, do not commit the change.

Running a single test or a subset:

```bash
vendor/bin/phpunit tests/Issues/Issue2181Test.php
vendor/bin/phpunit --filter testSomeMethod tests/Mpdf/MpdfTest.php
vendor/bin/phpunit tests/Mpdf/Css
```

## Hard constraints

- **PHP 5.6 is the floor** (`^5.6 || ^7.0 || ~8.0–8.5`), and CI runs all 12 versions on Ubuntu *and*
  Windows. `src/` contains no `??`, no arrow functions, no typed properties, no return types — keep
  it that way. Use `isset()`/ternaries, `list()` destructuring, and docblock `@var`/`@param` types.
- **Indentation is tabs**, 4-wide (`ruleset.xml` = PSR-2 with `DisallowSpaceIndent` and tab
  `ScopeIndent`). Space indentation fails `composer cs`.
- **The `Strict` trait** (`src/Strict.php`, used by `Mpdf`, `Tag`, and most services) makes reading
  or writing an undeclared property throw `MpdfException`. Declare a property before assigning to it.
- Every PR should add a `CHANGELOG.md` entry under the current `mPDF 8.3.x` section
  (New features / Bugfixes), and a test where possible.
- Where applicable, docs should also be updated with more significant code changes and proposed as
  a separate PR in their own repository.

## Conventions

- Create a separate aptly named branch for the work.
- Commit changes in small self-contained chunks.
- Do not use excessive comments, comment in code only when absolutely necessary.
- Commit messages themselves have to fit to a single line.
- Always acknowledge used model in the third line of the commit message when AI was used.

## Architecture

### The god object and its services

`src/Mpdf.php` is ~27.5k lines and holds nearly all rendering state as public properties. It is not
being decomposed by class extraction so much as by *service extraction*.

`Mpdf::__construct()` builds config (`initConfig()` merging `Config/ConfigVariables.php` +
`Config/FontVariables.php` with the user's `$config` array), then calls
`ServiceFactory::getServices()`. That returns an array keyed by property name; the constructor loops
and does `$this->{$key} = $service`, recording the names in `$this->services`. So collaborators like
`$this->cssManager`, `$this->otl`, `$this->writer`, `$this->colorConverter` appear as properties with
no constructor parameter — grep `ServiceFactory` to find where any of them is built.

An optional second constructor argument, a `Mpdf\Container\ContainerInterface`, lets callers override
`httpClient`, `localContentLoader`, and `assetFetcher`. This is the only supported extension seam for
services; `ServiceFactory` checks `$container->has(...)` for exactly those keys.

### HTML → PDF pipeline

1. `WriteHTML($html, $mode, $init, $close)` (~line 13182) is the entry point. `$mode` comes from
   `HTMLParserMode` — `DEFAULT_MODE` (whole document), `HEADER_CSS` (stylesheet only), `HTML_BODY`,
   plus two `@internal` modes for non-writing parses and header buffering.
2. Charset detection (`ReadCharset`), `purify_utf8`, meta tags, `<base href>`.
3. `CssManager::readCss()` strips stylesheets out of the HTML and parses them. `CssManager` is a
   thin facade over `Css\CssParser` (parse) and `Css\CssMerger` (cascade/specificity), recently
   refactored — note that call sites in `Mpdf.php` still use the old casing (`ReadCSS`, `MergeCSS`),
   which works only because PHP method names are case-insensitive. Parsed rules live in the public
   `$CSS` / `$cascadeCSS` / `$tablecascadeCSS` arrays; `mergeCss($inherit, $tag, $attr)` resolves the
   cascade for one element.
4. The HTML is tokenized and each tag dispatches through `Tag::OpenTag()`/`CloseTag()`, which resolve
   a handler class via the static map in `Tag::getTagClassName()` → `src/Tag/*.php` (one class per
   HTML element, e.g. `Tag\Div`, `Tag\Table`, plus mPDF-specific ones like `Tag\Barcode`,
   `Tag\DotTab`, `Tag\PageHeader`, `Tag\IndexEntry`). **Adding element support means adding a class
   in `src/Tag/` and, if the class name differs from the uppercased tag, an entry in that map.**
5. Text accumulates in `$this->textbuffer`, then flows through line-breaking, justification, and
   `src/Otl.php` (~6.2k lines) for OpenType layout: GSUB/GPOS, bidi, and the complex-script shapers in
   `src/Shaper/` (Indic, Myanmar, Sea).
6. `src/Writer/*` emit the actual PDF objects — one writer per concern (`FontWriter`, `ImageWriter`,
   `PageWriter`, `MetadataWriter`, `BookmarkWriter`, …), all coordinating through `BaseWriter`, which
   appends to `Buffer` (an array-backed buffer, for memory).
7. `Output()` / `OutputFile()` / `OutputHttpInline()` / `OutputBinaryData()` finish the document;
   `Output\Destination` holds the destination constants.

### Fonts

`Fonts\FontFileFinder` locates TTFs under the configured `fontDir`; `TTFontFile` parses them;
`Fonts\MetricsGenerator` produces metrics, and `Fonts\FontCache` (wrapping `Cache`) persists parsed
font data under `<tempDir>/mpdf/ttfontdata`. Font families and their file mappings are declared in
`Config/FontVariables.php`; bundled fonts live in `ttfonts/` and `data/font/`. Subsetting is on by
default. `Language\LanguageToFont` + `Language\ScriptToLanguage` drive `autoLangToFont` /
`autoScriptToLang`.

### Other subsystems

- `src/Image/` — `ImageProcessor` plus decoders for BMP, WMF, SVG (`Image\Svg` is a full SVG
  renderer), with `src/Gif/` for GIF. `ImageTypeGuesser` sniffs formats.
- `src/Barcode/` — one class per symbology behind `BarcodeInterface`/`AbstractBarcode`; 2D QR codes
  come from the optional `mpdf/qrcode` package.
- `src/Color/` — `ColorConverter` is the single entry point; `ColorSpaceRestrictor` enforces
  PDF/A and PDF/X colorspace rules.
- `src/Conversion/DecTo*` — list numbering systems (roman, alpha, greek, hebrew, CJK, other).
- `src/FpdiTrait.php` + `setasign/fpdi` — importing pages from existing PDFs.
- `src/Pdf/Protection.php` — encryption and permissions.
- `data/` — runtime data files loaded with `require`: `upperCase.php`, `entity_substitutions.php`,
  `CJKdata.php`, collation tables, hyphenation `patterns/`, ICC profiles, `mpdf.css` (the default
  stylesheet).
- `utils/` — standalone CLI/browser scripts for inspecting fonts and images; not part of the
  distributed package (`export-ignore`).

## Tests

Three suites, all under `tests/` with PSR-4 roots `Mpdf\` → `tests/Mpdf`, `Issues\` → `tests/Issues`,
`Snapshots\` → `tests/Snapshots`.

- `tests/Mpdf/` — unit tests per class, mirroring `src/`. Most extend
  `Mpdf\BaseMpdfTest`, which constructs `new Mpdf(['mode' => 'c'])` in `set_up()` and calls
  `cleanup()` in `tear_down()`.
- `tests/Issues/IssueNNNNTest.php` — regression tests named after the GitHub issue number. This is
  the conventional home for a bugfix test.
- `tests/Snapshots/` — subclasses of `Snapshots\Snapshot` implementing `getId()` and
  `generatePdf()` (which sets `$this->mpdf`; do **not** call `Output*()` in it). The base class
  renders the PDF, converts both it and `tests/data/snapshots/<id>.pdf` to PNGs via Imagick, and
  compares page by page with `METRIC_ABSOLUTEERRORMETRIC` against `getComparisonFuzzyLimit()`.
  Failures dump diff images and the generated PDF into `<tempDir>/artifacts/`.

Snapshot tests carry `@group snapshot` and are **excluded** by `phpunit.xml`, because they need
`ext-imagick`, ghostscript, and an ImageMagick policy that permits PDF reads. They run in their own
CI workflow on PHP 8.5. Committed baseline PDFs — not images — are the fixtures, since Imagick
versions rasterize differently.

Test base classes use `Yoast\PHPUnitPolyfills\TestCases\TestCase`, hence the snake_case
`set_up()`/`tear_down()` hooks, which keep the suite runnable from PHPUnit 5 through 9.
`tests/bootstrap.php` instantiates an `Mpdf` once so its constants are defined for every test.

## CI

Five GitHub Actions workflows on `pull_request` and pushes to `development`/`master`/`test`:
`tests.yml` (12 PHP versions × Ubuntu/Windows), `cs.yml` (PHP 7.4), `static-analysis.yml` (PHP 8.2),
`snapshots.yml` (PHP 8.5 + imagick), `coverage.yml` (PHP 7.4 + xdebug, `development` only).

## Notes

- `composer.lock` is gitignored — the library is tested against floating dependencies.
- `tmp/` is the default temp dir and must be writable; mPDF cleans up old files there, so a
  dedicated path is recommended for real use (`tempDir` config).
- Fetching remote assets is unreliable under single-threaded servers such as `php -S`.
