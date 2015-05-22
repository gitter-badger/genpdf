<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Etiquette;


use Exaprint\DAL\DB;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\DAL;

class EtiquetteDAL extends DAL
{

    /**
     * @param $orderId
     * @return array
     */
    public static function getPartnersData($orderId)
    {
        $stmt = DB::get()->prepare("
            SELECT cp.DonneesProprietaires
            FROM TBL_COMMANDE c
              LEFT JOIN TBL_COMMANDE_PARTENAIRE cp ON cp.IDCommandePartenaire = c.IDCommandePartenaire
            WHERE c.IDCommande = :IDCommande
        ");

        $stmt->execute([
            'IDCommande' => intval($orderId)
        ]);

        return (array)simplexml_load_string($stmt->fetchColumn());
    }

} 