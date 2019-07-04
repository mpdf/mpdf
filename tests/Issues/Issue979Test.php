<?php

namespace Issues;

use Mpdf\Mpdf;

function autoLoader($className, $checkonly=false) {
    if(file_exists($className)) {
        include $className;
    }
    else {
        throw new \Exception("Class '{$className}' not found"); # <----- !!!
    }
    return false;
}

class Issue979Test extends \Mpdf\BaseMpdfTest
{

	public function testEmptyTag()
	{
	    spl_autoload_register('autoLoader');
		$html = '<!DOCTYPE html>
		<html>
			<body>
			<div class="bill">
				<div class="header">
					<div class="logo">
						<img src="" />
					</div>

				</div>
			</div>
			</body>
		</html>';

		$this->mpdf->WriteHTML($html);

		$out = $this->mpdf->output('', 'S');
	}

}
