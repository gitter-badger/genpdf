<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Amalgame;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;

class Negoce extends Amalgame
{

    public function build() {
        // complétion code famille et code produit
        $code = NegoceDAL::getFamilleCodification($this->planche['IDPlanche']);
        $this->planche['Famille'] = $code->famille;
        $this->planche['Codification'] = $code->codification;
    }

    public function planche() {
        $planchePdf = new NegocePlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        foreach ($this->planche['commandes'] as $commande) {
            $this->commande($commande);
            $this->details($commande);
        }

        foreach ($this->_formesDeDecoupe as $formeDeDecoupe) {
            $this->formeDecoupe($formeDeDecoupe['IDCommande'], $formeDeDecoupe['Fichier']);
        }
    }

    protected function details($commande)
    {
        if ($this->currentCommandePosition < 3) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = $this->layout->xBloc($this->currentCommandePosition);
        $y = $this->layout->yBloc($this->currentCommandePosition);

        new Details($commande, $this->pdf, $x, $y, $this->layout);
    }

} 