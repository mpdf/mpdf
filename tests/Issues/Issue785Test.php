<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue785Test extends \Mpdf\BaseMpdfTest
{
	public function testFloatAndPageBreakInside()
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'default_font_size' => 14,
		]);

		$html = '<div style="page-break-inside: avoid !important;">
          <p>Signed</p>
          <div>
            <div style="float: left, width: 50%;">_________________________<br>Me</div>
            <div style="float: left, width: 50%;">_________________________<br>You</div>
          </div>
          <div style="clear: both"></div>
        </div>';

		$this->mpdf->img_dpi = 300;
		$this->mpdf->dpi = 96;
		$this->mpdf->SetHTMLFooter('<div style="text-align:center;font-size: 9pt;">Page {PAGENO} (total {nbpg})</div>');
		$this->mpdf->WriteHTML($html);
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFloatAndPageBreakInsideAvoid()
	{
		$html = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<style type="text/css">
				.f-l {
				  float: left;
				}

				@page {
				  size: 210mm 297mm;
				  margin: 30mm 10mm 20mm 10mm;
				  margin-header: 10mm;
				  margin-footer: 5mm;
				  marks: none;
				  background: white;
				}
			</style>
		</head>

		<body>
			<div>
				<div style="page-break-inside: avoid">
					<div class="f-l">some text</div>
					<div class="f-l">some text</div>
				</div>
			</div>
		</body>
		</html>';

		$this->mpdf->WriteHTML($html);
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testFloatAndPageBreakInsideWithCss()
	{
		$stylesheet = "
		.subject-segment-ctn {
			overflow: hidden;
			margin: 0 0 1cm;
			position: relative;
			page-break-inside: avoid;
		}

		.title-ctn {
			padding: 1%;
			margin: 0;
			vertical-align: top;
			line-height: 1.2em;
		}

		h4 {
			margin: 0;
			font-size: 1rem;
			padding: 1%;
			background: #eee;
			border-bottom: 3px solid #ccc;
			page-break-after: avoid;
		}
		.class-segment {
			border: 1px solid #eee;
			border-bottom: 3px solid #ccc;
			border-top: 0;
		}
		.class-title {
			width: 10%;
			text-align: center;
			font-size: 1rem;
			float: left;
		}
		.strands {
			font-size: 7pt;
			width: 88%;
		}
		.strand {
			overflow: hidden;
			border-left: 2px dotted #eee;
		}
		.strand-title {
			width: 30%;
			float: left;
		}
		.units {
			width: 68%;
		}
		.unit {
			border-left: 1px solid #eee;
			border-bottom: 1px solid #eee;
			padding-bottom: 1%;
		}
		.last-unit {
			border-bottom: 1px solid #fff;
		}
		.objectives {
			padding: 0 1% 0 2em;
			list-style: disc outside;
			margin: 0;
		}
		.objective {
			margin-bottom: 0.25em;
		}";

		$html = '<div class="subject-segment-ctn first">
		<h4>Subject Title</h4>
		<div class="class-segment">
			<p class="class-title title-ctn">Class Title</p>
			<div class="strands">
				<div class="strand">
					<p class="strand-title title-ctn">Strand Title</p>
					<div class="units">
						<div class="unit ">
							<p class="unit-title title-ctn">Unit Title</p>
							<ul class="objectives">
								<li class="objective">Objective One</li>
								<li class="objective">Objective Two</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>';

		$this->mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$this->mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringStartsWith('%PDF-', $output);
	}
}
