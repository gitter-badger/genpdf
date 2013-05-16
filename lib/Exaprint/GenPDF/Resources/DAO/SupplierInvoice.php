<?php
/**
 * Created by JetBrains PhpStorm.
 * User: main_dev
 * Date: 27/02/13
 * Time: 08:45
 * To change this template use File | Settings | File Templates.
 */
namespace Exaprint\GenPDF\Resources\DAO;

use Exaprint\DAL\DB;

class SupplierInvoice
{

    /**
     * @param $IDFactureFournisseur
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function getXML($IDFactureFournisseur)
    {
        if($result = DB::get()->query("select CAST(dbo.f_XML_FactureFournisseur($IDFactureFournisseur) AS varchar(max))")){
            $xml = $result->fetchColumn();
            return simplexml_load_string($xml);
        }
        throw new \Exception("Impossible de récupérer le XML : " . print_r(DB::get()->errorInfo(), true));
    }
}