<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Etiquette;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Impression;
use Exaprint\GenPDF\FicheDeFabrication\Etiquette\Planche\EtiquetteFinitions;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegocePlanche;
use Exaprint\TCPDF\Position;

class EtiquettePlanche extends NegocePlanche
{

    public function impression()
    {
        $impression = new Impression($this->planche);
        $impression->draw($this->pdf, $this->position->add(new Position(0, 17)));

    }

    public function finitions()
    {
        $finitions = new EtiquetteFinitions($this->planche);
        $finitions->draw($this->pdf, $this->position->add(new Position(0, 33)));
        $this->hauteurFinitions = 33 + 11;
    }

} 