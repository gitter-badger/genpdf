<?php

require '../vendor/autoload.php';

define('K_PATH_FONTS', '../fonts/');

$pdf = new \TCPDF(
    PDF_PAGE_ORIENTATION,
    PDF_UNIT,
    PDF_PAGE_FORMAT,
    true,
    'UTF-8',
    false
);
$pdf->AddPage();
$page = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Page();
$page->numero = '01';
$page->setActive(1);
$page->setActive(2, \Exaprint\TCPDF\Color::cmyk(100, 0, 0, 0));
$page->draw($pdf, new \Exaprint\TCPDF\Position(100, 100));


$compteur = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Compteur();
$compteur->label = 'BAT';
$compteur->count = 14;
$compteur->draw($pdf, new \Exaprint\TCPDF\Position(80, 100));

$cell = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Cellule();
$cell->label = 'N° Planche';
//$cell->value = 360619;
$cell->dimensions = new \Exaprint\TCPDF\Dimensions(36, 16);
$cell->draw($pdf, new \Exaprint\TCPDF\Position(120, 100));


$finition = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Finition();
$finition->label = 'VERN';
$finition->value = 'Acrylique satiné R°V°';
$finition->prepare();
$finition->draw($pdf, new \Exaprint\TCPDF\Position(20, 20));
$pdf->Output();