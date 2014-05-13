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

class PorteCarte implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (isset($commande['NbPorteCarte']) && $commande['NbPorteCarte']) {
            $cell           = new Cell();
            $cell->position = $position;
            $cell->fill     = false;
            $cell->width    = $cellSize;
            $cell->height   = $cellSize;
            $cell->border   = true;
            $cell->align    = Cell::ALIGN_CENTER;
            $cell->vAlign   = Cell::VALIGN_CENTER;
            $cell->font     = new Font('bagc-bold', 28, new TextColor(Color::greyscale(190)));
            $cell->text     = 'PC';
            $cell->draw($pdf);

            $cell->font = new Font('bagc-bold', 16, new TextColor(Color::greyscale(0)));
            $cell->text = $commande['NbPorteCarte'];
            $cell->draw($pdf);


        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}