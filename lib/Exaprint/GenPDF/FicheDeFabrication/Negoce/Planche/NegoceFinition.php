<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Rang;

class NegoceFinition extends Rang
{

    public $entries = [];

    public $planche;

    /**
     * @param $planche
     */
    public function __constructor($planche)
    {
        $this->planche = $planche;
    }

    public function setEntries($entries)
    {
        $this->entries = $entries;
    }

} 