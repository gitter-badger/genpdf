<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 04/02/13
 * Time: 22:58
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources;

use Locale\Helper;

class Invoice extends Resource implements IResource
{

    protected $_data;

    /**
     * @var \SimpleXMLElement
     */
    protected $_xml;

    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        $dao = new DAO\Invoice();
        if ($xml = $dao->getXML($id)) {
            $this->_xml  = $xml;
            $this->_data = (array)$xml;
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        $filename = "resources/";
        if (\Locale\Helper::$current == 'it_IT') {
            $filename .= "countries/invoice.it.twig";
        } else if (\Locale\Helper::$current == 'es_ES') {
                $filename .= "countries/invoice.es.twig";
        } else {
            $filename .= "invoice.twig";
        }
        return $filename;
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->_xml->asXML();
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

}