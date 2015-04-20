<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Configurateur\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinitions;

class ConfigurateurFinitions extends NegoceFinitions
{

    public function __construct($planche)
    {
        $this->planche = $planche;

        $finition = new FinitionMasterprint($this->planche);
        $finition->setValue("Masterprint N°XXX - YYY");
        $this->finitions[] = $finition;

        // on retourne le nombre de finitions implémentées
        // important, car permet de pouvoir placer le prochain bloc directement après
        $this->nbLines = count($this->finitions);
    }
} 