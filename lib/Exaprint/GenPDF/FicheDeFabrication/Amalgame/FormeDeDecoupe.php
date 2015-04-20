<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 14:39
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Rect;
use Exaprint\TCPDF\TextColor;

class FormeDeDecoupe
{

    public function __construct($IDCommande, $fichier, \TCPDF $pdf, $x, $y, Layout $layout)
    {

        $cell            = new Cell();
        $cell->fillColor = new FillColor(Color::black());
        $cell->text      = Formatter::idCommande($IDCommande);
        $cell->font      = new Font('bagc-bold', 14, new TextColor(Color::white()));
        $cell->position  = new Position($x, $y);
        $cell->border    = 1;
        $cell->fill      = true;
        $cell->width     = 20;
        $cell->height    = 8;

        $rect             = new Rect();
        $rect->dimensions = new Dimensions($layout->wBloc(), $layout->hBloc());
        $rect->position   = new Position($x, $y);
        $rect->style      = Rect::STYLE_STROKE;

        $image = new ImageInContainer(
            $fichier['href'],
            new Dimensions($fichier['width'], $fichier['height']),
            $rect->dimensions,
            $rect->position,
            true
        );


//        var_dump($fichier, $cell, $rect, $image); die();

        $rect->draw($pdf);
        $image->draw($pdf);
        $cell->draw($pdf);

    }
} 