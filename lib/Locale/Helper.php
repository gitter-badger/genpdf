<?php

namespace Locale;

use Slim\Environment;

class Helper
{
    public static $current;

    public static $map = [
        1 => 'fr_FR',
        2 => 'es_ES',
        5 => 'it_IT',
        6 => 'pt_PT',
        9 => 'en_GB',
    ];

    public static function formatMoney($value)
    {
        $fmt = numfmt_create(self::$current, \NumberFormatter::CURRENCY);
        $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, '<span class="currency">' . $fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL) . '</span>');
        return numfmt_format($fmt, floatval($value));
    }

    public static function formatNumber($value)
    {
        $fmt = numfmt_create(self::$current, \NumberFormatter::DECIMAL);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
        return numfmt_format($fmt, floatval($value));
    }

    public static function formatDate($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public static function getCollationForIDLangue($IDLangue)
    {
        return self::$map[$IDLangue];
    }
}