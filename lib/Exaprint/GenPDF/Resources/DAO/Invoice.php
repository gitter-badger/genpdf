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
        $db = DB::get();

        $db->exec('SET ANSI_NULLS ON');
        $db->exec('SET ANSI_PADDING ON');
        $db->exec('SET ANSI_WARNINGS ON');
        $db->exec('SET ARITHABORT ON');
        $db->exec('SET CONCAT_NULL_YIELDS_NULL ON');
        $db->exec('SET NUMERIC_ROUNDABORT OFF');
        $db->exec('SET QUOTED_IDENTIFIER ON');

        $query = "select LEN(CAST(dbo.f_XML_Facture($IDFacture) AS nvarchar(max)))";
        if ($result = $db->query($query)) {
            if (($length = $result->fetchColumn()) !== false) {
                $txt = "SET TEXTSIZE " . ($length*2);
                $db->exec($txt);
            }
        }

        $query = "select CAST(dbo.f_XML_Facture($IDFacture) AS nvarchar(max))";
        if ($result = $db->query($query)) {
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