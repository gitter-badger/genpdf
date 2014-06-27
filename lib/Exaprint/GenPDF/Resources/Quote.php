<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 13/06/14
 * Time: 10:24
 */

namespace Exaprint\GenPDF\Resources;


use Curl;
use Locale\Helper;

class Quote extends Resource implements IResource
{

    protected $_data;
    protected $_idQuote;

    /**
     * @param $quoteId
     * @return bool
     */
    public function fetchFromID($quoteId)
    {
        $dao = new DAO\Quote();
        list($id, $quantity) = explode('-', $quoteId);
        $this->_data    = $dao->fetchFromId($id, $quantity);
        $this->_idQuote = $id;

        return !is_null($this->_data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!isset($_SERVER['masterprint_url'])) {
            $_SERVER['masterprint_url'] = 'http://masterprint.exaprint.fr';
        }
        $url = "$_SERVER[masterprint_url]/quote/" . $this->_idQuote;

        $curl = new Curl();
        //Execute la requete GET de récupération de devis
        $result = $curl->get($url);

        $data = json_decode($result, true);

        //$k = array_keys($data['result']['detail_demande']['element']);
        reset($data['result']['detail_demande']['element']);
        $k = each($data['result']['detail_demande']['element']);
        if (!is_numeric($k[0])) {
            $data['result']['detail_demande']['element'] = array($data['result']['detail_demande']['element']);
        }

        $data['result']['data'] = $this->_data;
        $zipCode                = "";
        $dao                    = new DAO\Quote();
        foreach ($data['result']['livraison'] as $delivery) {
            if(isset($delivery['value'])){
                if ($delivery['value'] == $this->_data->Quantite) {
                    //Cas du multi-point de livraison
                    if(is_array($delivery['code_postal'])){
                        foreach($delivery['code_postal'] as $k=>$shipment){
                            if (strlen($shipment) < 2) {
                                $zipCode = "0" . $shipment;
                            } else {
                                $zipCode = $shipment;
                            }
                            $data['result']['city'][$this->_data->Quantite][] = [
                                'code'        => $zipCode,
                                'departement' => $dao->getDepartementNameByCode((string)$shipment)->NomDepartement,
                                'quantite'    => $delivery['quantite'][$k]
                            ];
                        }
                        //Mono point de livraison
                    }else{
                        if (strlen($delivery['code_postal']) < 2) {
                            $zipCode = "0" . $delivery['code_postal'];
                        } else {
                            $zipCode = $delivery['code_postal'];
                        }
                        $data['result']['city'][$this->_data->Quantite][] = [
                            'code'        => $zipCode,
                            'departement' => $dao->getDepartementNameByCode((string)$delivery['code_postal'])->NomDepartement,
                            'quantite'    => $delivery['quantite']
                        ];
                    }
                }
                //Cas du multi-quantité
            }else{
                foreach ($delivery as $shipment) {
                    if(isset($shipment['value'])){
                        if ($shipment['value'] == $this->_data->Quantite) {
                            //Mono point de livraison
                            if(!is_array($shipment['code_postal'])){
                                if (strlen($shipment['code_postal']) < 2) {
                                    $zipCode = "0" . $shipment['code_postal'];
                                } else {
                                    $zipCode = $shipment['code_postal'];
                                }
                                $data['result']['city'][$this->_data->Quantite][] = [
                                    'code'        => $zipCode,
                                    'departement' => $dao->getDepartementNameByCode((string)$shipment['code_postal'])->NomDepartement,
                                    'quantite'    => $shipment['quantite']
                                ];
                                //Multi points de livraison
                            }else{
                                foreach($shipment['code_postal'] as $key=>$dep){
                                    if (strlen($dep) < 2) {
                                        $zipCode = "0" . $dep;
                                    } else {
                                        $zipCode = $dep;
                                    }
                                    $data['result']['city'][$this->_data->Quantite][] = [
                                        'code'        => $zipCode,
                                        'departement' => $dao->getDepartementNameByCode((string)$dep)->NomDepartement,
                                        'quantite'    => $shipment['quantite'][$key]
                                    ];
                                }
                            }

                        }
                    }
                }
            }
        }

        return $data['result'];
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->_imageFolder . Helper::$current . "/header.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_imageFolder . Helper::$current . "/footer.html";
    }

    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        return "resources/quote.twig";
    }

    /**
     * @return string
     */
    public function getXml()
    {
        // TODO: Implement getXml() method.
    }
}