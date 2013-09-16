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

        /*$tbl_client_adresselivraison = [
            'client.address.title' => 'Intitule',
            'client.address.line1' => 'AdresseLivraison1',
            'client.address.line2' => 'AdresseLivraison2',
            'client.address.line3' => 'AdresseLivraison3',
            'client.address.postcode' => 'CodePostalLivraison',
            'client.address.city' => 'VilleLivraison'
        ];
        $tbl_commmande_ligne = [
            'product.designation' => 'Libelle',
            'product.priceHT' => 'MontantPrixAchat'
        ];
        $tbl_produit = [
            'product.id' => 'IDProduit',
            'product.famille' => 'IDProduitFamilleProduit',
            'product.reference' => 'Code'
        ];

        $sql = new Select();
        $sql->cols([
            'id' => 'IDCommande',
            'number' => 'NumeroCommande',
            'reference' => 'ReferenceClient',
            'date' => 'DateAjout',
            'montantHT' => 'MontantHT',
            'montantTVA' => 'MontantTVA',
            'montantTTC' => 'MontantTTC'
        ]);
        $sql->client()->cols([
            'client.id' => 'IDClient',
            'client.code' => 'CodeClient',
            'client.email' => 'MailFacturation',
            'client.company_name' => 'RaisonSociale'
        ]);
        $sql->filter()->eq('IDCommande', $id);

        // JOINS
        $sql->join('TBL_CLIENT_ADRESSELIVRAISON', 'IDClientAdresseLivraison', 'IDClientAdresseLivraison', $tbl_client_adresselivraison);

        $l1 = $sql->join('TBL_COMMANDE_LIGNE', 'IDCommande', 'IDCommande', $tbl_commmande_ligne, null, 'INNER');
        $l2 = $l1->join('TBL_PRODUIT', 'IDProduit', 'IDProduit', $tbl_produit, null, 'INNER');
        $l3 = $l2->join('TBL_PRODUIT_FAMILLE_PRODUIT', 'IDProduitFamilleProduit', 'IDProduitFamilleProduit', [], null, 'INNER');
        $l4 = $l3->join('TBL_PRODUIT_FAMILLE_ARTICLES', 'IDProduitFamilleArticles', 'IDProduitFamilleArticles', [], null, 'INNER');
        $select = new Select('TBL_PRODUIT_FAMILLE_ARTICLES_TRAD');
        $select->setJoinType('LEFT OUTER');
        $select->joinCondition()
            ->equals(new Column('IDProduitFamilleArticles', 'TBL_PRODUIT_FAMILLE_ARTICLES_TRAD'), new Column('IDProduitFamilleArticles', 'TBL_PRODUIT_FAMILLE_ARTICLES'))
            ->equals(new Column('IDLangue', 'TBL_PRODUIT_FAMILLE_ARTICLES_TRAD'), $IDLangue);
        $l4->addJoin($select);
        $select = new Select('TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION');
        $select->setJoinType('LEFT OUTER');
        $select->joinCondition()
            ->equals(new Column('IDProduitFamilleProduit', 'TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION'), new Column('IDProduitFamilleProduit', 'TBL_PRODUIT_FAMILLE_PRODUIT'))
            ->equals(new Column('IDLangue', 'TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION'), $IDLangue);
        $l3->addJoin($select);
        $l3 = $l2->join('TBL_PRODUIT_UNITE_TARIF', 'IDProduitUniteTarif', 'IDProduitUniteTarif', [], null, 'INNER');
        $select = new Select('TBL_PRODUIT_UNITE_TARIF_TRAD');
        $select->setJoinType('LEFT OUTER');
        $select->joinCondition()
            ->equals(new Column('IDProduitUniteTarif', 'TBL_PRODUIT_UNITE_TARIF_TRAD'), new Column('IDProduitUniteTarif', 'TBL_PRODUIT_FAMILLE_PRODUIT'))
            ->equals(new Column('IDLangue', 'TBL_PRODUIT_UNITE_TARIF_TRAD'), $IDLangue);
        $l3->addJoin($select);
        $l3 = $l2->join('TBL_PRODUIT_OFFRE', 'IDProduitOffre', 'IDProduitOffre', [], null, 'LEFT OUTER');
        $select = new Select('TBL_PRODUIT_OFFRE_TRAD');
        $select->setJoinType('LEFT OUTER');
        $select->cols(['designation.offer' => 'LibelleTraduit']);
        $select->joinCondition()
            ->equals(new Column('IDProduitOffre', 'TBL_PRODUIT_OFFRE_TRAD'), new Column('IDProduitOffre', 'TBL_PRODUIT_OFFRE'))
            ->equals(new Column('IDLangue', 'TBL_PRODUIT_OFFRE_TRAD'), $IDLangue);
        $l3->addJoin($select);
        $select = new Select('TBL_PRODUIT_LIBELLE_FRONT_TRAD');
        $select->setJoinType('LEFT OUTER');
        $select->joinCondition()
            ->equals(new Column('IDProduit', 'TBL_PRODUIT_LIBELLE_FRONT_TRAD'), new Column('IDProduit', 'TBL_PRODUIT'))
            ->equals(new Column('IDLangue', 'TBL_PRODUIT_LIBELLE_FRONT_TRAD'), 1);
        $l2->addJoin($select);
        $sql->join('TBL_DEVIS', 'IDDevis', 'IDDevis', ['unknownOptions' => 'OptionsNonReconnues'], null, 'LEFT OUTER');*/

        $select = "
            SELECT
                TBL_COMMANDE.IDCommande AS [order.id],
                TBL_COMMANDE.MontantTTC AS [project.ati_amount],
                TBL_COMMANDE.MontantTVA AS [project.vat_amount],
                TBL_COMMANDE.MontantHT AS [project.et_amount],
                TBL_COMMANDE.ReferenceClient AS [order.reference],
                TBL_COMMANDE.DateAjout AS [order.creation_date],
                TBL_COMMANDE.MontantTTC AS [order.ati_amount],
                TBL_COMMANDE.MontantTVA AS [order.vat_amount],
                TBL_COMMANDE.MontantHT AS [order.et_amount],
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
            WHERE
                (TBL_COMMANDE.IDCommande = $IDCommande)";

        //echo($select);

        if ($r = DB::get()->query($select)) {
            $combinator = new ResultsCombinator();
            $data = $combinator->combine(
                $r->fetchAll(\PDO::FETCH_ASSOC),
                "order.id",
                array(
                    "order.fees" => "id",
                    "product.options" => "id"
                )
            );

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
        return "resources/order/receipt.twig";
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
        return Helper::$current . "/footer.html";
    }



}