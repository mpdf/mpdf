<?php

namespace Issues;

use Mpdf\Output\Destination;

class Issue617Test extends \Mpdf\BaseMpdfTest
{

	public function testInvalidTableMarkup()
	{
		$html = '<html>
<body>
  <div style="width: 100%; text-align: center;">
    <p style="margin: 0px 0px 5px 0px; font-size: 20px; font-weight: bold;">TITLE 1</p>
    <p style="margin: 0px 0px 5px 0px; font-size: 16px; font-weight: normal;">TITLE 2</p>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 10px 0px 10px; font-size: 42px; font-weight: bold;">VOUCHER</p>
  </div>
  <div style="width: 100%; margin-top: 30px; clear: both; text-align: left;">
    <p style="margin: 0px 0px 5px 0px; font-size: 20px; font-weight: bold;">Tourist</p>
    <table style="width: 100%; font-size: 13px;">
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Name</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Name</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Address</th></th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Address</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Contact</th></th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Contact</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Persons</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Persons</td></tr>
    </table>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: left;">
    <p style="margin: 0px 0px 5px 0px; font-size: 20px; font-weight: bold;">Accommodation</p>
    <table style="width: 100%; font-size: 13px;">
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Period</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Period</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Length of stay</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Length of stay</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Beds</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Beds</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Extra-beds</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Extra-beds</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Accommodation</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Accommodation</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">House</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">House</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Address</th></th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Address</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">GPS</th></th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">GPS</td></tr>
      <tr><th style="width: 30%; padding: 9px; background-color: #DDDDDD; text-align: right;">Contact</th><td style="width: 70%; padding: 9px; background-color: #EEEEEE;">Contact</td></tr>
    </table>
  </div>

  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 0px 0px 0px; font-size: 14px; font-weight: normal;">text</p>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 0px 0px 0px; font-size: 14px; font-weight: normal;">text</p>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 0px 0px 0px; font-size: 14px; font-weight: normal;">text</p>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 0px 0px 0px; font-size: 14px; font-weight: normal;">text</p>
  </div>
  <div style="width: 100%; margin-top: 20px; clear: both; text-align: center;">
    <p style="margin: 10px 0px 0px 0px; font-size: 14px; font-weight: normal;">text</p>
  </div>


</body>
</html>';

		$mpdf1 = new \Mpdf\Mpdf();
		$mpdf1->WriteHTML($html);
		$mpdf1->Output('', 'S');
	}

}
