<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\Commande\Select;
use Exaprint\DAL\DB;
use RBM\ResultsCombinator\ResultsCombinator;
use Locale\Helper;
use RBM\SqlQuery\Column;

class PartnersOrder implements IResource
{
    protected $_data;


    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($IDCommandePartenaire)
    {
        $IDLangue = 1;

        //$select = new \Exaprint\DAL\Client\Select();

        $string = file_get_contents("http://local.api.partners.exaprint.fr/orders/$IDCommandePartenaire");
        $json_a = json_decode($string, true);

        $this->_data = $json_a[$IDCommandePartenaire];

        // Reading data
        $this->_data['data'] = simplexml_load_string($this->_data['data']);

        $id = $this->_data['data']->material;
        $option = $this->_data['data']->option;
        $string = file_get_contents("http://local.api.stickers.exaprint.fr/materials/$id/options.json");

        $qupport = '';
        $supports = json_decode($string, true);
        foreach ($supports as $n => $s) {
            if ($s['id'] == $option) {
                $support = $s['name'];
            }
        }
        $this->_data['support'] = $support;

        //var_dump($this->_data);

        return true;

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
        return "resources/partners-order.twig";
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
        return Helper::$current . "/header.html";
    }

    public function getFooter()
    {
        return Helper::$current . "/footer.html";
    }



}