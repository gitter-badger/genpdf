<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Exaprod\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\FinitionMax;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinitions;

class ExaprodFinitions extends NegoceFinitions
{

    public function __construct($planche)
    {
        $this->planche = $planche;

        $finition = new FinitionMax($this->planche);
        $finition->setValue("Détails dans l'encadré de commande");
        $this->finitions[] = $finition;

        // on retourne le nombre de finitions implémentées
        // important, car permet de pouvoir placer le prochain bloc directement après
        $this->nbLines = count($this->finitions);
    }
} 