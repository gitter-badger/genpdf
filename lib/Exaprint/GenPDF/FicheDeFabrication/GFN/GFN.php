<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\GFN;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Exaprint\GenPDF\FicheDeFabrication\Etiquette\Etiquette;
use Exaprint\GenPDF\FicheDeFabrication\Etiquette\EtiquetteDAL;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegoceDAL;

class GFN extends Etiquette
{
    public function build()
    {
        // complétion code famille et code produit
        $code                          = NegoceDAL::getFamilleCodification($this->planche['IDPlanche']);
        $this->planche['Famille']      = $code->famille;
        $this->planche['Codification'] = $code->codification;

        // récupérer les données propriétaires
        $data                          = EtiquetteDAL::getPartnersData($this->planche['commandes'][0]['IDCommande']);
        $this->planche['partnersData'] = $data;
    }

    public function planche()
    {
        $planchePdf = new GFNPlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        $commande                  = $this->planche['commandes'][0];
        $commande['nbModeles']     = is_object($this->planche['partnersData']['models']) ? 1 : count($this->planche['partnersData']['models']);
        $commande['surfaceTotale'] = $this->_getSurfaceTotale($this->planche['partnersData']['models']);
        $this->commande($commande);

        if (is_object($this->planche['partnersData']['models'])) {
            $commande['modele'] = $this->planche['partnersData']['models'];
            $this->modele($commande);
        } else {
            foreach ($this->planche['partnersData']['models'] as $modele) {
                $commande['modele'] = $modele;
                $this->modele($commande);
            }
        }
    }

    protected function commande($commande)
    {
        if ($this->currentCommandePosition < 3) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = $this->layout->xBloc($this->currentCommandePosition);
        $y = $this->layout->yBloc($this->currentCommandePosition);

        new GFNCommande($commande, $this->pdf, $x, $y, $this->layout);
    }

    protected function modele($commande)
    {
        if ($this->currentCommandePosition < 3) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = $this->layout->xBloc($this->currentCommandePosition);
        $y = $this->layout->yBloc($this->currentCommandePosition);

        new GFNModele($commande, $this->pdf, $x, $y, $this->layout);
    }

    protected function _getSurfaceTotale($partnersData)
    {
        $surfaceTotale = 0;
        if (is_object($partnersData)) {
            $surfaceTotale = $partnersData->size->width * $partnersData->size->height;
        } else {
            foreach ($this->planche['partnersData']['models'] as $modele) {
                $surfaceTotale += $modele->size->width * $modele->size->height;
            }
        }
        if ($surfaceTotale > 0) {
            $surfaceTotale /= 1000;
        }
        return $surfaceTotale;
    }

} 