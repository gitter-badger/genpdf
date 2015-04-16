<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 17:23
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Impression;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegoceDAL;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;

class NegoceImpression extends Impression
{

    public function __construct($planche)
    {
        $this->dimensions = new Dimensions(200, 16);
        $this->cellules   = [];
        $this->cellules[] = $this->nbFeuilles($planche['NbFeuilles'], $planche['EstSousTraitance']);
        $this->cellules[] = $this->familleCodification($planche['Famille'], $planche['Codification']);
    }

    public function nbFeuilles($nb, $EstSousTraitance)
    {
        $c                    = new Cellule();
        $c->dimensions->width = 36;
        $c->value             = number_format($nb, 0, '.', ' ');
        $c->vAlign            = Cell::VALIGN_CENTER;

        if ($EstSousTraitance) {
            // fond blanc, texte noir
        } else {
            $c->fillColor->color            = Color::cmyk(100, 100, 0, 0);
            $c->valueFont->textColor->color = Color::white();
        }

        return $c;
    }

    public function familleCodification($famille, $codification)
    {
        $c                    = new Cellule();
        $c->value             = $famille . ' - ' . $codification;
        $c->dimensions->width = 80;
        $c->valueFont->family = 'bagc-light';
        $c->valueFont->size   = 22;
        $c->align             = Cell::ALIGN_LEFT;
        $c->vAlign            = Cell::VALIGN_CENTER;
        $c->dimensions->width = 164;

        return $c;
    }
} 