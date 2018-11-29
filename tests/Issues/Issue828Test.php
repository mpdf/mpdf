<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue828Test extends \Mpdf\BaseMpdfTest
{

	public function testFixedHeaderFooter()
	{
		$this->mpdf->WriteHTML('
	    <html>
	        <head>
	            <style>
	                @page {
	                    header: html_myHeader;
	                    footer: html_myFooter;
	                }
	
	                .fixed-position {
	                    position: fixed;
	                }
	            </style>
	        </head>
	        <body>
	            <htmlpageheader name="myHeader">
	                <div class="fixed-position">FIXED HEADER</div>
	            </htmlpageheader>
	
	            <htmlpagefooter name="myFooter">
	                <div class="fixed-position">FIXED FOOTER</div>
	            </htmlpagefooter>
	        </body>
	    </html>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
