<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Etiquette;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Exaprint\GenPDF\FicheDeFabrication\GFN\GFNDAL;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegoceDAL;

class Etiquette extends Negoce
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
        $this->planche['nbModeles']    = count($this->_getModels($data));

        // récupérer tous les images des modèles
        $this->planche['imagesModeles'] = GFNDAL::getImagesFichiers($this->planche['commandes'][0]['IDCommande']);
    }

    public function planche()
    {
        $planchePdf = new EtiquettePlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        $models                = $this->_getModels($this->planche['partnersData']);
        $commande              = $this->planche['commandes'][0];
        $commande['nbModeles'] = count($models);
        $this->commande($commande);

        $thetis = $this->planche['partnersData']['thetis']->xpath('thetis_W2PCommande');

        $n = 0;
        foreach ($models as $n => $modele) {
            $commande['modele'] = $modele;
            if (isset($this->planche['imagesModeles'][$n])) {
                $commande['Fichiers']['Visuels'] = [$this->planche['imagesModeles'][$n]];
            }
            $commande['modele']['EtiquetteparRouleau'] = $thetis[$n]->EtiquetteparRouleau;
            $this->modele($commande);
            $n++;
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

        new EtiquetteCommande($commande, $this->pdf, $x, $y, $this->layout);
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

        new EtiquetteModele($commande, $this->pdf, $x, $y, $this->layout);
    }

    protected function getPageCount()
    {
        if (!isset($this->_pageCount)) {
            $nbModeles        = $this->planche['nbModeles'];
            $this->_pageCount = ceil(($nbModeles + 2) / 4);
        }
        return $this->_pageCount;
    }

    protected function _getModels($data)
    {
        $models = $data['models'];
        if (is_array($models)) {
            // do nothing
        } else if (is_object($models) && isset($models->model)) {
            $models = $models->xpath('model');
        } else {
            $models = [$models];
        }
        return $models;
    }

} 