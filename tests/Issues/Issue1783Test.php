<?php

namespace Issues;

class Issue1783Test extends \Mpdf\BaseMpdfTest
{

	public function testMemoryExhausted()
	{
		$this->mpdf->WriteHTML('
			<div class="a b c d e f g h i j k l m n o p q r s t u v w x y z">
				Lorum Ipsum
			</div>
		');
		
		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testMemoryExhaustedWithMatchingCss()
	{
		$this->mpdf->WriteHTML('
			<style>
			.a, .b, .c, .d, .e, .f, .g, .h, .i, .j, .k, .l, .m, .n, .o, .p, .q, .r, .s, .t, .u, .v, .w, .x, .y, .z {
				color: red;
			}
			</style>
			
			<div class="a b c d e f g h i j k l m n o p q r s t u v w x y z">
				Lorum Ipsum
			</div>
		');

		$output = $this->mpdf->OutputBinaryData();
		$this->assertStringStartsWith('%PDF-', $output);
	}

}
