<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 15:14
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


class Formatter {

    public static function idCommande($IDCommande)
    {
        return preg_replace("#([0-9]{3})([0-9]{2})([0-9]{2})#", '$1 $2 $3', (string)$IDCommande);
    }


    public static function idPlanche($IDPlanche)
    {
        return number_format($IDPlanche, 0, '.', ' ');
    }

    public static function quantite($quantite)
    {
        return number_format($quantite, 0, '.', ' ');
    }
} 