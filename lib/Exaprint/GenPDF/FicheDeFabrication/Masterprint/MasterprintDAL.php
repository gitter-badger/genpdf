<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\DAL\DB;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\DAL;

class MasterprintDAL extends DAL
{

    /**
     * @param $orderId
     * @return mixed
     */
    public static function cleanOrderComment($orderId)
    {
        $stmt = DB::get()->prepare("
            SELECT dbo.f_sCommentairePAOCommandePartenaire(c.CommentairePAO, cp.CommentairePAO)
            FROM TBL_commande c
              JOIN tbl_commande_partenaire cp ON cp.IDCommandePartenaire = c.IDCommandePartenaire
            WHERE c.IDCommande = :IDCommande
        ");

        $stmt->execute([
            'IDCommande' => intval($orderId)
        ]);

        return $stmt->fetchColumn();
    }

    /**
     * @param $planche
     * @return array
     */
    public static function getDevis($planche) {
        preg_match('/#(.*)#/', $planche['commandes'][0]['CommentairePAO'], $infos);

        $stmt = DB::get()->prepare("
            SELECT
              NumeroDevisAtelier,
              Nom AS NomAtelier
            FROM Sc_Masterprint.TBL_DEVIS d
              JOIN TBL_ATELIER a ON a.IDAtelier = d.IDAtelier
            WHERE d.NumeroDevis = :NumeroDevis
        ");

        $stmt->execute([
            'NumeroDevis' => $infos[1]
        ]);

        return $stmt->fetchObject();
    }

} 