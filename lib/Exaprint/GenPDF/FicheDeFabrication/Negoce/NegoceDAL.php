<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\DAL\DB;
use Exaprint\GenPDF\FicheDeFabrication\Common\DAL;

class NegoceDAL extends DAL
{

    /**
     * Récupère la liste des options valeur
     * @param $IDPlanche
     * @return array
     */
    public static function getFamilleCodification($IDPlanche)
    {
        $stmt = DB::get()->prepare('
                SELECT
                  TOP 1
                  trad.libelletraduit         AS famille
                  , famille.referenceproduit  AS codification
                FROM TBL_PLANCHE p
                  JOIN TBL_PLANCHE_TL_COMMANDE tl ON tl.idplanche = p.idplanche
                  JOIN TBL_COMMANDE_LIGNE ligne ON ligne.idcommande = tl.idcommande
                  JOIN TBL_PRODUIT produit ON produit.idproduit = ligne.idproduit
                  JOIN TBL_PRODUIT_FAMILLE_PRODUIT famille ON famille.idproduitfamilleproduit = produit.IDProduitFamilleProduit
                  JOIN TBL_PRODUIT_FAMILLE_ARTICLES_TRAD trad ON trad.idproduitfamillearticles = famille.idproduitfamillearticles
                WHERE p.idplanche = :IDPlanche AND idlangue = 1
        ');

        $stmt->execute(['IDPlanche' => $IDPlanche]);
        return $stmt->fetch();
    }

    /**
     * Récupère la liste des options valeur
     * @param $IDPlanche
     * @return array
     */
    public static function getFinitions($IDPlanche)
    {
        $stmt = DB::get()->prepare('
                SELECT
                  v.idproduitoption
                  , v.idproduitoptionvaleur
                  , trad.libelletraduit as libelleOption
                  , v.libelletraduit as libelleValeur
                FROM vue_produit_option_valeur v
                  JOIN TBL_PRODUIT_OPTION_TRAD trad ON trad.idproduitoption = v.idproduitoption AND trad.idlangue = 1
                  JOIN TBL_COMMANDE_LIGNE l ON l.idproduit = v.idproduit
                  JOIN TBL_PLANCHE_TL_COMMANDE tl ON tl.IDCommande = l.idcommande
                WHERE v.idlangue = 1 AND tl.idplanche = :IDPlanche
                ORDER BY v.idproduitoption, v.idproduitoptionvaleur
        ');

        $stmt->execute(['IDPlanche' => $IDPlanche]);
        return $stmt->fetchAll();
    }

} 