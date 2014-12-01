<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:51
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;

use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;

class Identification extends Rang
{


    public function __construct($planche)
    {

        $this->dimensions = new Dimensions(200, 17);
        $this->cellules[] = $this->idPlanche($planche['IDPlanche']);
        $this->cellules[] = $this->expeSansFaconnage($planche['ExpeSansFaconnage']);
        $this->cellules[] = $this->expeAvecFaconnage($planche['ExpeAvecFaconnage']);
        $this->cellules[] = $this->imperatifs($this->countImperatifs($planche['commandes']));
        $this->cellules[] = $this->sousTraitance($planche['EstSousTraitance']);
        $this->cellules[] = $this->codeBarre($planche['IDPlanche']);
        $this->cellules[] = $this->nbCommandes(count($planche['commandes']));
    }

    protected function idPlanche($id)
    {
        $c                     = new Cellule();
        $c->label              = 'N° Planche';
        $c->dimensions->width  = 40;
        $c->dimensions->height = $this->dimensions->height;
        $c->value              = number_format($id, 0, '', ' ');
        return $c;
    }

    protected function expeSansFaconnage($date)
    {
        $c                              = new Cellule();
        $c->label                       = 'Expé SANS façonnage';
        $c->dimensions->width           = 30;
        $c->dimensions->height          = $this->dimensions->height;
        $c->value                       = $date;
        $c->valueFont->textColor->color = Color::red();
        return $c;
    }

    protected function expeAvecFaconnage($date)
    {
        $c                              = new Cellule();
        $c->label                       = 'Expé AVEC façonnage';
        $c->dimensions->width           = 30;
        $c->dimensions->height          = $this->dimensions->height;
        $c->value                       = $date;
        $c->valueFont->textColor->color = Color::red();
        return $c;
    }

    protected function imperatifs($nb)
    {
        $c                              = new Cellule();
        $c->label                       = 'Impératifs';
        $c->dimensions->width           = 20;
        $c->dimensions->height          = $this->dimensions->height;
        $c->fillColor                   = new FillColor(Color::red());
        $c->valueFont->textColor->color = Color::white();
        $c->value                       = $nb;
        if ($nb) {
            $c->labelFont->textColor->color = Color::white();
        }
        return $c;
    }

    protected function sousTraitance($EstSousTraitance)
    {
        $c                              = new Cellule();
        $c->label                       = 'Sous-Traitance';
        $c->dimensions->width           = 20;
        $c->dimensions->height          = $this->dimensions->height;
        $c->value                       = ($EstSousTraitance) ? t('ffa.abbr.planche_sous_traitance'): t('ffa.abbr.planche_principale');
        $c->valueFont->textColor->color = Color::white();
        $c->fillColor->color            = Color::cmyk(0, 75, 100, 0);
        if ($EstSousTraitance) {
            $c->labelFont->textColor->color = Color::white();
        }
        return $c;
    }

    protected function codeBarre($id)
    {
        $c                    = new CodeBarre($id);
        $c->dimensions->width = 40;
        return $c;
    }

    protected function nbCommandes($count)
    {
        $c                     = new Cellule();
        $c->label              = 'Nb commandes';
        $c->dimensions->width  = 20;
        $c->dimensions->height = $this->dimensions->height;
        $c->value              = $count;
        return $c;
    }

    protected function countImperatifs($commandes)
    {
        $count = 0;
        foreach ($commandes as $c) {
            if ($c['EstImperatif'] || substr($c['CodeProduit'], -4) == 'RUSH') $count++;
        }
        return $count;
    }
} 