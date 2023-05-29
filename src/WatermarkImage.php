<?php

namespace Mpdf;

class WatermarkImage implements \Mpdf\Watermark
{

	const SIZE_DEFAULT = 'D';
	const SIZE_FIT_PAGE = 'P';
	const SIZE_FIT_FRAME = 'F';
	const POSITION_CENTER_PAGE = 'P';
	const POSITION_CENTER_FRAME = 'F';

	/** @var string */
	private $path;

	/** @var mixed */
	private $size;

	/** @var mixed */
	private $position;

	/** @var float */
	private $alpha;

	/** @var bool */
	private $behindContent;
	
	/** @var string */
	private $alphaBlend;

	public function __construct($path, $size = self::SIZE_DEFAULT, $position = self::POSITION_CENTER_PAGE, $alpha = -1, $behindContent = false, $alphaBlend = 'Normal')
	{
		$this->path = $path;
		$this->size = $size;
		$this->position = $position;
		$this->alpha = $alpha;
		$this->behindContent = $behindContent;
		$this->alphaBlend = $alphaBlend;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function getAlpha()
	{
		return $this->alpha;
	}

	public function isBehindContent()
	{
		return $this->behindContent;
	}

	public function getAlphaBlend()
	{
		return $this->alphaBlend;
	}

}
