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

class BatPapier implements ICellule
{

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande){
        if(isset($commande['BAT']) && $commande['BAT']){
            $cell = new Cell();
            $cell->position = $position;
            $cell->fillColor = new FillColor(Color::cmyk(85, 10, 100, 0));
            $cell->fill = true;
            $cell->width = $cellSize;
            $cell->height = $cellSize;
            $cell->border = true;
            $cell->font = new Font('bagc-bold', 16, new TextColor(Color::greyscale(255)));
            $cell->align = Cell::ALIGN_CENTER;
            $cell->vAlign = Cell::VALIGN_CENTER;
            $cell->text = 'BàT';
            $cell->draw($pdf);
        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
} 