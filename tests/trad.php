<?php

require '../vendor/autoload.php';
$language = 'fr_FR';
// textdomain
putenv("LC_MESSAGES=" . $language);
setlocale(LC_MESSAGES, $language);
if (function_exists('bindtextdomain') && function_exists('textdomain')) {
    bindtextdomain("messages", "../locale");
    textdomain("messages");
    bind_textdomain_codeset("messages", "UTF-8");
}

echo _('valeur_185');

