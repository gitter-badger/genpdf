<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Exaprod;


use Exaprint\GenPDF\FicheDeFabrication\Exaprod\Planche\ExaprodFinitions;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegocePlanche;
use Exaprint\TCPDF\Position;

class ExaprodPlanche extends NegocePlanche
{

    public function finitions()
    {
        $finitions = new ExaprodFinitions($this->planche);
        $finitions->draw($this->pdf, $this->position->add(new Position(0, 33)));
        $this->hauteurFinitions = 33 + 11 * $finitions->nbLines;
    }

} 