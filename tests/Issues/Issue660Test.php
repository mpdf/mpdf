<?php

namespace Issues;

class Issue660Test extends \Mpdf\BaseMpdfTest
{

	public function testTemporaryImageFile()
	{
		$this->mpdf->showImageErrors = true;
		$this->mpdf->WriteHTML('
			<style>
				li {
					list-style-image : url(data:image/gif;base64,R0lGODlhEgASAKIAAP/jyvihV/aKLfmxc/////9mAAAAAAAAACH5BAAAAAAALAAAAAASABIAAAMpWLrc/jDKOQkRy8pBhuKeRAAKQFBBxwVUYY5twXVxodV3nLd77f9ASQIAOw==);
				}
			</style>
			
			<ul>
				<li>Test</li>
				<li>Test</li>
				<li>Test</li>
			</ul>');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
