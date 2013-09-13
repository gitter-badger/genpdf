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
    public function fetchFromID($id)
    {
        $IDLangue = 1;

        $db = DB::get();

        $tbl_client_adresselivraison = [
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
        $sql->join('TBL_DEVIS', 'IDDevis', 'IDDevis', ['unknownOptions' => 'OptionsNonReconnues'], null, 'LEFT OUTER');

        $query2 = "SELECT
        Case when TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit IS not null then TBL_PRODUIT_OPTION_TRAD_SAISIE.LibelleTraduit else TBL_PRODUIT_OPTION_TRAD_LISTE.LibelleTraduit end as LibelleOption, tbl_produit_option_valeur_trad.LibelleTraduit as LibelleCombo, dbo.f_sConversionUniteValeurOption(%1,TBL_PRODUIT_OPTION_SAISIE.IDProduitOption,
        CASE WHEN TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.IDCommandeLigne IS NOT NULL THEN TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.Valeur
        ELSE Case when TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit IS not null then TBL_PRODUIT_TL_OPTION_PRODUIT.ValeurMin end End,DEFAULT,DEFAULT,DEFAULT) AS ValeurMin, dbo.f_sConversionUniteValeurOption(%1,TBL_PRODUIT_OPTION_SAISIE.IDProduitOption,
        CASE WHEN TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.IDCommandeLigne IS NOT NULL THEN TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.Valeur
        ELSE Case when TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit IS not null then TBL_PRODUIT_TL_OPTION_PRODUIT.ValeurMax end End,DEFAULT,DEFAULT,DEFAULT) AS ValeurMax,
        Case when TBL_PRODUIT_OPTION_LISTE.Ordre is not null then TBL_PRODUIT_OPTION_LISTE.Ordre else TBL_PRODUIT_OPTION_SAISIE.Ordre end as ordre,
        Case when TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit IS null then TBL_PRODUIT_OPTION_LISTE.TypeProduitOption else TBL_PRODUIT_OPTION_SAISIE.TypeProduitOption end as Type,
        CASE WHEN TBL_PRODUIT_OPTION_SAISIE.IDProduitOption IS NULL THEN TBL_PRODUIT_OPTION_LISTE.ParticulariteOption ELSE TBL_PRODUIT_OPTION_SAISIE.ParticulariteOption END AS ParticulariteOption,TBL_PRODUIT_OPTION_VALEUR.ValeurNumParticularite AS ValeurNumParticularite

        FROM TBL_PRODUIT_TL_OPTION_PRODUIT
        INNER JOIN TBL_PRODUIT
        ON ( TBL_PRODUIT.IDProduit = TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduit)
        LEFT OUTER JOIN TBL_PRODUIT_TL_PRODUIT_OPTION_FAMILLE_PRODUIT AS TBL_PRODUIT_OPTION_PRODUIT_SAISIE
            INNER JOIN TBL_PRODUIT_OPTION AS TBL_PRODUIT_OPTION_SAISIE
                LEFT OUTER JOIN TBL_PRODUIT_OPTION_TRAD AS TBL_PRODUIT_OPTION_TRAD_SAISIE
                ON ( TBL_PRODUIT_OPTION_TRAD_SAISIE.IDProduitOption = TBL_PRODUIT_OPTION_SAISIE.IDProduitOption AND TBL_PRODUIT_OPTION_TRAD_SAISIE.IDLangue = %1)
            ON ( TBL_PRODUIT_OPTION_SAISIE.IDProduitOption = TBL_PRODUIT_OPTION_PRODUIT_SAISIE.IDProduitOption )
        ON ( TBL_PRODUIT_OPTION_PRODUIT_SAISIE.IDProduitTLProduitOptionFamilleProduit = TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit AND TBL_PRODUIT_OPTION_PRODUIT_SAISIE.Actif = 1)
        LEFT OUTER JOIN TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT
            INNER JOIN TBL_PRODUIT_TL_PRODUIT_OPTION_FAMILLE_PRODUIT AS TBL_PRODUIT_OPTION_PRODUIT_LISTE
            ON (TBL_PRODUIT_OPTION_PRODUIT_LISTE.IDProduitTLProduitOptionFamilleProduit = TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT.IDProduitTLProduitOptionFamilleProduit)
            INNER JOIN TBL_PRODUIT_OPTION_VALEUR
                INNER JOIN TBL_PRODUIT_OPTION AS TBL_PRODUIT_OPTION_LISTE
                    LEFT OUTER JOIN TBL_PRODUIT_OPTION_TRAD AS TBL_PRODUIT_OPTION_TRAD_LISTE
                    ON ( TBL_PRODUIT_OPTION_TRAD_LISTE.IDProduitOption = TBL_PRODUIT_OPTION_LISTE.IDProduitOption AND TBL_PRODUIT_OPTION_TRAD_LISTE.IDLangue = %1)
                ON ( TBL_PRODUIT_OPTION_LISTE.IDProduitOption = TBL_PRODUIT_OPTION_VALEUR.IDProduitOption )
                LEFT OUTER JOIN tbl_produit_option_valeur_trad
                ON ( tbl_produit_option_valeur_trad.IDProduitOptionValeur = TBL_PRODUIT_OPTION_VALEUR.IDProduitOptionValeur AND tbl_produit_option_valeur_trad.IDLangue = %1)
            ON ( TBL_PRODUIT_OPTION_VALEUR.IDProduitOptionValeur = TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT.IDProduitOptionValeur AND ISNULL(TBL_PRODUIT_OPTION_VALEUR.EstSans,0)=0)
        ON ( TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit = TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit AND TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT.Actif = 1 )
        LEFT OUTER JOIN TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT
            INNER JOIN TBL_COMMANDE_LIGNE
            ON (TBL_COMMANDE_LIGNE.IDCommandeLigne = TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.IDCommandeLigne AND ISNULL(TBL_COMMANDE_LIGNE.EstSupp,0) = 0)
        ON (TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT.IDProduitTLOptionProduit = TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLOptionProduit
        AND (
            (
                %4 <= 0 AND TBL_COMMANDE_LIGNE.IDCommande IS NULL
            )
            OR
            (
                %4 > 0 AND TBL_COMMANDE_LIGNE.IDCommande = %4
            )
        ))
    WHERE TBL_PRODUIT.EstSupp = 0
        AND ( TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit IS NULL OR TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit IS NULL )
        AND ( (TBL_PRODUIT_OPTION_PRODUIT_SAISIE.IDProduitFamilleProduit = %3 AND TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit IS null)
        OR (TBL_PRODUIT_OPTION_PRODUIT_LISTE.IDProduitFamilleProduit = %3 AND TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduitTLProduitOptionFamilleProduit is null))
        AND TBL_PRODUIT.IDProduit = %2
    ORDER BY
        TBL_PRODUIT_TL_OPTION_PRODUIT.IDProduit, Ordre";

        if ($stmt = $db->query($sql)) {

            $combinator = new ResultsCombinator();
            //$combinator->setClass('\Exaprint\Partners\Order\Order');
            $combinator->setIdentifier('id');
            //$combinator->addSubClass('job', '\Exaprint\Partners\Job\Job');
            //$combinator->addGroup('uploadSlots', 'dirname', '\Exaprint\Partners\Order\UploadSlot');
            /** @var Order $order */
            $data = $combinator->process($stmt->fetchAll(DB::FETCH_ASSOC));

            $IDProduit = $data[$id]->product->id;
            $IDProduitFamilleProduit = $data[$id]->product->famille;

            $query2 = str_replace(['%1', '%2', '%3', '%4'], [$IDLangue, $IDProduit, $IDProduitFamilleProduit, $id], $query2);
            $resume = [];
            $result2 = $db->query($query2);
            if ($donnees = $result2->fetchAll(DB::FETCH_ASSOC)) {

                foreach($donnees as $n => $donnee) {
                    $resume[] = $donnee['LibelleOption']." : ".$donnee['LibelleCombo'];
                    
                }
            }
            $data[$id]->product->resume = $resume;

            $this->_data = $data[$id]; // hack
            return true;
        }

        return false;

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