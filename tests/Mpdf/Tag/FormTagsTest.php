<?php

namespace Mpdf\Tag;

class FormTagsTest extends BaseTagTestCase
{
	public function testForm_Open()
	{
		$tag = $this->createTag(Form::class);

		$attr = ['ACTION' => '/submit', 'METHOD' => 'POST'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify form properties are set
		$form = $this->getService('form');
		$this->assertEquals('POST', $form->formMethod);
		$this->assertEquals('/submit', $form->formAction);
		
		// Verify block level increased
		$this->assertEquals(1, $this->mpdf->blklvl);
	}

	public function testForm_WithGetMethod()
	{
		$tag = $this->createTag(Form::class);

		$attr = ['ACTION' => '/search', 'METHOD' => 'GET'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify GET method is set
		$form = $this->getService('form');
		$this->assertEquals('GET', $form->formMethod);
	}

	public function testForm_WithoutMethod()
	{
		$tag = $this->createTag(Form::class);

		$attr = ['ACTION' => '/submit'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);

		// Verify default method is POST
		$form = $this->getService('form');
		$this->assertEquals('POST', $form->formMethod);
	}

	public function testOption_Open()
	{
		$tag = $this->createTag(Option::class);

		$attr = ['VALUE' => 'test'];
		$ahtml = [];
		$ihtml = 0;

		// Option tag requires select context, verify it executes
		$tag->open($attr, $ahtml, $ihtml);
		
		// Option doesn't change block level (it's inline)
		$this->assertEquals(0, $this->mpdf->blklvl);
	}

	public function testOption_WithSelected()
	{
		$tag = $this->createTag(Option::class);

		$attr = ['VALUE' => 'test', 'SELECTED' => 'selected'];
		$ahtml = [];
		$ihtml = 0;

		$tag->open($attr, $ahtml, $ihtml);
		
		// Option doesn't change block level (it's inline)
		$this->assertEquals(0, $this->mpdf->blklvl);
	}
}
