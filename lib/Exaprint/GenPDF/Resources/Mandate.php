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

class Mandate implements IResource
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
        /*$dao = new DAO\Mandate();
        if ($xml = $dao->getXML($id)) {
            $this->_xml  = $xml;
            $this->_data = (array)$xml;
            return true;
        }
        return false;*/
        $this->_data = [];
        return true;
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
        return "resources/mandate.twig";
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
        return Helper::$current . "/header.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return Helper::$current . "/footer.html";
    }

}