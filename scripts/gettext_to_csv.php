<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/11/2014
 * Time: 09:45
 */

require '../vendor/autoload.php';

$languages = ['fr_FR', 'es_ES', 'it_IT', 'pt_PT', 'en_GB'];

/** @var \Gettext\Translations[] $sources */
$sources = [];

$translations = [
    'header' => [
        'k' => 'key',
        'fr_FR' => 'fr_FR',
        'es_ES' => 'es_ES',
        'it_IT' => 'it_IT',
        'pt_PT' => 'pt_PT',
        'en_GB' => 'en_GB'
    ],
];


define('TRANSLATIONS_FILENAME', '../locale/%s/LC_MESSAGES/messages.po');

foreach($languages as $language){
    $sources[$language] = Gettext\Extractors\Po::fromFile(sprintf(TRANSLATIONS_FILENAME, $language));
}

foreach($languages as $language){
    /** @var $entry \Gettext\Translation */
    foreach ($sources[$language] as $entry){
        if(!isset($translations[$entry->original]))
            $translations[$entry->original] = ['k' => $entry->original];

        $translations[$entry->original][$language] = $entry->translation;
    }
}

\Locale\Translations::$path = '../locale';

$csv = fopen(\Locale\Translations::$path . '/main.csv', 'w+');

foreach($translations as $translation){
    fputcsv($csv, $translation, ";", '"');
}