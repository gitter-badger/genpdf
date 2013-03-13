<?php
header("Content-Type: text/plain");
$cmd = "php ~/composer.phar -d=" . __DIR__ . '/../ update';
exec($cmd, $output, $return);

echo PHP_EOL;
print_r($cmd);
echo PHP_EOL;
print_r($output);
echo PHP_EOL;
print_r($return);