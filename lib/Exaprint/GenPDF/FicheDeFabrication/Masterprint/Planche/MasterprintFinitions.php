<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Masterprint\MasterprintDAL;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinition;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinitions;
use Exaprint\TCPDF\Position;

class MasterprintFinitions extends NegoceFinitions
{
    /** @var NegoceFinition[] */
    public $finitions = [];

    public function __construct($planche)
    {
        $this->planche = $planche;

        $finition = new Finition1Cell($this->planche);
        $devis = MasterprintDAL::getDevis($this->planche);
        $finition->setValue("MASTERPRINT N°$devis->NumeroDevisAtelier - $devis->NomAtelier");
        $this->finitions[] = $finition;

    }

    /**
     * @param \TCPDF $pdf
     * @param Position $position
     */
    public function draw(\TCPDF $pdf, Position $position)
    {

        foreach ($this->finitions as $finition) {
            $finition->draw($pdf, $position);
            $position = $position->add(new Position(0, 11));
        }
    }
} 