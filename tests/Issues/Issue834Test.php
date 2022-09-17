<?php

namespace Issues;

use Mpdf\BaseMpdfTest;
use Mpdf\Output\Destination;

final class Issue834Test extends BaseMpdfTest
{
	public function testInputFormFontSizeAuto()
	{
		$this->mpdf->useActiveForms = true;
		$this->mpdf->WriteHTML('<form><input type="text" name="test" style="font-size: auto" /></form>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringContainsString('/T (test)', $output);
		$this->assertStringContainsString('/DA (/F2 0 Tf 0.000 g)', $output);
	}

	public function testTextareaFormFontSizeAuto()
	{
		$this->mpdf->useActiveForms = true;
		$this->mpdf->WriteHTML('<form><textarea name="test" style="font-size: auto">&nbsp;</textarea></form>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringContainsString('/T (test)', $output);
		$this->assertStringContainsString('/DA (/F2 0 Tf 0.000 g)', $output);
	}

	public function testInputFormFontSize10pt()
	{
		$this->mpdf->useActiveForms = true;
		$this->mpdf->WriteHTML('<form><input type="text" name="test" style="font-size: 10pt" /></form>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringContainsString('/T (test)', $output);
		$this->assertStringContainsString('/DA (/F2 10 Tf 0.000 g)', $output);
	}

	public function testTextareaFormFontSize10pt()
	{
		$this->mpdf->useActiveForms = true;
		$this->mpdf->WriteHTML('<form><textarea name="test" style="font-size: 10pt">&nbsp;</textarea></form>');

		$output = $this->mpdf->Output('', Destination::STRING_RETURN);
		$this->assertStringContainsString('/T (test)', $output);
		$this->assertStringContainsString('/DA (/F2 10 Tf 0.000 g)', $output);
	}
}
