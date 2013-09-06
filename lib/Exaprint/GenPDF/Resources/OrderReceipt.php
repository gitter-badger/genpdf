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
        $sql->join('TBL_CLIENT_ADRESSELIVRAISON', 'IDClientAdresseLivraison', 'IDClientAdresseLivraison',
            [
                'client.address.title' => 'Intitule',
                'client.address.line1' => 'AdresseLivraison1',
                'client.address.line2' => 'AdresseLivraison2',
                'client.address.line3' => 'AdresseLivraison3',
                'client.address.postcode' => 'CodePostalLivraison',
                'client.address.city' => 'VilleLivraison'
            ]);
        $sql->join('TBL_COMMANDE_LIGNE', 'IDCommande', 'IDCommande', [
            'product.id' => 'IDCommandeLigne',
            'product.designation' => 'Libelle',
            'product.priceHT' => 'MontantPrixAchat'
        ])->join('TBL_PRODUIT', 'IDProduit', 'IDProduit', [
            'product.reference' => 'Code'
        ]);

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