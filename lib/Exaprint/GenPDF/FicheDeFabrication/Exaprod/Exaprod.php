<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Exaprod;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;

class Exaprod extends Negoce
{

    public function planche() {
        $planchePdf = new ExaprodPlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
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

} 