<?php

namespace MpdfAnalize\Tag;

use MpdfAnalize\Strict;

use MpdfAnalize\Cache;
use MpdfAnalize\Color\ColorConverter;
use MpdfAnalize\CssManager;
use MpdfAnalize\Form;
use MpdfAnalize\Image\ImageProcessor;
use MpdfAnalize\Language\LanguageToFontInterface;
use MpdfAnalize\MpdfAnalize;
use MpdfAnalize\Otl;
use MpdfAnalize\SizeConverter;
use MpdfAnalize\TableOfContents;

abstract class Tag
{

	use Strict;

	/**
	 * @var \MpdfAnalize\Mpdf
	 */
	protected $mpdf;

	/**
	 * @var \MpdfAnalize\Cache
	 */
	protected $cache;

	/**
	 * @var \MpdfAnalize\CssManager
	 */
	protected $cssManager;

	/**
	 * @var \MpdfAnalize\Form
	 */
	protected $form;

	/**
	 * @var \MpdfAnalize\Otl
	 */
	protected $otl;

	/**
	 * @var \MpdfAnalize\TableOfContents
	 */
	protected $tableOfContents;

	/**
	 * @var \MpdfAnalize\SizeConverter
	 */
	protected $sizeConverter;

	/**
	 * @var \MpdfAnalize\Color\ColorConverter
	 */
	protected $colorConverter;

	/**
	 * @var \MpdfAnalize\Image\ImageProcessor
	 */
	protected $imageProcessor;

	/**
	 * @var \MpdfAnalize\Language\LanguageToFontInterface
	 */
	protected $languageToFont;

	const ALIGN = [
		'left' => 'L',
		'center' => 'C',
		'right' => 'R',
		'top' => 'T',
		'text-top' => 'TT',
		'middle' => 'M',
		'baseline' => 'BS',
		'bottom' => 'B',
		'text-bottom' => 'TB',
		'justify' => 'J'
	];

	public function __construct(
		MpdfAnalize $mpdf,
		Cache $cache,
		CssManager $cssManager,
		Form $form,
		Otl $otl,
		TableOfContents $tableOfContents,
		SizeConverter $sizeConverter,
		ColorConverter $colorConverter,
		ImageProcessor $imageProcessor,
		LanguageToFontInterface $languageToFont
	) {

		$this->mpdf = $mpdf;
		$this->cache = $cache;
		$this->cssManager = $cssManager;
		$this->form = $form;
		$this->otl = $otl;
		$this->tableOfContents = $tableOfContents;
		$this->sizeConverter = $sizeConverter;
		$this->colorConverter = $colorConverter;
		$this->imageProcessor = $imageProcessor;
		$this->languageToFont = $languageToFont;
	}

	public function getTagName()
	{
		$tag = get_class($this);
		return strtoupper(str_replace('MpdfAnalize\Tag\\', '', $tag));
	}

	protected function getAlign($property)
	{
		$property = strtolower($property);
		return array_key_exists($property, self::ALIGN) ? self::ALIGN[$property] : '';
	}

	abstract public function open($attr, &$ahtml, &$ihtml);

	abstract public function close(&$ahtml, &$ihtml);

}
