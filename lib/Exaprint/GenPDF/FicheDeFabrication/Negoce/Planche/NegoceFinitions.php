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
        $lines = 0;

        $details = NegoceDAL::getFinitions($planche['IDPlanche']);

        $finition = null;

        $last = null;

        for ($n = 0; $n < min(5, count($details)); $n++) {

            $d = $details[$n];

            $last = ($n > 0) ? $details[$n - 1] : null;

            // si on passe à un nouveau bloc, ou à une nouvelle ligne du bloc
            if (is_null($last) || $last->Bloc != $d->Bloc || $last->Ligne != $d->Ligne) {

                // Si une finition existe, on la finalise
                if (isset($finition)) {

                    // on finalise la couleur
                    if ($finition->Type == 1 && $last->Encadre == 2) {
                        if ($last->EstRecto) {
                            $label = $finition->getA2();
                            $finition->setA2($label . '0');
                        }
                        if ($last->EstVerso) {
                            $label = $finition->getA2();
                            $finition->setA2('0+' . $label);
                        }
                    }

                    $this->finitions[] = $finition;

                    // nouvelle ligne
                    $lines++;
                }

                // on cherche quel genre de finition créer
                if (in_array($d->Bloc, [1, 2, 3])) {
                    if ($d->Ligne == 1) {
                        $finition       = new Finition1();
                        $finition->Type = 1;
                    } else {
                        $finition       = new Finition2();
                        $finition->Type = 2;
                    }

                    // titre seulement pour les finitions 1 & 2
                    $title = ($d->IDProduitOptionNecessairePourTitreAlternatif) ? $d->TitreAlternatif : $d->Titre;
                    $finition->setTitle($title);

                } else {
                    $finition       = new Finition3();
                    $finition->Type = 3;
                }
            }

            // gestion de l'encadré
            switch ($d->Encadre) {
                case 0:
                    $title = ($d->IDProduitOptionNecessairePourTitreAlternatif) ? $d->TitreAlternatif : $d->Titre;
                    $finition->setTitle($title);
                    break;
                case 1:
                    $title = ($d->LibelleValeurPredefinie) ? $d->LibelleValeurPredefinie : $d->LibelleValeur;
                    $finition->setA1($title);
                    break;
                case 2:
                    $title = ($d->LibelleValeurPredefinie) ? $d->LibelleValeurPredefinie : $d->LibelleValeur;
                    $finition->setA2($title);
                    break;
                case 3:
                    $title = ($d->LibelleValeurPredefinie) ? $d->LibelleValeurPredefinie : $d->LibelleValeur;
                    $finition->setA3($title);
                    break;
            }

        }

        if (!is_null($finition)) {
            $this->finitions[] = $finition;

            // nouvelle ligne
            $lines++;
        }

        /*
        $finition = new Finition1();
        $finition->setTitle('COUV');
        $finition->setA1('350g Mat Condat Périgord');
        $finition->setA2('4+0');
        $finition->setA3('Bril. R°');
        $this->finitions[] = $finition;
        $lines++;

        $finition = new Finition2();
        $finition->setTitle('+');
        $finition->setA1('Vernis UV sélectif : Vernis UV sélectif 3D');
        $finition->setA2('R');
        $this->finitions[] = $finition;
        $lines++;

        $options = ['Numérotation', 'Découpe', 'Test'];
        for ($i = 0; $i < count($options); $i += 2) {
            $finition = new Finition3();
            $finition->setOption1('+');
            $finition->setA1($options[$i]);
            if ($i + 1 < count($options)) {
                $finition->setOption2('+');
                $finition->setA2($options[$i + 1]);
            }
            $this->finitions[] = $finition;
            $lines++;
        }
        */

        $this->nbLines = $lines;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {

        foreach ($this->finitions as $finition) {
            $finition->draw($pdf, $position);
            $position = $position->add(new Position(0, 11));
        }
    }
} 