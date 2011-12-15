<?php

// Définition facultative du répertoire des polices systèmes
// Sinon tFPDF utilise le répertoire [chemin vers tFPDF]/font/unifont/
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

require('tfpdf/tfpdf.php');
require('etend_tfpdf/class_pdf.php');

$taillePolice = isset ($_GET['police']) ? intval($_GET['police']) : 12;
$tailleLigne = floor($taillePolice / 2);

$fichier = '10k_c1.txt';
$fichier2 = '10k_c2.txt';

$txt = file_get_contents($fichier);
$txt2 = file_get_contents($fichier2);

$pdf = new ptFPDF();

// Ajoute une police Unicode (utilise UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVuSerif','','DejaVuSerif.ttf',true);
$pdf->AddFont('DejaVuSerif','I','DejaVuSerif-Italic.ttf',true);
$pdf->AddFont('DejaVuSerifGras','','DejaVuSerif-Bold.ttf',true);

$pdf->AddPage();

$pdf->SetFont('DejaVuSerif','',$taillePolice);

// calcul du nombre de lignes
$taille_txt = intval($pdf->TailleChapitre($txt, 90));
$taille_txt2 = intval($pdf->TailleChapitre($txt2, 90));
$diffTaille = ($taille_txt2 - $taille_txt) * $tailleLigne;

$pdf->Write(5,"Taille de la police : ".$taillePolice.', hauteur des lignes : '.$tailleLigne.' mm');
$pdf->Ln();
$pdf->Write(5,"La taille de ces textes est : ".$taille_txt.' lignes et '.$taille_txt2.' lignes. ');
$pdf->Write(5,"La différence sera de  : ".($taille_txt2 - $taille_txt).' x '.$tailleLigne.' mm soit '.$diffTaille.' mm');
$pdf->Ln();

$pdf->SetY($pdf->getY() + 3);
		
$hDebut = $pdf->getY();
$lDebut = $pdf->getX();

$pdf->MultiCell(90,$tailleLigne,$txt,1,'L');
		$h1 = $pdf->getY();
		$l = $pdf->getX();
		
// Saut de ligne
$pdf->Ln();
$pdf->Cell(0,5,"y : ".$h1." x : ".$l);


$pdf->setXY($lDebut+92,$hDebut);
$pdf->MultiCell(90,$tailleLigne,$txt2,1,'L');
		$h2 = $pdf->getY();
		$l = $lDebut+92;
		// Saut de ligne
		$pdf->Ln();
$pdf->SetX($lDebut+92);
		$pdf->Cell(0,5,"y : ".$h2." x : ".$l);
		
// Saut de ligne
$pdf->Ln();
$pdf->Write(5,"La différence de taille est de : ".($h2 - $h1).' mm');

$pdf->Output();

?>
