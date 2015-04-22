<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Page;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class DecoupeALaForme implements ICellule
{
    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {

        if (isset($commande['DecoupeALaForme']) && ($decoupe = $commande['DecoupeALaForme'])) {

            if(isset($commande['IndicateurFormeDeDecoupe'])){
                $page = new Page();
                $page->numero = $commande['IndicateurFormeDeDecoupe']['numero'];
                $page->setActive($commande['IndicateurFormeDeDecoupe']['index']);
                $page->draw($pdf, $position->add(new Position(2, 0)));
            } else {

                $cell           = new Cell();
                $cell->position = $position;
                $cell->fill     = false;
                $cell->width    = $cellSize;
                $cell->height   = $cellSize;
                $cell->border   = true;
                $cell->draw($pdf);

                $cTxt           = new Cell();
                $cTxt->position = $position;
                $cTxt->fill     = false;
                $cTxt->border   = 0;
                $cTxt->text     = $decoupe;
                $cTxt->font     = new Font('bagc-bold', 16, new TextColor(Color::greyscale(0)));
                $cTxt->width    = $cellSize;
                $cTxt->height   = $cellSize;
                $cTxt->draw($pdf);
            }
        } else {
            CelluleHelper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
}