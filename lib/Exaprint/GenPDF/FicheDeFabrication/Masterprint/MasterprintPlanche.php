<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Masterprint\Planche\MasterprintFinitions;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegocePlanche;
use Exaprint\TCPDF\Position;

class MasterprintPlanche extends NegocePlanche
{

    public function finitions()
    {
        $finitions = new MasterprintFinitions($this->planche);
        $finitions->draw($this->pdf, $this->position->add(new Position(0, 33)));
        $this->hauteurFinitions = 33 + 11;
    }

} 