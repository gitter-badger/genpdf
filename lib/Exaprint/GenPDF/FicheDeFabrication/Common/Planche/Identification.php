<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:51
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Planche;

use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;

class Identification extends Rang
{


    public function __construct($planche)
    {

        $this->dimensions = new Dimensions(200, 17);
        $this->cellules[] = $this->idPlanche($planche['IDPlanche']);
        $this->cellules[] = $this->expeSansFaconnage($planche['ExpeSansFaconnage'], $planche['EstSousTraitance']);
        if ($planche['EstSousTraitance']) {
            $this->cellules[] = $this->colisageFinal($planche['ColiseNomAtelier'], $planche['ColiseDateExpe'], $planche['ColiseIDPlanche']);
        } else {
            $this->cellules[] = $this->expeAvecFaconnage($planche['ExpeAvecFaconnage']);
        }
        $this->cellules[] = $this->imperatifs($this->countImperatifs($planche['commandes']), $planche['EstSousTraitance']);
        $this->cellules[] = $this->sousTraitance($planche['EstPrincipale'], $planche['EstSousTraitance']);
        $this->cellules[] = $this->codeBarre($planche['IDPlanche']);
        $this->cellules[] = $this->nbCommandes(count($planche['commandes']));
    }

    protected function idPlanche($id)
    {
        $c                     = new Cellule();
        $c->label              = t('ffa.id.num_planche');
        $c->dimensions->width  = 40;
        $c->dimensions->height = $this->dimensions->height;
        $c->value              = number_format($id, 0, '', ' ');
        return $c;
    }

    protected function expeSansFaconnage($date, $EstSousTraitance)
    {
        $c                     = new Cellule();
        $c->label              = t('ffa.id.date_expe_sans_faco');
        $c->dimensions->width  = 30;
        $c->dimensions->height = $this->dimensions->height;
        $c->value              = $date;

        if ($EstSousTraitance) {
            $c->fillColor->color            = Color::cmyk(0, 75, 100, 0);
            $c->valueFont->textColor->color = Color::white();
            $c->labelFont->textColor->color = Color::white();
        } else {
            $c->valueFont->textColor->color = Color::red();
        }

        return $c;
    }

    protected function expeAvecFaconnage($date)
    {

        $c                              = new Cellule();
        $c->label                       = t('ffa.id.date_expe_avec_faco');
        $c->dimensions->width           = 30;
        $c->dimensions->height          = $this->dimensions->height;
        $c->value                       = $date;
        $c->valueFont->textColor->color = Color::red();
        return $c;
    }

    protected function colisageFinal($nomAtelier, $date, $IDPlanche)
    {
        $c                     = new CelluleMultiligne();
        $c->label              = 'Colisage final chez';
        $c->dimensions->width  = 30;
        $c->dimensions->height = $this->dimensions->height;
        $c->text               = $nomAtelier . "\n" . $date . ' - ' . $IDPlanche;
        return $c;
    }

    protected function imperatifs($nb, $EstSousTraitance)
    {
        $c                     = new Cellule();
        $c->label              = t('ffa.id.imperatifs');
        $c->dimensions->width  = 20;
        $c->dimensions->height = $this->dimensions->height;
        $c->value              = $nb;

        if ($EstSousTraitance) {
            // fond blanc, texte noir
        } else {
            $c->fillColor->color            = Color::red();
            $c->valueFont->textColor->color = Color::white();
            $c->labelFont->textColor->color = Color::white();
        }

        return $c;
    }

    protected function sousTraitance($EstPrincipale, $EstSousTraitance)
    {
        $c                     = new Cellule();
        $c->label              = t('ffa.id.sous_traitance');
        $c->dimensions->width  = 20;
        $c->dimensions->height = $this->dimensions->height;

        $c->value = '';
        if ($EstPrincipale) {
            $c->value                       = 'P';
            $c->valueFont->textColor->color = Color::white();
            $c->fillColor->color            = Color::cmyk(0, 75, 100, 0);
            $c->labelFont->textColor->color = Color::white();
        }
        if ($EstSousTraitance) {
            $c->value                       = 'ST';
            $c->valueFont->textColor->color = Color::cmyk(0, 75, 100, 0);
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
        $c->label              = t('ffa.id.nb_commandes');
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