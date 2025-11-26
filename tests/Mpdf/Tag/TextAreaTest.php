<?php

namespace Mpdf\Tag;

class TextAreaTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(TextArea::class);
	}

	public function testOpen_TextArea()
	{
		$attr = ['NAME' => 'textarea_field', 'ROWS' => '5', 'COLS' => '30'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertNotEmpty($this->mpdf->specialcontent);
	}

	public function testOpen_TextArea_WithInlineStyle()
	{
		$attr = [
			'NAME' => 'textarea_field',
			'ROWS' => '5',
			'COLS' => '30',
			'STYLE' => 'width: 300px; font-size: 12pt;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertNotEmpty($this->mpdf->specialcontent);
		// Verify CSS properties were processed
		// TextArea tag sets font size on mpdf object
		$this->assertEqualsWithDelta(12, $this->mpdf->FontSizePt, 0.001);
	}

	public function testClose_TextArea()
	{
		$ahtml = [];
		$ihtml = 0;

		// Call open first to properly initialize state
		$attr = ['NAME' => 'textarea_field', 'ROWS' => '5', 'COLS' => '30'];
		$this->tag->open($attr, $ahtml, $ihtml);

		// Now close should work properly
		$this->tag->close($ahtml, $ihtml);

		$this->assertEmpty($this->mpdf->specialcontent);
	}
}
