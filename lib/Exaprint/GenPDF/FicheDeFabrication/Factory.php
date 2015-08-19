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
use Exaprint\GenPDF\FicheDeFabrication\Etiquette\Etiquette;
use Exaprint\GenPDF\FicheDeFabrication\Exaprod\Exaprod;
use Exaprint\GenPDF\FicheDeFabrication\GFN\GFN;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;
use Exaprint\GenPDF\FicheDeFabrication\Masterprint\Masterprint;

class Factory
{

    /**
     * AMALGAMME
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

        if ($type == 0) {
            throw new \Exception('Planche invalide');
        }

        $planche = DAL::getPlanche($IDPlanche);

        switch ($type) {
            case 0:
                throw new \Exception('Planche invalide');
            case 1:
                return new Amalgame($planche);
            case 2:
                return new Negoce($planche);
            case 3:
                return new Exaprod($planche);
            case 4:
                return new Masterprint($planche);
            case 5:
                return new Etiquette($planche);
            case 6:
                return new GFN($planche);
        }
    }
} 