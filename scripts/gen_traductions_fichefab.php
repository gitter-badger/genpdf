<?php

require '../vendor/autoload.php';
require '/Users/admin/Sites/env.php';

$queries = [
    "SELECT
        'activite_production_' as label,
        ap.IDProduitActiviteProduction as pk,
        LibelleTraduit as value,
        IDLangue as lang
        FROM TBL_PRODUIT_ACTIVITE_PRODUCTION ap
        JOIN TBL_PRODUIT_ACTIVITE_PRODUCTION_TRAD apt ON apt.IDProduitActiviteProduction = ap.IDProduitActiviteProduction
        WHERE EstSupp = 0",
    "SELECT
        'valeur_' as label,
        pov.IDProduitOptionValeur as pk,
        LibelleTraduit as value,
        IDLangue as lang
        FROM TBL_PRODUIT_OPTION_VALEUR pov
        LEFT JOIN TBL_PRODUIT_OPTION_VALEUR_TRAD povt ON povt.IDProduitOptionValeur = pov.IDProduitOptionValeur
        WHERE EstSupp = 0
        --AND EstSans = 0
        AND IDProduitOption IN (
            75,78,79,80,81,85,86,87,88,89,90,93,96,98,99,100,104,105,147
        )
        ORDER BY IDProduitOption"
];

$keys  = [];
$langs = [
    0 => null,
    1 => 'fr_FR',
    2 => 'es_ES',
    4 => null,
    5 => 'it_IT',
    6 => 'pt_PT',
    9 => 'en_GB'
];

$handlers = [];

$header = 'msgid ""
msgstr ""
"Project-Id-Version: genPDF-fichefab\n"
"POT-Creation-Date: 2014-01-06 15:24+0100\n"
"PO-Revision-Date: 2014-01-06 15:24+0100\n"
"Last-Translator: \n"
"Language-Team: Exaprint <romain@exaprint.fr>\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Language: %s\n"' . PHP_EOL . PHP_EOL;


foreach ($langs as $lang) {
    if (is_null($lang)) continue;
    $dir = "../locale/$lang/LC_MESSAGES";
    if (!is_dir($dir)) {
        mkdir($dir);
    }
    $handlers[$lang] = fopen("$dir/messages.po", 'a');
    fwrite($handlers[$lang], "\n\n#FICHE DE FAB\n\n");
}

foreach ($queries as $query) {
    $stmt = \Exaprint\DAL\DB::get('stage')->query($query);
    while ($row = $stmt->fetchObject()) {
        $id = $row->label . $row->pk;
        echo $id . PHP_EOL;
        if (!isset($keys[$id])) {
            $keys[$id] = [
                $id
            ];
        }
        if (isset($langs[$row->lang]) && !is_null($langs[$row->lang])) {
            $keys[$id][$langs[$row->lang]] = $row->value;
        }
    }
}

foreach ($keys as $msgid => $values) {
    foreach ($values as $lang => $msgstr) {
        if ($lang && $handlers[$lang]) {
            fwrite($handlers[$lang], sprintf('msgid "%s"' . PHP_EOL, $msgid));
            fwrite($handlers[$lang], sprintf('msgstr "%s"' . PHP_EOL, $msgstr));
            fwrite($handlers[$lang], PHP_EOL);
        } else {
            var_dump($handlers[$lang]);
            var_dump($lang);
            var_dump($values);
        }
    }
}

foreach ($handlers as $handler) {
    if ($handler) fclose($handler);
}