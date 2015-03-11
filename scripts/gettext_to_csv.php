<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/11/2014
 * Time: 09:45
 */

use Exaprint\DAL\DB;

require '../vendor/autoload.php';

$_SERVER['exaprint_env'] = 'prod';

$select = "
    SELECT DISTINCT
      idproduitoptionvaleur
      , idlangue
      , libelletraduit
    FROM VUE_PRODUIT_OPTION_VALEUR pov
      JOIN TBL_PRODUIT p ON pov.IDProduit = p.IDProduit
    WHERE idlangue IN (1, 2, 5, 6, 9)
          AND p.IDproduitOffre = 1
          AND ISNULL(p.EstSupp, 0) = 0
";
$stmt   = DB::get()->query($select);
$dto    = $stmt->fetchAll();

$translations = [];

foreach ($dto as $d) {
    $tab      = [
        1 => 1,
        2 => 2,
        5 => 3,
        6 => 4,
        9 => 5,
    ];
    $position = $tab[$d->idlangue];

    $id = 'valeur_' . $d->idproduitoptionvaleur;

    if (!array_key_exists($id, $translations)) {
        $translations[$id] = [$id, "", "", "", ""];
    }
    $translations[$id][$position] = $d->libelletraduit;
}

$csv = fopen('output.csv', 'w+');

fputcsv($csv, ["", "FR", "ES", "IT", "PT", "UK"], ",", '"');
foreach ($translations as $translation) {
    fputcsv($csv, $translation, ",", '"');
}