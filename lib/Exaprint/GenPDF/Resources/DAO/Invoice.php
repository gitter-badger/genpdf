<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 05/02/13
 * Time: 00:11
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources\DAO;

class Invoice
{

    /**
     * @param $IDFacture
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getXML($IDFacture)
    {
        $db = new Database("dev");
        if($result = $db->query("select CAST(dbo.f_XML_Facture($IDFacture) AS varchar(max))")){
            $xml = $result->fetchColumn();
            $xml = str_replace("\r\n", "", $xml);
            $xml = mb_convert_encoding($xml, "UTF-8", "ISO-8859-1");
            return simplexml_load_string($xml);
        }
        throw new \Exception("Impossible de récupérer le XML : " . print_r($db->errorInfo(), true));
    }
}