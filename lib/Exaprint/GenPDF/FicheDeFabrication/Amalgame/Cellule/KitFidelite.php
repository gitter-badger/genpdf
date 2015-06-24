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

class KitFidelite implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (strpos($commande['CodeProduit'], 'CCOMF') === 0 || strpos($commande['CodeProduit'], 'CCOMDF') === 0) {
            $cell           = new Cell();
            $cell->position = $position;
            $cell->fill     = false;
            $cell->width    = $cellSize;
            $cell->height   = $cellSize;
            $cell->border   = true;
            $cell->align    = Cell::ALIGN_CENTER;
            $cell->vAlign   = Cell::VALIGN_BOTTOM;
            $cell->font     = new Font('bagc-bold', 28, new TextColor(Color::cmyk(80,40,0,60)));
            $cell->text     = t('ffa.cell.kit_fidelite');
            $cell->draw($pdf);


        } else {
            CelluleHelper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}