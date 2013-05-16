<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 05/02/13
 * Time: 00:11
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources\DAO;

use Exaprint\DAL\DB;

class Invoice
{

    /**
     * @param $IDFacture
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getXML($IDFacture)
    {
        if($result = DB::get()->query("select CAST(dbo.f_XML_Facture($IDFacture) AS varchar(max))")){
            $xml = $result->fetchColumn();
            $xml = str_replace("\r\n", "", $xml);
            return simplexml_load_string($xml);
        }
        throw new \Exception("Impossible de récupérer le XML : " . print_r(DB::get()->errorInfo(), true));
    }
}