<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/02/2014
 * Time: 09:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Amalgame;
use Exaprint\GenPDF\FicheDeFabrication\Exaprod\Exaprod;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;
use Exaprint\GenPDF\FicheDeFabrication\Configurateur\Configurateur;

class Factory
{

    /**
     *
     * COMMON
     *      AMALGAMME
     *      NEGOCE
     *          EXAPROD
     *          CONFIGURATEUR
     *          ETIQ
     *          CALC VINYLE
     *
     * @param $planche
     * @return Amalgame|Configurateur|Exaprod|Negoce|null
     * @throws \Exception
     */
    public static function createFicheDeFabrication($planche)
    {
        if (!$planche) {
            throw new \Exception('Planche is null');
        }
        switch ($planche['IDProduitActiviteProduction']) {
            case 1:
            case 2:
            case 4:
            case 7:
                return new Amalgame($planche);
                break;
            case 3:
                $case = 1;
                if ($case == 1) {
                    return new Negoce($planche);
                } else if ($case == 2) {
                    return new Exaprod($planche);
                } else if ($case == 3) {
                    return new Configurateur($planche);
                }
                break;
            default:
                var_dump($planche);
                return null;
        }
    }
} 