<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\Commande\Select;
use Exaprint\DAL\DB;
use RBM\ResultsCombinator\ResultsCombinator;
use Locale\Helper;
use RBM\SqlQuery\Column;

class OrderReceipt extends Resource implements IResource
{
    protected $_data;


    /**
     * @param $IDCommande
     * @return bool|null
     */
    public function fetchFromID($IDCommande)
    {
        $language = array_keys(Helper::$map, Helper::$current);
        $language = (count($language) > 0) ? $language[0]: 1;

        $select = "
            SELECT
              TBL_COMMANDE.*,
              TBL_COMMANDE.IDCommande                                           AS [order.id],
              TBL_COMMANDE.TauxTVA                                              AS [project.tva],
              TBL_COMMANDE.MontantTTC                                           AS [project.ati_amount],
              TBL_COMMANDE.MontantTVA                                           AS [project.vat_amount],
              TBL_COMMANDE.MontantHT                                            AS [project.et_amount],
              TBL_COMMANDE.ReferenceClient                                      AS [order.reference],
              TBL_COMMANDE.DateCommande                                         AS [order.creation_date],
              dbo.f_nDelaiProduit(
                  TBL_COMMANDE_LIGNE.IDProduit,
                  TBL_COMMANDE_LIGNE.Quantite,
                  client.IDSociete,
                  1) + (
                SELECT
              ValeurParametre
                FROM TBL_PARAMETRE_SOCIETE
                WHERE TBL_PARAMETRE_SOCIETE.NomParametre = 'DecalageDelai'
                      AND TBL_PARAMETRE_SOCIETE.IDSociete = client.IDSociete
              )                                                                 AS [order.lead_time],
              TBL_DEVIS.IDDevis                                                 AS [order.devis_id],
              TBL_DEVIS.Intitule                                                AS [order.devis_reference],
              [regulation].LibelleTraduit                                       AS [order.regulation],
              TBL_COMMANDE.Solde                                                AS [order.balance],
              TBL_COMMANDE.IDClient                                             AS [client.id],
              [client].RaisonSociale                                            AS [client.company_name],
              [client].IdTva                                                    AS [client.id_tva],
              [contact].NomContact                                              AS [client.contact_name],
              [contact].PrenomContact                                           AS [client.contact_forename],
              [contact].CiviliteContact                                         AS [client.civility],
              TBL_DEVIS.IDUtilisateurAgent                                      AS [agent.id],
              [agent].NomUtilisateur                                            AS [agent.name],
              [agent].PrenomUtilisateur                                         AS [agent.forename],
              TBL_COMMANDE_LIGNE.Quantite                                       AS [order.quantity],
              TBL_COMMANDE_LIGNE.Prix                                           AS [product.et_amount],
              TBL_COMMANDE_LIGNE.MontantTVAPrix                                 AS [product.vat_amount],
              TBL_COMMANDE_LIGNE.MontantTTCPrix                                 AS [product.ati_amount],
              TBL_COMMANDE_LIGNE.PoidsLigne                                     AS [product.weight],
              CASE
              WHEN TBL_PRODUIT.idproduitfamilleproduit = 538
              THEN 1
              ELSE 0
              END                                                               AS [product.estStickersRollers],
              TBL_PRODUIT_UNITE_TARIF_TRAD.SigleAffichage                       AS [order.unit.abbr],
              TBL_PRODUIT_UNITE_TARIF_TRAD.LibelleTraduit                       AS [order.unit.label],
              TBL_PRODUIT_LIBELLE_FRONT_TRAD.LibelleTraduit                     AS [product.name],
              TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.LibelleTraduit AS [product.subfamily],
              TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.LibelleTraduit                  AS [product.family],
              TBL_FRAIS.IDFrais                                                 AS [order.fees.id],
              TBL_FRAIS.Quantite                                                AS [order.fees.quantity],
              TBL_FRAIS.MontantUnitaireHT                                       AS [order.fees.unit_amount],
              TBL_FRAIS.MontantTTC                                              AS [order.fees.ati_amount],
              TBL_FRAIS.MontantHT                                               AS [order.fees.et_amount],
              TBL_FRAIS.MontantTVA                                              AS [order.fees.vat_amount],
              TBL_FRAIS_TYPE_FRAIS_TRAD.LibelleTraduit                          AS [order.fees.type],
              Options.IDProduitOption                                           AS [product.options.id],
              Options.ProduitOption                                             AS [product.options.label],
              CASE
              WHEN (Options.ProduitOptionValeur IS NULL)
              THEN CAST(Options.Valeur AS VARCHAR)
              ELSE Options.ProduitOptionValeur
              END                                                               AS [product.options.value],
              Options.Sigle                                                     AS [product.options.unit],
              [delivery].NomDestinataire                                        AS [delivery.recipient],
              [delivery].NomContact                                             AS [delivery.contact_name],
              [delivery].AdresseLivraison1                                      AS [delivery.address.line1],
              [delivery].AdresseLivraison2                                      AS [delivery.address.line2],
              [delivery].AdresseLivraison3                                      AS [delivery.address.line3],
              [delivery].Commentaire                                            AS [delivery.comment],
              TBL_PAYS_TRAD.LibelleTraduit                                      AS [delivery.country],
              CASE
              WHEN ([delivery].IDVille IS NULL)
              THEN [delivery].VilleLivraison
              ELSE [delivery_city].LibelleVille
              END                                                               AS [delivery.address.city],
              CASE
              WHEN ([delivery].IDVille IS NULL)
              THEN [delivery].CodePostalLivraison
              ELSE [delivery_city].CodePostal
              END                                                               AS [delivery.address.post_code],
              cert.Nom                                                          AS [cert.name],
              ISNULL(cert_societe.NumeroCertification, '')                      AS [cert.number],
              ISNULL(cert_societe.IndicationCertification, '')                  AS [cert.indication]
            FROM
              TBL_COMMANDE

              JOIN TBL_CLIENT AS [client]
                ON ([client].IDClient = TBL_COMMANDE.IDClient)

              JOIN TBL_CLIENT_CONTACT AS [contact]
                ON ([contact].IDClientContact = TBL_COMMANDE.IDClientContact)

              JOIN TBL_COMMANDE_LIGNE
                ON (TBL_COMMANDE_LIGNE.IDCommande = TBL_COMMANDE.IDCommande)

              CROSS APPLY dbo.f_OptionsCommande(TBL_COMMANDE.IDCommande, client.IDLangueMailing) AS Options

              LEFT JOIN TBL_FRAIS
                ON (TBL_FRAIS.IDCommande = TBL_COMMANDE.IDCommande)

              JOIN TBL_PRODUIT
                ON (TBL_PRODUIT.IDProduit = TBL_COMMANDE_LIGNE.IDProduit)

              JOIN TBL_PRODUIT_UNITE_TARIF_TRAD
                ON (TBL_PRODUIT.IDProduitUniteTarif = TBL_PRODUIT_UNITE_TARIF_TRAD.IDProduitUniteTarif AND
                    IDLangue = client.IDLangueMailing)
              LEFT JOIN TBL_PRODUIT_LIBELLE_FRONT_TRAD
                ON (TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDProduit = TBL_PRODUIT.IDProduit)
                   AND (TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDLangue = client.IDLangueMailing)

              JOIN TBL_PRODUIT_FAMILLE_PRODUIT
                ON (TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit = TBL_PRODUIT.IDProduitFamilleProduit)

              JOIN TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION
                ON (
                     TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.IDProduitFamilleProduit =
                     TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit)
                   AND (TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION.IDLangue = client.IDLangueMailing)

              JOIN TBL_PRODUIT_FAMILLE_ARTICLES
                ON (TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles = TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleArticles)

              JOIN TBL_PRODUIT_FAMILLE_ARTICLES_TRAD
                ON (TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.IDProduitFamilleArticles =
                    TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles)
                   AND (TBL_PRODUIT_FAMILLE_ARTICLES_TRAD.IDLangue = client.IDLangueMailing)

              LEFT JOIN TBL_FRAIS_TYPE_FRAIS_TRAD
                ON (TBL_FRAIS_TYPE_FRAIS_TRAD.IDFraisTypeFrais = TBL_FRAIS.IDFraisTypeFrais) AND
                   (TBL_FRAIS_TYPE_FRAIS_TRAD.IDLangue = client.IDLangueMailing)

              JOIN TBL_CLIENT_ADRESSELIVRAISON AS [delivery]
                ON (TBL_COMMANDE.IDClientAdresseLivraison = [delivery].IDClientAdresseLivraison)

              LEFT JOIN TBL_VILLE AS [delivery_city]
                ON [delivery_city].IDVille = [delivery].IDVille

              LEFT JOIN TBL_PAYS
                ON TBL_PAYS.IDPays = [delivery].IDPays

              LEFT JOIN TBL_PAYS_TRAD
                ON TBL_PAYS_TRAD.IDPays = TBL_PAYS.IDPays AND TBL_PAYS_TRAD.IDLangue = $language

              JOIN TBL_MODEREGLEMENT_TRAD AS [regulation]
                ON (TBL_COMMANDE.IDModeReglement = [regulation].IDModeReglement AND [regulation].IDLangue = client.IDLangueMailing)

              LEFT JOIN TBL_DEVIS
                ON (TBL_COMMANDE.IDDevis = TBL_DEVIS.IDDevis)

              LEFT JOIN TBL_UTILISATEUR AS [agent]
                ON (TBL_DEVIS.IDUtilisateurAgent = [agent].IDUtilisateur)

              LEFT JOIN TBL_COMMANDE_TL_CERTIFICATION_SOCIETE AS comm_cert_societe
                ON comm_cert_societe.IDCommande = TBL_COMMANDE.IDCommande

              LEFT JOIN TBL_CERTIFICATION_TL_SOCIETE AS cert_societe
                ON cert_societe.IDCertificationSociete = comm_cert_societe.IDCertificationSociete

              LEFT JOIN TBL_CERTIFICATION AS cert
                ON cert.IDCertification = cert_societe.IDCertification

            WHERE
              (TBL_COMMANDE.IDCommande = $IDCommande)
              AND (
                Options.IDProduitOption != 97
                OR TBL_PRODUIT_FAMILLE_ARTICLES.IDProduitFamilleArticles NOT IN (126, 134, 135, 136, 137)
              )";

        //echo($select);

        if ($r = DB::get()->query($select)) {
            $combinator = new ResultsCombinator();
            $combinator->setIdentifier("order.id");
            $combinator->addGroup("order.fees", "id");
            $combinator->addGroup("product.options", "id");
            $data = $combinator->process($r->fetchAll(\PDO::FETCH_ASSOC));

            if (isset($data[$IDCommande])) {
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
        return (array)$this->_data;
    }

    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        $filename = "resources/";
        if (\Locale\Helper::$current == 'en_GB') {
            $filename .= "countries/order-receipt.en.twig";
        } else if (\Locale\Helper::$current == 'es_ES') {
            $filename .= "countries/order-receipt.es.twig";
        } else if (\Locale\Helper::$current == 'fr_FR') {
            $filename .= "countries/order-receipt.fr.twig";
        } else if (\Locale\Helper::$current == 'it_IT') {
            $filename .= "countries/order-receipt.it.twig";
        } else if (\Locale\Helper::$current == 'pt_PT') {
            $filename .= "countries/order-receipt.pt.twig";
        } else {
            $filename .= "countries/order-receipt.fr.twig";
        }
        return $filename;
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
        return $this->_imageFolder . Helper::$current . "/header.html";
    }

    public function getFooter()
    {
        return $this->_imageFolder . Helper::$current . "/footer.html";
    }


}