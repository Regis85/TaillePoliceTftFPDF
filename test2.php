<?php

require('tfpdf/tfpdf.php');
require('etend_tfpdf/class_pdf.php');

$maxPolice = isset ($_GET['maxPolice']) ? intval($_GET['maxPolice']) : 12;
$minPolice = isset ($_GET['minPolice']) ? intval($_GET['minPolice']) : 2;
$largeCadre = isset ($_GET['largeCadre']) ? intval($_GET['largeCadre']) : 12;
$hautCadre = isset ($_GET['hautCadre']) ? intval($_GET['hautCadre']) : 12;

$fichier = '10k_c1.txt';
$txt = file_get_contents($fichier);

$taillePolice = $maxPolice;
// $tailleLigne = floor($taillePolice / 2);

$pdf = new ptFPDF();

// Ajoute une police Unicode (utilise UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVuSerif','','DejaVuSerif.ttf',true);
$pdf->AddFont('DejaVuSerif','I','DejaVuSerif-Italic.ttf',true);
$pdf->AddFont('DejaVuSerifGras','','DejaVuSerif-Bold.ttf',true);

$police = 'DejaVuSerif';
$policeStyle = '';

$pdf->AddPage();

$taillePoliceOrigine = $taillePolice;

// on cherche le nombre de ligne et au besoin on réduit la taille
$tailleLigne = $pdf->AdapteTaille($txt, $police, $policeStyle, $taillePolice, $minPolice, $largeCadre, $hautCadre);

if (!$tailleLigne) {
	$txt = "texte trop grand";
	$pdf->	SetFont($police,$policeStyle,$taillePoliceOrigine);
	$tailleLigne = floor($taillePoliceOrigine / 2);
}

$pdf->MultiCell($largeCadre,$tailleLigne,$txt,1,'L');

$pdf->Output();

?>
