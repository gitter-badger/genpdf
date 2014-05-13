<?php

require '../vendor/autoload.php';
require '/Users/lucien/Sites/exaprint/env.php';

$_SERVER['exaprint_db_env'] = 'stage';

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
        ORDER BY IDProduitOption",
    "SELECT
        'action_st_' as label
        pov.IDProduitOptionValeur as pk,
        LibelleTraduit as value,
        IDLangue as lang
    "

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
unlink('../cache/fiche-fab-keys.php');
$f = fopen('../cache/fiche-fab-keys.php', 'w+');
fwrite($f, "<?php \n");
foreach ($keys as $msgid => $values) {
   fwrite($f, "_('$msgid');". PHP_EOL);
}
