<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/02/2014
 * Time: 09:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


class Factory
{

    public static function createFicheDeFabrication($planche)
    {
        if(!$planche){
            throw new \Exception('Planche is null');
        }
        switch ($planche['IDProduitActiviteProduction']) {
            case 1:
            case 2:
            case 4:
            case 7:
                return new Amalgame($planche);
            default:
                var_dump($planche);
                return null;
        }
    }
} 