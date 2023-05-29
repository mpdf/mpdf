<?php

namespace Mpdf;

class WatermarkText implements \Mpdf\Watermark
{

	/** @var string */
	private $text;

	/** @var int */
	private $size;

	/** @var int */
	private $angle;

	/** @var mixed */
	private $color;

	/** @var float */
	private $alpha;

	/** @var string */
	private $font;

	public function __construct($text, $size = 96, $angle = 45, $color = 0, $alpha = 0.2, $font = null)
	{
		$this->text = $text;
		$this->size = $size;
		$this->angle = $angle;
		$this->color = $color;
		$this->alpha = $alpha;
		$this->font = $font;
	}

	public function getText()
	{
		return $this->text;
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getAngle()
	{
		return $this->angle;
	}

	public function getColor()
	{
		return $this->color;
	}

	public function getAlpha()
	{
		return $this->alpha;
	}

	public function getFont()
	{
		return $this->font;
	}

}
