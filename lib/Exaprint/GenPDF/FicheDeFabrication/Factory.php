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
        switch ($planche['IDProduitActiviteProduction']){
            case 1:
            case 2:
                return new Amalgame($planche);
        }
        return null;
    }
} 