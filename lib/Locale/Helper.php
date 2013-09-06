<?php

namespace Locale;

use Slim\Environment;

class Helper
{
    public static $current;

    public static function formatMoney($value)
    {
        $fmt = numfmt_create(self::$current, \NumberFormatter::CURRENCY);
        $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, '<span class="currency">'.$fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL).'</span>');
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
}