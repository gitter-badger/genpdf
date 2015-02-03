<?php

require '../vendor/autoload.php';


function t($key, $language = null)
{
    return \Locale\Translations::get()->getEntry($key, $language);
}

\Locale\Translations::$path = '../locale';

echo t('num_planche', 'fr_FR');