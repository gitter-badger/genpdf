<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Finition;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\NegoceDAL;
use Exaprint\TCPDF\Position;

class NegoceFinitions
{

    /** @var  Finition[] */
    public $finitions = [];

    public function __construct($planche)
    {
        // désigne le tableau des entrées
        // ces informations proviennent de la base de données et seront "converties" en finitions
        $entries = NegoceDAL::getFinitions($planche['IDPlanche']);

        // désigne la finition en cours
        // une finition est un bloc s'étalant sur une seule ligne, et possédant plusieurs colonnes.
        $finition = null;

        // désigne le nombre de finitions déjà implémentées
        $nbLines = 0;

        // désigne la position du curseur, au niveau du tableau des entrées
        $currEntry = -1;

        // 2 conditions d'arrêts :
        // - dès qu'on atteint 5 finitions déjà implémentées
        // - dès qu'on atteint la fin du tableau des entrées
        while ($nbLines < 5 && $currEntry + 1 < count($entries)) {

            // on déplace le curseur sur la prochaine entrée
            $entry = $entries[++$currEntry];

            // en fonction du Bloc et de la ligne, on en déduit le type de finition
            if (in_array($entry->Bloc, [1, 2, 3])) {
                if ($entry->Ligne == 1) {
                    $finition       = new Finition1();
                    $finition->Type = 1;
                } else {
                    $finition       = new Finition2();
                    $finition->Type = 2;
                }

                // pour les finitions de type 1 et 2, on met à jour le titre...
                $title = (!is_null($entry->IDProduitOptionNecessairePourTitreAlternatif)) ? $entry->TitreAlternatif : $entry->Titre;
                $finition->setTitle($title);

            } else {
                $finition       = new Finition3();
                $finition->Type = 3;

                // pour les finitions de type 3, on met à jour la celulle Option1...
                $title = (!is_null($entry->IDProduitOptionNecessairePourTitreAlternatif)) ? $entry->TitreAlternatif : $entry->Titre;
                $finition->setOption1($title);

            }

            // ...et la celulle A1.
            $title = (!is_null($entry->LibelleValeurPredefinie)) ? $entry->LibelleValeurPredefinie : $entry->LibelleValeur;
            $finition->setA1($title);

            if ($finition->Type == 2) {
                $finition->setA2($entry->EstRecto, $entry->EstVerso);
            }

            // désigne le fait qu'on a plus d'autres entrées pour compléter la finition en cours
            $noOthers = false;

            // 2 conditions d'arrêt :
            // - lorsqu'on a plus d'autres entrées pour compléter la finition en cours
            // - lorsqu'on atteint la fin du tableau des entrées
            while (!$noOthers && $currEntry + 1 < count($entries)) {

                // on déplace le curseur sur la prochaine entrée
                $nextEntry = $entries[++$currEntry];

                // on regarde que l'entrée ne corresponde pas à une nouvelle finition
                // si c'est le cas, son n° de Bloc ou de Ligne différent
                if ($nextEntry->Bloc != $entry->Bloc || $nextEntry->Ligne != $entry->Ligne) {

                    // on déplace le curseur en arrière
                    $currEntry--;

                    // prêt à finaliser la finition
                    $noOthers = true;
                }

                // gestion du recto/verso des finitions de type 2
                if ($finition->Type == 2) {
                    $finition->setA2($nextEntry->EstRecto, $nextEntry->EstVerso);

                    // prêt à finaliser la finition
                    $noOthers = true;
                }

                // si l'entrée correspond à la nouvelle finition
                if (!$noOthers) {

                    // si le Bloc de l'entrée n'est pas 4
                    if ($nextEntry->Bloc < 4) {

                        // on complète la finition en cours avec les celulles A1, A2 et A3
                        switch ($nextEntry->Encadre) {
                            case 1:
                                $title = (!is_null($nextEntry->LibelleValeurPredefinie)) ? $nextEntry->LibelleValeurPredefinie : $nextEntry->LibelleValeur;
                                $finition->setA1($title);
                                break;
                            case 2:
                                // gestion de la couleur seulement pour les finitions de type 1
                                if ($finition->Type == 1) {
                                    $title = (!is_null($nextEntry->LibelleValeurPredefinie)) ? $nextEntry->LibelleValeurPredefinie : $nextEntry->LibelleValeur;
                                    $a2    = $finition->getA2();
                                    $finition->setA2($a2 . $title);
                                }
                                break;
                            case 3: // gestion du R/V
                                $title = (!is_null($nextEntry->LibelleValeurPredefinie)) ? $nextEntry->LibelleValeurPredefinie : $nextEntry->LibelleValeur;
                                $rv = '';
                                $rv .= ($nextEntry->EstRecto) ? 'R°': '';
                                $rv .= ($nextEntry->EstVerso) ? 'V°': '';
                                if (strlen($rv) > 0) $rv = ' ' . $rv;
                                $finition->setA3($title . $rv);
                                break;
                        }
                    } // si le bloc de l'entrée est 4
                    else {

                        $title = (!is_null($nextEntry->IDProduitOptionNecessairePourTitreAlternatif)) ? $nextEntry->TitreAlternatif : $nextEntry->Titre;
                        $finition->setOption2($title);

                        $title = (!is_null($nextEntry->LibelleValeurPredefinie)) ? $nextEntry->LibelleValeurPredefinie : $nextEntry->LibelleValeur;
                        $finition->setA2($title);

                        // prêt à finaliser la finition
                        $noOthers = true;
                    }
                }
            }

            // si c'est une finition de type 1, on compléte la cellule A2 (gestion de la couleur)
            if ($finition->Type == 1) {
                $a2 = $finition->getA2();
                if (!empty($a2) && strpos($a2, '+') === false) {
                    $finition->setA2('0+' . $a2);
                }
                if (substr($a2, -1) == '+') {
                    $finition->setA2($a2 . '0');
                }
            }

            // on finalise la finition
            $this->finitions[] = $finition;

            // on incrémente la finition implémentée
            $nbLines++;

        }

        // on retourne le nombre de finitions implémentées
        // important, car permet de pouvoir placer le prochain bloc directement après
        $this->nbLines = $nbLines;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {

        foreach ($this->finitions as $finition) {
            $finition->draw($pdf, $position);
            $position = $position->add(new Position(0, 11));
        }
    }
} 