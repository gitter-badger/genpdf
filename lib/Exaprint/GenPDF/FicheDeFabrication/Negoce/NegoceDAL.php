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
                  *
                FROM
                  dbo.ft_FicheFabNegoceAffichageBlocs(:IDPlanche, 1)
                ORDER BY Bloc, Ligne, Encadre
        ');

        $stmt->execute(['IDPlanche' => $IDPlanche]);
        return $stmt->fetchAll();
    }

} 