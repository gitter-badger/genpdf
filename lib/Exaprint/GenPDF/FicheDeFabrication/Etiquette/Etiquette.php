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
    public function build() {
        // complétion code famille et code produit
        $code = NegoceDAL::getFamilleCodification($this->planche['IDPlanche']);
        $this->planche['Famille'] = $code->famille;
        $this->planche['Codification'] = $code->codification;

        // récupérer les données propriétaires
        $data = EtiquetteDAL::getPartnersData($this->planche['commandes'][0]['IDCommande']);
        $this->planche['partnersData'] = $data;

        // récupérer tous les images des modèles
        $this->planche['imagesModeles'] = GFNDAL::getImagesFichiers($this->planche['commandes'][0]['IDCommande']);
    }

    public function planche() {
        $planchePdf = new EtiquettePlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        $models = $this->planche['partnersData']['models'];
        $models = (is_array($models)) ? $models: $models->children();
        $commande = $this->planche['commandes'][0];
        $commande['nbModeles'] = count($models);
        $this->commande($commande);

        $n = 0;
        foreach ($models as $modele) {
            $commande['modele'] = $modele;
            if (isset($this->planche['imagesModeles'][$n])) {
                $commande['Fichiers']['Visuels'] = [$this->planche['imagesModeles'][$n]];
            }
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

} 