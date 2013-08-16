<?php

namespace Locale;

use Slim\Environment;

class Helper
{
    public static $current;

    public static $default = 'fr_FR';

    public static $extensionMap = [
        "it" => "it_IT",
        "es" => "es_ES",
        "pt" => "pt_PT",
        "uk" => "en_GB",
        "fr" => "fr_FR",
    ];

    public static function detect(Environment $env)
    {
        $serverName = $env["SERVER_NAME"];
        preg_match('#\.([a-z]+)$#', $serverName, $matches);
        $extension     = isset($matches[1]) ? $matches[1] : 'fr';
        self::$current = isset(self::$extensionMap[$extension]) ? self::$extensionMap[$extension] : self::$default;
        putenv("LC_MESSAGES=" . self::$current);
        setlocale(LC_MESSAGES, self::$current);
        if (function_exists('bindtextdomain') && function_exists('textdomain')) {
            bindtextdomain("messages", APPLICATION_ROOT . "/locale");
            textdomain("messages");
            bind_textdomain_codeset("messages", "UTF-8");
        }
    }

    public static function formatMoney($value)
    {
        if (self::$current == "en_GB") {
            return '<span class="currency">£</span>' . number_format($value, 2, '.', ',');
        }
        return number_format($value, 2, ',', ' ') . '&nbsp;<span class="currency">&euro;</span>';

    }

    public static function formatDate($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}