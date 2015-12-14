<?php

// Set file name of HTML document source
$html_data = file_get_contents("amnesty2014-report-english-litho-full.html");

// Set file name of CSS design
$style_data = file_get_contents("amnesty2014-report-litho.css");

// Set file name of footers
$footer_data = file_get_contents("amnesty2014-report-english-footers.html");

// Include the main mPDF script
include("../../mpdf.php");

// Performance tweaks
$mpdf->useSubstitutions = false;
$mpdf->simpleTables = true;

// Create a PDF file with font sub-setting 155mm wide, 230mm high
$mpdf=new mPDF('s', array(155,230));

// Set left and right sided pages
$mpdf->mirrorMargins = 1;

// Set Adobe Reader initial display to two-up full pages
$mpdf->SetDisplayMode('fullpage','tworight');

// Set uneven column lengths
$mpdf->keepColumns = true;

// Read in the stylesheet
$mpdf->WriteHTML($style_data,1);        // The parameter 1 tells mPDF that this is CSS and not HTML
    
// Generate the table of contents from H1 elements
$mpdf->h2toc = array('H1'=>0);

// Add a new page array for the first separator
$mpdf->AddPageByArray(array(
    'suppress' => 'off',
    'pagenumstyle' => 'I',
    'pagesel' => 'separator',
    ));

// Initialise and write the footers
$mpdf->WriteHTML($footer_data, 2, true, false);

// Write the main text and close
$mpdf->WriteHTML($html_data, 2, false, true);

$mpdf->SetTitle("Annual Report 2014/15");
$mpdf->SetAuthor("Amnesty International");
$mpdf->SetCreator("Booktype 2.0");
$mpdf->SetSubject("Human Rights");
$mpdf->SetKeywords("Human Rights, Prisoners of Conscience");

// Generate the PDF file
$mpdf->Output('amnesty2014-report-english-litho-full.pdf','F');

// Stop mPDF
exit;

?>
