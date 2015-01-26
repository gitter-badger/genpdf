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

    const RECTO_QUADRI              = 180;
    const RECTO_SANS                = 232;
    const RECTO_1_COULEUR           = 318;
    const RECTO_2_COULEURS          = 319;
    const RECTO_3_COULEURS          = 320;
    const RECTO_4_COULEURS          = 321;
    const RECTO_QUADRI_ET_BLANC     = 322;
    const RECTO_5_COULEURS          = 323;
    const RECTO_2_COULEURS_ET_BLANC = 324;
    const RECTO_3_COULEURS_ET_BLANC = 325;
    const RECTO_4_COULEURS_ET_BLANC = 326;
    const RECTO_NOIR                = 376;
    const RECTO_SANS_2              = 798;
    const RECTO_QUADRI_HD           = 1362;
    const RECTO_BLANC               = 1616;
    const RECTO_GRAVURE_ROUGE       = 1695;
    const RECTO_GRAVURE_OR          = 1696;
    const RECTO_GRAVURE_OR_MAT      = 1697;
    const RECTO_GRAVURE_TRANSPARENT = 1698;
    const RECTO_GRAVURE_NOIR        = 1699;
    const RECTO_GRAVURE_BLANC       = 1700;
    const RECTO_GRAVURE_GRIS        = 1701;
    const RECTO_NOIR_ET_1_COULEUR   = 1895;
    const RECTO_BLEU                = 1914;
    const RECTO_1_TEINTE            = 1986;

    const VERSO_QUADRI          = 181;
    const VERSO_SANS            = 233;
    const VERSO_NOIR            = 238;
    const VERSO_1_COULEUR       = 542;
    const VERSO_2_COULEURS      = 804;
    const VERSO_QUADRI_ET_BLANC = 1196;

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
        $c->value             = $this->_nbCouleurs($nbRecto) . ' + ' . $this->_nbCouleurs($nbVerso);
        $c->vAlign            = Cell::VALIGN_CENTER;
        $c->dimensions->width = 32;
        $c->valueFont->size   = 28;
        return $c;
    }

    private function _nbCouleurs($idProduitOptionValeur)
    {
        switch ($idProduitOptionValeur) {
            case self::RECTO_QUADRI:
                $nbCouleurs = 4;
                break;
            case self::RECTO_SANS:
                $nbCouleurs = 0;
                break;
            case self::RECTO_1_COULEUR:
                $nbCouleurs = 1;
                break;
            case self::RECTO_2_COULEURS:
                $nbCouleurs = 2;
                break;
            case self::RECTO_3_COULEURS:
                $nbCouleurs = 3;
                break;
            case self::RECTO_4_COULEURS:
                $nbCouleurs = 4;
                break;
            case self::RECTO_QUADRI_ET_BLANC:
                $nbCouleurs = 5;
                break;
            case self::RECTO_5_COULEURS:
                $nbCouleurs = 5;
                break;
            case self::RECTO_2_COULEURS_ET_BLANC:
                $nbCouleurs = 3;
                break;
            case self::RECTO_3_COULEURS_ET_BLANC:
                $nbCouleurs = 4;
                break;
            case self::RECTO_4_COULEURS_ET_BLANC:
                $nbCouleurs = 5;
                break;
            case self::RECTO_NOIR:
                $nbCouleurs = 1;
                break;
            case self::RECTO_SANS_2:
                $nbCouleurs = 0;
                break;
            case self::RECTO_QUADRI_HD:
                $nbCouleurs = 4;
                break;
            case self::RECTO_BLANC:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_ROUGE:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_OR:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_OR_MAT:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_TRANSPARENT:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_NOIR:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_BLANC:
                $nbCouleurs = 1;
                break;
            case self::RECTO_GRAVURE_GRIS:
                $nbCouleurs = 1;
                break;
            case self::RECTO_NOIR_ET_1_COULEUR:
                $nbCouleurs = 2;
                break;
            case self::RECTO_BLEU:
                $nbCouleurs = 1;
                break;
            case self::RECTO_1_TEINTE:
                $nbCouleurs = 4;
                break;
            case self::VERSO_QUADRI:
                $nbCouleurs = 4;
                break;
            case self::VERSO_SANS:
                $nbCouleurs = 0;
                break;
            case self::VERSO_NOIR:
                $nbCouleurs = 1;
                break;
            case self::VERSO_1_COULEUR:
                $nbCouleurs = 1;
                break;
            case self::VERSO_2_COULEURS:
                $nbCouleurs = 2;
                break;
            case self::VERSO_QUADRI_ET_BLANC:
                $nbCouleurs = 5;
                break;
            default:
                $nbCouleurs = '?';
        }
        return $nbCouleurs;
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