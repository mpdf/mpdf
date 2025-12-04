<?php

namespace Mpdf\Tag;

class SelectTest extends BaseTagTestCase
{
	private $tag;

	protected function set_up()
	{
		parent::set_up();

		$this->tag = $this->createTag(Select::class);
	}

	public function testOpen_Select()
	{
		$attr = ['NAME' => 'select_field'];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('type=select', $this->mpdf->specialcontent);
	}

	public function testOpen_Select_WithInlineStyle()
	{
		$attr = [
			'NAME' => 'select_name',
			'STYLE' => 'width: 200px; font-family: Arial; color: blue;'
		];
		$ahtml = [];
		$ihtml = 0;

		$this->tag->open($attr, $ahtml, $ihtml);

		$this->assertEquals('type=select', $this->mpdf->specialcontent);
		// Verify CSS properties were processed
		// Select tag stores color in selectoption
		$this->assertArrayHasKey('COLOR', $this->mpdf->selectoption);
		$color = $this->mpdf->selectoption['COLOR'];

		// Color is blue (RGB: 0, 0, 255)
		$this->assertEquals(3, $color[0]);
		$this->assertEquals(0, ord($color[1]));
		$this->assertEquals(0, ord($color[2]));
		$this->assertEquals(255, ord($color[3]));
	}

	public function testClose_Select()
	{
		$ahtml = [];
		$ihtml = 0;

		// Call open first to properly initialize state
		$attr = ['NAME' => 'select_field'];
		$this->tag->open($attr, $ahtml, $ihtml);

		// Set additional state for close test
		$this->mpdf->selectoption['SELECTED'] = 'Option 1';
		$this->mpdf->selectoption['MAXWIDTH'] = 50;

		// Now close should work properly
		$this->tag->close($ahtml, $ihtml);

		$this->assertEmpty($this->mpdf->selectoption);
		$this->assertEmpty($this->mpdf->specialcontent);
	}
}
