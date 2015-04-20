<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\TwoCells;
use Exaprint\TCPDF\Position;

class NombreDeFeuillets implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (isset($commande['NombreDeFeuillets']) && $commande['NombreDeFeuillets']) {
            $cell         = new TwoCells();
            $cell->value1 = ceil($commande['NombreDeFeuillets']);
            $cell->value2 = 'feuillets';
            $cell->width  = $cellSize;
            $cell->height  = $cellSize;
            $cell->draw($pdf, $position);
        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}