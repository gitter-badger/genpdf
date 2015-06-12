<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\GFN;


use Exaprint\GenPDF\FicheDeFabrication\GFN\Planche\GFNImpression;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegocePlanche;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinitions;
use Exaprint\TCPDF\Position;

class GFNPlanche extends NegocePlanche
{

    public function impression()
    {
        $impression = new GFNImpression($this->planche);
        $impression->draw($this->pdf, $this->position->add(new Position(0, 17)));

    }

    public function finitions()
    {
        $finitions = new NegoceFinitions($this->planche);
        $finitions->draw($this->pdf, $this->position->add(new Position(0, 33)));
        $this->hauteurFinitions = 33 + 11 * $finitions->nbLines;
    }

} 