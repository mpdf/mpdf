<?php

namespace Mpdf;

use Mpdf\Color\ColorConverter;
use Mpdf\Color\ColorModeConverter;
use Mpdf\Color\ColorSpaceRestrictor;

use Mpdf\Fonts\FontCache;
use Mpdf\Fonts\FontFileFinder;

use Mpdf\Image\ImageProcessor;

use Mpdf\Pdf\Protection;
use Mpdf\Pdf\Protection\UniqidGenerator;

use Mpdf\Writer\BaseWriter;
use Mpdf\Writer\FontWriter;
use Mpdf\Writer\FormWriter;
use Mpdf\Writer\ImageWriter;
use Mpdf\Writer\MetadataWriter;

use Mpdf\Writer\PageWriter;
use Psr\Log\LoggerInterface;

class ServiceFactory
{

	public function getServices(
		Mpdf $mpdf,
		LoggerInterface $logger,
		$config,
		$restrictColorSpace,
		$languageToFont,
		$scriptToLanguage,
		$fontDescriptor,
		$bmp,
		$directWrite,
		$wmf
	)
	{
		$sizeConverter = new SizeConverter($mpdf->dpi, $mpdf->default_font_size, $mpdf, $logger);

		$colorModeConverter = new ColorModeConverter();
		$colorSpaceRestrictor = new ColorSpaceRestrictor(
			$mpdf,
			$colorModeConverter,
			$restrictColorSpace
		);
		$colorConverter = new ColorConverter($mpdf, $colorModeConverter, $colorSpaceRestrictor);

		$gradient = new Gradient($mpdf, $sizeConverter, $colorConverter);
		$tableOfContents = new TableOfContents($mpdf, $sizeConverter);

		$cache = new Cache($config['tempDir']);
		$fontCache = new FontCache(new Cache($config['tempDir'] . '/ttfontdata'));

		$fontFileFinder = new FontFileFinder($config['fontDir']);

		$cssManager = new CssManager($mpdf, $cache, $sizeConverter, $colorConverter);

		$otl = new Otl($mpdf, $fontCache);

		$protection = new Protection(new UniqidGenerator());

		$writer = new BaseWriter($mpdf, $protection);

		$formWriter = new FormWriter($mpdf, $writer);

		$form = new Form($mpdf, $otl, $colorConverter, $writer, $formWriter);

		$hyphenator = new Hyphenator($mpdf);

		$imageProcessor = new ImageProcessor(
			$mpdf,
			$otl,
			$cssManager,
			$sizeConverter,
			$colorConverter,
			$colorModeConverter,
			$cache,
			$languageToFont,
			$scriptToLanguage,
			$logger
		);

		$tag = new Tag(
			$mpdf,
			$cache,
			$cssManager,
			$form,
			$otl,
			$tableOfContents,
			$sizeConverter,
			$colorConverter,
			$imageProcessor,
			$languageToFont
		);

		$fontWriter = new FontWriter($mpdf, $writer, $fontCache, $fontDescriptor);
		$metadataWriter = new MetadataWriter($mpdf, $writer, $form, $protection, $logger);
		$imageWriter = new ImageWriter($mpdf, $writer);
		$pageWriter = new PageWriter($mpdf, $form, $writer, $metadataWriter);

		return [
			'otl' => $otl,
			'bmp' => $bmp,
			'cache' => $cache,
			'cssManager' => $cssManager,
			'directWrite' => $directWrite,
			'fontCache' => $fontCache,
			'fontFileFinder' => $fontFileFinder,
			'form' => $form,
			'gradient' => $gradient,
			'tableOfContents' => $tableOfContents,
			'tag' => $tag,
			'wmf' => $wmf,
			'sizeConverter' => $sizeConverter,
			'colorConverter' => $colorConverter,
			'hyphenator' => $hyphenator,
			'imageProcessor' => $imageProcessor,
			'protection' => $protection,
			'languageToFont' => $languageToFont,
			'scriptToLanguage' => $scriptToLanguage,
			'writer' => $writer,
			'fontWriter' => $fontWriter,
			'metadataWriter' => $metadataWriter,
			'imageWriter' => $imageWriter,
			'formWriter' => $formWriter,
			'pageWriter' => $pageWriter,
		];
	}

}
