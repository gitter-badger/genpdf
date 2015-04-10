<?php

namespace Exaprint\GenPDF\FicheDeFabrication;

class Functions
{

    public static function short($str, $nbr)
    {
        if (strlen($str) < $nbr) {
            return $str;
        }

        return str_split($str, $nbr)[0] . '...';

    }

}