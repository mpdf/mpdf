<?php

namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\Output\Destination;

class Issue538Test extends BaseMpdfTest
{
	public function testCompoundClassSelector()
	{
		$this->mpdf->WriteHTML('
<style>
.one {
    font-weight: bold;
}

.two {
    font-style: italic;
}

.one.two {
    color: red;
}

span.three.four {
	color: green;
}

span.three.four.five {
	font-weight: bold;
}

.one.three {
    font-size: xx-small;
}
</style>

<p class="one">First paragraph</p>
<p class="two">Second paragraph</p>
<p class="one two one">Third paragraph</p>

<p><span class="three four">A wild fox</span> jumped over <span class="five four three">a lazy dog</span></p>
<p class="one two three four">This should be really small</p>
');
		$this->mpdf->SetCompression(false);
		$output = $this->mpdf->Output('', Destination::STRING_RETURN);

		$this->assertStringContainsString("BT /F2 11.000 Tf ET\nq 0.000 g  0 Tr BT 42.520 785.363 Td  (First paragraph) Tj ET Q", $output);
		$this->assertStringContainsString("BT /F3 11.000 Tf ET\nq 0.000 g  0 Tr BT 42.520 759.197 Td  (Second paragraph) Tj ET Q", $output);
		$this->assertStringContainsString("BT /F4 11.000 Tf ET\nq 1.000 0.000 0.000 rg  0 Tr BT 42.520 732.635 Td  (Third paragraph) Tj ET Q", $output);

		$this->assertStringContainsString("BT /F1 11.000 Tf ET\n" .
			"/GS1 gs\n" .
			"q 0.000 0.502 0.000 rg  0 Tr BT 42.520 705.867 Td  (A wild fox) Tj ET Q\n" .
			"q 0.000 g  0 Tr BT 90.183 705.867 Td  ( jumped over ) Tj ET Q\n" .
			"BT /F2 11.000 Tf ET\n" .
			"q 0.000 0.502 0.000 rg  0 Tr BT 150.980 705.867 Td  (a lazy dog) Tj ET Q", $output);

		$this->assertStringContainsString("BT /F4 7.700 Tf ET\nq 1.000 0.000 0.000 rg  0 Tr BT 42.520 682.556 Td  (This should be really small) Tj ET Q", $output);
	}
}
