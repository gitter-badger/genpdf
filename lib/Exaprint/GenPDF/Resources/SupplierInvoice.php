<?php
/**
 * Created by JetBrains PhpStorm.
 * User: main_dev
 * Date: 27/02/13
 * Time: 08:48
 * To change this template use File | Settings | File Templates.
 */
namespace Exaprint\GenPDF\Resources;

class SupplierInvoice implements IResource
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
        $dao = new DAO\SupplierInvoice();
        if ($xml = $dao->getXML($id)) {
            $this->_xml = $xml;
            $this->_data = (array) $xml;
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
        return "resources/supplierinvoice.html";
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->_xml->asXML();
    }


}