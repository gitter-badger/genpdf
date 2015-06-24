<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/02/2014
 * Time: 09:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Amalgame;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\DAL;
use Exaprint\GenPDF\FicheDeFabrication\Exaprod\Exaprod;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;

class Factory
{

    /**
     * COMMON
     *      AMALGAMME
     *      NEGOCE
     *          EXAPROD
     *          CONFIGURATEUR
     *          ETIQ
     *          CALC VINYLE
     * @param $IDPlanche
     * @return Amalgame|Exaprod|Negoce
     * @throws \Exception
     */
    public static function createFicheDeFabrication($IDPlanche)
    {
        $type = Helper::getTypeFicheFabGenPDF($IDPlanche);

        switch ($type) {
            case 0:
                throw new \Exception('Planche invalide');
            case 1:
                $planche = DAL::getPlanche($IDPlanche);
                return new Amalgame($planche);
            case 2:
                $planche = DAL::getPlanche($IDPlanche);
                return new Negoce($planche);
            case 3:
                $planche = DAL::getPlanche($IDPlanche);
                return new Exaprod($planche);
            case 4:
                throw new \Exception('Masterprint non pris en charge encore');
            //return new Masterprint($planche);
            case 5:
                throw new \Exception('Etiquette non pris en charge encore');
            //return new Etiquette($planche);
            case 6:
                throw new \Exception('Grand format numérique non pris en charge encore');
            //return new GrandFormatNum($planche);
        }
    }
} 