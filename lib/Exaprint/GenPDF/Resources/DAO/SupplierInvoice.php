<?php
/**
 * Created by JetBrains PhpStorm.
 * User: main_dev
 * Date: 27/02/13
 * Time: 08:45
 * To change this template use File | Settings | File Templates.
 */
namespace Exaprint\GenPDF\Resources\DAO;

class SupplierInvoice
{

    /**
     * @param $IDFactureFournisseur
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getXML($IDFactureFournisseur)
    {
        $db = new Database("test");
        if($result = $db->query("select CAST(dbo.f_XML_FactureFournisseur($IDFactureFournisseur) AS varchar(max))")){
            $xml = $result->fetchColumn();
            $xml = str_replace("\r\n", "", $xml);
            $xml = mb_convert_encoding($xml, "UTF-8", "ISO-8859-1");
            return simplexml_load_string($xml);
        }
        throw new \Exception("Impossible de récupérer le XML : " . print_r($db->errorInfo(), true));
    }
}