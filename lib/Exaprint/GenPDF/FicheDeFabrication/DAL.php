<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\DAL\DB;
use RBM\TaggedCache\Duration;
use RBM\TaggedCache\Storage\SQLite;
use RBM\TaggedCache\TaggedCache;

class DAL
{

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

    public static function getPlanche($IDPlanche)
    {
        if ($p = self::_cache()->get("planche_$IDPlanche")) {
            return $p;
        }

        $stmt = DB::get()->prepare('SELECT * FROM dbo.VUE_PDF_PLANCHE WHERE IDPlanche = :IDPlanche');
        $stmt->execute(['IDPlanche' => $IDPlanche]);
        if ($dto = $stmt->fetch(DB::FETCH_ASSOC)) {
            self::formatDate($dto, 'ExpeSansFaconnage');
            self::formatDate($dto, 'ExpeAvecFaconnage');
            $dto['commandes'] = self::getCommandes($IDPlanche);
        }

        self::_cache()->save("planche_$IDPlanche", $dto, Duration::get(2, Duration::MINUTE), ['planche']);
        return $dto;
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
            $commande['Visuels'] = self::getFichiers($commande);
            $commandes[]         = $commande;
            if (!isset($groupages[$commande['IDClientAdresseLivraison']])) {
                $groupages[$commande['IDClientAdresseLivraison']] = [$i];
            } else {
                $groupages[$commande['IDClientAdresseLivraison']][] = $i;
            }
            $i++;
        }

        $i = 0;//todo remplacer par un for()
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

        $raw      = file_get_contents("http://fileserver.exaprint.fr/orders/$commande[IDCommande]");
        $json     = json_decode($raw, true);
        $fichiers = [];
        foreach ($json as $fichier) {
            if ($fichier['type'] == 'normalized' && $fichier['ext'] == 'jpg') {
                $fichiers[$fichier['href']] = $fichier;
            }
        }

        return array_values($fichiers);
    }

} 