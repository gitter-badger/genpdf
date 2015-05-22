<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;

class Masterprint extends Negoce
{
    public function build() {
        parent::build();
        $this->planche['devis'] = MasterprintDAL::getDevis($this->planche);
    }

    public function planche()
    {
        $planchePdf = new MasterprintPlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        foreach ($this->planche['commandes'] as $commande) {
            $this->commande($commande);
            $this->details($commande);
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

        new MasterprintCommande($commande, $this->pdf, $x, $y, $this->layout);
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

        new MasterprintDetails($commande, $this->planche['devis'], $this->pdf, $x, $y, $this->layout);
    }

}