<?php

namespace Mpdf;

class MpdfAbsoluteHeaderFooter extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testAbsoluteHeaderFooter()
	{
		$mpdf = $this->getMockBuilder('Mpdf\Mpdf')
			->setMethods(['WriteFixedPosHTML'])
			->getMock();

		/*
		 * The header/footer is absolute-positioned individually to both pages during Output().
		 * It is also added once each in the initial WriteHTML call (gets overridden above).
		 * The total number of calls to WriteFixedPosHTML is 10
		 */
		$mpdf->expects($this->exactly(10))
			->method('WriteFixedPosHTML');

		$mpdf->WriteHTML('
			<style>
				@page {
				header: html_myHeader;
				footer: html_myFooter;
				}

				#header {
					position: absolute;
					top: 20mm;
					left: 30mm;

					width: 50mm;
					height: 50mm;

					background: green;
				}

				#footer {
					position: absolute;
					bottom: 20mm;
					left: 30mm;

					width: 50mm;
					height: 50mm;

					background: red;
				}
			</style>

			<htmlpageheader name="myHeader">
				<div id="header">
					This is the header
				</div>
			</htmlpageheader>

			<htmlpagefooter name="myFooter">
				<div id="footer">
					Page {PAGENO} / {nbpg}
				</div>
			</htmlpagefooter>

			<pagebreak />

			<pagebreak />

			<pagebreak />');

		$mpdf->Close();
	}
}
