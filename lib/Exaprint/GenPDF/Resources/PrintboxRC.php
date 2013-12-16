<?php

namespace Exaprint\GenPDF\Resources;

use Locale\Helper;

class PrintboxRC extends Resource implements IResource
{
    protected $_data;


    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        $dao = new DAO\PrintboxProject();
        $this->_data = $dao->fetchFromId($id);
        return !is_null($this->_data);
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
        return "resources/printbox-rc.twig";
    }

    /**
     * @return string
     */
    public function getXml()
    {
        // TODO: Implement getXml() method.
    }


    public function getHeader()
    {
        return $this->_imageFolder . "printbox/header.html";
    }

    public function getFooter()
    {
        return $this->_imageFolder . Helper::$current . "/footer.html";
    }



}