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

$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
    'log.level' => 4,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => '../logs',
        'name_format' => 'y-m-d'
    ))
));

\Locale\Helper::detect($app->environment());

// Prepare view
\Slim\Extras\Views\Twig::$twigOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
    'debug' => true
);



/** @var $twig \Slim\Extras\Views\Twig */
$twig = $app->view(new \Slim\Extras\Views\Twig());
/** @var $twigEnv Twig_Environment */
$twigEnv = $twig->getEnvironment();

$twigEnv->addExtension(new Twig_Extension_Debug());
$twigEnv->addExtension(new Twig_Extensions_Extension_I18n());

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