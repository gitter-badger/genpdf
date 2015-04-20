<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


use Exaprint\DAL\DB;
use RBM\TaggedCache\Duration;
use RBM\TaggedCache\Storage\SQLite;
use RBM\TaggedCache\TaggedCache;

class DAL
{

    const ACTION_VERNIS_UV = 21;

    protected static $_cache;

    /**
     * @return TaggedCache
     */
    protected static function _cache()
    {
        if (isset(self::$_cache))
            return self::$_cache;

        $store        = new SQLite('../cache/fiche-fab.sqlite');
        self::$_cache = new TaggedCache();

        self::$_cache->dataStore  = $store;
        self::$_cache->indexStore = $store;
        self::$_cache->tagsStore  = $store;

        return self::$_cache;
    }

    /**
     * @param $IDPlanche
     * @return bool|mixed
     */
    public static function getPlanche($IDPlanche)
    {
        if ($p = self::_cache()->get("planche_$IDPlanche")) {
            return $p;
        }

        $stmt = DB::get()->prepare('SELECT * FROM dbo.VUE_PDF_PLANCHE WHERE IDPlanche = :IDPlanche');
        if ($stmt->execute(['IDPlanche' => $IDPlanche])) {
            $subcontracts = $stmt->fetchAll(DB::FETCH_ASSOC);
            $dto = $subcontracts[0];
            self::formatDate($dto, 'ExpeSansFaconnage');
            self::formatDate($dto, 'ExpeAvecFaconnage');
            $dto['commandes']            = self::getCommandes($IDPlanche);
            $dto['ActionsSousTraitance'] = [];
            foreach ($subcontracts as $subcontract) {
                $action['IDPlancheSousTraitance'] = $subcontract['IDPlancheSousTraitance'];
                $action['NomAtelierSousTraitance'] = $subcontract['NomAtelierSousTraitance'];
                if ($subcontract['EstPrincipale']) {
                    $action['actions'] = self::getActions($subcontract['IDPlancheSousTraitance']);
                } else if ($subcontract['EstSousTraitance']) {
                    $action['actions'] = self::getActions($subcontract['IDPlanche']);
                } else {
                    // planche normale
                }
                $dto['ActionsSousTraitance'][] = $action;
            }

            if ($dto['EstSousTraitance']) {
                $ColiseInfos = self::getColiseInfos($dto['IDPlanche'], $dto['IDPlanchePrincipale']);
                if (count($ColiseInfos) == 1) {
                    $dto['ColiseDateExpe']   = date('d/m', strtotime($ColiseInfos[0]['ColiseDateExpe']));
                    $dto['ColiseIDPlanche']  = $ColiseInfos[0]['ColiseIDPlanche'];
                    $dto['ColiseNomAtelier'] = $ColiseInfos[0]['ColiseNomAtelier'];
                }
            }

            self::_cache()->save("planche_$IDPlanche", $dto, Duration::get(2, Duration::MINUTE), ['planche']);
            return $dto;
        } else {
            var_dump($stmt->errorInfo());
            echo $stmt->queryString;
        }
    }

    /**
     * Récupère la liste des actions de sous-traitance associées à la planche (découpe outil, vernis UV, etc.)
     * @param $IDPlancheSousTraitance
     * @return array
     */
    protected static function getActions($IDPlancheSousTraitance)
    {
        $stmt = DB::get()->prepare('
              SELECT TBL_PLANCHE_ACTION.IDPlancheAction
              FROM TBL_PLANCHE_ACTION
              LEFT OUTER JOIN TBL_PLANCHE_ACTION_TRAD
              ON TBL_PLANCHE_ACTION.IDPlancheAction = TBL_PLANCHE_ACTION_TRAD.IDPlancheAction
              AND TBL_PLANCHE_ACTION_TRAD.IDLangue = 1
              LEFT OUTER JOIN TBL_PLANCHE_TL_PLANCHE_ACTION
              ON TBL_PLANCHE_ACTION.IDPlancheAction = TBL_PLANCHE_TL_PLANCHE_ACTION.IDPlancheAction
              AND TBL_PLANCHE_TL_PLANCHE_ACTION.IDPlanche = :IDPlancheSousTraitance
              WHERE TBL_PLANCHE_ACTION.Actif = 1 AND TBL_PLANCHE_TL_PLANCHE_ACTION.IDPlancheAction IS NOT NULL
        ');

        $stmt->execute(['IDPlancheSousTraitance' => $IDPlancheSousTraitance]);
        return $stmt->fetchAll(DB::FETCH_COLUMN);
    }

    /**
     * @param $IDPlanchePrincipale
     * @param $IDPlancheSousTraitance
     * @return array
     */
    protected static function getColiseInfos($IDPlanchePrincipale, $IDPlancheSousTraitance)
    {
        $stmt = DB::get()->prepare('
              SELECT
                    CASE WHEN p.ExpeAvecFaconnage IS NULL
                    THEN
                      p.ExpeSansFaconnage
                    ELSE (
                      CASE WHEN p.ExpeSansFaconnage < p.ExpeAvecFaconnage
                      THEN p.ExpeSansFaconnage
                      ELSE p.ExpeAvecFaconnage
                      END) END   AS ColiseDateExpe
                  , c.IDPlanche  AS ColiseIDPlanche
                  , p.NomAtelier AS ColiseNomAtelier
                FROM VUE_PDF_COMMANDE c
                  LEFT JOIN VUE_PDF_PLANCHE p ON p.IDPlanche = c.IDPlanche
                WHERE (c.IDPlanche = :IDPlanchePrincipale OR c.IDPlanche = :IDPlancheSousTraitance) AND EstColise = 1
                GROUP BY c.IDPlanche, p.NomAtelier, p.ExpeSansFaconnage, p.ExpeAvecFaconnage
        ');

        $stmt->execute([
            'IDPlanchePrincipale'    => $IDPlanchePrincipale,
            'IDPlancheSousTraitance' => $IDPlancheSousTraitance
        ]);
        return $stmt->fetchAll(DB::FETCH_ASSOC);
    }

    protected static function getCommandes($IDPlanche)
    {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $stmt = DB::get()->prepare('
            SELECT *
            FROM dbo.VUE_PDF_COMMANDE
            WHERE IDPlanche = :IDPlanche
            ORDER BY Pliage DESC, Rainage DESC, Decoupe DESC, DecoupeALaForme DESC, Perforation DESC
        ');
        $stmt->execute(['IDPlanche' => $IDPlanche]);
        $groupages = [];
        $commandes = [];
        $i         = 0;
        while ($commande = $stmt->fetch(DB::FETCH_ASSOC)) {
            self::formatDate($commande, 'DateExpedition');
            self::formatDate($commande, 'DateImperatif');
            self::formatFloat($commande, 'LargeurOuvert');
            self::formatFloat($commande, 'LongueurOuvert');
            self::formatFloat($commande, 'LargeurFerme');
            self::formatFloat($commande, 'LongueurFerme');
            $commande['Fichiers'] = self::getFichiers($commande);
            $commandes[]          = $commande;
            if (!isset($groupages[$commande['IDClientAdresseLivraison']])) {
                $groupages[$commande['IDClientAdresseLivraison']] = [$i];
            } else {
                $groupages[$commande['IDClientAdresseLivraison']][] = $i;
            }
            $i++;
        }

        $i = 0; //todo remplacer par un for()
        foreach ($groupages as $cle => $groupage) {
            if (count($groupage) > 1) {
                foreach ($groupage as $index) {
                    $commandes[$index]['Groupage'] = $alphabet[$i];
                }
                $i++;
            }

        }

        return $commandes;
    }

    protected static function formatDate(& $item, $prop)
    {
        if ($d = $item[$prop]) $item[$prop] = date('d/m', strtotime($d));
    }

    protected static function formatFloat(& $item, $prop)
    {
        if ($f = $item[$prop]) $item[$prop] = floatval($f);
    }


    protected static function getFichiers($commande)
    {

        $raw             = file_get_contents("http://fileserver.exaprint.fr/orders/$commande[IDCommande]");
        $json            = json_decode($raw, true);
        $visuels         = [];
        $formeDeDecoupes = null;
        foreach ($json as $fichier) {
            if ($fichier['type'] == 'normalized'
                && $fichier['ext'] == 'jpg'
            ) {
                if (strpos($fichier["filename"], 'outil') === false) {
                    $visuels[$fichier['href']] = $fichier;
                } else {
                    if (strpos($fichier["filename"], 'outil_1-1.jpg') !== false) {
                        $formeDeDecoupes = $fichier;
                    }
                }
            }
        }

        return [
            'Visuels'        => array_values($visuels),
            'FormeDeDecoupe' => $formeDeDecoupes
        ];
    }

} 