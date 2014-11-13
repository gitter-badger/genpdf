<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 04/04/2014
 * Time: 14:30
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Position;

class PEFC implements ICellule
{

    const CERTIFICATION_PEFC = 'PEFC™';

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (isset($commande['Certification']) && $commande['Certification'] == self::CERTIFICATION_PEFC) {
            $cell            = new Cell();
            $cell->position  = $position;
            $cell->fillColor = new FillColor(Color::cmyk(100, 0, 100, 0));
            $cell->fill      = true;
            $cell->width     = $cellSize;
            $cell->height    = $cellSize;
            $cell->border    = true;
            $cell->draw($pdf);
        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }

} 