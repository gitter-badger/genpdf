<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegoceDAL;
use Exaprint\TCPDF\Position;

class NegoceFinitions
{

    /** @var NegoceFinition[] */
    public $finitions = [];

    public $entries;

    public $nbLines = 0;

    public function __construct($planche)
    {
        // ces informations proviennent de la base de données et seront "converties" en finitions
        $this->entries = NegoceDAL::getFinitions($planche['IDPlanche']);

        // on prend une nouvelle entrée
        while (!empty($this->entries)) {

            $finition = $this->_takeLines();

            if ($finition->Type != 3) {
                $finition->build();
                $this->finitions[] = $finition;
            } else {
                $ret = [];

                foreach ($finition->entries as $entry) {
                    $ret[] = $entry->LibelleValeurPredefinie ? $entry->LibelleValeurPredefinie : $entry->LibelleValeur;
                }

                $ret = array_keys(array_flip($ret));

                for ($i = 0; $i < count($ret); $i += 2) {
                    $finition       = new Finition3();
                    $finition->Type = 3;
                    $finition->setOption1('+');
                    $finition->setA1($ret[$i]);
                    if ($i + 1 < count($ret)) {
                        $finition->setOption2('+');
                        $finition->setA2($ret[$i + 1]);
                    }
                    $this->finitions[] = $finition;
                }

            }
        }

        // on retourne le nombre de finitions implémentées
        // important, car permet de pouvoir placer le prochain bloc directement après
        $this->nbLines = count($this->finitions);
    }

    /**
     * @return Finition1|Finition2|Finition3
     */
    private function _takeLines()
    {
        $Bloc  = $this->entries[0]->Bloc;
        $Ligne = $this->entries[0]->Ligne;

        // en fonction du Bloc et de la ligne, on en déduit le type de finition
        if (in_array($Bloc, [1, 2, 3])) {
            if ($Ligne == 1) {
                $finition       = new Finition1();
                $finition->Type = 1;
            } else {
                $finition       = new Finition2();
                $finition->Type = 2;
            }
        } else {
            $finition       = new Finition3();
            $finition->Type = 3;
        }

        $ret = [];

        // on cherche toutes les entrées de la finition
        foreach ($this->entries as $entry) {
            if ($entry->Bloc == $Bloc and $entry->Ligne == $Ligne) {
                $ret[] = array_shift($this->entries);
            }
        }

        $finition->setEntries($ret);

        return $finition;
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