<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 01/07/2014
 * Time: 11:16
 */

namespace Exaprint\GenPDF\CGF;


use Exaprint\DAL\DB;
use RBM\SqlQuery\Factory;

class Quote
{
    protected $_data;

    protected $_client;

    public function __construct($data)
    {
        $this->_data = $data;
    }

    public function getCollation()
    {
        $l      = $this->_data->lang_id;
        $select = Factory::select('TBL_LANGUE', ['NormeBCP']);
        $select->where()->eq('IDLangue', $l);
        $stmt = DB::get()->query($select);
        return $stmt->fetchColumn();
    }

    public function getProduct()
    {
        $l = $this->_data->lang_id;
        /** @var \Exaprint\DAL\Produit\Select $produit */
        $produit = Factory::select('TBL_PRODUIT');
        $produit->libelleFront($l)->cols(['p' => 'LibelleTraduit']);
        $pfp = $produit->familleProduit();
        $pfp->designation($l)->cols(['pfp' => 'LibelleTraduit']);
        $pfp->familleArticles()->libelle($l)->cols(['pfa' => 'LibelleTraduit']);
        $produit->where()->eq('IDProduit', $this->_data->product_id);

        $stmt = DB::get()->query($produit);
        $row  = $stmt->fetchObject();

        return $row->pfa . ' ' . $row->pfp . ' ' . $row->p;
    }

    public function getOptions()
    {
        $pov = Factory::select('VUE_PRODUIT_OPTION_VALEUR');
        $pov->cols(['value' => 'LibelleTraduit']);
        $pov->where()->eq('IDProduit', $this->_data->product_id);
        $pov->where()->eq('IDLangue', $this->_data->lang_id)->addBitClause('EstSans', false);
        $po = $pov->join('TBL_PRODUIT_OPTION_TRAD', 'IDProduitOption');
        $po->where()->eq('IDLangue', $this->_data->lang_id);
        $po->cols(['option' => 'LibelleTraduit']);
        $pov->orderBy('Ordre');

        $stmt = DB::get()->query($pov);
        return $stmt->fetchAll(DB::FETCH_OBJ);
    }

    public function getModels()
    {
        return $this->_data->models;
    }

    public function getSellingPrice()
    {
        return floatval($this->_data->selling_price);
    }

    /**
     * @return \stdClass
     */
    public function getClient()
    {
        if (isset($this->_client)) {
            return $this->_client;
        }

        /** @var \Exaprint\DAL\Client\Select $client */
        $client = Factory::select('VUE_INFOS_CLIENT');
        $client->join('TBL_CLIENT_CONTACT', 'IDClient')->where()->eq('IDClientContact', $this->_data->client_id);
        $stmt          = DB::get()->query($client);
        $this->_client = $stmt->fetchObject();
        return $this->_client;
    }

    public function getWeight()
    {
        return floatval($this->_data->weight) * 1000;
    }

    public function getLeadTime()
    {
        return intval($this->_data->lead_time);
    }

    public function getRush()
    {
        return ($this->_data->rush);
    }

    public function getLangId()
    {
        return $this->_data->lang_id;
    }
} 