<?php

$cacheFilename = __DIR__ . "/../cache/twig_gettext_keys.php";
$pattern = '#\{% ?trans "(.*)"#';

if (file_exists($cacheFilename)) {
    unlink($cacheFilename);
}
/** @var SplFileInfo $file */

$directory = new RecursiveDirectoryIterator(__DIR__ . '/../templates');
$iterator = new RecursiveIteratorIterator($directory);
$files = new RegexIterator($iterator, '/^.+\.twig$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $filename => $v){
    $content = file_get_contents($filename);
    preg_match_all($pattern, $content, $matches);
    $keys = array_unique($matches[1]);
    array_walk($keys, function (&$key) {
        $key = "_('$key');";
    });
    file_put_contents($cacheFilename, "<?php " . PHP_EOL . implode(PHP_EOL, $keys));
}


