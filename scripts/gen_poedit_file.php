<?php

$dirIterator   = new RecursiveDirectoryIterator(__DIR__ . '/../templates');
$cacheFilename = __DIR__ . "/../cache/twig_gettext_keys.php";
$pattern = '#\{% ?trans "([].*)"#';

if (file_exists($cacheFilename)) {
    unlink($cacheFilename);
}
/** @var SplFileInfo $file */
foreach (new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
    if ($file->isFile() && $file->getExtension() == 'twig') {
        $content = file_get_contents($file->getPathname());
        preg_match_all($pattern, $content, $matches);
        $keys = array_unique($matches[1]);
        array_walk($keys, function (&$key) {
            $key = "_('$key');";
        });
        file_put_contents($cacheFilename, "<?php " . PHP_EOL . implode(PHP_EOL, $keys));
    }
}