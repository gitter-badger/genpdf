<?php

require "vendor/autoload.php";

define("APPLICATION_NAME", "GenPDF");
define("APPLICATION_EDITOR", "Exaprint");
define("APPLICATION_VERSION", "0.0.1");
define("APPLICATION_ROOT", realpath("../"));
define("LIBRARY_PATH", "../library/");

ini_set("display_errors", "1");
ini_set('mssql.charset', 'UTF-8');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Paris");

\Exaprint\DAL\DB::setDefaultEnv(\Exaprint\DAL\DB::ENV_PROD);
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig(),
    'templates.path' => '../templates',
    'log.level' => 4,
    'log.enabled' => true
    //'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
    //    'path' => '../logs',
    //    'name_format' => 'y-m-d'
    //))
));

\Locale\Helper::detect($app->environment());

// Prepare view
$twig = $app->view();
$twig->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
    'debug' => true
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

$app->get("/phpinfo", function(){
    phpinfo();
});

$app->delete("/template-cache", function() use ($app) {
    $app->contentType("text/plain");
    deleteTemplateCache(true);
});

function deleteTemplateCache($print = false)
{
    foreach(glob("../templates/cache/*") as $cachefile){
        if($print) echo "deleting $cachefile" . PHP_EOL;
        rrmdir($cachefile);
    }
}
function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file)) rrmdir($file); else unlink($file);
    } rmdir($dir);
}