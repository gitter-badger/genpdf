<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Perforation implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (isset($commande['Perforation']) && $commande['Perforation']) {
            $cell           = new Cell();
            $cell->position = $position;
            $cell->fill     = false;
            $cell->width    = $cellSize;
            $cell->height   = $cellSize;
            $cell->border   = true;
            $cell->align    = Cell::ALIGN_CENTER;
            $cell->vAlign   = Cell::VALIGN_CENTER;

            $cell->font = new Font('bagc-bold', 10, new TextColor(Color::greyscale(0)));
            $cell->text = t('valeur_court_' . $commande['Perforation']);
            $cell->draw($pdf);


        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}