<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\Commande\Select;
use Exaprint\DAL\DB;
use RBM\ResultsCombinator\ResultsCombinator;
use Locale\Helper;
use RBM\SqlQuery\Column;

class OrderReceipt implements IResource
{
    protected $_data;


    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($IDCommande)
    {
        $IDLangue = 1;

        $select = "
            SELECT
                TBL_COMMANDE.IDCommande AS [order.id],
                TBL_COMMANDE.TauxTVA AS [project.tva],
                TBL_COMMANDE.MontantTTC AS [project.ati_amount],
                TBL_COMMANDE.MontantTVA AS [project.vat_amount],
                TBL_COMMANDE.MontantHT AS [project.et_amount],
                TBL_COMMANDE.ReferenceClient AS [order.reference],
                TBL_COMMANDE.DateCommande AS [order.creation_date],
                TBL_DEVIS.IDDevis AS [order.devis_id],
                TBL_DEVIS.Intitule AS [order.devis_reference],
                [regulation].LibelleTraduit AS [order.regulation],
                TBL_COMMANDE.Solde AS [order.balance],
                TBL_COMMANDE.IDClient AS [client.id],
                [client].RaisonSociale AS [client.company_name],
                [client].IdTva AS [client.id_tva],
                [contact].NomContact AS [client.contact_name],
                [contact].PrenomContact AS [client.contact_forename],
                [contact].CiviliteContact AS [client.civility],
                TBL_COMMANDE_LIGNE.Quantite AS [order.quantity],
                TBL_COMMANDE_LIGNE.Prix AS [product.et_amount],
                TBL_COMMANDE_LIGNE.MontantTVAPrix AS [product.vat_amount],
                TBL_COMMANDE_LIGNE.MontantTTCPrix AS [product.ati_amount],
                TBL_PRODUIT_UNITE_TARIF_TRAD.SigleAffichage AS [order.unit.abbr],
                TBL_PRODUIT_UNITE_TARIF_TRAD.LibelleTraduit AS [order.unit.label],
                TBL_PRODUIT_LIBELLE_FRONT_TRAD.LibelleTraduit AS [product.name],
                TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.LibelleTraduit AS [product.subfamily],
                TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.LibelleTraduit AS [product.family],
                TBL_FRAIS.IDFrais AS [order.fees.id],
                TBL_FRAIS.Quantite AS [order.fees.quantity],
                TBL_FRAIS.MontantUnitaireHT AS [order.fees.unit_amount],
                TBL_FRAIS.MontantTTC AS [order.fees.ati_amount],
                TBL_FRAIS.MontantHT AS [order.fees.et_amount],
                TBL_FRAIS.MontantTVA AS [order.fees.vat_amount],
                TBL_FRAIS_TYPE_FRAIS_TRAD.LibelleTraduit AS [order.fees.type],
                Options.IDProduitOption AS [product.options.id],
                Options.ProduitOption AS [product.options.label],
                CASE
                    WHEN (Options.ProduitOptionValeur IS NULL)
                    THEN CAST (Options.Valeur AS VARCHAR)
                    ELSE Options.ProduitOptionValeur
                END AS [product.options.value],
                Options.Sigle AS [product.options.unit],
                [delivery].NomDestinataire AS [delivery.recipient],
                [delivery].NomContact AS [delivery.contact_name],
                [delivery].AdresseLivraison1 AS [delivery.address.line1],
                [delivery].AdresseLivraison2 AS [delivery.address.line2],
                [delivery].AdresseLivraison3 AS [delivery.address.line3],
                [delivery].Commentaire AS [delivery.comment],
                TBL_PAYS.LibellePays AS [delivery.country],
                CASE
                    WHEN ([delivery].IDVille IS NULL)
                    THEN [delivery].VilleLivraison
                    ELSE [delivery_city].LibelleVille
                END AS [delivery.address.city],
                CASE
                    WHEN ([delivery].IDVille IS NULL)
                    THEN [delivery].CodePostalLivraison
                    ELSE [delivery_city].CodePostal
                END AS [delivery.address.post_code]
            FROM
                TBL_COMMANDE
            JOIN TBL_CLIENT AS [client] ON ([client].IDClient = TBL_COMMANDE.IDClient)
            JOIN TBL_CLIENT_CONTACT AS [contact] ON ([contact].IDClient = TBL_COMMANDE.IDClient AND ISNULL([contact].ContactPrincipal, 0) = 1)
            JOIN TBL_COMMANDE_LIGNE ON (TBL_COMMANDE_LIGNE.IDCommande = TBL_COMMANDE.IDCommande)
            CROSS APPLY dbo.f_OptionsCommande (TBL_COMMANDE.IDCommande, $IDLangue) AS Options
            LEFT JOIN TBL_FRAIS ON (TBL_FRAIS.IDCommande = TBL_COMMANDE.IDCommande)
            JOIN TBL_PRODUIT ON (TBL_PRODUIT.IDProduit = TBL_COMMANDE_LIGNE.IDProduit)
            JOIN TBL_PRODUIT_UNITE_TARIF_TRAD ON (TBL_PRODUIT.IDProduitUniteTarif = TBL_PRODUIT_UNITE_TARIF_TRAD.IDProduitUniteTarif AND IDLangue = $IDLangue)
            JOIN TBL_PRODUIT_LIBELLE_FRONT_TRAD ON (TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDProduit = TBL_PRODUIT.IDProduit)
            AND (TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDLangue = $IDLangue)
            JOIN TBL_PRODUIT_FAMILLE_PRODUIT ON (TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit = TBL_PRODUIT.IDProduitFamilleProduit)
            JOIN TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION ON (TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.IDProduitFamilleProduit = TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit)
            AND (TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.IDLangue = $IDLangue)
            JOIN TBL_PRODUIT_FAMILLE_ARTICLES ON (TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles = TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleArticles)
            JOIN TBL_PRODUIT_FAMILLE_ARTICLES_TRAD ON (TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.IDProduitFamilleArticles = TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles)
            AND (TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.IDLangue = $IDLangue)
            LEFT JOIN TBL_FRAIS_TYPE_FRAIS_TRAD ON (TBL_FRAIS_TYPE_FRAIS_TRAD.IDFraisTypeFrais = TBL_FRAIS.IDFraisTypeFrais)
            AND (TBL_FRAIS_TYPE_FRAIS_TRAD.IDLangue = $IDLangue)
            JOIN TBL_CLIENT_ADRESSELIVRAISON AS [delivery] ON (TBL_COMMANDE.IDClientAdresseLivraison = [delivery].IDClientAdresseLivraison)
            LEFT JOIN TBL_VILLE AS [delivery_city] ON [delivery_city].IDVille = [delivery].IDVille
            LEFT JOIN TBL_PAYS ON TBL_PAYS.IDPays = [delivery].IDPays
            JOIN TBL_MODEREGLEMENT_TRAD AS [regulation] ON (TBL_COMMANDE.IDModeReglement = [regulation].IDModeReglement AND [regulation].IDLangue = $IDLangue)
            LEFT JOIN TBL_DEVIS ON (TBL_COMMANDE.IDDevis = TBL_DEVIS.IDDevis)
            WHERE
                (TBL_COMMANDE.IDCommande = $IDCommande)";

        //echo($select);

        if ($r = DB::get()->query($select)) {
            $combinator = new ResultsCombinator();
            $combinator->setIdentifier("order.id");
            $combinator->addGroup("order.fees", "id");
            $combinator->addGroup("product.options", "id");
            $data = $combinator->process($r->fetchAll(\PDO::FETCH_ASSOC));

            if(isset($data[$IDCommande])){
                $this->_data = $data[$IDCommande];
                return true;
            }
        } else {

        }
        return null;

    }

    /**
     * @return array
     */
    public function getData()
    {
        return (array) $this->_data;
    }

    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        return "resources/order-receipt.twig";
    }

    /**
     * @return string
     */
    public function getXml()
    {
        //return $this->_xml->asXML();
    }


    public function getHeader()
    {
        return Helper::$current . "/header.html";
    }

    public function getFooter()
    {
        return Helper::$current . "/footer.svg";
    }



}