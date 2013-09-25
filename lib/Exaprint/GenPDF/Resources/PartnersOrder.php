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
     * @param $IDCommandePartenaire
     * @internal param $id
     * @return bool
     */
    public function fetchFromID($IDCommandePartenaire)
    {
        $IDLangue = 1;

        try {

            $string = file_get_contents($_SERVER['url_api_partners']."/orders/$IDCommandePartenaire");
            $json_a = json_decode($string, true);

            $this->_data = $json_a[$IDCommandePartenaire];

            // Converting XML data
            $this->_data['data'] = simplexml_load_string($this->_data['data']);

            $id = $this->_data['data']->material;
            $string = file_get_contents($_SERVER['url_api_stickers']."/materials/$id/options.json");

            // Reading support
            $support = '';
            $option = $this->_data['data']->option;
            $supports = json_decode($string, true);
            foreach ($supports as $n => $s) {
                if ($s['id'] == $option) {
                    $support = $s['name'];
                }
            }
            $this->_data['support'] = $support;

        } catch (\Exception $e) {
            return false;
        }

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