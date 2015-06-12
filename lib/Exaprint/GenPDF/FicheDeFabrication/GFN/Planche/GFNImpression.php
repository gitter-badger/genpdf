<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 17:23
 */

namespace Exaprint\GenPDF\FicheDeFabrication\GFN\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceImpression;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;

class GFNImpression extends NegoceImpression
{

    public function __construct($planche)
    {
        $this->dimensions = new Dimensions(200, 16);
        $this->cellules   = [];
        $this->cellules[] = $this->surfaceTotale($planche['surfaceTotale']);
        $this->cellules[] = $this->familleCodification($planche['Famille'], $planche['Codification']);
    }

    public function surfaceTotale($surface)
    {
        $c                    = new Cellule();
        $c->dimensions->width = 36;
        $c->value             = $surface.' m2';
        $c->vAlign            = Cell::VALIGN_CENTER;

        $c->valueFont->size = 36;
        if (strlen($c->value) > 4) {
            $c->valueFont->size = 26;
        }
        if (strlen($c->value) > 7) {
            $c->valueFont->size = 20;
        }

        $c->fillColor->color            = Color::cmyk(100, 100, 0, 0);
        $c->valueFont->textColor->color = Color::white();

        return $c;
    }
} 