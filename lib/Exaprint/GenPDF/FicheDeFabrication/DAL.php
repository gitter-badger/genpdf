<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\DAL\DB;

class DAL
{

    public static function getPlanche($IDPlanche)
    {

        $stmt = DB::get()->prepare('SELECT * FROM dbo.VUE_PDF_PLANCHE WHERE IDPlanche = :IDPlanche');
        $stmt->execute(['IDPlanche' => $IDPlanche]);
        if ($dto = $stmt->fetch(DB::FETCH_ASSOC)) {
            self::formatDate($dto, 'ExpeSansFaconnage');
            self::formatDate($dto, 'ExpeAvecFaconnage');
            $dto['commandes'] = self::getCommandes($IDPlanche);
        }
        return $dto;
    }

    protected static function getCommandes($IDPlanche)
    {
        $stmt = DB::get()->prepare('SELECT * FROM dbo.VUE_PDF_COMMANDE WHERE IDPlanche = :IDPlanche');
        $stmt->execute(['IDPlanche' => $IDPlanche]);
        $commandes = [];
        while ($commande = $stmt->fetch(DB::FETCH_ASSOC)) {
            self::formatDate($commande, 'DateExpedition');
            self::formatDate($commande, 'DateImperatif');
            self::formatFloat($commande, 'LargeurOuvert');
            self::formatFloat($commande, 'LongueurOuvert');
            self::formatFloat($commande, 'LargeurFerme');
            self::formatFloat($commande, 'LongueurFerme');
            $commande['Visuels'] = self::getFichiers($commande);
            $commandes[]          = $commande;

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
                $fichier['href'] = 'http://fileserver.exaprint.fr' . $fichier['href'];
                $fichiers[] = $fichier;
            }
        }

        return $fichiers;
    }

} 