<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxProject implements IResource
{
    protected $_data;


    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        $dao = new \Exaprint\GenPDF\Resources\DAO\PrintboxProject();
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
        return "resources/printbox/project.twig";
    }

    /**
     * @return string
     */
    public function getXml()
    {
        // TODO: Implement getXml() method.
    }

}