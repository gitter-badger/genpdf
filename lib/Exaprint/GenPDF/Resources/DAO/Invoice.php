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
        $query = "select CAST(dbo.f_XML_Facture($IDFacture) AS nvarchar(max))";
        if ($result = DB::get()->query($query)) {
            if (($xml = $result->fetchColumn()) !== false) {
                $xml = str_replace("\r\n", "", $xml);
                if ($simplexml = simplexml_load_string($xml)) {
                    return $simplexml;
                }
                throw new \Exception("Impossible de parser le xml $xml $query");
            }
            throw new \Exception("XML vide $query");
        }
        throw new \Exception("Impossible de récupérer le XML : " . print_r(DB::get()->errorInfo(), true));
    }
}