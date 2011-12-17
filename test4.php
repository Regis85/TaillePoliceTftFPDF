<?php
require('tfpdf/tfpdf.php');
require('etend_tfpdf/class_pdf.php');

$maxPolice = isset ($_GET['maxPolice']) ? floatval($_GET['maxPolice']) : 12;
$minPolice = isset ($_GET['minPolice']) ? floatval($_GET['minPolice']) : 2;
$largeCadre = isset ($_GET['largeCadre']) ? intval($_GET['largeCadre']) : 12;
$hautCadre = isset ($_GET['hautCadre']) ? intval($_GET['hautCadre']) : 12;

$fichier = '20k_c1.txt';
$fichier = '10k_c1.txt';
$txt = file_get_contents($fichier);

$taillePolice = $maxPolice;

$pdf = new ptFPDF();

// Ajoute une police Unicode (utilise UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVuSerif','','DejaVuSerif.ttf',true);
$pdf->AddFont('DejaVuSerif','I','DejaVuSerif-Italic.ttf',true);
$pdf->AddFont('DejaVuSerifGras','','DejaVuSerif-Bold.ttf',true);
// $pdf->AddFont('EssaiPolice','','Comic_Sans_MS_Bold.ttf',true);

$police = 'DejaVuSerif';
// $police = 'Courier';
// $police = 'EssaiPolice';
$policeStyle = '';

$pdf->AddPage();

$taillePoliceOrigine = $taillePolice;
$pdf->SetFont($police,$policeStyle,$taillePolice);

if ("Core" == $pdf->CurrentFont['type']) {
	// On n'a pas une police TTF
	$txt = utf8_decode($txt);
}

// On trace le cadre
$hDebut = $pdf->getY();
$lDebut = $pdf->getX();
$pdf->Rect($lDebut, $hDebut, $largeCadre, $hautCadre);

// on cherche le $y du cadre texte
$positionCadre = $pdf->CentreMulticell($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre, $hDebut);
$pdf->SetY($positionCadre);
$tailleLigne = ($pdf->cMargin + $pdf->FontSize);
$pdf->MultiCell($largeCadre,$tailleLigne,$txt,1,'J');

$pdf->Output();

?>
