<?php

require "vendor/autoload.php";
require "lib/Log/MyLogWriter.php";

define("APPLICATION_NAME", "GenPDF");
define("APPLICATION_EDITOR", "Exaprint");
define("APPLICATION_VERSION", "0.0.1");
define("APPLICATION_ROOT", realpath("../"));
define("LIBRARY_PATH", "../library/");
define('K_PATH_FONTS', '../fonts/');

ini_set("display_errors", "1");
ini_set('mssql.charset', 'UTF-8');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Paris");

\Locale\Translations::$path = '../locale';

//\Exaprint\DAL\DB::setDefaultEnv(\Exaprint\DAL\DB::ENV_PROD);
$app = new \Slim\Slim(array(
    'view'           => new \Slim\Views\Twig(),
    'templates.path' => '../templates',
    'debug' => true,
    'mode' => 'development'
));

$app->hook('slim.before', function () use ($app) {
    $log = $app->getLog();
    $log->setEnabled(true);
    $log->setWriter(new \Log\MyLogWriter());
});

// locale-detector
$localeDetector = new Menencia\LocaleDetector\LocaleDetector();
$language = $localeDetector->detect();
\Locale\Helper::$current = $language;

//$langage = 'fr_FR';


// textdomain
putenv("LC_MESSAGES=" . $language);
setlocale(LC_MESSAGES, $language);
if (function_exists('bindtextdomain') && function_exists('textdomain')) {
    bindtextdomain("messages", APPLICATION_ROOT . "/locale");
    textdomain("messages");
    bind_textdomain_codeset("messages", "UTF-8");
}

// Prepare view
$twig                   = $app->view();
$twig->parserOptions    = array(
    'charset'          => 'utf-8',
    'cache'            => realpath('../templates/cache'),
    'auto_reload'      => true,
    'strict_variables' => false,
    'autoescape'       => true,
    'debug'            => true
);
$twig->parserExtensions = array(
    new Twig_Extension_Debug(),
    new Twig_Extensions_Extension_I18n(),
    new \Locale\TwigExtension()
);

$app->get("/version", function () use ($app) {
    $app->contentType("text/plain");
    echo APPLICATION_VERSION;
});

$app->get("/phpinfo", function () {
    phpinfo();
});

$app->delete("/template-cache", function () use ($app) {
    $app->contentType("text/plain");
    deleteTemplateCache(true);
});

function deleteTemplateCache($print = false)
{
    foreach (glob("../templates/cache/*") as $cachefile) {
        if ($print) echo "deleting $cachefile" . PHP_EOL;
        rrmdir($cachefile);
    }
}

function rrmdir($dir)
{
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) rrmdir($file); else unlink($file);
    }
    rmdir($dir);
}

function t($key, $language = null)
{
    return \Locale\Translations::get()->getEntry($key, $language);
}