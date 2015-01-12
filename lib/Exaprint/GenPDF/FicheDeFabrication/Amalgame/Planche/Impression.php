<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 17:23
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;

class Impression extends Rang
{

    public function __construct($planche)
    {
        $this->dimensions = new Dimensions(200, 16);
        $this->cellules   = [];
        $this->cellules[] = $this->nbFeuilles($planche['NbFeuilles'], $planche['EstSousTraitance']);
        $this->cellules[] = $this->format($planche['Largeur'], $planche['Longueur']);
        $this->cellules[] = $this->couleurs($planche['NbCouleursRecto'], $planche['NbCouleursVerso']);
        $this->cellules[] = $this->bascule($planche['Bascule']);
        $this->cellules[] = $this->papier($planche['Support'], $planche['EstSousTraitance']);
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

    public function format($largeur, $longueur)
    {
        $c                    = new Cellule();
        $c->value             = $largeur . '×' . $longueur;
        $c->valueFont->family = 'bagc-light';
        $c->vAlign            = Cell::VALIGN_CENTER;
        $c->dimensions->width = 36;
        return $c;
    }

    public function couleurs($nbRecto, $nbVerso)
    {
        $c                    = new Cellule();
        $c->label             = 'Couleurs';
        $c->value             = $nbRecto . ' + ' . $nbVerso;
        $c->valueFont->family = 'bagc-light';
        $c->vAlign            = Cell::VALIGN_CENTER;
        $c->dimensions->width = 32;
        $c->valueFont->size   = 22;
        return $c;
    }

    public function bascule($bascule)
    {
        $c                    = new Cellule();
        $c->label             = 'Bascule';
        $c->dimensions->width = 16;
        $c->valueFont->size   = 16;
        $c->valueFont->family = 'bagc-medium';
        $c->vAlign            = Cell::VALIGN_CENTER;
        $c->value             = ($bascule == 1) ? null : _('bascule_' . $bascule);
        return $c;
    }

    public function papier($id, $EstSousTraitance)
    {
        $c                    = new Cellule();
        $c->value             = _('valeur_' . $id);
        $c->dimensions->width = 80;
        $c->valueFont->family = 'bagc-light';
        $c->valueFont->size   = 22;
        $c->vAlign            = Cell::VALIGN_CENTER;

        if ($EstSousTraitance) {
            // fond blanc, texte noir
        } else {
            $c->fillColor->color = Color::cmyk(0, 0, 75, 0);
        }

        return $c;
    }
} 