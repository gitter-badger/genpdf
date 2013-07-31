<?php

namespace Exaprint\GenPDF\Resources\DAO;

use Exaprint\DAL\DB;

class Customer
{
    /**
     * @param $IDClient
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getXML($IDClient)
    {
        $query = "select CAST(dbo.f_XML_Client($IDClient) AS nvarchar(max))";
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