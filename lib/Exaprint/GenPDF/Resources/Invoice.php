<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 04/02/13
 * Time: 22:58
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources;

class Invoice implements IResource
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
        return "resources/invoice.twig";
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
        return "header." . \Locale\Helper::$current . ".html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return "footer." . \Locale\Helper::$current . ".html";
    }


}