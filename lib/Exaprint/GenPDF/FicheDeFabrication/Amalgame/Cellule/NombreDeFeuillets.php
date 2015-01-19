<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\CelluleMultiligne;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\TwoCells;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

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