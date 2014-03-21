<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


use Exaprint\TCPDF\Position;

interface ICellule {

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande);
}