<?php

namespace Exaprint\GenPDF\FicheDeFabrication;

use Exaprint\DAL\DB;

class Helper
{

    public static function short($str, $nbr)
    {
        if (strlen($str) < $nbr) {
            return $str;
        }

        return str_split($str, $nbr)[0] . '...';

    }

    /**
     *    0 = planche invalide
     *    1 = planche amalgame
     *    2 = planche négoce
     *    3 = planche exaprod (idem planche négoce, mais ça peut peut-être t'éviter une autre requête pour déterminer l'Exaprod)
     *    4 = planche masterprint
     *    5 = planche étiquette
     *    6 = planche grand format numérique
     * @param $IDPlanche
     * @return mixed
     */
    public static function getTypeFicheFabGenPDF($IDPlanche) {
        $select = "select dbo.f_nTypeFicheFabGenPDF(:IDPlanche) as type";
        $stmt = DB::get()->prepare($select);
        $stmt->execute([
            "IDPlanche" => $IDPlanche
        ]);
        return $stmt->fetch(DB::FETCH_COLUMN);
    }

}