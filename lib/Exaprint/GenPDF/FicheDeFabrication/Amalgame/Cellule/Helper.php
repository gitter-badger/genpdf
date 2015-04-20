<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 08/04/2014
 * Time: 16:01
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\Position;

class Helper
{

    public static function drawEmptyCell(Position $position, \TCPDF $pdf, $cellSize)
    {
        $lineStyle        = new LineStyle();
        $lineStyle->color = Color::greyscale(200);
        $lineStyle->apply($pdf);
        $lineStyle->width = 0.2;
        $pdf->Rect($position->x, $position->y, $cellSize, $cellSize);
        $lineStyle->revert($pdf);
    }
} 