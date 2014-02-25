<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 25/02/2014
 * Time: 08:54
 */

namespace Exaprint\GenPDF\Resources;


use Exaprint\DAL\DB;
use Locale\Helper;

class MasterprintQuote extends Resource implements IResource {

    protected $_data;

    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        $stmt = DB::get()->prepare("SELECT * FROM Sc_MasterPrint.TBL_ENTETE WHERE NumeroDevis = :id");

        if($stmt->execute(['id' => $id])){
            $rows = $stmt->fetchAll(DB::FETCH_ASSOC);
            $this->_data = $rows[0];

            $stmt = DB::get()->prepare("SELECT r.*
                FROM Sc_MasterPrint.TBL_DISPATCH d
                JOIN Sc_MasterPrint.TBL_REPONSE r ON r.NumeroDevisAtelier = d.NumeroDevisAtelier
                WHERE NumeroDevis = :id AND Valide = 1 ORDER BY Quantite");


            $stmt->execute(['id' => $id]);
            $this->_data['Reponses'] = $stmt->fetchAll(DB::FETCH_ASSOC);
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
        return "resources/masterprint-quote.twig";

    }

    /**
     * @return string
     */
    public function getXml()
    {
        // TODO: Implement getXml() method.
    }

} 