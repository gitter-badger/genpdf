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

class Mandate extends Resource implements IResource
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
        $id = explode('-', $id);
        $IDClient = $id[0];

        $dao = new DAO\Mandate();
        if ($xml = $dao->getXML($IDClient)) {
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
        if (\Locale\Helper::$current == 'pt_PT') {
            $filename .= "countries/mandate.pt.twig";
        } else {
            $filename .= "mandate.twig";
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
        return $this->_imageFolder . "header.empty.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_imageFolder . "footer.empty.html";
    }

}