<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/02/2014
 * Time: 09:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Amalgame;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Exaprod;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;

class Factory
{

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
                }
                break;
            default:
                var_dump($planche);
                return null;
        }
    }
} 