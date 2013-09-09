<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\Commande\Select;
use Exaprint\DAL\DB;
use RBM\ResultsCombinator\ResultsCombinator;

class OrderReceipt implements IResource
{
    protected $_data;


    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
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
            'product.id' => 'IDCommandeLigne',
            'product.designation' => 'Libelle',
            'product.priceHT' => 'MontantPrixAchat'
        ];
        $tbl_produit = ['product.reference' => 'Code'];

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
        $sql->join('TBL_COMMANDE_LIGNE', 'IDCommande', 'IDCommande', $tbl_commmande_ligne)
            ->join('TBL_PRODUIT', 'IDProduit', 'IDProduit', $tbl_produit);
                    //->join('TBL_PRODUIT_FAMILLE_PRODUIT', 'TBL_PRODUIT_FAMILLE_PRODUIT.IDProduitFamilleProduit', 'TBL_PRODUIT.IDProduitFamilleProduit')
                    //->join('TBL_PRODUIT_UNITE_TARIF', 'TBL_PRODUIT_UNITE_TARIF.IDProduitUniteTarif', 'TBL_PRODUIT.IDProduitUniteTarif')
                    //->join('TBL_PRODUIT_OFFRE', 'TBL_PRODUIT_OFFRE.IDProduitOffre', 'TBL_PRODUIT.IDProduitOffre')
                    //->join('TBL_PRODUIT_LIBELLE_FRONT_TRAD', 'TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDProduit', 'TBL_PRODUIT.IDProduit', 'TBL_PRODUIT_LIBELLE_FRONT_TRAD.IDLangue', '1');

        if ($stmt = $db->query($sql)) {
            $combinator = new ResultsCombinator();
            //$combinator->setClass('\Exaprint\Partners\Order\Order');
            $combinator->setIdentifier('id');
            //$combinator->addSubClass('job', '\Exaprint\Partners\Job\Job');
            //$combinator->addGroup('uploadSlots', 'dirname', '\Exaprint\Partners\Order\UploadSlot');
            /** @var Order $order */
            $data = $combinator->process($stmt->fetchAll(DB::FETCH_ASSOC));
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
        return "printbox/header.html";
    }

    public function getFooter()
    {
        return "printbox/footer.html";
    }



}