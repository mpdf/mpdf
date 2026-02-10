<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class FixedPositionHtmlSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'fixed-position-html';
	}

	/**
	 * Generate a PDF document by initializing the Mpdf object on $this->mpdf and
	 * loading it with content
	 *
	 * @return   void
	 * @internal Don't call any $this->mpdf->Output*() method
	 */
	public function generatePdf()
	{
		$this->mpdf = new \Mpdf\Mpdf(['mode' => 'c']);
		$this->mpdf->SetBasePath(__DIR__ . '/../data');

		/* Position with non-default font properties */
		$this->mpdf->WriteFixedPosHTML(
			'<div style="font-size: 30pt; font-weight: bold; color: blue;">Main Heading</div>',
			20,
			20,
			100,
			5
		);

		/* Position with content that needs to be resized to fit the container */
		$this->mpdf->WriteFixedPosHTML(
			'Text is scaled to fit the container',
			20,
			40,
			100,
			4,
			'auto'
		);

		/* Position text that doesn't need resizing */
		$this->mpdf->WriteFixedPosHTML(
			'Normal Text',
			20,
			60,
			50,
			5
		);

		/* Position with non-default font properties */
		$this->mpdf->WriteFixedPosHTML(
			'<div style="font-size: 35pt; font-style: italic; color: red;">Main Heading</div>',
			20,
			80,
			100,
			5
		);

		/* Position with content that needs to be resized to fit the container */
		$this->mpdf->WriteFixedPosHTML(
			'Text is scaled to fit the container',
			20,
			100,
			100,
			4,
			'auto'
		);

		$this->mpdf->WriteFixedPosHTML(
			'<div style="font-size: 25pt; text-decoration: underline; color: green;">Main Heading</div>',
			20,
			120,
			100,
			4,
			'auto'
		);

		$this->mpdf->WriteFixedPosHTML(
			'Text is scaled to fit the container',
			20,
			140,
			100,
			4,
			'auto'
		);

		/* Position text that doesn't need resizing */
		$this->mpdf->WriteFixedPosHTML(
			'Normal Text 3',
			20,
			160,
			50,
			5
		);
	}
}
