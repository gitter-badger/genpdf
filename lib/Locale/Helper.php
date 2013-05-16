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
        $extension = $matches[1];
        self::$current = isset(self::$extensionMap[$extension]) ? self::$extensionMap[$extension] : self::$default;
        putenv("LC_MESSAGES=" . self::$current);
        setlocale(LC_MESSAGES, self::$current);
        if (function_exists('bindtextdomain') && function_exists('textdomain')) {
            bindtextdomain("messages", APPLICATION_ROOT . "/locale");
            textdomain("messages");
            bind_textdomain_codeset("messages", "UTF-8");
        }
    }
}