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
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Predecoupe implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {


        if (isset($commande['Predecoupe']) && ($predecoupe = $commande['Predecoupe'])) {
            $cell           = new Cell();
            $cell->position = $position;
            $cell->fill     = false;
            $cell->width    = $cellSize;
            $cell->height   = $cellSize;
            $cell->border   = true;
            $cell->draw($pdf);

            $v = explode('+', t('valeur_court_' . $predecoupe));

            $cTxt            = new Cell();
            $cTxt->position  = $position;
            $cTxt->fill      = true;
            $cTxt->fillColor = new FillColor(Color::cmyk(80, 0, 80, 0));
            $cTxt->border    = 0;
            $cTxt->text      = $v[0];
            $cTxt->font      = new Font('freeserif', 14, new TextColor(Color::greyscale(0)));
            $cTxt->width     = $cellSize;


            $cTxt->font->size = 16;
            $cTxt->height     = $cellSize;
            $cTxt->draw($pdf);

        } else if ($commande['PredecoupeSaisie']) {

            $file = '../assets/Cellule/predecoupe.png';

            $image = new ImageInContainer(
                $file,
                new Dimensions(71, 71),
                new Dimensions($cellSize, $cellSize),
                $position
            );

            $image->draw($pdf);
        } else {
            CelluleHelper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}