<?php

require '../vendor/autoload.php';
require '/Users/admin/Sites/env.php';
$_SERVER['exaprint_env'] = 'stage';

// textdomain
define("APPLICATION_ROOT", realpath("../"));
putenv("LC_MESSAGES=" . 'fr_FR');
setlocale(LC_MESSAGES, 'fr_FR');
if (function_exists('bindtextdomain') && function_exists('textdomain')) {
    bindtextdomain("messages", APPLICATION_ROOT . "/locale");
    textdomain("messages");
    bind_textdomain_codeset("messages", "UTF-8");
}

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

$dto = \Exaprint\GenPDF\FicheDeFabrication\DAL::getPlanche('377322');

$planche = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche2(
    new \Exaprint\TCPDF\Dimensions(200, 140),
    $pdf,
    $dto,
    new \Exaprint\TCPDF\Position(5, 12)
);

$pdf->Output();