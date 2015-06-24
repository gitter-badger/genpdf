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
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Pliage implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {


        if (isset($commande['Pliage']) || isset($commande['PliageComplexe'])) {

            if (isset($commande['Pliage'])) {
                $pliage = $commande['Pliage'];
            } else {
                $pliage = $commande['PliageComplexe'];
            }

            $cell           = new Cell();
            $cell->position = $position;
            $cell->fill     = false;
            $cell->width    = $cellSize;
            $cell->height   = $cellSize;
            $cell->border   = true;
            $cell->draw($pdf);
            $v = explode('+', t('valeur_court_' . $pliage));

            $cTxt           = new Cell();
            $cTxt->position = $position;
            $cTxt->fill     = false;
            $cTxt->border   = 0;
            $cTxt->text     = $v[0];
            $cTxt->font     = new Font('bagc-bold', 14, new TextColor(Color::greyscale(0)));
            $cTxt->width    = $cellSize;

            if (count($v) > 1) {
                $cTxt->vAlign = Cell::VALIGN_BOTTOM;
                $cTxt->height = $cellSize / 2;
                $cTxt->draw($pdf);
                $cTxt->position = $position->add(new Position(0, $cellSize / 2));
                $cTxt->vAlign   = Cell::VALIGN_TOP;
                $cTxt->text     = $v[1];
                $cTxt->draw($pdf);
            } else {
                $cTxt->font->size = 16;
                $cTxt->height     = $cellSize;
                $cTxt->draw($pdf);
            }
        } else {
            CelluleHelper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}