<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\DAL\DB;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\DAL;

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
     * @param $IDCommande
     * @return mixed
     */
    public static function displayDetails($commande)
    {
        $stmt = DB::get()->prepare("
            SELECT
                Options.ProduitOption                                    AS label
              , CASE
                WHEN (Options.ProduitOptionValeur IS NULL)
                THEN CAST(Options.Valeur AS VARCHAR)
                ELSE Options.ProduitOptionValeur
                END                                                      AS value
              , Options.Sigle                                            AS unit
              , dbo.f_RetourneFormatCommande(TBL_COMMANDE.IDCommande, 1) AS format
              , TBL_PRODUIT_LIBELLE_FRONT_TRAD.LibelleTraduit            AS libelleProduit
            FROM TBL_COMMANDE
              JOIN TBL_CLIENT AS [client] ON ([client].IDClient = TBL_COMMANDE.IDClient)
              JOIN TBL_COMMANDE_LIGNE ON (TBL_COMMANDE_LIGNE.IDCommande = TBL_COMMANDE.IDCommande)
              CROSS APPLY dbo.f_OptionsCommande(TBL_COMMANDE.IDCommande, client.IDLangueMailing) AS Options
              JOIN TBL_PRODUIT ON (TBL_PRODUIT.IDProduit = TBL_COMMANDE_LIGNE.IDProduit)
              JOIN TBL_PRODUIT_FAMILLE_PRODUIT
                ON (TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit = TBL_PRODUIT.IDProduitFamilleProduit)
              JOIN TBL_PRODUIT_FAMILLE_ARTICLES
                ON (TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles = TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleArticles)
                   AND TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles NOT IN (126, 134, 135, 136, 137)
              LEFT JOIN TBL_PRODUIT_LIBELLE_FRONT_TRAD
                ON TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDProduit = TBL_PRODUIT.IDProduit
                   AND TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDLangue = 1
            WHERE
              (TBL_COMMANDE.IDCommande = :IDCommande)
              AND Options.IDProduitOption != 97");

        $stmt->execute(['IDCommande' => $commande['IDCommande']]);
        $options = $stmt->fetchAll();

        $html = [];

        if (count($options) > 0) {
            if (!empty($options[0]->libelleProduit)) {
                $html[] = 'Libellé produit : ' . $options[0]->libelleProduit;
            }
            $html[] = 'Code produit : ' . $commande['CodeProduit'];
            $html[] = 'Format commandé : ' . $options[0]->format;
        }

        foreach ($options as $option) {
            $html[] = $option->label . ': ' . $option->value . ' ' . $option->unit;
        }
        return $html;
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
                ORDER BY Bloc, Ligne, Encadre, EstRecto DESC
        ');

        $stmt->execute(['IDPlanche' => $IDPlanche]);
        return $stmt->fetchAll();
    }

} 