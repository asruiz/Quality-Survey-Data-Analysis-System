<?php
//Update title if necessary - %% is a placeholder for whatever the module's readable name is.
$pos = strpos($title, '%%');
if($pos !== false)
{
    $title = substr_replace($title, $reporter->readable_name, $pos, 2);
}

$orientation = 'L'; //force landscape (horizontal) orientation
$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Cobalt Enterprise Framework');
$pdf->SetTitle($title);
$pdf->SetSubject($sess_var);
$pdf->SetKeywords('');

// set header off, set footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetPrintHeader(false);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
// set font for title
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Write(0, $title, '', 0, 'L', true, 0, false, false, 0);
$pdf->SetFont('helvetica', '', 8);

$tbl = file_get_contents(TMP_DIRECTORY . '/' . TMP_PDF_STORE . '/' . $_SESSION[$sess_var]['pdf_tmp_filename']);
$pdf->writeHTML($tbl, true, false, false, false, '');
